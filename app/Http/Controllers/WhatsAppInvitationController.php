<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Guest;
use App\Models\Event;
use App\Services\WhatsAppService;
use App\Services\InvitationImageGenerator;
use App\Services\InvitationQrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WhatsAppInvitationController extends Controller
{
    protected $whatsAppService;
    protected $imageGenerator;
    protected $invitationQrService;

    public function __construct(
        WhatsAppService $whatsAppService,
        InvitationImageGenerator $imageGenerator,
        InvitationQrService $invitationQrService
    )
    {
        $this->whatsAppService = $whatsAppService;
        $this->imageGenerator = $imageGenerator;
        $this->invitationQrService = $invitationQrService;
    }

    /**
     * Send a single invitation via WhatsApp
     */
    public function sendInvitation(Invitation $invitation): array
    {
        try {
            // Check if WhatsApp is configured
            if (!$this->whatsAppService->isConfigured()) {
                Log::warning('WhatsApp not configured, skipping invitation send', [
                    'invitation_id' => $invitation->id
                ]);
                
                return [
                    'success' => false,
                    'error' => 'WhatsApp service not configured'
                ];
            }

            $guest = $invitation->guest;
            $event = $invitation->event;
            $invitation = $this->invitationQrService->ensureQrCode($invitation);

            // Generate invitation image
            $invitationImage = $this->imageGenerator->generate($invitation);
            
            if (!$invitationImage) {
                Log::error('Failed to generate invitation image', [
                    'invitation_id' => $invitation->id,
                    'guest_id' => $guest->id
                ]);
                
                return [
                    'success' => false,
                    'error' => 'Failed to generate invitation image'
                ];
            }

            // Save image to temporary file
            $tempImagePath = $this->saveImageToTempFile($invitationImage, $invitation, $guest);
            
            if (!$tempImagePath) {
                return [
                    'success' => false,
                    'error' => 'Failed to save invitation image'
                ];
            }

            // Prepare WhatsApp message
            $message = $this->prepareInvitationMessage($invitation, $guest, $event);
            $caption = "🎉 " . $guest->full_name . " - You're invited! 🎉";

            // Send invitation via WhatsApp
            $result = $this->whatsAppService->sendInvitationWithImage(
                $guest->phone_number,
                $message,
                $tempImagePath,
                $caption
            );

            // Clean up temporary file
            if (file_exists($tempImagePath)) {
                unlink($tempImagePath);
            }

            // Update invitation status
            $this->updateInvitationStatus($invitation, $result);

            return $result;

        } catch (\Exception $e) {
            Log::error('WhatsApp invitation sending exception', [
                'invitation_id' => $invitation->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Update invitation status to failed
            $invitation->update([
                'status' => 'failed',
                'delivered_at' => null,
                'whatsapp_message' => $invitation->whatsapp_message . " [ERROR: " . $e->getMessage() . "]"
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send invitations to multiple guests
     */
    public function sendBulkInvitations(array $invitationIds): array
    {
        $results = [];
        $successCount = 0;
        $failureCount = 0;

        foreach ($invitationIds as $invitationId) {
            try {
                $invitation = Invitation::with(['guest', 'event'])->find($invitationId);
                
                if (!$invitation) {
                    $results[] = [
                        'invitation_id' => $invitationId,
                        'success' => false,
                        'error' => 'Invitation not found'
                    ];
                    $failureCount++;
                    continue;
                }

                $result = $this->sendInvitation($invitation);
                $results[] = array_merge($result, ['invitation_id' => $invitationId]);

                if ($result['success']) {
                    $successCount++;
                } else {
                    $failureCount++;
                }

                // Add delay between messages to avoid rate limiting
                sleep(1);

            } catch (\Exception $e) {
                Log::error('Bulk invitation sending error', [
                    'invitation_id' => $invitationId,
                    'error' => $e->getMessage()
                ]);

                $results[] = [
                    'invitation_id' => $invitationId,
                    'success' => false,
                    'error' => $e->getMessage()
                ];
                $failureCount++;
            }
        }

        return [
            'success' => $failureCount === 0,
            'message' => "Sent {$successCount} invitations successfully, {$failureCount} failed",
            'total_sent' => count($invitationIds),
            'success_count' => $successCount,
            'failure_count' => $failureCount,
            'results' => $results
        ];
    }

    /**
     * Send invitation directly from guest selection
     */
    public function sendDirectInvitation(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'guest_ids' => 'required|array|min:1',
            'guest_ids.*' => 'exists:guests,id',
            'message' => 'required|string|max:1000',
        ]);

        try {
            $host = Auth::user();
            $event = Event::where('host_id', $host->id)->first();

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'error' => 'No event found for this host'
                ], 404);
            }

            // Validate guests belong to this host's event
            $guestIds = $request->guest_ids;
            $validGuests = Guest::where('event_id', $event->id)
                ->whereIn('id', $guestIds)
                ->get();

            if ($validGuests->count() !== count($guestIds)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Some guests do not belong to your event'
                ], 400);
            }

            $invitationIds = [];

            // Create invitations for each guest
            foreach ($validGuests as $guest) {
                $invitation = Invitation::create([
                    'event_id' => $event->id,
                    'guest_id' => $guest->id,
                    'type' => 'whatsapp',
                    'status' => 'pending',
                    'whatsapp_message' => $request->message,
                ]);

                $this->invitationQrService->ensureQrCode($invitation);

                $invitationIds[] = $invitation->id;
            }

            // Send invitations via WhatsApp
            $result = $this->sendBulkInvitations($invitationIds);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Direct invitation sending error', [
                'error' => $e->getMessage(),
                'host_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to send invitations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test WhatsApp connection
     */
    public function testConnection(): \Illuminate\Http\JsonResponse
    {
        try {
            $result = $this->whatsAppService->testConnection();
            
            return response()->json($result, $result['success'] ? 200 : 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resend failed invitation
     */
    public function resendInvitation(Invitation $invitation): \Illuminate\Http\JsonResponse
    {
        try {
            // Check if invitation belongs to authenticated host
            $host = Auth::user();
            $event = Event::where('host_id', $host->id)->first();
            
            if (!$event || $invitation->event_id !== $event->id) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invitation not found or unauthorized'
                ], 403);
            }

            $result = $this->sendInvitation($invitation);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Resend invitation error', [
                'invitation_id' => $invitation->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save generated image to temporary file
     */
    protected function saveImageToTempFile($imageResource, Invitation $invitation, Guest $guest): ?string
    {
        try {
            $tempPath = tempnam(sys_get_temp_dir(), 'whatsapp_invitation_');
            $tempPath .= '.jpg';

            // Save image as JPEG
            imagejpeg($imageResource, $tempPath, 90);

            // Clean up image resource
            imagedestroy($imageResource);

            return file_exists($tempPath) ? $tempPath : null;

        } catch (\Exception $e) {
            Log::error('Failed to save temporary image', [
                'invitation_id' => $invitation->id,
                'guest_id' => $guest->id,
                'error' => $e->getMessage()
            ]);

            // Clean up image resource if not already destroyed
            if (isset($imageResource) && is_resource($imageResource)) {
                imagedestroy($imageResource);
            }

            return null;
        }
    }

    /**
     * Prepare WhatsApp invitation message
     */
    protected function prepareInvitationMessage(Invitation $invitation, Guest $guest, Event $event): string
    {
        $message = $invitation->whatsapp_message ?? "You're invited to our special event!";
        
        // Add event details
        $message .= "\n\n" . $event->title;
        
        if ($event->date) {
            $message .= "\n📅 " . $event->date->format('F d, Y');
        }
        
        if ($event->time) {
            $message .= "\n⏰ " . $event->time;
        }
        
        if ($event->venue) {
            $message .= "\n📍 " . $event->venue;
        }
        
        // Add QR code info
        $verificationUrl = $this->invitationQrService->getVerificationUrl($invitation);
        $message .= "\n\nScan the attached QR code or verify here: " . $verificationUrl;
        $message .= "\n\n📱 RSVP now!";
        
        return $message;
    }

    /**
     * Update invitation status based on sending result
     */
    protected function updateInvitationStatus(Invitation $invitation, array $result): void
    {
        if ($result['success']) {
            $invitation->update([
                'status' => 'sent',
                'delivered_at' => now(),
                'whatsapp_message' => $invitation->whatsapp_message . " [SENT: " . now()->format('Y-m-d H:i:s') . "]"
            ]);
        } else {
            $invitation->update([
                'status' => 'failed',
                'delivered_at' => null,
                'whatsapp_message' => $invitation->whatsapp_message . " [FAILED: " . ($result['error'] ?? 'Unknown error') . "]"
            ]);
        }
    }

    /**
     * Get invitation statistics
     */
    public function getStats(): \Illuminate\Http\JsonResponse
    {
        try {
            $host = Auth::user();
            $event = Event::where('host_id', $host->id)->first();

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'error' => 'No event found'
                ], 404);
            }

            $stats = [
                'total_invitations' => Invitation::where('event_id', $event->id)->count(),
                'sent' => Invitation::where('event_id', $event->id)->where('status', 'sent')->count(),
                'delivered' => Invitation::where('event_id', $event->id)->whereNotNull('delivered_at')->count(),
                'failed' => Invitation::where('event_id', $event->id)->where('status', 'failed')->count(),
                'opened' => Invitation::where('event_id', $event->id)->whereNotNull('opened_at')->count(),
                'whatsapp_enabled' => $this->whatsAppService->isConfigured()
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
