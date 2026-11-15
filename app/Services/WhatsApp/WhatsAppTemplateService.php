<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppBusinessAccount;
use App\Models\WhatsAppMessageTemplate;
use Illuminate\Support\Facades\Log;

class WhatsAppTemplateService
{
    protected WhatsAppClient $client;

    public function __construct(WhatsAppClient $client)
    {
        $this->client = $client;
    }

    /**
     * Synchronize all approved/active message templates from Meta Graph API for a WABA.
     */
    public function syncTemplatesFromMeta(WhatsAppBusinessAccount $account): array
    {
        $token = $account->access_token;
        if (empty($token)) {
            return [
                'success' => false,
                'error' => 'No access token available for this WhatsApp Business Account.',
            ];
        }

        $result = $this->client->get("{$account->waba_id}/message_templates", [
            'limit' => 100,
            'fields' => 'id,name,language,status,category,components,rejected_reason',
        ], $token);

        if (!$result['success']) {
            return [
                'success' => false,
                'error' => 'Failed to fetch templates from Meta: ' . ($result['error'] ?? 'Unknown error'),
                'details' => $result,
            ];
        }

        $templatesData = $result['data']['data'] ?? [];
        $syncedCount = 0;

        foreach ($templatesData as $tpl) {
            WhatsAppMessageTemplate::updateOrCreate(
                [
                    'whatsapp_business_account_id' => $account->id,
                    'name' => $tpl['name'],
                    'language' => $tpl['language'] ?? 'en',
                ],
                [
                    'host_id' => $account->host_id,
                    'meta_template_id' => $tpl['id'] ?? null,
                    'category' => $tpl['category'] ?? 'UTILITY',
                    'status' => strtoupper($tpl['status'] ?? 'APPROVED'),
                    'components' => $tpl['components'] ?? [],
                    'rejected_reason' => $tpl['rejected_reason'] ?? null,
                ]
            );
            $syncedCount++;
        }

        Log::info('Synchronized WhatsApp message templates from Meta', [
            'waba_id' => $account->waba_id,
            'count' => $syncedCount,
        ]);

        return [
            'success' => true,
            'synced_count' => $syncedCount,
            'templates' => $account->templates()->get(),
        ];
    }

    /**
     * Submit a standard Tukio360 template creation request to Meta for approval.
     */
    public function registerTukioStandardTemplate(WhatsAppBusinessAccount $account, string $type = 'invitation'): array
    {
        $token = $account->access_token;
        if (empty($token)) {
            return ['success' => false, 'error' => 'Account access token missing.'];
        }

        $templatePayload = match ($type) {
            'reminder' => [
                'name' => 'tukio_event_reminder',
                'category' => 'UTILITY',
                'language' => 'en',
                'components' => [
                    [
                        'type' => 'HEADER',
                        'format' => 'TEXT',
                        'text' => 'Event Reminder'
                    ],
                    [
                        'type' => 'BODY',
                        'text' => 'Hello {{1}}, this is a friendly reminder that {{2}} is approaching on {{3}} at {{4}}. We look forward to celebrating with you! Event Details: {{5}}',
                        'example' => [
                            'body_text' => [
                                ['Doris Jackson', 'Michael & Jessica Wedding', 'Saturday, Dec 12, 2026', 'Serena Hotel Ballroom', 'https://tukio360.co.tz/e/sample']
                            ]
                        ]
                    ],
                    [
                        'type' => 'FOOTER',
                        'text' => 'Sent via Tukio360'
                    ]
                ]
            ],
            'rsvp_confirmation' => [
                'name' => 'tukio_rsvp_confirmation',
                'category' => 'UTILITY',
                'language' => 'en',
                'components' => [
                    [
                        'type' => 'HEADER',
                        'format' => 'TEXT',
                        'text' => 'RSVP Confirmed'
                    ],
                    [
                        'type' => 'BODY',
                        'text' => 'Hello {{1}}, your attendance for {{2}} has been confirmed! Your entry pass code is {{3}}. View your digital pass & QR code here: {{4}}',
                        'example' => [
                            'body_text' => [
                                ['Doris Jackson', 'Michael & Jessica Wedding', 'PASS-84920', 'https://tukio360.co.tz/track?query=PASS-84920']
                            ]
                        ]
                    ],
                    [
                        'type' => 'FOOTER',
                        'text' => 'Present your QR code at the entrance'
                    ]
                ]
            ],
            default => [
                'name' => 'tukio_digital_invitation',
                'category' => 'UTILITY',
                'language' => 'en',
                'components' => [
                    [
                        'type' => 'HEADER',
                        'format' => 'TEXT',
                        'text' => 'Celebration Invitation'
                    ],
                    [
                        'type' => 'BODY',
                        'text' => 'Hello {{1}}, you are cordially invited to celebrate {{2}} on {{3}} at {{4}}. Please view your official digital invitation and confirm your attendance (RSVP) here: {{5}}',
                        'example' => [
                            'body_text' => [
                                ['Doris Jackson', 'Michael & Jessica Wedding', 'Saturday, Dec 12, 2026', 'Serena Hotel Ballroom', 'https://tukio360.co.tz/e/sample']
                            ]
                        ]
                    ],
                    [
                        'type' => 'FOOTER',
                        'text' => 'RSVP via Tukio360'
                    ]
                ]
            ],
        };

        return $this->client->post("{$account->waba_id}/message_templates", $templatePayload, $token);
    }
}

