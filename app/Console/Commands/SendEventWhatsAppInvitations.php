<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Guest;
use App\Services\BeemWhatsAppService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendEventWhatsAppInvitations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invitations:whatsapp:send 
                            {eventId : The ID of the event to send invitations for}
                            {--template= : The Beem template ID to use}
                            {--media= : Optional media URL for the template}
                            {--dry-run : Show what would be sent without actually sending}
                            {--limit= : Limit number of invitations to send (for testing)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp invitations via Beem Template API for an event';

    /**
     * Execute the console command.
     */
    public function handle(BeemWhatsAppService $beemService): int
    {
        $eventId = (int) $this->argument('eventId');
        $templateId = (int) $this->option('template');
        $mediaUrl = $this->option('media') ?: null;
        $dryRun = $this->option('dry-run');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        // Validate template ID
        if (!$templateId) {
            $this->error('Missing required --template=ID parameter');
            $this->line('Use --template=<template_id> to specify the Beem template ID');
            return 1;
        }

        // Check Beem configuration
        if (!$beemService->isConfigured()) {
            $this->error('Beem WhatsApp configuration is incomplete');
            $this->line('Please check your .env file for BEEM_API_KEY, BEEM_SECRET_KEY, and BEEM_WHATSAPP_FROM');
            return 1;
        }

        // Load event with guests
        $event = Event::with(['guests' => function ($query) {
            $query->where('active', true);
        }])->find($eventId);

        if (!$event) {
            $this->error("Event with ID {$eventId} not found");
            return 1;
        }

        $this->info("Found event: {$event->title}");
        $this->line("Event date: " . ($event->start_time ? $event->start_time->format('D, j M Y g:i A') : 'Not set'));

        // Build destinations for template
        $destinations = $this->buildInvitationDestinations($event, $limit);

        if (empty($destinations)) {
            $this->warn('No active guests found to send invitations to.');
            return 0;
        }

        $this->info("Prepared " . count($destinations) . " invitations to send");

        // Show preview in dry-run mode
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No messages will be sent');
            $this->line('');
            $this->line('Template ID: ' . $templateId);
            if ($mediaUrl) {
                $this->line('Media URL: ' . $mediaUrl);
            }
            $this->line('');
            $this->line('Sample destinations:');
            
            foreach (array_slice($destinations, 0, 3) as $dest) {
                $this->line("  Phone: {$dest['phoneNumber']}");
                $this->line("  Params: " . implode(', ', $dest['params']));
                $this->line('');
            }
            
            if (count($destinations) > 3) {
                $this->line('... and ' . (count($destinations) - 3) . ' more');
            }
            
            return 0;
        }

        // Confirm before sending
        if (!$this->confirm("Send " . count($destinations) . " WhatsApp invitations using template {$templateId}?")) {
            $this->info('Cancelled by user');
            return 0;
        }

        // Send bulk invitations
        $this->info('Sending bulk invitations...');
        $result = $beemService->sendTemplateBulk($destinations, $templateId, $mediaUrl);

        if ($result['success']) {
            $this->info('✅ Bulk invitations sent successfully!');
            
            $response = $result['response'];
            $this->line("Job ID: " . ($response['jobId'] ?? 'N/A'));
            $this->line("Valid recipients: " . ($result['valid_count'] ?? 0));
            $this->line("Invalid recipients: " . ($result['invalid_count'] ?? 0));
            
            if (isset($response['credits']['totalPrice'])) {
                $this->line("Total cost: " . $response['credits']['totalPrice'] . " credits");
            }

            // Log invalid numbers if any
            if (isset($response['validation']['invalidNumbers']) && !empty($response['validation']['invalidNumbers'])) {
                $this->warn('Invalid phone numbers:');
                foreach ($response['validation']['invalidNumbers'] as $invalid) {
                    $this->line("  - {$invalid['phoneNumber']}");
                }
            }

            // Log the send attempt
            Log::info('Bulk WhatsApp invitations sent', [
                'event_id' => $eventId,
                'template_id' => $templateId,
                'guest_count' => count($destinations),
                'job_id' => $response['jobId'] ?? null,
                'valid_count' => $result['valid_count'],
                'invalid_count' => $result['invalid_count']
            ]);

            return 0;
        } else {
            $this->error('❌ Failed to send bulk invitations');
            $this->line('Error: ' . ($result['error'] ?? 'Unknown error'));
            
            Log::error('Bulk WhatsApp invitations failed', [
                'event_id' => $eventId,
                'template_id' => $templateId,
                'error' => $result['error'] ?? 'Unknown error'
            ]);

            return 1;
        }
    }

    /**
     * Build invitation destinations from event guests
     * 
     * @param Event $event
     * @param int|null $limit
     * @return array
     */
    protected function buildInvitationDestinations(Event $event, ?int $limit = null): array
    {
        $guests = $event->guests()->where('active', true);
        
        if ($limit) {
            $guests = $guests->limit($limit);
        }
        
        $guests = $guests->get();

        $invitationLink = url('/invite/' . $event->id);
        $eventDate = $event->start_time ? $event->start_time->format('D, j M g:i A') : 'TBA';
        $venue = $event->venue ?? 'TBA';

        $destinations = [];

        foreach ($guests as $guest) {
            // Skip guests without phone numbers
            if (empty($guest->phone_number)) {
                continue;
            }

            // Format phone number (remove spaces, ensure international format)
            $phoneNumber = preg_replace('/[^\d]/', '', $guest->phone_number);
            if (!preg_match('/^255/', $phoneNumber)) {
                $phoneNumber = '255' . ltrim($phoneNumber, '0');
            }

            $destinations[] = [
                'phoneNumber' => $phoneNumber,
                'params' => [
                    $guest->full_name,           // {{1}} - Guest name
                    $eventDate,                 // {{2}} - Event date/time
                    $venue,                     // {{3}} - Venue
                    $invitationLink,            // {{4}} - RSVP link
                ],
            ];
        }

        return $destinations;
    }
}

