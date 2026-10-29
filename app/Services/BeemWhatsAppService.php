<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BeemWhatsAppService
{
    private string $apiKey;
    private string $secretKey;
    private string $from;
    private string $broadcastBase;
    private string $chatBase;

    public function __construct()
    {
        $cfg = config('services.beem');
        $this->apiKey = (string)($cfg['api_key'] ?? '');
        $this->secretKey = (string)($cfg['secret_key'] ?? '');
        $this->from = (string)($cfg['from'] ?? '');
        $this->broadcastBase = (string)($cfg['broadcast_base'] ?? 'https://apibroadcast.beem.africa');
        $this->chatBase = (string)($cfg['chat_base'] ?? 'https://apichatcore.beem.africa');
    }

    /**
     * Get HTTP client options including CA bundle if configured/present.
     */
    protected function httpOptions(): array
    {
        $options = [];
        $caCert = config('services.sms.beem.ca_bundle')
            ?: (env('HTTP_CA_BUNDLE') ? base_path((string) env('HTTP_CA_BUNDLE')) : base_path('storage/certs/cacert.pem'));

        if (!empty($caCert) && file_exists($caCert)) {
            $options['verify'] = $caCert;
        }

        return $options;
    }

    /**
     * Send template-based bulk messages via Beem WhatsApp API
     * 
     * @param array $destinations Array of destination objects with phoneNumber and params
     * @param int $templateId The Beem template ID (from template list endpoint)
     * @param string|null $mediaUrl Optional media URL for templates with media
     * @return array Response with success status and data
     */
    public function sendTemplateBulk(array $destinations, int $templateId, ?string $mediaUrl = null): array
    {
        // Validate required configuration
        if (empty($this->apiKey) || empty($this->secretKey) || empty($this->from)) {
            Log::error('Beem WhatsApp configuration missing', [
                'api_key_set' => !empty($this->apiKey),
                'secret_key_set' => !empty($this->secretKey),
                'from_set' => !empty($this->from)
            ]);
            
            return [
                'success' => false,
                'error' => 'Beem WhatsApp configuration is incomplete'
            ];
        }

        $url = rtrim($this->broadcastBase, '/') . '/v1/broadcast/template/api-send';

        $payload = [
            'from_addr' => $this->from,
            'destination_addr' => array_values($destinations),
            'channel' => 'whatsapp',
            'messageTemplateData' => ['id' => $templateId],
        ];

        if ($mediaUrl) {
            $payload['content'] = ['mediaUrl' => $mediaUrl];
        }

        try {
            $response = Http::withOptions($this->httpOptions())->withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':' . $this->secretKey),
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($url, $payload);

            if ($response->successful()) {
                $json = $response->json();
                Log::info('Beem template bulk send success', [
                    'template_id' => $templateId,
                    'destination_count' => count($destinations),
                    'response' => $json
                ]);
                
                return [
                    'success' => true,
                    'response' => $json,
                    'job_id' => $json['jobId'] ?? null,
                    'valid_count' => $json['validation']['validCounts'] ?? 0,
                    'invalid_count' => $json['validation']['invalidCounts'] ?? 0
                ];
            }

            Log::error('Beem template bulk send failed', [
                'template_id' => $templateId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            
            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $response->body()
            ];
        } catch (\Exception $e) {
            Log::error('Beem template bulk send exception', [
                'template_id' => $templateId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Fetch list of WhatsApp templates from Beem
     * 
     * @param array $filters Optional filters (name, category, status, etc.)
     * @return array Template list response
     */
    public function listTemplates(array $filters = []): array
    {
        if (empty($this->apiKey) || empty($this->secretKey)) {
            return [
                'success' => false,
                'error' => 'Beem API credentials not configured'
            ];
        }

        $url = rtrim($this->chatBase, '/') . '/v1/message-templates/list';
        
        try {
            $response = Http::withOptions($this->httpOptions())->withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':' . $this->secretKey),
                'Content-Type' => 'application/json',
            ])->timeout(15)->get($url, $filters);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $response->body()
            ];
        } catch (\Exception $e) {
            Log::error('Beem template list exception', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get active sessions (for 24-hour window messaging)
     * 
     * @return array Active sessions response
     */
    public function getActiveSessions(): array
    {
        if (empty($this->apiKey) || empty($this->secretKey)) {
            return [
                'success' => false,
                'error' => 'Beem API credentials not configured'
            ];
        }

        $url = rtrim($this->chatBase, '/') . '/v1/chatapi/active-users';
        
        try {
            $response = Http::withOptions($this->httpOptions())->withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':' . $this->secretKey),
                'Content-Type' => 'application/json',
            ])->timeout(15)->get($url);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $response->body()
            ];
        } catch (\Exception $e) {
            Log::error('Beem active sessions exception', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send a single text message to active session (24-hour window)
     * 
     * @param string $to Recipient phone number
     * @param string $message Text message content
     * @param string|null $transactionId Optional transaction ID
     * @return array Response with success status
     */
    public function sendTextToActiveSession(string $to, string $message, ?string $transactionId = null): array
    {
        if (empty($this->apiKey) || empty($this->secretKey) || empty($this->from)) {
            return [
                'success' => false,
                'error' => 'Beem WhatsApp configuration is incomplete'
            ];
        }

        $url = rtrim($this->chatBase, '/') . '/v1/chatapi';

        $payload = [
            'from' => $this->from,
            'to' => $this->formatPhoneNumber($to),
            'channel' => 'whatsapp',
            'message_type' => 'text',
            'text' => $message,
        ];

        if ($transactionId) {
            $payload['transaction_id'] = $transactionId;
        }

        try {
            $response = Http::withOptions($this->httpOptions())->withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':' . $this->secretKey),
                'Content-Type' => 'application/json',
            ])->timeout(15)->post($url, $payload);

            if ($response->successful()) {
                $json = $response->json();
                Log::info('Beem text message sent to active session', [
                    'to' => $to,
                    'response' => $json
                ]);
                
                return [
                    'success' => true,
                    'response' => $json
                ];
            }

            Log::error('Beem text message failed', [
                'to' => $to,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            
            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $response->body()
            ];
        } catch (\Exception $e) {
            Log::error('Beem text message exception', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format phone number for Beem API (remove spaces, ensure international format)
     * 
     * @param string $phoneNumber Raw phone number
     * @return string Formatted phone number
     */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove all non-digit characters
        $cleanNumber = preg_replace('/[^\d]/', '', $phoneNumber);
        
        // If number doesn't start with country code, assume Tanzania (+255)
        if (!preg_match('/^255/', $cleanNumber)) {
            $cleanNumber = '255' . ltrim($cleanNumber, '0');
        }
        
        return $cleanNumber;
    }

    /**
     * Check if Beem configuration is valid
     * 
     * @return bool True if configuration is complete
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && 
               !empty($this->secretKey) && 
               !empty($this->from) &&
               $this->apiKey !== 'your_beem_api_key_here' &&
               $this->secretKey !== 'your_beem_secret_key_here';
    }

    /**
     * Test Beem API connection
     * 
     * @return array Test result
     */
    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Beem configuration is incomplete'
            ];
        }

        // Test by fetching templates
        $result = $this->listTemplates(['page' => '1']);
        
        if ($result['success']) {
            return [
                'success' => true,
                'message' => 'Beem API connection successful',
                'template_count' => count($result['data']['data'] ?? [])
            ];
        }

        return [
            'success' => false,
            'message' => 'Beem API connection failed',
            'error' => $result['error'] ?? 'Unknown error'
        ];
    }
}

