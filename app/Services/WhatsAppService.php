<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WhatsAppService
{
    protected $accessToken;
    protected $phoneNumberId;
    protected $apiVersion;
    protected $baseUrl;

    public function __construct()
    {
        $this->accessToken = config('services.whatsapp.access_token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->apiVersion = config('services.whatsapp.graph_api_version', 'v18.0');
        $this->baseUrl = "https://graph.facebook.com/{$this->apiVersion}/{$this->phoneNumberId}/messages";
    }

    /**
     * Send a text message via WhatsApp
     */
    public function sendTextMessage(string $to, string $message): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->accessToken}",
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl, [
                'messaging_product' => 'whatsapp',
                'to' => $this->formatPhoneNumber($to),
                'type' => 'text',
                'text' => [
                    'body' => $message,
                    'preview_url' => false
                ]
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('WhatsApp text message sent successfully', [
                    'to' => $to,
                    'message_id' => $responseData['messages'][0]['id'] ?? null,
                    'response' => $responseData
                ]);
                
                return [
                    'success' => true,
                    'message_id' => $responseData['messages'][0]['id'] ?? null,
                    'response' => $responseData
                ];
            } else {
                Log::error('WhatsApp text message failed', [
                    'to' => $to,
                    'error' => $response->body(),
                    'status' => $response->status()
                ]);
                
                return [
                    'success' => false,
                    'error' => $response->body(),
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp text message exception', [
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
     * Send an image message via WhatsApp
     */
    public function sendImageMessage(string $to, string $imagePath, string $caption = ''): array
    {
        try {
            // First upload the media to WhatsApp servers
            $mediaId = $this->uploadMedia($imagePath);
            
            if (!$mediaId) {
                return [
                    'success' => false,
                    'error' => 'Failed to upload media to WhatsApp'
                ];
            }

            // Send the message with the media ID
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->accessToken}",
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl, [
                'messaging_product' => 'whatsapp',
                'to' => $this->formatPhoneNumber($to),
                'type' => 'image',
                'image' => [
                    'id' => $mediaId,
                    'caption' => $caption
                ]
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('WhatsApp image message sent successfully', [
                    'to' => $to,
                    'media_id' => $mediaId,
                    'message_id' => $responseData['messages'][0]['id'] ?? null,
                    'response' => $responseData
                ]);
                
                return [
                    'success' => true,
                    'message_id' => $responseData['messages'][0]['id'] ?? null,
                    'media_id' => $mediaId,
                    'response' => $responseData
                ];
            } else {
                Log::error('WhatsApp image message failed', [
                    'to' => $to,
                    'media_id' => $mediaId,
                    'error' => $response->body(),
                    'status' => $response->status()
                ]);
                
                return [
                    'success' => false,
                    'error' => $response->body(),
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp image message exception', [
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
     * Upload media to WhatsApp servers
     */
    protected function uploadMedia(string $filePath): ?string
    {
        try {
            // Ensure the file exists
            if (!file_exists($filePath)) {
                Log::error('Media file not found', ['path' => $filePath]);
                return null;
            }

            // Read the file content
            $fileContent = file_get_contents($filePath);
            $mimeType = mime_content_type($filePath);
            
            // Upload to WhatsApp
            $uploadUrl = "https://graph.facebook.com/{$this->apiVersion}/{$this->phoneNumberId}/media";
            
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->accessToken}",
            ])->attach('file', $fileContent, basename($filePath))
            ->post($uploadUrl, [
                'messaging_product' => 'whatsapp',
                'type' => $mediaType = in_array($mimeType, ['image/jpeg', 'image/jpg', 'image/png']) ? 'image' : 'document'
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Media uploaded to WhatsApp successfully', [
                    'file_path' => $filePath,
                    'media_id' => $responseData['id']
                ]);
                
                return $responseData['id'];
            } else {
                Log::error('Media upload to WhatsApp failed', [
                    'file_path' => $filePath,
                    'error' => $response->body(),
                    'status' => $response->status()
                ]);
                
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Media upload exception', [
                'file_path' => $filePath,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Send a media message using base64 data
     */
    public function sendImageFromBase64(string $to, string $base64Data, string $mimeType = 'image/jpeg', string $caption = '', string $filename = 'invitation.jpg'): array
    {
        try {
            // Create a temporary file from base64 data
            $tempPath = tempnam(sys_get_temp_dir(), 'whatsapp_invitation_');
            $tempPath .= '.' . ($mimeType === 'image/jpeg' ? 'jpg' : 'png');
            
            // Write base64 data to temporary file
            $imageData = base64_decode($base64Data);
            file_put_contents($tempPath, $imageData);
            
            // Send the image message
            $result = $this->sendImageMessage($to, $tempPath, $caption);
            
            // Clean up temporary file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('WhatsApp base64 image message exception', [
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
     * Send an invitation with both text and image
     */
    public function sendInvitationWithImage(string $phoneNumber, string $message, string $imagePath, string $caption = ''): array
    {
        try {
            // Send the image first
            $imageResult = $this->sendImageMessage($phoneNumber, $imagePath, $caption);
            
            if (!$imageResult['success']) {
                Log::warning('Image sending failed, trying text only', [
                    'phone' => $phoneNumber,
                    'image_error' => $imageResult['error']
                ]);
                
                // Fallback to text only message
                return $this->sendTextMessage($phoneNumber, $message);
            }
            
            // If image was sent successfully, also send the text message
            $textResult = $this->sendTextMessage($phoneNumber, $message);
            
            return [
                'success' => true,
                'image_sent' => $imageResult['success'],
                'text_sent' => $textResult['success'],
                'image_message_id' => $imageResult['message_id'] ?? null,
                'text_message_id' => $textResult['message_id'] ?? null,
                'total_message_id' => $imageResult['message_id'] ?? $textResult['message_id'],
                'media_id' => $imageResult['media_id'] ?? null
            ];
            
        } catch (\Exception $e) {
            Log::error('WhatsApp invitation with image exception', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format phone number for WhatsApp (remove spaces, add country code if needed)
     */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove all non-digit characters
        $cleanNumber = preg_replace('/[^\d]/', '', $phoneNumber);
        
        // If number doesn't start with country code, assume Tanzania (+255)
        if (!preg_match('/^\+?255/', $cleanNumber)) {
            $cleanNumber = '255' . ltrim($cleanNumber, '0');
        }
        
        return $cleanNumber;
    }

    /**
     * Get media URL by media ID
     */
    public function getMediaUrl(string $mediaId): ?string
    {
        try {
            $url = "https://graph.facebook.com/{$this->apiVersion}/{$mediaId}";
            
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->accessToken}",
            ])->get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['url'] ?? null;
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Failed to get media URL', [
                'media_id' => $mediaId,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Check if WhatsApp configuration is valid
     */
    public function isConfigured(): bool
    {
        return !empty($this->accessToken) && 
               !empty($this->phoneNumberId) && 
               config('services.whatsapp.access_token') !== 'your_whatsapp_access_token_here';
    }

    /**
     * Test WhatsApp connection
     */
    public function testConnection(): array
    {
        try {
            $testUrl = "https://graph.facebook.com/{$this->apiVersion}/{$this->phoneNumberId}";
            
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->accessToken}",
            ])->get($testUrl);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'WhatsApp API connection successful',
                    'data' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'WhatsApp API connection failed',
                    'error' => $response->body(),
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'WhatsApp API connection exception',
                'error' => $e->getMessage()
            ];
        }
    }
}
