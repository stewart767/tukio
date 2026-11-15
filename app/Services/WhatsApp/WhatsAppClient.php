<?php

namespace App\Services\WhatsApp;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppClient
{
    protected string $apiVersion;
    protected string $baseGraphUrl;
    protected ?string $appId;
    protected ?string $appSecret;

    public function __construct()
    {
        $this->apiVersion = config('services.whatsapp.graph_api_version', 'v22.0');
        $this->baseGraphUrl = "https://graph.facebook.com/{$this->apiVersion}";
        $this->appId = config('services.whatsapp.app_id');
        $this->appSecret = config('services.whatsapp.app_secret');
    }

    /**
     * Get Meta App ID.
     */
    public function getAppId(): ?string
    {
        return $this->appId;
    }

    /**
     * Get Meta App Secret.
     */
    public function getAppSecret(): ?string
    {
        return $this->appSecret;
    }

    /**
     * Get Meta Graph API Version.
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    /**
     * Execute a GET request against Meta Graph API.
     */
    public function get(string $endpoint, array $query = [], ?string $accessToken = null): array
    {
        return $this->send('GET', $endpoint, ['query' => $query], $accessToken);
    }

    /**
     * Execute a POST request against Meta Graph API.
     */
    public function post(string $endpoint, array $data = [], ?string $accessToken = null): array
    {
        return $this->send('POST', $endpoint, ['json' => $data], $accessToken);
    }

    /**
     * Execute a DELETE request against Meta Graph API.
     */
    public function delete(string $endpoint, array $data = [], ?string $accessToken = null): array
    {
        return $this->send('DELETE', $endpoint, ['json' => $data], $accessToken);
    }

    /**
     * Upload multipart media file to Meta Graph API.
     */
    public function uploadMedia(string $phoneNumberId, string $filePath, string $mimeType, ?string $accessToken = null): array
    {
        $url = "{$this->baseGraphUrl}/{$phoneNumberId}/media";
        $token = $accessToken ?: config('services.whatsapp.access_token');

        if (!file_exists($filePath)) {
            return [
                'success' => false,
                'error' => "Media file does not exist at path: {$filePath}",
                'status' => 404,
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
            ])
            ->timeout(60)
            ->attach('file', file_get_contents($filePath), basename($filePath), ['Content-Type' => $mimeType])
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'type' => $mimeType,
            ]);

            return $this->formatResponse($response, 'uploadMedia', ['phone_number_id' => $phoneNumberId]);
        } catch (\Throwable $e) {
            Log::error('WhatsAppClient::uploadMedia exception', [
                'error' => $e->getMessage(),
                'phone_number_id' => $phoneNumberId,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Core HTTP execution wrapper with error normalization.
     */
    protected function send(string $method, string $endpoint, array $options, ?string $accessToken = null): array
    {
        $url = str_starts_with($endpoint, 'http') ? $endpoint : "{$this->baseGraphUrl}/" . ltrim($endpoint, '/');
        $token = $accessToken ?: config('services.whatsapp.access_token');

        $headers = [
            'Accept' => 'application/json',
        ];

        if (!empty($token)) {
            $headers['Authorization'] = "Bearer {$token}";
        }

        try {
            $httpClient = Http::withHeaders($headers)->timeout(30);

            if ($this->appSecret && !empty($token)) {
                // Generate AppSecret Proof for enhanced security if app_secret is set
                $appSecretProof = hash_hmac('sha256', $token, $this->appSecret);
                if (isset($options['query'])) {
                    $options['query']['appsecret_proof'] = $appSecretProof;
                } else {
                    $options['query'] = ['appsecret_proof' => $appSecretProof];
                }
            }

            $response = match (strtoupper($method)) {
                'GET' => $httpClient->get($url, $options['query'] ?? []),
                'POST' => $httpClient->post($url, $options['json'] ?? $options['query'] ?? []),
                'DELETE' => $httpClient->delete($url, $options['json'] ?? $options['query'] ?? []),
                default => throw new \InvalidArgumentException("Unsupported HTTP method [{$method}]"),
            };

            return $this->formatResponse($response, $endpoint, ['method' => $method]);
        } catch (\Throwable $e) {
            Log::error("WhatsAppClient::{$method} exception", [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Parse and structure response from Meta.
     */
    protected function formatResponse(Response $response, string $endpoint, array $context = []): array
    {
        $data = $response->json() ?? [];
        $status = $response->status();

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $data,
                'status' => $status,
            ];
        }

        $errorData = $data['error'] ?? [];
        $errorMessage = $errorData['message'] ?? $response->body();
        $errorCode = $errorData['code'] ?? null;
        $errorSubcode = $errorData['error_subcode'] ?? null;

        Log::warning('Meta Graph API request failed', [
            'endpoint' => $endpoint,
            'status' => $status,
            'code' => $errorCode,
            'subcode' => $errorSubcode,
            'message' => $errorMessage,
            'context' => $context,
        ]);

        return [
            'success' => false,
            'error' => $errorMessage,
            'code' => $errorCode,
            'subcode' => $errorSubcode,
            'data' => $data,
            'status' => $status,
        ];
    }
}

