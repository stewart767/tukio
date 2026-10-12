<?php

namespace App\Services\Invitations;

class QrPlacementAdvisor
{
    /**
     * Recommend the best QR code position based on detected text boxes, image dimensions, and image luminance.
     *
     * @param array $detectedFields Array of field definitions with normalized x, y, width, height.
     * @param string|null $imageAbsolutePath Absolute path to artwork file for contrast analysis.
     * @param int|null $imgWidth Artwork width in px.
     * @param int|null $imgHeight Artwork height in px.
     * @return array Contains recommended x, y, width, height, position_name, and confidence.
     */
    public function recommendPosition(array $detectedFields, ?string $imageAbsolutePath = null, ?int $imgWidth = null, ?int $imgHeight = null): array
    {
        $qrSize = config('ai_invitation.qr.recommended_size', 0.16);
        $candidates = [
            'bottom_right' => [
                'x' => round(1.0 - $qrSize - 0.08, 4),
                'y' => round(1.0 - $qrSize - 0.08, 4),
                'width' => $qrSize,
                'height' => $qrSize,
                'zone_name' => 'Bottom Right',
            ],
            'bottom_left' => [
                'x' => 0.08,
                'y' => round(1.0 - $qrSize - 0.08, 4),
                'width' => $qrSize,
                'height' => $qrSize,
                'zone_name' => 'Bottom Left',
            ],
            'bottom_center' => [
                'x' => round((1.0 - $qrSize) / 2, 4),
                'y' => round(1.0 - $qrSize - 0.08, 4),
                'width' => $qrSize,
                'height' => $qrSize,
                'zone_name' => 'Bottom Center',
            ],
            'top_right' => [
                'x' => round(1.0 - $qrSize - 0.08, 4),
                'y' => 0.08,
                'width' => $qrSize,
                'height' => $qrSize,
                'zone_name' => 'Top Right',
            ],
        ];

        $scoredCandidates = [];

        foreach ($candidates as $name => $candidate) {
            $score = 100;
            $overlapPenalty = 0;

            // 1. Calculate overlap penalty against detected text fields
            foreach ($detectedFields as $fieldKey => $field) {
                if ($fieldKey === 'qr_code') {
                    continue;
                }

                $overlap = $this->calculateOverlapArea($candidate, $field);
                if ($overlap > 0) {
                    $overlapPenalty += ($overlap * 400); // severe penalty for overlapping text
                }
            }

            $score -= $overlapPenalty;

            // 2. Evaluate image background luminance/contrast if image file is readable
            if ($imageAbsolutePath && file_exists($imageAbsolutePath) && function_exists('imagecreatefromstring')) {
                $contrastScore = $this->evaluateBackgroundSuitability($imageAbsolutePath, $candidate);
                $score += $contrastScore; // +10 to +30 for clear light/dark solid background
            }

            // Preference for standard bottom corners
            if ($name === 'bottom_right') {
                $score += 15;
            } elseif ($name === 'bottom_center') {
                $score += 10;
            }

            $scoredCandidates[$name] = [
                'candidate' => $candidate,
                'score' => $score,
            ];
        }

        // Sort descending by score
        uasort($scoredCandidates, fn($a, $b) => $b['score'] <=> $a['score']);
        $best = reset($scoredCandidates);

        $chosen = $best['candidate'];
        $chosen['type'] = 'qr';
        $chosen['label'] = 'QR Pass Code';
        $chosen['confidence'] = min(0.98, max(0.50, round($best['score'] / 100, 2)));
        $chosen['quiet_zone'] = config('ai_invitation.qr.quiet_zone_padding', 0.015);
        $chosen['validation_warnings'] = $this->validateQrDimensions($chosen['width'], $imgWidth);

        return $chosen;
    }

    /**
     * Calculate 2D intersection area of two normalized rectangles.
     */
    public function calculateOverlapArea(array $boxA, array $boxB): float
    {
        $x1 = max($boxA['x'], $boxB['x']);
        $y1 = max($boxA['y'], $boxB['y']);
        $x2 = min($boxA['x'] + $boxA['width'], $boxB['x'] + $boxB['width']);
        $y2 = min($boxA['y'] + $boxA['height'], $boxB['y'] + $boxB['height']);

        if ($x2 > $x1 && $y2 > $y1) {
            return ($x2 - $x1) * ($y2 - $y1);
        }

        return 0.0;
    }

    /**
     * Check if candidate location has good contrast and minimal texture variance.
     */
    protected function evaluateBackgroundSuitability(string $imagePath, array $box): int
    {
        try {
            $data = file_get_contents($imagePath);
            if (!$data) return 0;
            $img = @imagecreatefromstring($data);
            if (!$img) return 0;

            $w = imagesx($img);
            $h = imagesy($img);

            $pxX = (int) ($box['x'] * $w);
            $pxY = (int) ($box['y'] * $h);
            $pxW = (int) ($box['width'] * $w);
            $pxH = (int) ($box['height'] * $h);

            $samples = 0;
            $totalLuma = 0;

            for ($x = $pxX; $x < $pxX + $pxW; $x += max(1, (int)($pxW / 8))) {
                for ($y = $pxY; $y < $pxY + $pxH; $y += max(1, (int)($pxH / 8))) {
                    if ($x >= 0 && $x < $w && $y >= 0 && $y < $h) {
                        $rgb = imagecolorat($img, $x, $y);
                        $r = ($rgb >> 16) & 0xFF;
                        $g = ($rgb >> 8) & 0xFF;
                        $b = $rgb & 0xFF;
                        $luma = (0.299 * $r + 0.587 * $g + 0.114 * $b);
                        $totalLuma += $luma;
                        $samples++;
                    }
                }
            }

            imagedestroy($img);

            if ($samples === 0) return 0;
            $avgLuma = $totalLuma / $samples;

            // Very high luminance (white or pale background) is ideal for QR contrast
            if ($avgLuma > 210) {
                return 25;
            } elseif ($avgLuma > 170) {
                return 15;
            } elseif ($avgLuma < 50) {
                return 10; // Dark background can work with a white quiet zone border
            }

            return 5;
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Validate QR dimensions to ensure scannability.
     */
    public function validateQrDimensions(float $normWidth, ?int $realWidthPx = null): array
    {
        $warnings = [];
        $minSize = config('ai_invitation.qr.min_size', 0.12);

        if ($normWidth < $minSize) {
            $warnings[] = sprintf('QR code width (%d%%) is below recommended scannable minimum (%d%%).', round($normWidth * 100), round($minSize * 100));
        }

        if ($realWidthPx && ($normWidth * $realWidthPx) < 130) {
            $warnings[] = 'Calculated QR code pixel width is under 130px, which may be difficult to scan on older phone cameras.';
        }

        return $warnings;
    }
}
