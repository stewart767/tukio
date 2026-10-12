<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WhatsAppWebhookController extends Controller
{
    /**
     * Handle WhatsApp webhook verification
     */
    public function verify(Request $request)
    {
        $verifyToken = config('services.whatsapp.webhook_verify_token');
        
        if ($request->query('hub_verify_token') === $verifyToken) {
            Log::info('WhatsApp webhook verified successfully');
            return response($request->query('hub_challenge'));
        }
        
        Log::warning('WhatsApp webhook verification failed', [
            'received_token' => $request->query('hub_verify_token'),
            'expected_token' => $verifyToken ? 'SET' : 'NOT_SET'
        ]);
        
        return response('Verification Failed', 403);
    }

    /**
     * Handle WhatsApp webhook events
     */
    public function webhook(Request $request)
    {
        try {
            $data = $request->all();
            Log::info('WhatsApp webhook received', ['data' => $data]);

            // Verify the webhook signature
            if (!$this->verifyWebhookSignature($request)) {
                Log::warning('WhatsApp webhook signature verification failed');
                return response('Unauthorized', 401);
            }

            // Handle status updates
            if (isset($data['entry'])) {
                foreach ($data['entry'] as $entry) {
                    if (isset($entry['changes'])) {
                        foreach ($entry['changes'] as $change) {
                            $this->handleStatusUpdate($change['value'] ?? []);
                        }
                    }
                }
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('WhatsApp webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);
            
            return response('Internal Server Error', 500);
        }
    }

    /**
     * Handle status updates from WhatsApp
     */
    protected function handleStatusUpdate(array $value)
    {
        if (isset($value['statuses'])) {
            foreach ($value['statuses'] as $status) {
                $this->updateInvitationStatus($status);
            }
        }

        // Handle incoming messages (for future features)
        if (isset($value['messages'])) {
            foreach ($value['messages'] as $message) {
                $this->handleIncomingMessage($message);
            }
        }
    }

    /**
     * Update invitation status based on WhatsApp delivery report
     */
    protected function updateInvitationStatus(array $status)
    {
        try {
            $messageId = $status['id'];
            $statusCode = $status['status'];
            $timestamp = $status['timestamp'];
            $recipientPhone = $status['recipient_id'];

            Log::info('Processing WhatsApp status update', [
                'message_id' => $messageId,
                'status' => $statusCode,
                'recipient' => $recipientPhone
            ]);

            // Find invitation by phone number (we'll need to store message_id in invitations table)
            $invitation = Invitation::whereHas('guest', function($query) use ($recipientPhone) {
                // Remove + prefix and format phone number
                $cleanPhone = preg_replace('/[^\d]/', '', $recipientPhone);
                $query->where('phone_number', 'LIKE', '%' . $cleanPhone . '%');
            })->latest()->first();

            if (!$invitation) {
                Log::warning('WhatsApp status update - invitation not found', [
                    'message_id' => $messageId,
                    'recipient' => $recipientPhone
                ]);
                return;
            }

            // Update status based on WhatsApp status
            $updatedStatus = $this->mapWhatsAppStatusToAppStatus($statusCode);
            
            if ($updatedStatus) {
                $updateData = [
                    'status' => $updatedStatus,
                    'delivered_at' => 
                        'delivered' === $updatedStatus 
                            ? now()->setTimestamp((int) $timestamp)
                            : ($invitation->delivered_at ?? null),
                ];

                // If read receipt, update opened_at
                if ('read' === $statusCode) {
                    $updateData['opened_at'] = now()->setTimestamp((int) $timestamp);
                }

                $invitation->update(array_filter($updateData));

                Log::info('Updated invitation status', [
                    'invitation_id' => $invitation->id,
                    'whatsapp_status' => $statusCode,
                    'app_status' => $updatedStatus
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to update invitation status', [
                'error' => $e->getMessage(),
                'status_data' => $status
            ]);
        }
    }

    /**
     * Map WhatsApp status to application status
     */
    protected function mapWhatsAppStatusToAppStatus(string $whatsappStatus): ?string
    {
        $statusMap = [
            'sent' => 'sent',
            'delivered' => 'delivered', 
            'read' => 'opened',
            'failed' => 'failed',
        ];

        return $statusMap[$whatsappStatus] ?? null;
    }

    /**
     * Handle incoming messages (for future implementation)
     */
    protected function handleIncomingMessage(array $message)
    {
        Log::info('WhatsApp incoming message', [
            'message_id' => $message['id'],
            'from' => $message['from'],
            'type' => $message['type'],
            'timestamp' => $message['timestamp']
        ]);

        // Future implementation: Handle RSVP responses, etc.
    }

    /**
     * Verify webhook signature
     */
    protected function verifyWebhookSignature(Request $request): bool
    {
        $signature = $request->header('X-Hub-Signature-256');
        $secret = config('services.whatsapp.webhook_secret');

        if (!$signature || !$secret) {
            return false;
        }

        $payload = $request->getContent();
        $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

        return hash_equals($signature, $expectedSignature);
    }

    /**
     * Get delivery statistics
     */
    public function getDeliveryStats(Request $request)
    {
        try {
            $request->validate([
                'event_id' => 'required|exists:events,id'
            ]);

            $stats = [
                'total_sent' => Invitation::where('event_id', $request->event_id)->count(),
                'sent' => Invitation::where('event_id', $request->event_id)->where('status', 'sent')->count(),
                'delivered' => Invitation::where('event_id', $request->event_id)->whereNotNull('delivered_at')->count(),
                'opened' => Invitation::where('event_id', $request->event_id)->whereNotNull('opened_at')->count(),
                'failed' => Invitation::where('event_id', $request->event_id)->where('status', 'failed')->count(),
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
