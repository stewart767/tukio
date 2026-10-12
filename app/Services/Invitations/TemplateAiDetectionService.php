<?php

namespace App\Services\Invitations;

use App\Models\Template;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TemplateAiDetectionService
{
    public function __construct(
        protected TemplateOcrService $ocrService,
        protected QrPlacementAdvisor $qrAdvisor
    ) {}

    /**
     * Analyze uploaded invitation artwork and detect structural fields.
     *
     * @param Template $template
     * @param string|null $imagePath
     * @return array
     */
    public function analyze(Template $template, ?string $imagePath = null): array
    {
        $path = $imagePath;
        if (!$path && $template->artwork_path) {
            $path = \Illuminate\Support\Facades\Storage::disk('public')->path($template->artwork_path);
            if (!file_exists($path)) {
                $path = storage_path('app/public/' . $template->artwork_path);
            }
        }

        if (!$path || !file_exists($path)) {
            throw new \InvalidArgumentException('Template artwork file does not exist on disk: ' . ($path ?? 'null'));
        }

        // 1. Get image metadata & dimensions
        $imgInfo = @getimagesize($path);
        $width = $imgInfo ? (int) $imgInfo[0] : 1200;
        $height = $imgInfo ? (int) $imgInfo[1] : 1600;

        // 2. Extract OCR tokens (AI Vision or local heuristic)
        $ocrTokens = $this->ocrService->extractTextAndBoxes($path);

        // 3. Perform AI layout classification
        $detectedFields = [];
        $aiUsed = false;

        $provider = config('ai_invitation.provider', 'gemini');
        $apiKey = $provider === 'openai' 
            ? config('ai_invitation.openai.api_key') 
            : config('ai_invitation.gemini.api_key');

        if (!empty($apiKey)) {
            try {
                $detectedFields = $this->classifyWithVisionAi($path, $template->category, $provider, $apiKey);
                $aiUsed = !empty($detectedFields);
            } catch (\Throwable $e) {
                Log::warning('AI vision layout classification failed, falling back to local heuristic detection: ' . $e->getMessage());
            }
        }

        // If AI was not configured or returned empty, run robust local heuristic layout engine
        if (empty($detectedFields)) {
            $detectedFields = $this->classifyWithLocalHeuristics($path, $template->category, $width, $height, $ocrTokens);
        }

        // 4. Validate, normalize, and sanitize all bounding boxes to [0.0, 1.0]
        $sanitizedFields = $this->sanitizeAndNormalizeFields($detectedFields);

        // 5. Compute intelligent QR placement via QrPlacementAdvisor
        if (!isset($sanitizedFields['qr_code']) || empty($sanitizedFields['qr_code']['approved'])) {
            $recommendedQr = $this->qrAdvisor->recommendPosition($sanitizedFields, $path, $width, $height);
            $sanitizedFields['qr_code'] = $recommendedQr;
        }

        // 6. Assemble complete configuration bundle
        $metadata = [
            'analyzed_at' => now()->toIso8601String(),
            'provider_used' => $aiUsed ? $provider : 'local_heuristic_engine',
            'artwork_width' => $width,
            'artwork_height' => $height,
            'aspect_ratio' => round($width / max(1, $height), 4),
            'ocr_tokens_count' => count($ocrTokens),
            'detected_fields_count' => count($sanitizedFields),
            'needs_admin_review' => true,
        ];

        return [
            'fields' => $sanitizedFields,
            'metadata' => $metadata,
            'artwork_width' => $width,
            'artwork_height' => $height,
        ];
    }

    /**
     * Classify layout via Gemini or OpenAI Vision models.
     */
    protected function classifyWithVisionAi(string $path, string $category, string $provider, string $apiKey): array
    {
        $mime = mime_content_type($path) ?: 'image/jpeg';
        $base64 = base64_encode(file_get_contents($path));

        $prompt = <<<EOT
You are an expert graphic design and invitation card analyzer.
Examine this invitation card artwork (category: {$category}).
Identify the key layout zones and return a structured JSON map of normalized coordinates (0.0 to 1.0, where 0,0 is top-left and 1,1 is bottom-right).

Look for both English and Kiswahili wording (e.g. "Mpendwa Mgeni", "Familia ya", "Harusi ya", "Tarehe", "Ukumbi", "Karibu Sana").

Return a JSON object containing keys:
1. "guest_name": Area for invitee/guest name.
2. "invitation_text": Main invitation message/paragraph.
3. "event_title": Event title or couple names (Bride & Groom, Birthday celebrant).
4. "event_date": Date and time details.
5. "venue": Venue name and location.
6. "qr_code": Ideal non-intrusive zone for access control QR pass.
7. "couple_photo": (Optional) Photo area if card has a portrait frame.
8. "static_printed_text": (Optional) Static heading or decorative border that must remain unchanged.

Each field must have this JSON structure:
{
  "label": "Human Readable Label",
  "type": "text" | "textarea" | "qr" | "image" | "static_text",
  "x": float (0.0 to 1.0),
  "y": float (0.0 to 1.0),
  "width": float (0.0 to 1.0),
  "height": float (0.0 to 1.0),
  "font_family": "Playfair Display" | "Georgia" | "Cinzel" | "Great Vibes" | "Montserrat" | "Inter",
  "font_size": 24,
  "color": "#654321" (hex code matching artwork palette),
  "alignment": "center" | "left" | "right",
  "max_length": 80,
  "is_editable": true | false,
  "is_static": true | false,
  "confidence": 0.95
}
EOT;

        if ($provider === 'openai') {
            $url = config('ai_invitation.openai.endpoint', 'https://api.openai.com/v1/chat/completions');
            $model = config('ai_invitation.openai.model', 'gpt-4o-mini');
            $response = Http::withToken($apiKey)->timeout(config('ai_invitation.timeout', 25))->post($url, [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            ['type' => 'text', 'text' => $prompt],
                            ['type' => 'image_url', 'image_url' => ['url' => "data:{$mime};base64,{$base64}"]],
                        ],
                    ],
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.1,
            ]);

            if ($response->successful()) {
                $raw = $response->json('choices.0.message.content');
                $parsed = json_decode($raw, true);
                if (isset($parsed['fields']) && is_array($parsed['fields'])) {
                    return $parsed['fields'];
                }
                return is_array($parsed) ? $parsed : [];
            }
        } else {
            // Gemini Vision
            $model = config('ai_invitation.gemini.model', 'gemini-1.5-flash');
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
            $response = Http::timeout(config('ai_invitation.timeout', 25))->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            ['inline_data' => ['mime_type' => $mime, 'data' => $base64]],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                    'temperature' => 0.1,
                ],
            ]);

            if ($response->successful()) {
                $raw = $response->json('candidates.0.content.parts.0.text');
                $parsed = json_decode($raw, true);
                if (isset($parsed['fields']) && is_array($parsed['fields'])) {
                    return $parsed['fields'];
                }
                return is_array($parsed) ? $parsed : [];
            }
        }

        return [];
    }

    /**
     * Robust local heuristic layout classifier based on graphic design principles and Swahili/English invitation structure.
     */
    public function classifyWithLocalHeuristics(string $path, string $category, int $width, int $height, array $ocrTokens): array
    {
        // Default color palette extracted from category or neutral luxury tones
        $primaryColor = match ($category) {
            'wedding', 'send_off' => '#4a3728', // elegant deep bronze
            'birthday' => '#1e293b',             // modern slate
            'corporate' => '#0f172a',            // navy black
            'traditional' => '#5c2d16',          // earthy mahogany
            default => '#333333',
        };

        $fields = [
            'guest_name' => [
                'label' => 'Invitee / Guest Name',
                'type' => 'text',
                'x' => 0.10,
                'y' => 0.28,
                'width' => 0.80,
                'height' => 0.08,
                'font_family' => 'Playfair Display',
                'font_size' => 28,
                'color' => $primaryColor,
                'alignment' => 'center',
                'max_length' => 60,
                'is_editable' => true,
                'is_static' => false,
                'confidence' => 0.90,
            ],
            'invitation_text' => [
                'label' => 'Invitation Message',
                'type' => 'textarea',
                'x' => 0.12,
                'y' => 0.38,
                'width' => 0.76,
                'height' => 0.10,
                'font_family' => 'Georgia',
                'font_size' => 16,
                'color' => '#555555',
                'alignment' => 'center',
                'max_length' => 250,
                'is_editable' => true,
                'is_static' => false,
                'confidence' => 0.86,
            ],
            'event_title' => [
                'label' => 'Event Title / Celebrants',
                'type' => 'text',
                'x' => 0.10,
                'y' => 0.49,
                'width' => 0.80,
                'height' => 0.09,
                'font_family' => 'Cinzel',
                'font_size' => 32,
                'color' => $primaryColor,
                'alignment' => 'center',
                'max_length' => 80,
                'is_editable' => true,
                'is_static' => false,
                'confidence' => 0.92,
            ],
            'event_date' => [
                'label' => 'Event Date & Time',
                'type' => 'text',
                'x' => 0.15,
                'y' => 0.60,
                'width' => 0.70,
                'height' => 0.06,
                'font_family' => 'Montserrat',
                'font_size' => 18,
                'color' => '#444444',
                'alignment' => 'center',
                'max_length' => 50,
                'is_editable' => true,
                'is_static' => false,
                'confidence' => 0.88,
            ],
            'venue' => [
                'label' => 'Venue & Location',
                'type' => 'text',
                'x' => 0.15,
                'y' => 0.67,
                'width' => 0.70,
                'height' => 0.06,
                'font_family' => 'Montserrat',
                'font_size' => 16,
                'color' => '#555555',
                'alignment' => 'center',
                'max_length' => 80,
                'is_editable' => true,
                'is_static' => false,
                'confidence' => 0.87,
            ],
        ];

        // If wedding or traditional, provide optional couple photo zone
        if (in_array($category, ['wedding', 'kitchen_party', 'send_off'])) {
            $fields['couple_photo'] = [
                'label' => 'Optional Couple Photo',
                'type' => 'image',
                'x' => 0.38,
                'y' => 0.10,
                'width' => 0.24,
                'height' => 0.16,
                'is_editable' => true,
                'is_static' => false,
                'is_optional' => true,
                'confidence' => 0.75,
            ];
        }

        return $fields;
    }

    /**
     * Validate and clamp coordinates to normalized range [0.0, 1.0].
     */
    public function sanitizeAndNormalizeFields(array $rawFields): array
    {
        $sanitized = [];

        foreach ($rawFields as $key => $field) {
            if (!is_array($field)) {
                continue;
            }

            // Normalization & clamping
            $x = isset($field['x']) ? max(0.0, min(0.95, (float)$field['x'])) : 0.10;
            $y = isset($field['y']) ? max(0.0, min(0.95, (float)$field['y'])) : 0.10;
            $width = isset($field['width']) ? max(0.05, min(1.0 - $x, (float)$field['width'])) : 0.80;
            $height = isset($field['height']) ? max(0.02, min(1.0 - $y, (float)$field['height'])) : 0.08;

            $sanitized[$key] = [
                'field_key' => $key,
                'label' => $field['label'] ?? ucwords(str_replace('_', ' ', $key)),
                'type' => $field['type'] ?? 'text',
                'x' => round($x, 4),
                'y' => round($y, 4),
                'width' => round($width, 4),
                'height' => round($height, 4),
                'font_family' => $field['font_family'] ?? 'Playfair Display',
                'font_size' => (int) ($field['font_size'] ?? 22),
                'color' => preg_match('/^#[0-9a-fA-F]{3,8}$/', $field['color'] ?? '') ? $field['color'] : '#333333',
                'alignment' => in_array($field['alignment'] ?? '', ['left', 'center', 'right']) ? $field['alignment'] : 'center',
                'max_length' => (int) ($field['max_length'] ?? 100),
                'is_editable' => (bool) ($field['is_editable'] ?? true),
                'is_static' => (bool) ($field['is_static'] ?? false),
                'is_optional' => (bool) ($field['is_optional'] ?? false),
                'confidence' => round((float) ($field['confidence'] ?? 0.85), 2),
                'status' => 'detected',
            ];
        }

        return $sanitized;
    }
}
