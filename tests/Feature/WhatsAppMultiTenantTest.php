<?php

namespace Tests\Feature;

use App\Jobs\ProcessWhatsAppWebhookJob;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Host;
use App\Models\Invitation;
use App\Models\WhatsAppBusinessAccount;
use App\Models\WhatsAppContactConsent;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppPhoneNumber;
use App\Models\WhatsAppWebhookEvent;
use App\Services\WhatsApp\WhatsAppClient;
use App\Services\WhatsApp\WhatsAppMessagingService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WhatsAppMultiTenantTest extends TestCase
{
    use DatabaseMigrations;

    protected Host $hostA;
    protected Host $hostB;
    protected Event $eventA;
    protected WhatsAppBusinessAccount $wabaA;
    protected WhatsAppPhoneNumber $phoneA;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.whatsapp.app_id' => '123456789012345',
            'services.whatsapp.app_secret' => 'meta_super_secret_test_key_32bytes!',
            'services.whatsapp.config_id' => '9988776655',
            'services.whatsapp.webhook_verify_token' => 'tukio_verify_token_secure_xyz',
            'services.whatsapp.graph_api_version' => 'v22.0',
        ]);

        // Create Tenant Host A
        $this->hostA = Host::create([
            'full_name' => 'Baraka Mwangi',
            'email' => 'baraka@tukio360.co.tz',
            'phone' => '0712345678',
            'password' => Hash::make('password123'),
            'event_type' => 'wedding',
            'number_of_guests' => 200,
            'total_cost' => 500000,
            'is_active' => true,
            'event_configured' => true,
        ]);

        $this->eventA = Event::create([
            'host_id' => $this->hostA->id,
            'title' => 'Baraka & Neema Royal Wedding',
            'category' => 'wedding',
            'date' => now()->addDays(20)->format('Y-m-d'),
            'time' => '15:00',
            'venue' => 'Serena Hotel Dar es Salaam',
            'status' => 'active',
            'guest_count' => 200,
            'total_cost' => 500000,
        ]);

        // Connect WABA for Host A
        $this->wabaA = WhatsAppBusinessAccount::create([
            'host_id' => $this->hostA->id,
            'waba_id' => 'WABA_111111',
            'account_name' => 'Baraka Event Management',
            'currency' => 'TZS',
            'timezone_id' => 'Africa/Dar_es_Salaam',
            'message_template_namespace' => 'ns_baraka_111',
            'access_token' => 'EAA_TEST_ACCESS_TOKEN_FOR_HOST_A',
            'status' => 'active',
        ]);

        $this->phoneA = WhatsAppPhoneNumber::create([
            'whatsapp_business_account_id' => $this->wabaA->id,
            'host_id' => $this->hostA->id,
            'phone_number_id' => 'PHONE_ID_111',
            'display_phone_number' => '+255 712 345 678',
            'verified_name' => 'Baraka Wedding 2026',
            'quality_rating' => 'GREEN',
            'code_verification_status' => 'VERIFIED',
            'is_default' => true,
        ]);

        // Create Tenant Host B (Isolated tenant without WABA)
        $this->hostB = Host::create([
            'full_name' => 'Zawadi Komba',
            'email' => 'zawadi@tukio360.co.tz',
            'phone' => '0787654321',
            'password' => Hash::make('password123'),
            'event_type' => 'send_off',
            'number_of_guests' => 100,
            'total_cost' => 250000,
            'is_active' => true,
            'event_configured' => true,
        ]);
    }

    /**
     * Test 1: Access token is automatically encrypted at rest and tenant relationships are isolated.
     */
    public function test_waba_access_token_is_encrypted_at_rest_and_tenant_isolated(): void
    {
        // 1. Fetch raw value from DB without Eloquent decrypt casting
        $rawRecord = \Illuminate\Support\Facades\DB::table('whatsapp_business_accounts')
            ->where('id', $this->wabaA->id)
            ->first();

        $this->assertNotEquals('EAA_TEST_ACCESS_TOKEN_FOR_HOST_A', $rawRecord->access_token);
        $this->assertEquals('EAA_TEST_ACCESS_TOKEN_FOR_HOST_A', Crypt::decryptString($rawRecord->access_token));

        // 2. Fetch through Eloquent model (auto decrypted)
        $model = WhatsAppBusinessAccount::find($this->wabaA->id);
        $this->assertEquals('EAA_TEST_ACCESS_TOKEN_FOR_HOST_A', $model->access_token);

        // 3. Verify tenant isolation
        $this->assertTrue($this->hostA->hasConnectedWhatsApp());
        $this->assertFalse($this->hostB->hasConnectedWhatsApp());
        $this->assertEquals('WABA_111111', $this->hostA->whatsappAccount->waba_id);
        $this->assertNull($this->hostB->whatsappAccount);
    }

    /**
     * Test 2: Webhook verification challenge responds with challenge token on valid token.
     */
    public function test_webhook_verification_succeeds_with_correct_token(): void
    {
        $challenge = 'challenge_random_string_123456';

        $response = $this->get('/webhook/whatsapp?' . http_build_query([
            'hub_mode' => 'subscribe',
            'hub_verify_token' => 'tukio_verify_token_secure_xyz',
            'hub_challenge' => $challenge,
        ]));

        $response->assertStatus(200);
        $this->assertEquals($challenge, $response->getContent());
    }

    /**
     * Test 3: Webhook verification rejects invalid verification token with 403 Forbidden.
     */
    public function test_webhook_verification_rejects_invalid_token(): void
    {
        $response = $this->get('/webhook/whatsapp?' . http_build_query([
            'hub_mode' => 'subscribe',
            'hub_verify_token' => 'wrong_token',
            'hub_challenge' => 'challenge_str',
        ]));

        $response->assertStatus(403);
    }

    /**
     * Test 4: Webhook POST verifies HMAC-SHA256 signature and rejects unauthorized payloads.
     */
    public function test_webhook_post_rejects_invalid_signature(): void
    {
        $payload = json_encode(['object' => 'whatsapp_business_account', 'entry' => []]);

        $response = $this->post('/webhook/whatsapp', [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_HUB_SIGNATURE_256' => 'sha256=invalid_signature_hash_here',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test 5: Webhook POST accepts valid signature, logs raw event, and queues async job.
     */
    public function test_webhook_post_accepts_valid_hmac_signature(): void
    {
        $payload = [
            'object' => 'whatsapp_business_account',
            'entry' => [
                [
                    'id' => 'WABA_111111',
                    'changes' => [
                        [
                            'value' => [
                                'messaging_product' => 'whatsapp',
                                'metadata' => [
                                    'display_phone_number' => '255712345678',
                                    'phone_number_id' => 'PHONE_ID_111',
                                ],
                                'statuses' => [
                                    [
                                        'id' => 'wamid.HBgLMjU1NzEyMzQ1Njc4FQIAERgSRTBB',
                                        'status' => 'delivered',
                                        'timestamp' => (string) now()->timestamp,
                                        'recipient_id' => '255712345678',
                                    ],
                                ],
                            ],
                            'field' => 'messages',
                        ],
                    ],
                ],
            ],
        ];

        $rawBody = json_encode($payload);
        $appSecret = config('services.whatsapp.app_secret');
        $signature = 'sha256=' . hash_hmac('sha256', $rawBody, $appSecret);

        $response = $this->call(
            'POST',
            '/webhook/whatsapp',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_HUB_SIGNATURE_256' => $signature,
            ],
            $rawBody
        );

        $response->assertStatus(200);
        $this->assertEquals('EVENT_RECEIVED', $response->getContent());

        // Verify stored in audit table
        $this->assertDatabaseHas('whatsapp_webhook_events', [
            'waba_id' => 'WABA_111111',
            'phone_number_id' => 'PHONE_ID_111',
            'event_type' => 'status',
        ]);
    }

    /**
     * Test 6: ProcessWhatsAppWebhookJob updates delivery status and reads receipts.
     */
    public function test_process_webhook_job_updates_message_status_to_delivered_and_read(): void
    {
        // Create an existing outbound message record
        $msg = WhatsAppMessage::create([
            'host_id' => $this->hostA->id,
            'event_id' => $this->eventA->id,
            'whatsapp_phone_number_id' => $this->phoneA->id,
            'recipient_phone' => '255712345678',
            'type' => 'template',
            'wamid' => 'wamid.HBgLMjU1NzEyMzQ1Njc4FQIAERgSRTBB',
            'status' => 'sent',
            'sent_at' => now()->subMinutes(5),
        ]);

        // 1. Simulate 'delivered' status payload
        $deliveredPayload = [
            'entry' => [
                [
                    'id' => 'WABA_111111',
                    'changes' => [
                        [
                            'value' => [
                                'metadata' => ['phone_number_id' => 'PHONE_ID_111'],
                                'statuses' => [
                                    [
                                        'id' => 'wamid.HBgLMjU1NzEyMzQ1Njc4FQIAERgSRTBB',
                                        'status' => 'delivered',
                                        'timestamp' => (string) now()->timestamp,
                                        'recipient_id' => '255712345678',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        (new ProcessWhatsAppWebhookJob($deliveredPayload))->handle();

        $msg->refresh();
        $this->assertEquals('delivered', $msg->status);
        $this->assertNotNull($msg->delivered_at);

        // 2. Simulate 'read' status payload
        $readPayload = [
            'entry' => [
                [
                    'id' => 'WABA_111111',
                    'changes' => [
                        [
                            'value' => [
                                'metadata' => ['phone_number_id' => 'PHONE_ID_111'],
                                'statuses' => [
                                    [
                                        'id' => 'wamid.HBgLMjU1NzEyMzQ1Njc4FQIAERgSRTBB',
                                        'status' => 'read',
                                        'timestamp' => (string) now()->timestamp,
                                        'recipient_id' => '255712345678',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        (new ProcessWhatsAppWebhookJob($readPayload))->handle();

        $msg->refresh();
        $this->assertEquals('read', $msg->status);
        $this->assertNotNull($msg->read_at);
    }

    /**
     * Test 7: Incoming STOP keyword automatically registers opt-out consent.
     */
    public function test_incoming_stop_keyword_registers_opt_out(): void
    {
        $incomingStopPayload = [
            'entry' => [
                [
                    'id' => 'WABA_111111',
                    'changes' => [
                        [
                            'value' => [
                                'metadata' => ['phone_number_id' => 'PHONE_ID_111'],
                                'messages' => [
                                    [
                                        'from' => '255712345678',
                                        'id' => 'wamid.INCOMING_USER_STOP',
                                        'timestamp' => (string) now()->timestamp,
                                        'type' => 'text',
                                        'text' => ['body' => 'STOP'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        (new ProcessWhatsAppWebhookJob($incomingStopPayload))->handle();

        $this->assertDatabaseHas('whatsapp_contact_consents', [
            'host_id' => $this->hostA->id,
            'phone' => '255712345678',
            'status' => 'opted_out',
        ]);

        $consent = WhatsAppContactConsent::where('host_id', $this->hostA->id)
            ->where('phone', '255712345678')
            ->first();
        $this->assertNotNull($consent);
        $this->assertTrue($consent->has_opted_out);
        $this->assertNotNull($consent->opted_out_at);
    }

    /**
     * Test 8: Phone number normalization to E.164 without leading plus.
     */
    public function test_phone_number_normalization(): void
    {
        $messagingService = app(WhatsAppMessagingService::class);

        $this->assertEquals('255712345678', $messagingService->formatPhoneNumber('0712345678'));
        $this->assertEquals('255712345678', $messagingService->formatPhoneNumber('+255 712 345 678'));
        $this->assertEquals('255688112233', $messagingService->formatPhoneNumber('255 688 112 233'));
        $this->assertEquals('255754000111', $messagingService->formatPhoneNumber('0754 000 111'));
    }

    /**
     * Test 9: Host Hub view displays connection details when authenticated as Host.
     */
    public function test_host_whatsapp_hub_renders_successfully(): void
    {
        $response = $this->actingAs($this->hostA, 'host')
            ->get(route('host.whatsapp.hub'));

        $response->assertStatus(200);
        $response->assertSee('WhatsApp Business Hub');
        $response->assertSee('WABA_111111');
        $response->assertSee('+255 712 345 678');
    }

    /**
     * Test 10: Disconnecting WABA soft deletes/removes the WABA relationship.
     */
    public function test_host_can_disconnect_waba(): void
    {
        $response = $this->actingAs($this->hostA, 'host')
            ->post(route('host.whatsapp.disconnect'));

        $response->assertRedirect(route('host.whatsapp.hub'));

        $this->hostA->refresh();
        $this->assertFalse($this->hostA->hasConnectedWhatsApp());
    }
}
