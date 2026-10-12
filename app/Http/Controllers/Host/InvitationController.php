<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\Template;
use App\Jobs\GenerateInvitationQrCode;
use App\Services\InvitationQrService;
use App\Services\Invitations\InvitationRenderer;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function __construct(
        protected WhatsAppService $whatsAppService,
        protected InvitationQrService $invitationQrService,
        protected InvitationRenderer $invitationRenderer
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $host = Auth::user();
        
        // Get the host's configured event
        $event = Event::where('host_id', $host->id)->first();
        
        if (!$event) {
            return redirect()->route('host.event-config')->with('error', 'Please configure your event first before managing invitations.');
        }
        
        $invitations = Invitation::where('event_id', $event->id)
            ->with(['guest'])
            ->latest()
            ->paginate(15);

        // Calculate stats
        $stats = [
            'total_sent' => Invitation::where('event_id', $event->id)->count(),
            'delivered' => Invitation::where('event_id', $event->id)->whereNotNull('delivered_at')->count(),
            'opened' => Invitation::where('event_id', $event->id)->whereNotNull('opened_at')->count(),
            'failed' => Invitation::where('event_id', $event->id)->where('status', 'failed')->count(),
        ];

        return view('host.invitations.index', compact('invitations', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $host = Auth::user();
        
        // Get the host's configured event
        $event = Event::where('host_id', $host->id)->first();
        
        if (!$event) {
            return redirect()->route('host.event-config')->with('error', 'Please configure your event first before sending invitations.');
        }
        
        // Get guests for this event
        $guests = Guest::where('event_id', $event->id)->get();
        
        // Get all active templates (matching category or standard/premium, plus custom templates for this host)
        $templates = Template::where('is_active', true)
            ->where(function ($query) use ($event, $host) {
                $query->where('category', $event->category)
                      ->orWhere('template_type', 'standard')
                      ->orWhere('template_type', 'premium')
                      ->orWhere('host_id', $host->id);
            })
            ->with('fields')
            ->orderByRaw("CASE WHEN category = '{$event->category}' THEN 0 ELSE 1 END")
            ->orderBy('is_paid', 'asc')
            ->get();

        return view('host.invitations.create', compact('guests', 'templates', 'event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Basic validation first
        $request->validate([
            'sending_type' => 'required|in:individual,bulk',
            'guest_id' => 'required_if:sending_type,individual',
            'guest_ids' => 'required_if:sending_type,bulk|array|min:1',
            'description' => 'required|string|max:1000',
            'sending_method' => 'required|in:whatsapp,sms,both',
            'template_id' => 'required|exists:templates,id',
        ]);

        $host = Auth::user();
        
        // Get the host's configured event
        $event = Event::where('host_id', $host->id)->first();
        
        if (!$event) {
            return redirect()->back()->with('error', 'No event found. Please configure your event first.');
        }

        // Validate guest IDs belong to this host's event
        if ($request->sending_type === 'bulk' && !empty($request->guest_ids)) {
            $validGuestIds = Guest::where('event_id', $event->id)
                ->whereIn('id', $request->guest_ids)
                ->pluck('id')
                ->toArray();
            
            // Convert to integers for comparison
            $requestGuestIds = array_map('intval', $request->guest_ids);
            
            $invalidGuestIds = array_diff($requestGuestIds, $validGuestIds);
            if (!empty($invalidGuestIds)) {
                \Log::error('Invalid guest IDs submitted', [
                    'event_id' => $event->id,
                    'host_id' => $host->id,
                    'submitted_guest_ids' => $request->guest_ids,
                    'requestGuestIds' => $requestGuestIds,
                    'valid_guest_ids' => $validGuestIds,
                    'invalid_guest_ids' => $invalidGuestIds,
                    'total_guests_for_event' => Guest::where('event_id', $event->id)->count()
                ]);
                return redirect()->back()->with('error', 'Some selected guests are not associated with your event. Please refresh the page and try again.');
            }
            
            // Additional check: ensure we actually have guests to send to
            if (empty($validGuestIds)) {
                \Log::error('No valid guests found for bulk send', [
                    'event_id' => $event->id,
                    'host_id' => $host->id,
                    'total_guests_for_event' => Guest::where('event_id', $event->id)->count(),
                    'request_guest_ids' => $request->guest_ids
                ]);
                return redirect()->back()->with('error', 'No guests found for this event. Please add guests first.');
            }
        }
        
        // Validate individual guest ID
        if ($request->sending_type === 'individual') {
            if (!$request->guest_id) {
                return redirect()->back()->with('error', 'Please select a guest.');
            }
            
            $guest = Guest::where('event_id', $event->id)
                ->where('id', $request->guest_id)
                ->first();
                
            if (!$guest) {
                \Log::error('Invalid individual guest selected', [
                    'event_id' => $event->id,
                    'host_id' => $host->id,
                    'selected_guest_id' => $request->guest_id,
                    'total_guests_for_event' => Guest::where('event_id', $event->id)->count()
                ]);
                return redirect()->back()->with('error', 'The selected guest is not associated with your event.');
            }
        }

        // Template validation removed - using custom design
        
        if ($request->sending_type === 'individual') {
            // Send to individual guest (already validated above)
            $guest = Guest::where('event_id', $event->id)->where('id', $request->guest_id)->first();
            
            $invitation = Invitation::create([
                'event_id' => $event->id,
                'guest_id' => $guest->id,
                'template_id' => $request->template_id,
                'type' => $request->sending_method,
                'status' => 'pending',
                'sent_at' => now(),
                'sms_code' => $request->sending_method === 'sms' || $request->sending_method === 'both' ? Str::random(6) : null,
                'whatsapp_message' => $request->sending_method === 'whatsapp' || $request->sending_method === 'both' ? $request->description : null,
            ]);

            GenerateInvitationQrCode::dispatch($invitation->id);
            
            // Try to send via WhatsApp if configured
            if (($request->sending_method === 'whatsapp' || $request->sending_method === 'both') && $this->whatsAppService->isConfigured()) {
                try {
                    $this->sendWhatsAppInvitation($invitation);
                } catch (\Exception $e) {
                    \Log::error('WhatsApp invitation sending failed', [
                        'invitation_id' => $invitation->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            $message = "Invitation sent successfully to {$guest->full_name}!";
        } else {
            // Send to selected guests
            $selectedGuestIds = $request->guest_ids ?? [];
            
            // Get the validated guests (already validated above)
            $guests = Guest::where('event_id', $event->id)
                ->whereIn('id', $selectedGuestIds)
                ->get();
            
            foreach ($guests as $guest) {
                $createdInvitation = Invitation::create([
                    'event_id' => $event->id,
                    'guest_id' => $guest->id,
                    'template_id' => $request->template_id,
                    'type' => $request->sending_method,
                    'status' => 'pending',
                    'sent_at' => now(),
                    'sms_code' => $request->sending_method === 'sms' || $request->sending_method === 'both' ? Str::random(6) : null,
                    'whatsapp_message' => $request->sending_method === 'whatsapp' || $request->sending_method === 'both' ? $request->description : null,
                ]);

                GenerateInvitationQrCode::dispatch($createdInvitation->id);
            }
            
            // Try to send via WhatsApp if configured
            if (($request->sending_method === 'whatsapp' || $request->sending_method === 'both') && $this->whatsAppService->isConfigured()) {
                try {
                    // Get the created invitations
                    $createdInvitations = Invitation::where('event_id', $event->id)
                        ->whereIn('guest_id', $selectedGuestIds)
                        ->latest()
                        ->limit(count($selectedGuestIds))
                        ->get();
                    
                    foreach ($createdInvitations as $invitation) {
                        try {
                            $this->sendWhatsAppInvitation($invitation);
                        } catch (\Exception $e) {
                            \Log::error('WhatsApp bulk invitation sending failed', [
                                'invitation_id' => $invitation->id,
                                'error' => $e->getMessage()
                            ]);
                        }
                        
                        // Add delay between messages to avoid rate limiting
                        usleep(500000); // 0.5 second delay
                    }
                } catch (\Exception $e) {
                    \Log::error('WhatsApp bulk invitation sending error', [
                        'event_id' => $event->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            $message = "Invitations sent successfully to {$guests->count()} guests!";
        }

        return redirect()->route('host.invitations.index')->with('success', $message);
    }

    /**
     * Download invitation card as PDF or PNG image
     */
    public function download(Invitation $invitation, Request $request)
    {
        $host = Auth::user();
        
        // Verify the invitation belongs to the host's event
        $event = Event::where('host_id', $host->id)->first();
        if (!$event || $invitation->event_id !== $event->id) {
            abort(403, 'Unauthorized access to invitation.');
        }
        
        if ($request->get('format') === 'png') {
            try {
                $imageGenerator = new \App\Services\InvitationImageGenerator();
                return $imageGenerator->generatePNGResponse($invitation);
            } catch (\Exception $e) {
                \Log::warning('PNG generation fallback to PDF', ['error' => $e->getMessage()]);
            }
        }

        try {
            return $this->invitationRenderer->renderPdf($invitation);
        } catch (\Exception $e) {
            \Log::error('Failed to generate invitation PDF', [
                'invitation_id' => $invitation->id,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->back()->with('error', 'Unable to generate invitation document: ' . $e->getMessage());
        }
    }

    /**
     * Preview invitation card inline in browser
     */
    public function preview(Invitation $invitation, Request $request)
    {
        $host = Auth::user();
        
        // Verify the invitation belongs to the host's event
        $event = Event::where('host_id', $host->id)->first();
        if (!$event || $invitation->event_id !== $event->id) {
            abort(403, 'Unauthorized access to invitation.');
        }

        if ($request->get('format') === 'png') {
            try {
                $imageGenerator = new \App\Services\InvitationImageGenerator();
                return $imageGenerator->generatePNGInlineResponse($invitation);
            } catch (\Exception $e) {
                \Log::warning('PNG inline preview error', ['error' => $e->getMessage()]);
            }
        }

        return $this->invitationRenderer->renderWeb($invitation, ['is_preview' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invitation $invitation)
    {
        $host = Auth::user();
        
        // Verify the invitation belongs to the host's event
        $event = Event::where('host_id', $host->id)->first();
        if (!$event || $invitation->event_id !== $event->id) {
            abort(403, 'Unauthorized access to invitation.');
        }
        
        $guestName = $invitation->guest->full_name ?? 'Unknown Guest';
        
        try {
            $invitation->delete();
            return redirect()->route('host.invitations.index')->with('success', "Invitation for {$guestName} has been deleted successfully.");
        } catch (\Exception $e) {
            \Log::error('Failed to delete invitation', [
                'invitation_id' => $invitation->id,
                'host_id' => $host->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Failed to delete invitation. Please try again.');
        }
    }

    /**
     * Generate or retrieve event password (5 characters)
     */
    private function generateEventPassword(Event $event): string
    {
        // If event already has a password, return it
        if ($event->event_password) {
            return $event->event_password;
        }
        
        // Generate a random 5-character password (alphanumeric, uppercase)
        $password = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5));
        
        // Save password to event
        $event->update(['event_password' => $password]);
        
        return $password;
    }

    /**
     * Generate QR code as base64 string
     */
    private function generateQRCodeBase64($qrData, $guestName)
    {
        try {
            // Get QR URL
            $qrUrl = $qrData['qr_url'] ?? url('/invitations/rsvp/' . $guestName);
            
            // Try Google Charts API first
            $qrSize = 150;
            $qrApiUrl = "https://chart.googleapis.com/chart?chs={$qrSize}x{$qrSize}&chld=M|0&cht=qr&chl=" . urlencode($qrUrl);
            
            $qrImageData = file_get_contents($qrApiUrl);
            if ($qrImageData && strlen($qrImageData) > 100) {
                return 'data:image/png;base64,' . base64_encode($qrImageData);
            }
        } catch (\Exception $e) {
            // Fall back to null if Google API fails
        }
        
        // Fallback: Return null if we can't generate QR code
        return null;
    }

    /**
     * Send invitation via WhatsApp
     */
    protected function sendWhatsAppInvitation(Invitation $invitation): void
    {
        try {
            $this->invitationQrService->ensureQrCode($invitation);
            $whatsAppController = new \App\Http\Controllers\WhatsAppInvitationController(
                $this->whatsAppService,
                new \App\Services\InvitationImageGenerator(),
                $this->invitationQrService
            );
            $result = $whatsAppController->sendInvitation($invitation);
            
            if ($result['success']) {
                \Log::info('WhatsApp invitation sent successfully', [
                    'invitation_id' => $invitation->id,
                    'guest_name' => $invitation->guest->full_name,
                    'message_id' => $result['message_id'] ?? null
                ]);
            } else {
                \Log::warning('WhatsApp invitation sending failed', [
                    'invitation_id' => $invitation->id,
                    'guest_name' => $invitation->guest->full_name,
                    'error' => $result['error'] ?? 'Unknown error'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('WhatsApp invitation sending exception', [
                'invitation_id' => $invitation->id,
                'guest_name' => $invitation->guest->full_name,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Resend invitation via WhatsApp
     */
    public function resend(Invitation $invitation)
    {
        $host = Auth::user();
        
        // Verify the invitation belongs to the host's event
        $event = Event::where('host_id', $host->id)->first();
        if (!$event || $invitation->event_id !== $event->id) {
            abort(403, 'Unauthorized access to invitation.');
        }

        if (!$this->whatsAppService->isConfigured()) {
            return redirect()->back()->with('error', 'WhatsApp service is not configured. Please contact administrator.');
        }

        try {
            $whatsAppController = new \App\Http\Controllers\WhatsAppInvitationController(
                $this->whatsAppService,
                new \App\Services\InvitationImageGenerator(),
                $this->invitationQrService
            );
            $result = $whatsAppController->sendInvitation($invitation);
            
            if ($result['success']) {
                return redirect()->back()->with('success', "Invitation resent successfully to {$invitation->guest->full_name}!");
            } else {
                return redirect()->back()->with('error', "Failed to resent invitation: {$result['error']}");
            }
        } catch (\Exception $e) {
            \Log::error('WhatsApp resend invitation exception', [
                'invitation_id' => $invitation->id,
                'host_id' => $host->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', "Failed to resent invitation: {$e->getMessage()}");
        }
    }

    /**
     * Test WhatsApp connection
     */
    public function testWhatsApp()
    {
        if (!$this->whatsAppService->isConfigured()) {
            return redirect()->back()->with('error', 'WhatsApp service is not configured. Please contact administrator.');
        }

        try {
            $whatsAppController = new \App\Http\Controllers\WhatsAppInvitationController(
                $this->whatsAppService,
                new \App\Services\InvitationImageGenerator(),
                $this->invitationQrService
            );
            $result = $whatsAppController->testConnection();
            
            if ($result['success']) {
                return redirect()->back()->with('success', 'WhatsApp connection test successful!');
            } else {
                return redirect()->back()->with('error', "WhatsApp connection test failed: {$result['error']}");
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "WhatsApp connection test failed: {$e->getMessage()}");
        }
    }
}
