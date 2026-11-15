<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\WhatsAppBusinessAccount;
use App\Models\WhatsAppContactConsent;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppPhoneNumber;
use App\Services\WhatsApp\WhatsAppAuthService;
use App\Services\WhatsApp\WhatsAppMessagingService;
use App\Services\WhatsApp\WhatsAppTemplateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class WhatsAppController extends Controller
{
    public function __construct(
        protected WhatsAppAuthService $authService,
        protected WhatsAppTemplateService $templateService,
        protected WhatsAppMessagingService $messagingService
    ) {}

    /**
     * Display the host's WhatsApp Integration Hub.
     */
    public function index(Request $request): View
    {
        $host = Auth::guard('host')->user();
        $event = $host->event;

        $account = $host->whatsappAccount;
        $phoneNumber = $account?->defaultPhoneNumber;
        $phoneNumbers = $account ? $account->phoneNumbers : collect();

        // Synced Meta templates
        $templates = $account
            ? $account->messageTemplates()->orderBy('template_name')->get()
            : collect();

        // Outbox / Message history (scoped strictly to this host)
        $messagesQuery = WhatsAppMessage::where('host_id', $host->id)
            ->with(['guest', 'event', 'phoneNumber'])
            ->latest();

        if ($statusFilter = $request->get('status')) {
            $messagesQuery->where('status', $statusFilter);
        }

        if ($typeFilter = $request->get('type')) {
            $messagesQuery->where('type', $typeFilter);
        }

        $messages = $messagesQuery->paginate(20)->withQueryString();

        // High-level delivery metrics
        $stats = [
            'total' => WhatsAppMessage::where('host_id', $host->id)->count(),
            'sent' => WhatsAppMessage::where('host_id', $host->id)->where('status', 'sent')->count(),
            'delivered' => WhatsAppMessage::where('host_id', $host->id)->where('status', 'delivered')->count(),
            'read' => WhatsAppMessage::where('host_id', $host->id)->where('status', 'read')->count(),
            'failed' => WhatsAppMessage::where('host_id', $host->id)->where('status', 'failed')->count(),
        ];

        // Opted-out contacts / Consent records
        $consents = WhatsAppContactConsent::where('host_id', $host->id)
            ->latest()
            ->take(15)
            ->get();

        // Guest list for sending broadcasts / invitations
        $guests = $event
            ? $event->guests()->with('invitation')->orderBy('name')->get()
            : collect();

        // Meta SDK credentials for Embedded Signup
        $metaConfig = [
            'app_id' => config('services.whatsapp.app_id'),
            'config_id' => config('services.whatsapp.config_id'),
            'graph_version' => config('services.whatsapp.graph_api_version', 'v22.0'),
        ];

        return view('host.whatsapp.index', compact(
            'host',
            'event',
            'account',
            'phoneNumber',
            'phoneNumbers',
            'templates',
            'messages',
            'stats',
            'consents',
            'guests',
            'metaConfig'
        ));
    }

    /**
     * Connect customer's WABA via Meta Embedded Signup callback.
     */
    public function connect(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'waba_id' => 'nullable|string',
            'phone_number_id' => 'nullable|string',
        ]);

        $host = Auth::guard('host')->user();

        $result = $this->authService->connectCustomerWaba(
            $host,
            $validated['code'],
            $validated['waba_id'] ?? null,
            $validated['phone_number_id'] ?? null
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['error'] ?? 'Imeshindwa kuunganisha WhatsApp Business Account. Tafadhali jaribu tena.',
            ], 422);
        }

        // Auto-sync initial templates in background
        if (!empty($result['account'])) {
            try {
                $this->templateService->syncTemplatesFromMeta($result['account']);
            } catch (\Throwable $e) {
                Log::warning('[WhatsAppHost] Auto-sync templates on connect failed: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Nambari yako ya WhatsApp Business imeunganishwa kikamilifu!',
            'account' => [
                'waba_id' => $result['account']->waba_id,
                'name' => $result['account']->name,
                'status' => $result['account']->status,
            ],
            'phone' => !empty($result['phone']) ? [
                'display_phone_number' => $result['phone']->display_phone_number,
                'verified_name' => $result['phone']->verified_name,
                'quality_rating' => $result['phone']->quality_rating,
            ] : null,
        ]);
    }

    /**
     * Disconnect customer's WABA.
     */
    public function disconnect(Request $request): RedirectResponse
    {
        $host = Auth::guard('host')->user();

        $result = $this->authService->disconnectCustomerWaba($host);

        if ($result['success']) {
            return redirect()->route('host.whatsapp.hub')->with('success', 'WhatsApp Business Account imetenganishwa kwa ufanisi.');
        }

        return redirect()->route('host.whatsapp.hub')->with('error', $result['error'] ?? 'Imeshindwa kutenganisha akaunti.');
    }

    /**
     * Sync approved message templates directly from Meta Graph API.
     */
    public function syncTemplates(Request $request): RedirectResponse
    {
        $host = Auth::guard('host')->user();
        $account = $host->whatsappAccount;

        if (!$account) {
            return redirect()->route('host.whatsapp.hub')->with('error', 'Hujaunganisha WhatsApp Business Account bado.');
        }

        $result = $this->templateService->syncTemplatesFromMeta($account);

        if ($result['success']) {
            return redirect()->route('host.whatsapp.hub')->with('success', "Violezo {$result['synced_count']} vya ujumbe vimelandanishwa kutoka Meta.");
        }

        return redirect()->route('host.whatsapp.hub')->with('error', $result['error'] ?? 'Hitilafu wakati wa kulandanisha violezo.');
    }

    /**
     * Register Tukio360 standard event templates on customer's WABA.
     */
    public function registerTemplates(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => 'required|in:invitation,reminder_3day,reminder_1day,rsvp_confirmation',
        ]);

        $host = Auth::guard('host')->user();
        $account = $host->whatsappAccount;

        if (!$account) {
            return redirect()->route('host.whatsapp.hub')->with('error', 'Hujaunganisha WhatsApp Business Account bado.');
        }

        $result = $this->templateService->registerTukioStandardTemplate($account, $request->input('type'));

        if ($result['success']) {
            return redirect()->route('host.whatsapp.hub')->with('success', 'Kiolezo kimetumwa Meta kwa ukaguzi na kuidhinishwa.');
        }

        return redirect()->route('host.whatsapp.hub')->with('error', $result['error'] ?? 'Hitilafu wakati wa kutengeneza kiolezo Meta.');
    }

    /**
     * Send a single live test WhatsApp message to the organizer.
     */
    public function sendTestMessage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone_number' => 'required|string',
            'message' => 'nullable|string|max:500',
        ]);

        $host = Auth::guard('host')->user();

        if (!$host->hasConnectedWhatsApp()) {
            return response()->json([
                'success' => false,
                'message' => 'Hujaunganisha nambari ya WhatsApp Business bado.',
            ], 422);
        }

        $text = !empty($validated['message'])
            ? $validated['message']
            : "Habari kutoka Tukio360! Muunganisho wako wa WhatsApp Cloud API unafanya kazi kikamilifu. Ujumbe huu umetumwa kutoka nambari yako ya biashara.";

        $result = $this->messagingService->sendTextMessage(
            $host,
            $validated['phone_number'],
            $text,
            $host->event
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Ujumbe wa jaribio umetumwa kikamilifu kwenye nambari yako ya WhatsApp!',
                'whatsapp_message_id' => $result['whatsapp_message_id'] ?? null,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['error'] ?? 'Imeshindwa kutuma ujumbe. Tafadhali thibitisha nambari na salio la Meta.',
        ], 422);
    }

    /**
     * Dispatch WhatsApp batch broadcast (Digital invitations or countdown reminders).
     */
    public function sendBroadcast(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'broadcast_type' => 'required|in:invitation,reminder_3day,reminder_1day',
            'recipient_scope' => 'required|in:all,selected',
            'guest_ids' => 'nullable|array',
            'guest_ids.*' => 'integer|exists:guests,id',
        ]);

        $host = Auth::guard('host')->user();
        $event = $host->event;

        if (!$host->hasConnectedWhatsApp()) {
            return redirect()->route('host.whatsapp.hub')->with('error', 'Hujaunganisha nambari ya WhatsApp Business bado.');
        }

        if (!$event) {
            return redirect()->route('host.whatsapp.hub')->with('error', 'Tafadhali kwanza sanidi taarifa za tukio lako.');
        }

        // Query target guests belonging strictly to this host's event
        $guestsQuery = Guest::where('event_id', $event->id)->whereNotNull('phone_number');

        if ($validated['recipient_scope'] === 'selected') {
            if (empty($validated['guest_ids'])) {
                return redirect()->route('host.whatsapp.hub')->with('error', 'Tafadhali chagua angalau mgeni mmoja.');
            }
            $guestsQuery->whereIn('id', $validated['guest_ids']);
        }

        $guests = $guestsQuery->get();
        if ($guests->isEmpty()) {
            return redirect()->route('host.whatsapp.hub')->with('error', 'Hakuna wageni wenye nambari za simu zilizopatikana.');
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($guests as $guest) {
            if ($validated['broadcast_type'] === 'invitation') {
                // Ensure invitation record exists
                $invitation = Invitation::firstOrCreate(
                    ['event_id' => $event->id, 'guest_id' => $guest->id],
                    [
                        'invitation_code' => 'TUKIO-' . strtoupper(bin2hex(random_bytes(3))),
                        'qr_code' => bin2hex(random_bytes(16)),
                        'status' => 'pending',
                    ]
                );

                $res = $this->messagingService->sendInvitation($host, $invitation);
                if ($res['success']) {
                    $sentCount++;
                } else {
                    $failedCount++;
                }
            } elseif (in_array($validated['broadcast_type'], ['reminder_3day', 'reminder_1day'])) {
                $days = $validated['broadcast_type'] === 'reminder_3day' ? 3 : 1;
                $res = $this->messagingService->sendEventReminder($host, $guest, $event, $days);
                if ($res['success']) {
                    $sentCount++;
                } else {
                    $failedCount++;
                }
            }
        }

        $msg = "Matangazo ya WhatsApp yamekamilika: {$sentCount} yametumwa kikamilifu";
        if ($failedCount > 0) {
            $msg .= ", {$failedCount} yameshindwa (tazama orodha ya ujumbe kwa maelezo zaidi).";
        }

        return redirect()->route('host.whatsapp.hub')->with($failedCount > 0 ? 'warning' : 'success', $msg);
    }

    /**
     * Retry sending an eligible failed message.
     */
    public function retryMessage(int $id): RedirectResponse
    {
        $host = Auth::guard('host')->user();
        $message = WhatsAppMessage::where('id', $id)->where('host_id', $host->id)->firstOrFail();

        if ($message->status !== 'failed') {
            return redirect()->route('host.whatsapp.hub')->with('error', 'Ujumbe huu hauna hadhi ya kushindwa.');
        }

        if ($message->guest && $message->event && ($message->type === 'template' || $message->template_name === 'invitation')) {
            $invitation = Invitation::where('guest_id', $message->guest_id)->where('event_id', $message->event_id)->first();
            if ($invitation) {
                $res = $this->messagingService->sendInvitation($host, $invitation);
                if ($res['success']) {
                    return redirect()->route('host.whatsapp.hub')->with('success', 'Ujumbe umetumwa tena kikamilifu.');
                }
                return redirect()->route('host.whatsapp.hub')->with('error', $res['error'] ?? 'Imeshindwa kutuma tena.');
            }
        }

        // Generic text message retry
        $res = $this->messagingService->sendTextMessage(
            $host,
            $message->recipient_phone,
            $message->content ?? 'Tukio360 Notification',
            $message->event,
            $message->guest
        );

        if ($res['success']) {
            return redirect()->route('host.whatsapp.hub')->with('success', 'Ujumbe umetumwa tena kikamilifu.');
        }

        return redirect()->route('host.whatsapp.hub')->with('error', $res['error'] ?? 'Imeshindwa kutuma tena.');
    }
}
