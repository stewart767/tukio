<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\Template;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class InvitationImageGenerator
{
    /**
     * Generate beautiful custom invitation card image
     */
    public function generate(Invitation $invitation)
    {
        // Get invitation data
        $invitationData = $this->prepareInvitationData($invitation);
        
        // Check if invitation has a template
        if ($invitation->template_id && $invitation->template) {
            // Use template as background, keep content and QR code
            return $this->generateCardWithTemplate($invitationData, $invitation->template);
        }
        
        // Create custom designed card (fallback)
        return $this->generateCustomCard($invitationData);
    }
    
    /**
     * Generate card using template as background while keeping content and QR code
     */
    private function generateCardWithTemplate($data, Template $template)
    {
        // Load template image
        $templateImage = $this->loadTemplateImage($template);
        
        if (!$templateImage) {
            Log::warning('Template image could not be loaded, using custom card');
            return $this->generateCustomCard($data);
        }
        
        // Get template dimensions
        $width = imagesx($templateImage);
        $height = imagesy($templateImage);
        
        // Create new image with template as background
        $image = imagecreatetruecolor($width, $height);
        imagealphablending($image, true);
        imagesavealpha($image, true);
        
        // Copy template as background
        imagecopy($image, $templateImage, 0, 0, 0, 0, $width, $height);
        imagedestroy($templateImage);
        
        // Check if template has AI-detected normalized field coordinates
        $normalizedFields = $template->getNormalizedFields();
        if (!empty($normalizedFields)) {
            return $this->renderCardWithNormalizedFields($image, $data, $template, $normalizedFields, $width, $height);
        }

        // Fallback: Legacy content area calculation
        $placeholders = $template->placeholder_config ?? [];
        if (!empty($placeholders) && isset($placeholders['content_area'])) {
            $contentArea = $this->calculateContentArea($width, $height, $placeholders);
        } else {
            // Attempt to detect the white content area from the template image
            $detected = $this->detectContentAreaFromImage($this->loadTemplateImage($template));
            if ($detected) {
                $contentArea = $detected;
            } else {
                $contentArea = $this->calculateContentArea($width, $height, $placeholders);
            }
        }
        
        // Add event name inside content area
        $this->addEventNameInContentArea($image, $data['event_title'] ?? 'Special Event', $contentArea);
        
        // Add Swahili invitation message inside content area
        $this->addSwahiliMessageInContentArea($image, $data, $contentArea);
        
        // Add QR code inside content area
        $this->addQRCodeInContentArea($image, $data['qr_code_value'] ?? ($data['qr_code'] ?? ''), $contentArea);

        // Insert couple photos from event data (if available) onto the template
        try {
            $this->insertCouplePhotos($image, $data, $width, $height, $placeholders);
        } catch (\Throwable $e) {
            Log::warning('Failed to insert couple photos on template', ['error' => $e->getMessage()]);
        }
        
        return $image;
    }

    /**
     * Render dynamic fields onto template artwork using AI-detected normalized coordinates [0.0, 1.0].
     */
    private function renderCardWithNormalizedFields($image, $data, Template $template, array $fields, int $width, int $height)
    {
        $fontPath = $this->getFontPath();
        $scale = max(0.6, $width / 1100);

        foreach ($fields as $key => $field) {
            $normX = (float) ($field['x'] ?? 0);
            $normY = (float) ($field['y'] ?? 0);
            $normW = (float) ($field['width'] ?? 0.8);
            $normH = (float) ($field['height'] ?? 0.08);

            $pxX = (int) ($normX * $width);
            $pxY = (int) ($normY * $height);
            $pxW = (int) ($normW * $width);
            $pxH = (int) ($normH * $height);

            $type = $field['type'] ?? 'text';

            // QR Code rendering
            if ($type === 'qr' || $key === 'qr_code') {
                $qrValue = $data['qr_code_value'] ?? ($data['qr_code'] ?? ($data['slug'] ?? ''));
                if (!empty($qrValue)) {
                    $qrSize = (int) min($pxW, $pxH);
                    $quietZone = (int) max(8, ($field['quiet_zone'] ?? 0.015) * $width);
                    
                    // Quiet zone background
                    $white = imagecolorallocate($image, 255, 255, 255);
                    imagefilledrectangle($image, $pxX, $pxY, $pxX + $qrSize + ($quietZone * 2), $pxY + $qrSize + ($quietZone * 2), $white);
                    
                    try {
                        $qrImg = $this->generateQRCodeImage($qrValue, $qrSize);
                        if ($qrImg) {
                            imagecopyresampled($image, $qrImg, $pxX + $quietZone, $pxY + $quietZone, 0, 0, $qrSize, $qrSize, imagesx($qrImg), imagesy($qrImg));
                            imagedestroy($qrImg);
                        }
                    } catch (\Throwable $e) {
                        Log::error('Normalized QR code rendering failed', ['error' => $e->getMessage()]);
                    }
                }
                continue;
            }

            // Photo frame rendering
            if ($type === 'image' || $key === 'couple_photo') {
                $photoPath = $data['couple_photo'] ?? ($data['guest_photo'] ?? null);
                if ($photoPath && file_exists($photoPath)) {
                    $photoImg = @imagecreatefromstring(file_get_contents($photoPath));
                    if ($photoImg) {
                        imagecopyresampled($image, $photoImg, $pxX, $pxY, 0, 0, $pxW, $pxH, imagesx($photoImg), imagesy($photoImg));
                        imagedestroy($photoImg);
                    }
                }
                continue;
            }

            // Static text zones that should remain unchanged
            if (!empty($field['is_static'])) {
                continue;
            }

            // Resolve dynamic text
            $text = match ($key) {
                'guest_name' => $data['guest_name'] ?? 'Mpendwa Mgeni',
                'invitation_text' => $data['invitation_message'] ?? ($data['whatsapp_message'] ?? ($field['default_value'] ?? '')),
                'event_title' => $data['event_title'] ?? ($data['couple_names'] ?? ($data['event_data']['children_names'] ?? 'Harusi ya Watoto')),
                'event_date' => $data['event_date_swahili'] ?? ($data['event_date'] ?? ''),
                'venue' => $data['venue'] ?? '',
                default => $field['default_value'] ?? ($data[$key] ?? '')
            };

            if (empty($text)) {
                continue;
            }

            // Parse text color
            $hex = $field['color'] ?? '#333333';
            $hex = ltrim($hex, '#');
            if (strlen($hex) === 3) {
                $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
            }
            $r = hexdec(substr($hex, 0, 2) ?: '33');
            $g = hexdec(substr($hex, 2, 2) ?: '33');
            $b = hexdec(substr($hex, 4, 2) ?: '33');
            $colorAlloc = imagecolorallocate($image, $r, $g, $b);

            $baseSize = (int) ($field['font_size'] ?? 24);
            $fontSize = (int) max(14, $baseSize * $scale);
            $align = $field['alignment'] ?? 'center';

            $this->drawBoundedText($image, $text, $pxX, $pxY, $pxW, $pxH, $fontSize, $colorAlloc, $fontPath, $align);
        }

        return $image;
    }

    /**
     * Draw text wrapped within a bounding box with alignment and size scaling.
     */
    private function drawBoundedText($image, string $text, int $x, int $y, int $w, int $h, int $fontSize, $color, ?string $fontPath, string $align = 'center')
    {
        if (empty($fontPath) || !function_exists('imagettftext')) {
            $px = $align === 'center' ? $x + max(0, ($w - strlen($text) * 8) / 2) : $x + 4;
            imagestring($image, 5, (int)$px, (int)($y + 4), $text, $color);
            return;
        }

        // Fit font size if initial single-line is too wide
        $testBox = imagettfbbox($fontSize, 0, $fontPath, $text);
        if ($testBox) {
            $textWidth = abs($testBox[4] - $testBox[0]);
            while ($textWidth > $w && $fontSize > 12) {
                $fontSize -= 2;
                $testBox = imagettfbbox($fontSize, 0, $fontPath, $text);
                $textWidth = $testBox ? abs($testBox[4] - $testBox[0]) : $w;
            }
        }

        // Split text into words for multi-line wrapping
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            $candidate = $currentLine === '' ? $word : $currentLine . ' ' . $word;
            $box = imagettfbbox($fontSize, 0, $fontPath, $candidate);
            $lineWidth = $box ? abs($box[4] - $box[0]) : (strlen($candidate) * ($fontSize * 0.6));

            if ($lineWidth <= $w || $currentLine === '') {
                $currentLine = $candidate;
            } else {
                $lines[] = $currentLine;
                $currentLine = $word;
            }
        }
        if ($currentLine !== '') {
            $lines[] = $currentLine;
        }

        // Calculate line heights and starting Y
        $lineHeight = (int) ($fontSize * 1.35);
        $totalHeight = count($lines) * $lineHeight;
        $startY = $y + max(0, (int)(($h - $totalHeight) / 2)) + $fontSize;

        $curY = $startY;
        foreach ($lines as $line) {
            $box = imagettfbbox($fontSize, 0, $fontPath, $line);
            $lineWidth = $box ? abs($box[4] - $box[0]) : (strlen($line) * ($fontSize * 0.6));

            $lineX = match ($align) {
                'left' => $x + 5,
                'right' => $x + $w - $lineWidth - 5,
                default => $x + max(0, ($w - $lineWidth) / 2),
            };

            imagettftext($image, $fontSize, 0, (int)$lineX, (int)$curY, $color, $fontPath, $line);
            $curY += $lineHeight;
        }
    }
    
    /**
     * Calculate content area boundaries within template
     */
    private function calculateContentArea($width, $height, $placeholders)
    {
        // If placeholders define a content area, use it
        if (isset($placeholders['content_area'])) {
            return [
                'x' => $placeholders['content_area']['x'] ?? $width * 0.15,
                'y' => $placeholders['content_area']['y'] ?? $height * 0.20,
                'width' => $placeholders['content_area']['width'] ?? $width * 0.70,
                'height' => $placeholders['content_area']['height'] ?? $height * 0.65,
            ];
        }
        
        // Default: Calculate safe content area (centered white panel area)
        // Typically templates have a white text area in the center
        $marginX = $width * 0.15; // 15% margin on sides
        $marginY = $height * 0.20; // 20% margin top
        $bottomMargin = $height * 0.15; // 15% margin bottom
        
        return [
            'x' => $marginX,
            'y' => $marginY,
            'width' => $width - ($marginX * 2),
            'height' => $height - $marginY - $bottomMargin,
        ];
    }

    /**
     * Try to detect a large light (white/near-white) content area inside a template image.
     * Returns null if detection fails. This is a best-effort heuristic used when no
     * `placeholder_config` is available for a template.
     */
    private function detectContentAreaFromImage($image)
    {
        if (!$image) {
            return null;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        // Parameters for detection - tuned for typical templates
        $rowSampleStep = max(1, (int)($width / 200)); // sample horizontally
        $colSampleStep = max(1, (int)($height / 200)); // sample vertically
        $lightThreshold = 230; // luma threshold (0-255) for a pixel to be considered "light"
        $rowLightFractionThreshold = 0.65; // fraction of light pixels in a row
        $minBlockHeight = max(40, (int)($height * 0.12)); // require at least this many rows

        // Scan rows to find contiguous block of "light" rows
        $rowIsLight = [];
        for ($y = 0; $y < $height; $y += $colSampleStep) {
            $lightCount = 0;
            $sampleCount = 0;
            for ($x = 0; $x < $width; $x += $rowSampleStep) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $luma = (int)((($r * 299) + ($g * 587) + ($b * 114)) / 1000);
                if ($luma >= $lightThreshold) {
                    $lightCount++;
                }
                $sampleCount++;
            }
            $fraction = $sampleCount > 0 ? ($lightCount / $sampleCount) : 0;
            $rowIsLight[$y] = $fraction >= $rowLightFractionThreshold;
        }

        // Find largest contiguous block of true values in rowIsLight
        $blocks = [];
        $currentStart = null;
        $rows = array_keys($rowIsLight);
        foreach ($rows as $idx => $y) {
            $isLight = $rowIsLight[$y];
            if ($isLight && $currentStart === null) {
                $currentStart = $y;
            }
            if ((!$isLight || $idx === count($rows) - 1) && $currentStart !== null) {
                $end = $isLight ? $y : $rows[$idx - 1];
                $blocks[] = ['y1' => $currentStart, 'y2' => $end];
                $currentStart = null;
            }
        }

        if (empty($blocks)) {
            return null;
        }

        // Choose the tallest block (most likely the content area)
        usort($blocks, function ($a, $b) {
            return ($b['y2'] - $b['y1']) - ($a['y2'] - $a['y1']);
        });
        $best = $blocks[0];
        $blockHeight = $best['y2'] - $best['y1'] + 1;
        if ($blockHeight < $minBlockHeight) {
            // Block too small to be a content area
            return null;
        }

        // Now compute left/right bounds for the block by scanning columns inside that Y range
        $colIsLight = [];
        for ($x = 0; $x < $width; $x += $rowSampleStep) {
            $lightCount = 0;
            $sampleCount = 0;
            for ($y = $best['y1']; $y <= $best['y2']; $y += $colSampleStep) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $luma = (int)((($r * 299) + ($g * 587) + ($b * 114)) / 1000);
                if ($luma >= $lightThreshold) {
                    $lightCount++;
                }
                $sampleCount++;
            }
            $fraction = $sampleCount > 0 ? ($lightCount / $sampleCount) : 0;
            $colIsLight[$x] = $fraction >= 0.60;
        }

        // Find leftmost and rightmost contiguous columns that are light
        $cols = array_keys($colIsLight);
        $startCol = null;
        $endCol = null;
        foreach ($cols as $idx => $x) {
            $isLight = $colIsLight[$x];
            if ($isLight && $startCol === null) {
                $startCol = $x;
            }
            if ((!$isLight || $idx === count($cols) - 1) && $startCol !== null) {
                $end = $isLight ? $x : $cols[$idx - 1];
                $endCol = $end;
                break;
            }
        }

        if ($startCol === null || $endCol === null) {
            return null;
        }

        // Add padding to the detected box
        $padX = (int)($width * 0.03) + 10; // small padding
        $padY = (int)($height * 0.02) + 8;

        $x = max(0, $startCol - $padX);
        $y = max(0, $best['y1'] - $padY);
        $w = min($width - $x, $endCol - $startCol + 1 + ($padX * 2));
        $h = min($height - $y, $best['y2'] - $best['y1'] + 1 + ($padY * 2));

        // Ensure reasonable minimum size
        if ($w < ($width * 0.2) || $h < ($height * 0.08)) {
            return null;
        }

        return ['x' => (int)$x, 'y' => (int)$y, 'width' => (int)$w, 'height' => (int)$h];
    }
    
    /**
     * Add event name inside content area (centered)
     */
    private function addEventNameInContentArea($image, $eventTitle, $contentArea)
    {
        $fontPath = $this->getFontPath();
        $fontSize = 36; // Smaller to fit in content area
        
        $textColor = imagecolorallocate($image, 101, 67, 33);
        $accentColor = imagecolorallocate($image, 139, 69, 19);
        
        if ($fontPath && function_exists('imagettftext')) {
            $textBox = imagettfbbox($fontSize, 0, $fontPath, $eventTitle);
            if ($textBox) {
                $textWidth = abs($textBox[4] - $textBox[0]);
                $maxWidth = $contentArea['width'] - 40; // Leave margins
                
                $currentFontSize = $fontSize;
                while ($textWidth > $maxWidth && $currentFontSize > 18) {
                    $currentFontSize -= 2;
                    $textBox = imagettfbbox($currentFontSize, 0, $fontPath, $eventTitle);
                    $textWidth = $textBox ? abs($textBox[4] - $textBox[0]) : $maxWidth;
                }
                
                $textHeight = abs($textBox[5] - $textBox[1]);
                // Center horizontally within content area
                $x = $contentArea['x'] + ($contentArea['width'] - $textWidth) / 2;
                // Position at top of content area with some padding
                $y = $contentArea['y'] + 50 + $textHeight;
                
                imagettftext($image, $currentFontSize, 0, $x, $y, $textColor, $fontPath, $eventTitle);
                
                $underlineY = $y + 12;
                $underlineWidth = $textWidth * 0.6;
                $underlineX = $x + ($textWidth - $underlineWidth) / 2;
                for ($i = 0; $i < 2; $i++) {
                    imageline($image, $underlineX, $underlineY + $i, $underlineX + $underlineWidth, $underlineY + $i, $accentColor);
                }
            }
        } else {
            $x = $contentArea['x'] + ($contentArea['width'] - strlen($eventTitle) * 25) / 2;
            imagestring($image, 5, $x, $contentArea['y'] + 30, $eventTitle, $textColor);
        }
    }
    
    /**
     * Add Swahili message inside content area (centered)
     */
    private function addSwahiliMessageInContentArea($image, $data, $contentArea)
    {
        $fontPath = $this->getFontPath();
        $fontSize = 20; // readable size for template area
        $lineHeight = 34;

        $textColor = imagecolorallocate($image, 101, 67, 33);

        $guestName = $data['guest_name'] ?? 'Ndugu';
        $hostData = $data['host_event_data'] ?? [];
        $hostFull = $hostData['host_fullname'] ?? $hostData['host_name'] ?? ($data['groom_name'] ?? '');
        $childrenNames = $hostData['children_names'] ?? $hostData['couple_names'] ?? ($data['event_data']['children_names'] ?? $data['event_title'] ?? '');
        $eventDate = $data['event_date_swahili'] ?? $data['event_date'] ?? '';
        $venue = $data['venue'] ?? '';

        $lines = [];
        $lines[] = trim("Familia ya Bw & Bi " . $hostFull . " inayofuraha kukualika/Kuwaalika");
        $lines[] = strtoupper($guestName);
        $lines[] = "kwenye Sherehe ya Harusi ya Watoto wao";
        if (!empty($childrenNames)) {
            $lines[] = $childrenNames;
        }
        $lines[] = trim("itakayofanyika Tar " . $eventDate . ", Katika Ukumbi wa " . $venue . ".");
        $lines[] = "Ukiwa kama ndugu, rafiki au jamaa uwepo wako ndio furaha yetu. Karibu Sana.";

        if ($fontPath && function_exists('imagettftext')) {
            // Calculate total height
            $totalMessageHeight = 0;
            foreach ($lines as $line) {
                $textBox = imagettfbbox($fontSize, 0, $fontPath, $line);
                $textHeight = $textBox ? abs($textBox[5] - $textBox[1]) : $fontSize;
                $totalMessageHeight += $textHeight + $lineHeight;
            }

            // Start Y position: place message centered within content area vertically (leaving room for QR)
            $reservedTop = 60; // space for title/decoration
            $reservedBottom = 240; // space reserved for QR and code
            $availableHeight = max(0, $contentArea['height'] - $reservedTop - $reservedBottom);
            $startY = $contentArea['y'] + $reservedTop + max(0, ($availableHeight - $totalMessageHeight) / 2);

            $y = $startY;
            foreach ($lines as $idx => $line) {
                // Determine role by line index
                $role = match($idx) {
                    0 => 'heading',
                    1 => 'guest_name',
                    2 => 'subheading',
                    3 => 'children',
                    4 => 'details',
                    5 => 'closing',
                    default => 'details'
                };
                $style = $this->getTextStyle($role);

                $renderLine = $line;
                if (!empty($style['upper'])) {
                    $renderLine = mb_strtoupper($renderLine);
                }

                $font = $style['font'];
                $size = $style['size'];
                $lineHeightLocal = $style['line'];
                $col = $style['color'];
                $colorAlloc = imagecolorallocate($image, $col['r'], $col['g'], $col['b']);

                $textBox = $font ? imagettfbbox($size, 0, $font, $renderLine) : null;
                $textWidth = $textBox ? abs($textBox[4] - $textBox[0]) : (strlen($renderLine) * 10);
                $textHeight = $textBox ? abs($textBox[5] - $textBox[1]) : $size;

                $x = $contentArea['x'] + ($contentArea['width'] - $textWidth) / 2;
                $currentY = $y + $textHeight;

                if ($font && function_exists('imagettftext')) {
                    imagettftext($image, $size, 0, $x, $currentY, $colorAlloc, $font, $renderLine);
                } else {
                    imagestring($image, 4, $x, $y, $renderLine, $colorAlloc);
                }

                $y = $currentY + $lineHeightLocal;
            }
        } else {
            // Fallback simple rendering
            $y = $contentArea['y'] + ($contentArea['height'] / 2) - (count($lines) * 10);
            foreach ($lines as $line) {
                $x = $contentArea['x'] + ($contentArea['width'] - strlen($line) * 8) / 2;
                imagestring($image, 4, $x, $y, $line, $textColor);
                $y += 18;
            }
        }
    }
    
    /**
     * Add QR code inside content area
     */
    private function addQRCodeInContentArea($image, $qrCode, $contentArea)
    {
        if (empty($qrCode)) {
            return;
        }
        
        // Calculate QR size to fit in content area
        $maxQrSize = min($contentArea['width'] * 0.4, 200); // Max 40% of content width or 200px
        $qrSize = (int)$maxQrSize;
        $containerPadding = 30;
        $borderWidth = 4;
        
        $invitationCodeTextHeight = 50;
        $bottomMargin = 30;
        
        // Position QR code in content area (centered horizontally, near bottom)
        $qrY = $contentArea['y'] + $contentArea['height'] - $bottomMargin - $invitationCodeTextHeight - $qrSize - $containerPadding;
        $qrX = $contentArea['x'] + ($contentArea['width'] - $qrSize - ($containerPadding * 2)) / 2;
        
        $qrCodeImage = null;
        try {
            $qrCodeImage = $this->generateQRCodeImage($qrCode, $qrSize);
            if (!$qrCodeImage) {
                $qrCodeImage = $this->generateQRCodeImage($qrCode, $qrSize);
            }
        } catch (\Exception $e) {
            Log::error('QR code generation failed on template', ['error' => $e->getMessage()]);
            return;
        }
        
        if ($qrCodeImage && (is_resource($qrCodeImage) || (is_object($qrCodeImage) && get_class($qrCodeImage) === 'GdImage'))) {
            $qrImageWidth = imagesx($qrCodeImage);
            $qrImageHeight = imagesy($qrCodeImage);
            
            if ($qrImageWidth != $qrSize || $qrImageHeight != $qrSize) {
                $resizedQr = imagecreatetruecolor($qrSize, $qrSize);
                imagealphablending($resizedQr, false);
                imagesavealpha($resizedQr, true);
                
                $white = imagecolorallocate($resizedQr, 255, 255, 255);
                imagefill($resizedQr, 0, 0, $white);
                
                imagecopyresampled($resizedQr, $qrCodeImage, 0, 0, 0, 0, $qrSize, $qrSize, $qrImageWidth, $qrImageHeight);
                imagedestroy($qrCodeImage);
                $qrCodeImage = $resizedQr;
            }
            
            $containerX = $qrX - $containerPadding;
            $containerY = $qrY - $containerPadding;
            $containerWidth = $qrSize + ($containerPadding * 2);
            $containerHeight = $containerWidth;
            
            $whiteBg = imagecolorallocate($image, 255, 255, 255);
            $shadowColor = imagecolorallocatealpha($image, 0, 0, 0, 30);
            
            imagefilledrectangle($image, 
                $containerX + 4, 
                $containerY + 4, 
                $containerX + $containerWidth + 4, 
                $containerY + $containerHeight + 4, 
                $shadowColor
            );
            
            imagefilledrectangle($image, 
                $containerX, 
                $containerY, 
                $containerX + $containerWidth, 
                $containerY + $containerHeight, 
                $whiteBg
            );
            
            $borderColor = imagecolorallocate($image, 160, 82, 45);
            $borderColor2 = imagecolorallocate($image, 139, 69, 19);
            
            for ($i = 0; $i < $borderWidth; $i++) {
                imagerectangle($image, 
                    $containerX + $i, 
                    $containerY + $i, 
                    $containerX + $containerWidth - $i, 
                    $containerY + $containerHeight - $i, 
                    $borderColor
                );
            }
            
            $innerOffset = $borderWidth + 3;
            imagerectangle($image, 
                $containerX + $innerOffset, 
                $containerY + $innerOffset, 
                $containerX + $containerWidth - $innerOffset, 
                $containerY + $containerHeight - $innerOffset, 
                $borderColor2
            );
            
            imagecopy($image, $qrCodeImage, $qrX, $qrY, 0, 0, $qrSize, $qrSize);
            imagedestroy($qrCodeImage);
            
            $this->addInvitationCodeTextInContentArea($image, $qrCode, $contentArea, $containerY + $containerHeight);
        }
    }
    
    /**
     * Add invitation code text inside content area
     */
    private function addInvitationCodeTextInContentArea($image, $code, $contentArea, $startY)
    {
        $fontPath = $this->getFontPath();
        $text = "Invitation Code: " . $code;
        $fontSize = 16; // Smaller to fit in content area
        
        $textColor = imagecolorallocate($image, 101, 67, 33);
        $accentColor = imagecolorallocate($image, 139, 69, 19);
        
        if ($fontPath && function_exists('imagettftext')) {
            $textBox = imagettfbbox($fontSize, 0, $fontPath, $text);
            if ($textBox) {
                $textWidth = abs($textBox[4] - $textBox[0]);
                $textHeight = abs($textBox[5] - $textBox[1]);
                $x = $contentArea['x'] + ($contentArea['width'] - $textWidth) / 2;
                $y = $startY + 30 + $textHeight;
                
                imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontPath, $text);
                
                $underlineY = $y + 6;
                $underlineWidth = $textWidth * 0.4;
                $underlineX = $x + ($textWidth - $underlineWidth) / 2;
                for ($i = 0; $i < 2; $i++) {
                    imageline($image, $underlineX, $underlineY + $i, $underlineX + $underlineWidth, $underlineY + $i, $accentColor);
                }
            }
        } else {
            $x = $contentArea['x'] + ($contentArea['width'] - strlen($text) * 10) / 2;
            imagestring($image, 4, $x, $startY + 20, $text, $textColor);
        }
    }
    
    /**
     * Add invitation code text on template
     */
    private function addInvitationCodeTextOnTemplate($image, $code, $width, $height, $startY)
    {
        $fontPath = $this->getFontPath();
        $text = "Invitation Code: " . $code;
        $fontSize = 20;
        
        $textColor = imagecolorallocate($image, 101, 67, 33);
        $accentColor = imagecolorallocate($image, 139, 69, 19);
        
        if ($fontPath && function_exists('imagettftext')) {
            $textBox = imagettfbbox($fontSize, 0, $fontPath, $text);
            if ($textBox) {
                $textWidth = abs($textBox[4] - $textBox[0]);
                $textHeight = abs($textBox[5] - $textBox[1]);
                $x = ($width - $textWidth) / 2;
                $y = $startY + 40 + $textHeight;
                
                imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontPath, $text);
                
                $underlineY = $y + 8;
                $underlineWidth = $textWidth * 0.4;
                $underlineX = $x + ($textWidth - $underlineWidth) / 2;
                for ($i = 0; $i < 2; $i++) {
                    imageline($image, $underlineX, $underlineY + $i, $underlineX + $underlineWidth, $underlineY + $i, $accentColor);
                }
            }
        } else {
            $x = ($width - strlen($text) * 12) / 2;
            imagestring($image, 4, $x, $startY + 25, $text, $textColor);
        }
    }
    
    /**
     * Generate modern, elegant custom invitation card with contemporary design elements
     */
    private function generateCustomCard($data)
    {
        // Card dimensions (matching HTML design - 540x675 scaled up 2x for quality)
        $width = 1080;
        $height = 1350;
        
        // Create image with transparency support
        $image = imagecreatetruecolor($width, $height);
        imagealphablending($image, true);
        imagesavealpha($image, true);
        
        // Create modern gradient background with subtle texture
        $this->createModernGradientBackground($image, $width, $height);
        
        // Add modern decorative border with shadow effect
        $this->addModernDecorativeBorder($image, $width, $height);
        
        // Add elegant inner border with rounded effect
        $this->addModernInnerBorder($image, $width, $height);
        
        // Add modern decorative elements (ornaments, patterns)
        $this->addModernDecorativeElements($image, $width, $height);
        
        // Add event name with elegant styling
        $this->addModernGuestName($image, $data['event_title'] ?? 'Special Event', $width, $height);
        
        // Add Swahili invitation message
        $this->addSwahiliInvitationMessage($image, $data, $width, $height);
        
        // Add modern QR code section with elegant presentation
        $this->addModernQRCodeSection($image, $data, $width, $height);
        
        return $image;
    }

    /**
     * Create modern gradient background with subtle texture and depth
     */
    private function createModernGradientBackground($image, $width, $height)
    {
        // Modern color palette with richer tones
        $colors = [
            ['r' => 255, 'g' => 250, 'b' => 245], // Lighter top
            ['r' => 255, 'g' => 248, 'b' => 240], // #FFF8F0 (middle-top)
            ['r' => 255, 'g' => 245, 'b' => 238], // #FFF5EE (middle)
            ['r' => 250, 'g' => 240, 'b' => 230], // #FAF0E6 (bottom)
        ];
        
        // Create smooth multi-stop gradient
        for ($y = 0; $y < $height; $y++) {
            $ratio = $y / $height;
            
            if ($ratio <= 0.33) {
                // Top to middle-top
                $r = $colors[0]['r'] + ($colors[1]['r'] - $colors[0]['r']) * ($ratio / 0.33);
                $g = $colors[0]['g'] + ($colors[1]['g'] - $colors[0]['g']) * ($ratio / 0.33);
                $b = $colors[0]['b'] + ($colors[1]['b'] - $colors[0]['b']) * ($ratio / 0.33);
            } elseif ($ratio <= 0.66) {
                // Middle-top to middle
                $r = $colors[1]['r'] + ($colors[2]['r'] - $colors[1]['r']) * (($ratio - 0.33) / 0.33);
                $g = $colors[1]['g'] + ($colors[2]['g'] - $colors[1]['g']) * (($ratio - 0.33) / 0.33);
                $b = $colors[1]['b'] + ($colors[2]['b'] - $colors[1]['b']) * (($ratio - 0.33) / 0.33);
            } else {
                // Middle to bottom
                $r = $colors[2]['r'] + ($colors[3]['r'] - $colors[2]['r']) * (($ratio - 0.66) / 0.34);
                $g = $colors[2]['g'] + ($colors[3]['g'] - $colors[2]['g']) * (($ratio - 0.66) / 0.34);
                $b = $colors[2]['b'] + ($colors[3]['b'] - $colors[2]['b']) * (($ratio - 0.66) / 0.34);
            }
            
            $color = imagecolorallocate($image, (int)$r, (int)$g, (int)$b);
            imageline($image, 0, $y, $width, $y, $color);
        }
        
        // Add subtle texture overlay for depth
        $this->addSubtleTexture($image, $width, $height);
    }
    
    /**
     * Add subtle texture overlay for modern depth effect
     */
    private function addSubtleTexture($image, $width, $height)
    {
        $textureColor = imagecolorallocatealpha($image, 139, 69, 19, 95); // Very transparent brown
        $density = 0.02; // 2% of pixels
        
        for ($i = 0; $i < ($width * $height * $density); $i++) {
            $x = rand(0, $width - 1);
            $y = rand(0, $height - 1);
            imagesetpixel($image, $x, $y, $textureColor);
        }
    }
    
    /**
     * Add modern decorative border with shadow effect
     */
    private function addModernDecorativeBorder($image, $width, $height)
    {
        $borderColor = imagecolorallocate($image, 139, 69, 19); // #8B4513
        $shadowColor = imagecolorallocatealpha($image, 0, 0, 0, 60);
        $borderWidth = 18; // Slightly thicker for modern look
        
        // Draw shadow first (offset)
        for ($i = 0; $i < $borderWidth; $i++) {
            imagerectangle($image, $i + 3, $i + 3, $width - 1 - $i + 3, $height - 1 - $i + 3, $shadowColor);
        }
        
        // Draw main border
        for ($i = 0; $i < $borderWidth; $i++) {
            imagerectangle($image, $i, $i, $width - 1 - $i, $height - 1 - $i, $borderColor);
        }
    }
    
    /**
     * Add modern inner border with elegant styling
     */
    private function addModernInnerBorder($image, $width, $height)
    {
        $innerColor = imagecolorallocate($image, 160, 82, 45); // #A0522D
        $offset = 35; // Slightly more offset
        
        // Double line effect for modern look
        imagerectangle($image, $offset, $offset, $width - $offset, $height - $offset, $innerColor);
        imagerectangle($image, $offset + 2, $offset + 2, $width - $offset - 2, $height - $offset - 2, $innerColor);
    }
    
    /**
     * Add modern decorative elements (ornaments, patterns, flourishes)
     */
    private function addModernDecorativeElements($image, $width, $height)
    {
        $decorColor = imagecolorallocatealpha($image, 160, 82, 45, 40); // Semi-transparent
        $offset = 50;
        
        // Modern corner flourishes (curved elements)
        $this->drawCornerFlourish($image, $offset, $offset, $decorColor, 'top-left');
        $this->drawCornerFlourish($image, $width - $offset, $offset, $decorColor, 'top-right');
        $this->drawCornerFlourish($image, $offset, $height - $offset, $decorColor, 'bottom-left');
        $this->drawCornerFlourish($image, $width - $offset, $height - $offset, $decorColor, 'bottom-right');
        
        // Add subtle side ornaments
        $this->drawSideOrnaments($image, $width, $height, $decorColor);
    }
    
    /**
     * Draw elegant corner flourish
     */
    private function drawCornerFlourish($image, $x, $y, $color, $position)
    {
        $size = 40;
        $thickness = 3;
        
        if ($position === 'top-left') {
            // Curved L-shape
            for ($i = 0; $i < $size; $i++) {
                $px = $x + $i;
                $py = $y + (int)($i * 0.3);
                for ($j = 0; $j < $thickness; $j++) {
                    imagesetpixel($image, $px, $py + $j, $color);
                    imagesetpixel($image, $x + $j, $y + $i, $color);
                }
            }
        } elseif ($position === 'top-right') {
            for ($i = 0; $i < $size; $i++) {
                $px = $x - $i;
                $py = $y + (int)($i * 0.3);
                for ($j = 0; $j < $thickness; $j++) {
                    imagesetpixel($image, $px, $py + $j, $color);
                    imagesetpixel($image, $x - $j, $y + $i, $color);
                }
            }
        } elseif ($position === 'bottom-left') {
            for ($i = 0; $i < $size; $i++) {
                $px = $x + $i;
                $py = $y - (int)($i * 0.3);
                for ($j = 0; $j < $thickness; $j++) {
                    imagesetpixel($image, $px, $py - $j, $color);
                    imagesetpixel($image, $x + $j, $y - $i, $color);
                }
            }
        } else { // bottom-right
            for ($i = 0; $i < $size; $i++) {
                $px = $x - $i;
                $py = $y - (int)($i * 0.3);
                for ($j = 0; $j < $thickness; $j++) {
                    imagesetpixel($image, $px, $py - $j, $color);
                    imagesetpixel($image, $x - $j, $y - $i, $color);
                }
            }
        }
    }
    
    /**
     * Draw subtle side ornaments
     */
    private function drawSideOrnaments($image, $width, $height, $color)
    {
        $centerY = $height / 2;
        $ornamentSize = 30;
        
        // Left side ornament
        for ($i = 0; $i < $ornamentSize; $i++) {
            $x = 60;
            $y = $centerY - ($ornamentSize / 2) + $i;
            imagesetpixel($image, $x, $y, $color);
            imagesetpixel($image, $x + 5, $y, $color);
        }
        
        // Right side ornament
        for ($i = 0; $i < $ornamentSize; $i++) {
            $x = $width - 60;
            $y = $centerY - ($ornamentSize / 2) + $i;
            imagesetpixel($image, $x, $y, $color);
            imagesetpixel($image, $x - 5, $y, $color);
        }
    }

    /**
     * Add decorative corners (matching CSS design)
     */
    private function addDecorativeCorners($image, $width, $height)
    {
        $decorColor = imagecolorallocate($image, 160, 82, 45); // #A0522D
        $cornerSize = 60; // 30px * 2 (scaled)
        $offset = 50; // 25px * 2 (scaled)
        $lineWidth = 4; // 2px * 2 (scaled)
        
        // Top left corner
        for ($i = 0; $i < $lineWidth; $i++) {
            imageline($image, $offset + $i, $offset, $offset + $cornerSize, $offset, $decorColor);
            imageline($image, $offset, $offset + $i, $offset, $offset + $cornerSize, $decorColor);
        }
        
        // Top right corner
        for ($i = 0; $i < $lineWidth; $i++) {
            imageline($image, $width - $offset - $i, $offset, $width - $offset - $cornerSize, $offset, $decorColor);
            imageline($image, $width - $offset, $offset + $i, $width - $offset, $offset + $cornerSize, $decorColor);
        }
        
        // Bottom left corner
        for ($i = 0; $i < $lineWidth; $i++) {
            imageline($image, $offset + $i, $height - $offset, $offset + $cornerSize, $height - $offset, $decorColor);
            imageline($image, $offset, $height - $offset - $i, $offset, $height - $offset - $cornerSize, $decorColor);
        }
        
        // Bottom right corner
        for ($i = 0; $i < $lineWidth; $i++) {
            imageline($image, $width - $offset - $i, $height - $offset, $width - $offset - $cornerSize, $height - $offset, $decorColor);
            imageline($image, $width - $offset, $height - $offset - $i, $width - $offset, $height - $offset - $cornerSize, $decorColor);
        }
    }
    
    /**
     * Add modern elegant welcome message with enhanced typography (using event title)
     */
    private function addModernWelcomeMessage($image, $data, $width, $height)
    {
        $fontPath = $this->getFontPath();
        $welcomeText = $data['event_title'] ?? "You Are Cordially Invited";
        $fontSize = 40; // Slightly larger for modern look
        $y = 190;
        
        $textColor = imagecolorallocate($image, 139, 69, 19); // #8B4513
        $accentColor = imagecolorallocate($image, 160, 82, 45); // #A0522D
        
        if ($fontPath && function_exists('imagettftext')) {
            $textBox = imagettfbbox($fontSize, 0, $fontPath, $welcomeText);
            if ($textBox) {
                $textHeight = abs($textBox[5] - $textBox[1]);
                $textWidth = abs($textBox[4] - $textBox[0]);
                $x = ($width - $textWidth) / 2;
                $y = 190 + $textHeight;
                
                // Main text with elegant styling (no shadow)
                imagettftext($image, $fontSize, -1.5, $x, $y, $textColor, $fontPath, $welcomeText);
            }
        } else {
            $x = ($width - strlen($welcomeText) * 22) / 2;
            imagestring($image, 5, $x, $y - 20, $welcomeText, $textColor);
        }
        
        // Modern decorative line with ornaments
        $lineY = $y + 60;
        $lineColor = $accentColor;
        $lineX1 = $width * 0.15; // More centered
        $lineX2 = $width * 0.85;
        $lineHeight = 5; // Slightly thicker
        
        // Add decorative dots at line ends
        $this->drawDecorativeDot($image, $lineX1 - 15, $lineY + 2, $accentColor);
        $this->drawDecorativeDot($image, $lineX2 + 15, $lineY + 2, $accentColor);
        
        // Draw elegant line
        for ($i = 0; $i < $lineHeight; $i++) {
            imageline($image, $lineX1, $lineY + $i, $lineX2, $lineY + $i, $lineColor);
        }
    }

    /**
     * Draw decorative dot ornament
     */
    private function drawDecorativeDot($image, $x, $y, $color)
    {
        $radius = 4;
        for ($i = -$radius; $i <= $radius; $i++) {
            for ($j = -$radius; $j <= $radius; $j++) {
                if ($i * $i + $j * $j <= $radius * $radius) {
                    imagesetpixel($image, $x + $i, $y + $j, $color);
                }
            }
        }
    }
    
    /**
     * Add modern guest name with elegant styling and emphasis
     */
    private function addModernGuestName($image, $guestName, $width, $height)
    {
        $fontPath = $this->getFontPath();
        $fontSize = 42; // Reduced size to fit better in card
        $y = 340;
        
        $textColor = imagecolorallocate($image, 101, 67, 33); // #654321
        $accentColor = imagecolorallocate($image, 139, 69, 19); // #8B4513
        
        if ($fontPath && function_exists('imagettftext')) {
            // Check if text fits, if not reduce font size
            $textBox = imagettfbbox($fontSize, 0, $fontPath, $guestName);
            if ($textBox) {
                $textWidth = abs($textBox[4] - $textBox[0]);
                $maxWidth = $width - 120; // Leave margins
                
                // Reduce font size if text is too wide
                $currentFontSize = $fontSize;
                while ($textWidth > $maxWidth && $currentFontSize > 20) {
                    $currentFontSize -= 2;
                    $textBox = imagettfbbox($currentFontSize, 0, $fontPath, $guestName);
                    $textWidth = $textBox ? abs($textBox[4] - $textBox[0]) : $maxWidth;
                }
                
                $textHeight = abs($textBox[5] - $textBox[1]);
                $x = ($width - $textWidth) / 2;
                $y = 340 + $textHeight;
                
                // Main text with emphasis (no shadow)
                imagettftext($image, $currentFontSize, 0, $x, $y, $textColor, $fontPath, $guestName);
                
                // Add subtle underline accent (using current text width)
                $underlineY = $y + 15;
                $underlineWidth = $textWidth * 0.6;
                $underlineX = $x + ($textWidth - $underlineWidth) / 2;
                for ($i = 0; $i < 3; $i++) {
                    imageline($image, $underlineX, $underlineY + $i, $underlineX + $underlineWidth, $underlineY + $i, $accentColor);
                }
            }
        } else {
            $x = ($width - strlen($guestName) * 35) / 2;
            imagestring($image, 5, $x, $y - 20, $guestName, $textColor);
        }
    }

    /**
     * Add Swahili invitation message with proper formatting
     */
    private function addSwahiliInvitationMessage($image, $data, $width, $height)
    {
        $fontPath = $this->getFontPath();
        $startY = 460;
        $fontSize = 26; // Readable size for the custom card
        $lineHeight = 42;

        $textColor = imagecolorallocate($image, 101, 67, 33); // #654321

        $guestName = $data['guest_name'] ?? 'Ndugu';
        $hostData = $data['host_event_data'] ?? [];
        $hostFull = $hostData['host_fullname'] ?? $hostData['host_name'] ?? ($data['groom_name'] ?? '');
        $childrenNames = $hostData['children_names'] ?? $hostData['couple_names'] ?? ($data['event_data']['children_names'] ?? $data['event_title'] ?? '');
        $eventDate = $data['event_date_swahili'] ?? $data['event_date'] ?? '';
        $venue = $data['venue'] ?? '';

        $lines = [];
        $lines[] = trim("Familia ya Bw & Bi " . $hostFull . " inayofuraha kukualika/Kuwaalika");
        $lines[] = strtoupper($guestName);
        $lines[] = "kwenye Sherehe ya Harusi ya Watoto wao";
        if (!empty($childrenNames)) {
            $lines[] = $childrenNames;
        }
        $lines[] = trim("itakayofanyika Tar " . $eventDate . ", Katika Ukumbi wa " . $venue . ".");
        $lines[] = "Ukiwa kama ndugu, rafiki au jamaa uwepo wako ndio furaha yetu. Karibu Sana.";

        if ($fontPath && function_exists('imagettftext')) {
            $y = $startY;
            foreach ($lines as $idx => $line) {
                $role = match($idx) {
                    0 => 'heading',
                    1 => 'guest_name',
                    2 => 'subheading',
                    3 => 'children',
                    4 => 'details',
                    5 => 'closing',
                    default => 'details'
                };

                $style = $this->getTextStyle($role);
                $renderLine = $line;
                if (!empty($style['upper'])) {
                    $renderLine = mb_strtoupper($renderLine);
                }

                $font = $style['font'];
                $size = $style['size'];
                $lineHeightLocal = $style['line'];
                $col = $style['color'];
                $colorAlloc = imagecolorallocate($image, $col['r'], $col['g'], $col['b']);

                $textBox = $font ? imagettfbbox($size, 0, $font, $renderLine) : null;
                $textWidth = $textBox ? abs($textBox[4] - $textBox[0]) : (strlen($renderLine) * 12);
                $textHeight = $textBox ? abs($textBox[5] - $textBox[1]) : $size;

                $x = ($width - $textWidth) / 2;
                $currentY = $y + $textHeight;

                if ($font && function_exists('imagettftext')) {
                    imagettftext($image, $size, 0, $x, $currentY, $colorAlloc, $font, $renderLine);
                } else {
                    imagestring($image, 4, $x, $y, $renderLine, $colorAlloc);
                }

                $y = $currentY + $lineHeightLocal;
            }
        } else {
            $y = $startY;
            foreach ($lines as $line) {
                $x = ($width - strlen($line) * 12) / 2;
                imagestring($image, 4, $x, $y, $line, $textColor);
                $y += 20;
            }
        }
    }
    
    /**
     * Draw simple decorative icon
     */
    private function drawDecorativeIcon($image, $x, $y, $color, $type)
    {
        $size = 8;
        if ($type === '★' || $type === 'Event:') {
            // Star shape
            for ($i = 0; $i < 5; $i++) {
                $angle = ($i * 144 - 90) * M_PI / 180;
                $px = $x + cos($angle) * $size;
                $py = $y + sin($angle) * $size;
                imagesetpixel($image, (int)$px, (int)$py, $color);
            }
        } else {
            // Simple circle
            for ($i = -$size; $i <= $size; $i++) {
                for ($j = -$size; $j <= $size; $j++) {
                    if ($i * $i + $j * $j <= $size * $size && $i * $i + $j * $j >= ($size - 2) * ($size - 2)) {
                        imagesetpixel($image, $x + $i, $y + $j, $color);
                    }
                }
            }
        }
    }

    /**
     * Add modern QR code section with elegant presentation and enhanced styling
     */
    private function addModernQRCodeSection($image, $qrCode, $width, $height)
    {
        // Log for debugging
        Log::info('addModernQRCodeSection called', [
            'qr_code' => $qrCode,
            'qr_code_type' => gettype($qrCode),
            'is_empty' => empty($qrCode)
        ]);
        
        // Support being passed the full data array (from generateCustomCard) or just the qr string
        if (is_array($qrCode)) {
            $data = $qrCode;
            $qrCode = $data['qr_code_value'] ?? ($data['qr_code'] ?? '');
        } else {
            $data = [];
        }

        if (empty($qrCode)) {
            Log::warning('QR code is empty, cannot add to card - will show placeholder');
            $this->addQRCodePlaceholder($image, $width, $height);
            return;
        }
        
        // QR code size: Increased for better scannability (250px)
        $qrSize = 250;
        // Container padding: 20px * 2 = 40px (scaled)
        $containerPadding = 45; // Slightly more padding
        // Border: 2px * 2 = 4px (scaled)
        $borderWidth = 5; // Slightly thicker
        
        // Position: Better centered, accounting for invitation code text below
        $invitationCodeTextHeight = 60; // More space for text
        $bottomMargin = 110; // More margin
        $qrY = $height - $bottomMargin - $invitationCodeTextHeight - $qrSize - $containerPadding;
        $qrX = ($width - $qrSize - ($containerPadding * 2)) / 2;
        
        Log::info('Adding modern QR code section to card', [
            'qr_code' => $qrCode,
            'size' => $qrSize,
            'position' => ['x' => $qrX, 'y' => $qrY]
        ]);
        
        // Generate QR code image - ensure it's always generated
        $qrCodeImage = null;
        try {
            $qrCodeImage = $this->generateQRCodeImage($qrCode, $qrSize);
            if (!$qrCodeImage) {
                Log::error('QR code generation returned null', [
                    'qr_code' => $qrCode,
                    'qr_size' => $qrSize
                ]);
                // Retry once
                $qrCodeImage = $this->generateQRCodeImage($qrCode, $qrSize);
            }
        } catch (\Exception $e) {
            Log::error('QR code generation failed with exception, retrying', [
                'error' => $e->getMessage(),
                'qr_code' => $qrCode,
                'trace' => $e->getTraceAsString()
            ]);
            // Retry once
            try {
                $qrCodeImage = $this->generateQRCodeImage($qrCode, $qrSize);
            } catch (\Exception $e2) {
                Log::error('QR code generation failed on retry', [
                    'error' => $e2->getMessage(),
                    'qr_code' => $qrCode
                ]);
                $qrCodeImage = null;
            }
        }
        
        if ($qrCodeImage && (is_resource($qrCodeImage) || (is_object($qrCodeImage) && get_class($qrCodeImage) === 'GdImage'))) {
            $qrImageWidth = imagesx($qrCodeImage);
            $qrImageHeight = imagesy($qrCodeImage);
            
            // Resize if needed - use high quality resampling
            if ($qrImageWidth != $qrSize || $qrImageHeight != $qrSize) {
                $resizedQr = imagecreatetruecolor($qrSize, $qrSize);
                imagealphablending($resizedQr, false);
                imagesavealpha($resizedQr, true);
                
                // Fill with white background first
                $white = imagecolorallocate($resizedQr, 255, 255, 255);
                imagefill($resizedQr, 0, 0, $white);
                
                // Use high quality resampling
                imagecopyresampled($resizedQr, $qrCodeImage, 0, 0, 0, 0, $qrSize, $qrSize, $qrImageWidth, $qrImageHeight);
                imagedestroy($qrCodeImage);
                $qrCodeImage = $resizedQr;
            }
            
            // Container dimensions
            $containerX = $qrX - $containerPadding;
            $containerY = $qrY - $containerPadding;
            $containerWidth = $qrSize + ($containerPadding * 2);
            $containerHeight = $containerWidth; // Square container
            
            // Add modern white background with shadow effect
            $whiteBg = imagecolorallocate($image, 255, 255, 255);
            $shadowColor = imagecolorallocatealpha($image, 0, 0, 0, 30);
            
            // Draw shadow first
            imagefilledrectangle($image, 
                $containerX + 4, 
                $containerY + 4, 
                $containerX + $containerWidth + 4, 
                $containerY + $containerHeight + 4, 
                $shadowColor
            );
            
            // Draw white background
            imagefilledrectangle($image, 
                $containerX, 
                $containerY, 
                $containerX + $containerWidth, 
                $containerY + $containerHeight, 
                $whiteBg
            );
            
            // Add modern decorative border with double line effect
            $borderColor = imagecolorallocate($image, 160, 82, 45); // #A0522D
            $borderColor2 = imagecolorallocate($image, 139, 69, 19); // #8B4513
            
            // Outer border
            for ($i = 0; $i < $borderWidth; $i++) {
                imagerectangle($image, 
                    $containerX + $i, 
                    $containerY + $i, 
                    $containerX + $containerWidth - $i, 
                    $containerY + $containerHeight - $i, 
                    $borderColor
                );
            }
            
            // Inner accent border
            $innerOffset = $borderWidth + 3;
            imagerectangle($image, 
                $containerX + $innerOffset, 
                $containerY + $innerOffset, 
                $containerX + $containerWidth - $innerOffset, 
                $containerY + $containerHeight - $innerOffset, 
                $borderColor2
            );
            
            // Copy QR code onto the image
            imagecopy($image, $qrCodeImage, $qrX, $qrY, 0, 0, $qrSize, $qrSize);
            imagedestroy($qrCodeImage);
            
            // Add modern invitation code text with elegant styling
            $this->addModernInvitationCodeText($image, $qrCode, $width, $height, $containerY + $containerHeight);

            // Draw guest type label (e.g., 'Single' or 'Double') to the right of the QR container
            $guestType = $data['guest_type_text'] ?? ($data['guest_type'] ?? 'Single');
            $labelText = trim($guestType ?: 'Single');
            if (!empty($labelText)) {
                $fontPath = $this->getFontPath();
                $labelSize = 28; // readable label size
                $labelColor = imagecolorallocate($image, 34, 34, 34); // dark gray

                // Calculate label position: to the right of the container, vertically centered
                $labelX = (int)($containerX + $containerWidth + 24);
                $labelY = (int)($containerY + ($containerHeight / 2));

                if ($fontPath && function_exists('imagettftext')) {
                    $box = imagettfbbox($labelSize, 0, $fontPath, $labelText);
                    $labelH = $box ? abs($box[5] - $box[1]) : $labelSize;
                    // Adjust Y so text is vertically centered on QR
                    $textY = $labelY + ($labelH / 2);
                    imagettftext($image, $labelSize, 0, $labelX, $textY, $labelColor, $fontPath, $labelText);
                } else {
                    imagestring($image, 5, $labelX, $labelY, $labelText, $labelColor);
                }
            }
            
            Log::info('Modern QR code inserted successfully', [
                'final_position' => ['x' => $qrX, 'y' => $qrY],
                'final_size' => $qrSize
            ]);
        } else {
            Log::error('Failed to generate QR code image', [
                'qr_data' => $qrCode ?? 'empty',
                'qr_size' => $qrSize
            ]);
            $this->addQRCodePlaceholder($image, $width, $height, $qrCode);
        }
    }
    
    /**
     * Add modern invitation code text with elegant styling
     */
    private function addModernInvitationCodeText($image, $code, $width, $height, $startY)
    {
        $fontPath = $this->getFontPath();
        $text = "Invitation Code: " . $code;
        $fontSize = 20; // Slightly larger
        $y = $startY + 40; // Better spacing
        
        $textColor = imagecolorallocate($image, 101, 67, 33); // #654321
        $accentColor = imagecolorallocate($image, 139, 69, 19); // #8B4513
        
        if ($fontPath && function_exists('imagettftext')) {
            $textBox = imagettfbbox($fontSize, 0, $fontPath, $text);
            if ($textBox) {
                $textWidth = abs($textBox[4] - $textBox[0]);
                $textHeight = abs($textBox[5] - $textBox[1]);
                $x = ($width - $textWidth) / 2;
                $y = $startY + 40 + $textHeight;
                
                // Main text (no shadow)
                imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontPath, $text);
                
                // Add decorative underline
                $underlineY = $y + 8;
                $underlineWidth = $textWidth * 0.4;
                $underlineX = $x + ($textWidth - $underlineWidth) / 2;
                for ($i = 0; $i < 2; $i++) {
                    imageline($image, $underlineX, $underlineY + $i, $underlineX + $underlineWidth, $underlineY + $i, $accentColor);
                }
            }
        } else {
            $x = ($width - strlen($text) * 12) / 2;
            imagestring($image, 4, $x, $y - 15, $text, $textColor);
        }
    }
    
    /**
     * Add QR code section (matching CSS design: white background, border, centered) - OLD METHOD
     */
    private function addQRCodeSection($image, $qrCode, $width, $height)
    {
        // Log for debugging
        Log::info('addQRCodeSection called', [
            'qr_code' => $qrCode,
            'qr_code_type' => gettype($qrCode),
            'is_empty' => empty($qrCode)
        ]);
        
        if (empty($qrCode)) {
            Log::warning('QR code is empty, cannot add to card - will show placeholder');
            // Show placeholder instead of returning
            $this->addQRCodePlaceholder($image, $width, $height);
            return;
        }
        
        // QR code size: Increased for better scannability (250px)
        $qrSize = 250;
        // Container padding: 20px * 2 = 40px (scaled)
        $containerPadding = 40;
        // Border: 2px * 2 = 4px (scaled)
        $borderWidth = 4;
        
        // Position: Better centered, accounting for invitation code text below
        // QR code should be positioned above the invitation code text
        $invitationCodeTextHeight = 50; // Space for invitation code text
        $bottomMargin = 100; // Margin from bottom
        $qrY = $height - $bottomMargin - $invitationCodeTextHeight - $qrSize - $containerPadding;
        $qrX = ($width - $qrSize - ($containerPadding * 2)) / 2;
        
        Log::info('Adding QR code section to card', [
            'qr_code' => $qrCode,
            'size' => $qrSize,
            'position' => ['x' => $qrX, 'y' => $qrY]
        ]);
        
        // Generate QR code image - ensure it's always generated
        $qrCodeImage = null;
        try {
            $qrCodeImage = $this->generateQRCodeImage($qrCode, $qrSize);
            if (!$qrCodeImage) {
                Log::error('QR code generation returned null', [
                    'qr_code' => $qrCode,
                    'qr_size' => $qrSize
                ]);
                // Retry once
                $qrCodeImage = $this->generateQRCodeImage($qrCode, $qrSize);
            }
        } catch (\Exception $e) {
            Log::error('QR code generation failed with exception, retrying', [
                'error' => $e->getMessage(),
                'qr_code' => $qrCode,
                'trace' => $e->getTraceAsString()
            ]);
            // Retry once
            try {
                $qrCodeImage = $this->generateQRCodeImage($qrCode, $qrSize);
            } catch (\Exception $e2) {
                Log::error('QR code generation failed on retry', [
                    'error' => $e2->getMessage(),
                    'qr_code' => $qrCode
                ]);
                $qrCodeImage = null;
            }
        }
        
        if ($qrCodeImage && (is_resource($qrCodeImage) || (is_object($qrCodeImage) && get_class($qrCodeImage) === 'GdImage'))) {
            $qrImageWidth = imagesx($qrCodeImage);
            $qrImageHeight = imagesy($qrCodeImage);
            
            // Resize if needed - use high quality resampling
            if ($qrImageWidth != $qrSize || $qrImageHeight != $qrSize) {
                $resizedQr = imagecreatetruecolor($qrSize, $qrSize);
                // Use high quality resampling to preserve QR code clarity
                imagealphablending($resizedQr, false);
                imagesavealpha($resizedQr, true);
                
                // Fill with white background first
                $white = imagecolorallocate($resizedQr, 255, 255, 255);
                imagefill($resizedQr, 0, 0, $white);
                
                // Use high quality resampling
                imagecopyresampled($resizedQr, $qrCodeImage, 0, 0, 0, 0, $qrSize, $qrSize, $qrImageWidth, $qrImageHeight);
                imagedestroy($qrCodeImage);
                $qrCodeImage = $resizedQr;
            }
            
            // Ensure QR code has proper alpha handling and white background
            imagealphablending($qrCodeImage, false);
            imagesavealpha($qrCodeImage, true);
            
            // Verify QR code image is valid
            if (imagesx($qrCodeImage) <= 0 || imagesy($qrCodeImage) <= 0) {
                Log::error('Invalid QR code image dimensions', [
                    'width' => imagesx($qrCodeImage),
                    'height' => imagesy($qrCodeImage)
                ]);
                imagedestroy($qrCodeImage);
                $qrCodeImage = null;
            }
            
            // Container dimensions
            $containerX = $qrX - $containerPadding;
            $containerY = $qrY - $containerPadding;
            $containerWidth = $qrSize + ($containerPadding * 2);
            $containerHeight = $containerWidth; // Square container
            
            // Add white background (matching CSS: background: white)
            $whiteBg = imagecolorallocate($image, 255, 255, 255);
            imagefilledrectangle($image, 
                $containerX, 
                $containerY, 
                $containerX + $containerWidth, 
                $containerY + $containerHeight, 
                $whiteBg);
            
            // Add border (2px solid #8B4513)
            $borderColor = imagecolorallocate($image, 139, 69, 19); // #8B4513
            for ($i = 0; $i < 4; $i++) {
                imagerectangle($image, 
                    $containerX + $i, 
                    $containerY + $i, 
                    $containerX + $containerWidth - $i, 
                    $containerY + $containerHeight - $i, 
                    $borderColor);
            }
            
            // Copy QR code onto card - ensure high quality copy
            // Disable alpha blending for QR code to preserve sharp edges
            imagealphablending($image, false);
            imagesavealpha($image, true);
            
            // Copy QR code with exact pixel mapping (no blending)
            imagecopy($image, $qrCodeImage, $qrX, $qrY, 0, 0, $qrSize, $qrSize);
            
            // Re-enable alpha blending for rest of image
            imagealphablending($image, true);
            
            imagedestroy($qrCodeImage);
            
            Log::info('QR code copied to card successfully', [
                'position' => ['x' => $qrX, 'y' => $qrY],
                'size' => $qrSize
            ]);
            
            // Add invitation code text below QR (font-size 9px * 2 = 18px, #654321)
            $this->addInvitationCodeText($image, $qrCode, $width, $height, $containerY + $containerHeight);
            
            Log::info('QR code section added successfully to card');
        } else {
            Log::error('Failed to generate QR code image for card - will show placeholder');
            // Show placeholder if QR generation fails
            $this->addQRCodePlaceholder($image, $width, $height, $qrCode);
        }
    }
    
    /**
     * Add QR code placeholder when QR code cannot be generated
     */
    private function addQRCodePlaceholder($image, $width, $height, $code = null)
    {
        // QR code size: Increased for better scannability (250px)
        $qrSize = 250;
        // Container padding: 20px * 2 = 40px (scaled)
        $containerPadding = 40;
        // Border: 2px * 2 = 4px (scaled)
        $borderWidth = 4;
        
        // Position: Better centered, accounting for invitation code text below
        $invitationCodeTextHeight = 50; // Space for invitation code text
        $bottomMargin = 100; // Margin from bottom
        $qrY = $height - $bottomMargin - $invitationCodeTextHeight - $qrSize - $containerPadding;
        $qrX = ($width - $qrSize - ($containerPadding * 2)) / 2;
        
        // Container dimensions
        $containerX = $qrX - $containerPadding;
        $containerY = $qrY - $containerPadding;
        $containerWidth = $qrSize + ($containerPadding * 2);
        $containerHeight = $containerWidth;
        
        // Add white background
        $whiteBg = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, 
            $containerX, 
            $containerY, 
            $containerX + $containerWidth, 
            $containerY + $containerHeight, 
            $whiteBg);
        
        // Add border
        $borderColor = imagecolorallocate($image, 139, 69, 19);
        for ($i = 0; $i < $borderWidth; $i++) {
            imagerectangle($image, 
                $containerX + $i, 
                $containerY + $i, 
                $containerX + $containerWidth - $i, 
                $containerY + $containerHeight - $i, 
                $borderColor);
        }
        
        // Add "QR" text in the center
        $fontPath = $this->getFontPath();
        $text = "QR";
        $fontSize = 48;
        $textColor = imagecolorallocate($image, 101, 67, 33);
        
        if ($fontPath && function_exists('imagettftext')) {
            $textBox = imagettfbbox($fontSize, 0, $fontPath, $text);
            if ($textBox) {
                $textWidth = abs($textBox[4] - $textBox[0]);
                $textHeight = abs($textBox[5] - $textBox[1]);
                $x = $qrX + ($qrSize - $textWidth) / 2;
                $y = $qrY + ($qrSize + $textHeight) / 2;
                imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontPath, $text);
            }
        }
        
        // Add invitation code text below if available
        if ($code) {
            $this->addInvitationCodeText($image, $code, $width, $height, $containerY + $containerHeight);
        }
    }

    /**
     * Add invitation code text below QR (matching CSS: font-size 9px, #654321, letter-spacing)
     */
    private function addInvitationCodeText($image, $code, $width, $height, $startY)
    {
        $fontPath = $this->getFontPath();
        $text = "Invitation Code: " . $code;
        $fontSize = 18; // 9px * 2 (scaled)
        $y = $startY + 30; // Better spacing from QR container
        
        $textColor = imagecolorallocate($image, 101, 67, 33); // #654321
        
        if ($fontPath && function_exists('imagettftext')) {
            $textBox = imagettfbbox($fontSize, 0, $fontPath, $text);
            if ($textBox) {
                $textHeight = abs($textBox[5] - $textBox[1]);
                $textWidth = abs($textBox[4] - $textBox[0]);
                $x = ($width - $textWidth) / 2;
                $y = $startY + 30 + $textHeight; // Adjust Y to account for text height
                imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontPath, $text);
            }
        } else {
            $x = ($width - strlen($text) * 14) / 2;
            imagestring($image, 3, $x, $y - 10, $text, $textColor);
        }
    }

    /**
     * Load template image from storage
     */
    private function loadTemplateImage(Template $template)
    {
        $relativePath = $template->artwork_path ?: $template->preview_image;
        if (empty($relativePath)) {
            Log::warning('Template has no artwork_path or preview_image set', ['template_id' => $template->id ?? null]);
            return null;
        }

        $imagePath = Storage::disk('public')->path($relativePath);

        if (!file_exists($imagePath)) {
            Log::error('Template image file not found', [
                'path' => $imagePath,
                'relative_path' => $relativePath,
                'template_id' => $template->id ?? null,
            ]);
            return null;
        }
        
        // Detect image type and load accordingly
        $imageInfo = getimagesize($imagePath);
        if (!$imageInfo) {
            return null;
        }
        
        $imageType = $imageInfo[2];
        
        switch ($imageType) {
            case IMAGETYPE_JPEG:
            case IMAGETYPE_JPEG2000:
                return imagecreatefromjpeg($imagePath);
                
            case IMAGETYPE_PNG:
                $img = imagecreatefrompng($imagePath);
                // Preserve transparency
                imagealphablending($img, false);
                imagesavealpha($img, true);
                return $img;
                
            case IMAGETYPE_GIF:
                return imagecreatefromgif($imagePath);
                
            case IMAGETYPE_WEBP:
                return imagecreatefromwebp($imagePath);
                
            default:
                Log::error('Unsupported image type', [
                    'type' => $imageType,
                    'path' => $imagePath
                ]);
                return null;
        }
    }

    /**
     * Insert content onto template background
     */
    private function insertContentOnTemplate($image, $data, Template $template, $width, $height)
    {
        // Preserve alpha channel
        imagealphablending($image, true);
        imagesavealpha($image, true);
        
        // Get placeholder config from template
        $placeholders = $template->placeholder_config ?? [];
        
        // Insert guest name
        $this->insertText($image, $data['guest_name'], $width, $height, 'guest_name', $placeholders);
        
        // Insert event title
        if (!empty($data['event_title'])) {
            $this->insertText($image, $data['event_title'], $width, $height, 'event_title', $placeholders);
        }
        
        // Insert event date
        if (!empty($data['event_date'])) {
            $this->insertText($image, $data['event_date'], $width, $height, 'event_date', $placeholders);
        }
        
        // Insert event time
        if (!empty($data['event_time'])) {
            $this->insertText($image, $data['event_time'], $width, $height, 'event_time', $placeholders);
        }
        
        // Insert venue
        if (!empty($data['venue'])) {
            $this->insertText($image, $data['venue'], $width, $height, 'venue', $placeholders);
        }
        
        // Insert description/message
        if (!empty($data['description'])) {
            $this->insertText($image, $data['description'], $width, $height, 'description', $placeholders);
        }
        
        // Insert QR code
        $this->insertQRCode($image, $data, $width, $height, $placeholders);
        
        // Insert couple photos if available
        $this->insertCouplePhotos($image, $data, $width, $height, $placeholders);
    }

    /**
     * Insert text at specified position
     */
    private function insertText($image, $text, $width, $height, $fieldName, $placeholders)
    {
        // Get position from placeholders or use default
        $position = $this->getTextPosition($fieldName, $placeholders, $width, $height);
        
        // Get text style from placeholders
        $fontSize = $placeholders[$fieldName]['font_size'] ?? $this->getDefaultFontSize($fieldName);
        $color = $this->getTextColor($image, $placeholders[$fieldName]['color'] ?? null);
        $align = $placeholders[$fieldName]['align'] ?? 'center';
        
        $fontPath = $this->getFontPath();
        
        // Calculate text position based on alignment
        $x = $position['x'];
        $y = $position['y'];
        
        if ($fontPath && function_exists('imagettftext')) {
            if ($align === 'center') {
                $textBox = imagettfbbox($fontSize, 0, $fontPath, $text);
                if ($textBox) {
                    $textWidth = abs($textBox[4] - $textBox[0]);
                    $x = $width / 2 - $textWidth / 2;
                }
            } elseif ($align === 'right') {
                $textBox = imagettfbbox($fontSize, 0, $fontPath, $text);
                if ($textBox) {
                    $textWidth = abs($textBox[4] - $textBox[0]);
                    $x = $width - $textWidth - ($width * 0.1);
                }
            }
            
            // Add text shadow for readability
            $shadowColor = imagecolorallocatealpha($image, 0, 0, 0, 50);
            imagettftext($image, $fontSize, 0, $x + 2, $y + 2, $shadowColor, $fontPath, $text);
            
            // Add main text
            imagettftext($image, $fontSize, 0, $x, $y, $color, $fontPath, $text);
        } else {
            // Fallback to built-in font
            $builtInFont = 5; // Large built-in font
            $textWidth = imagefontwidth($builtInFont) * strlen($text);
            
            if ($align === 'center') {
                $x = $width / 2 - $textWidth / 2;
            } elseif ($align === 'right') {
                $x = $width - $textWidth - ($width * 0.1);
            }
            
            // Add shadow
            $shadowColor = imagecolorallocate($image, 64, 64, 64);
            imagestring($image, $builtInFont, $x + 2, $y + 2, $text, $shadowColor);
            
            // Add main text
            imagestring($image, $builtInFont, $x, $y, $text, $color);
        }
    }

    /**
     * Get text position from placeholders or default
     */
    private function getTextPosition($fieldName, $placeholders, $width, $height)
    {
        // Check if position is defined in placeholders
        if (isset($placeholders[$fieldName]['x']) && isset($placeholders[$fieldName]['y'])) {
            return [
                'x' => $placeholders[$fieldName]['x'],
                'y' => $placeholders[$fieldName]['y']
            ];
        }
        
        // Default positions based on field name
        $defaultPositions = [
            'guest_name' => ['x' => $width / 2, 'y' => $height * 0.15],
            'event_title' => ['x' => $width / 2, 'y' => $height * 0.25],
            'event_date' => ['x' => $width / 2, 'y' => $height * 0.50],
            'event_time' => ['x' => $width / 2, 'y' => $height * 0.55],
            'venue' => ['x' => $width / 2, 'y' => $height * 0.60],
            'description' => ['x' => $width / 2, 'y' => $height * 0.70],
        ];
        
        return $defaultPositions[$fieldName] ?? ['x' => $width / 2, 'y' => $height * 0.5];
    }

    /**
     * Get default font size for field
     */
    private function getDefaultFontSize($fieldName)
    {
        $sizes = [
            'guest_name' => 32,
            'event_title' => 34,
            'event_date' => 24,
            'event_time' => 20,
            'venue' => 45,
            'description' => 28,
        ];
        
        return $sizes[$fieldName] ?? 20;
    }

    /**
     * Get text color
     */
    private function getTextColor($image, $colorHex = null)
    {
        if ($colorHex) {
            // Convert hex to RGB
            $rgb = $this->hexToRgb($colorHex);
            return imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']);
        }
        
        // Default dark color for readability
        return imagecolorallocate($image, 0, 0, 0);
    }

    /**
     * Convert hex color to RGB
     */
    private function hexToRgb($hex)
    {
        $hex = ltrim($hex, '#');
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2))
        ];
    }

    /**
     * Insert QR code on template
     */
    private function insertQRCode($image, $data, $width, $height, $placeholders)
    {
        // Get QR code position from placeholders or use default (bottom right)
        $qrSize = $placeholders['qr_code']['size'] ?? 150;
        
        // Ensure QR code size is reasonable
        $qrSize = min($qrSize, min($width, $height) * 0.3); // Max 30% of smallest dimension
        $qrSize = max($qrSize, 100); // Minimum 100px
        
        // Calculate position - ensure it's within image bounds
        $padding = 30;
        $qrX = $placeholders['qr_code']['x'] ?? ($width - $qrSize - $padding);
        $qrY = $placeholders['qr_code']['y'] ?? ($height - $qrSize - $padding);
        
        // Ensure position is within image bounds
        $qrX = max($padding, min($qrX, $width - $qrSize - $padding));
        $qrY = max($padding, min($qrY, $height - $qrSize - $padding));
        
        // Log QR code data for debugging
        Log::info('Generating QR code', [
            'qr_data' => $data['qr_code_value'] ?? ($data['qr_code'] ?? 'empty'),
            'size' => $qrSize,
            'position' => ['x' => $qrX, 'y' => $qrY],
            'image_dimensions' => ['width' => $width, 'height' => $height]
        ]);
        
        // Generate QR code image
        $qrCodeImage = $this->generateQRCodeImage($data['qr_code_value'] ?? ($data['qr_code'] ?? ''), $qrSize);
        
        if ($qrCodeImage) {
            $qrImageWidth = imagesx($qrCodeImage);
            $qrImageHeight = imagesy($qrCodeImage);
            
            Log::info('QR code image generated', [
                'qr_image_width' => $qrImageWidth,
                'qr_image_height' => $qrImageHeight,
                'target_size' => $qrSize
            ]);
            
            // Resize QR code if needed to match target size
            if ($qrImageWidth != $qrSize || $qrImageHeight != $qrSize) {
                $resizedQr = imagecreatetruecolor($qrSize, $qrSize);
                imagecopyresampled($resizedQr, $qrCodeImage, 0, 0, 0, 0, $qrSize, $qrSize, $qrImageWidth, $qrImageHeight);
                imagedestroy($qrCodeImage);
                $qrCodeImage = $resizedQr;
            }
            
            // Add white background for QR code for better visibility
            $whiteBg = imagecolorallocate($image, 255, 255, 255);
            $bgPadding = 15;
            imagefilledrectangle($image, 
                max(0, $qrX - $bgPadding), 
                max(0, $qrY - $bgPadding), 
                min($width - 1, $qrX + $qrSize + $bgPadding), 
                min($height - 1, $qrY + $qrSize + $bgPadding), 
                $whiteBg);
            
            // Add border around QR code
            $blackBorder = imagecolorallocate($image, 0, 0, 0);
            imagerectangle($image, 
                max(0, $qrX - $bgPadding), 
                max(0, $qrY - $bgPadding), 
                min($width - 1, $qrX + $qrSize + $bgPadding), 
                min($height - 1, $qrY + $qrSize + $bgPadding), 
                $blackBorder);
            
            // Copy QR code onto template
            imagecopy($image, $qrCodeImage, $qrX, $qrY, 0, 0, $qrSize, $qrSize);
            imagedestroy($qrCodeImage);
            
            Log::info('QR code inserted successfully', [
                'final_position' => ['x' => $qrX, 'y' => $qrY],
                'final_size' => $qrSize
            ]);
        } else {
            Log::error('Failed to generate QR code image', [
                'qr_data' => $data['qr_code_value'] ?? ($data['qr_code'] ?? 'empty'),
                'qr_size' => $qrSize
            ]);
        }
    }

    /**
     * Generate QR code image using invitation code (5-digit code)
     * Uses multiple fallback methods to ensure QR code is always generated
     */
    private function generateQRCodeImage($qrData, $size)
    {
        // Use invitation code directly as QR code data
        $invitationCode = is_string($qrData) && !empty($qrData) ? trim($qrData) : '';
        
        if (empty($invitationCode)) {
            Log::warning('Invitation code is empty, cannot generate QR code');
            return null;
        }
        
        $qrText = trim((string)$invitationCode);
        
        Log::info('Generating QR code with invitation code', [
            'invitation_code' => $qrText,
            'code_length' => strlen($qrText),
            'size' => $size
        ]);
        
        // Method 1: Try using facade (simplest and most reliable)
        $qrImage = $this->generateQRCodeWithFacade($qrText, $size);
        if ($qrImage) {
            return $qrImage;
        }
        
        // Method 2: Try using web API as fallback
        $qrImage = $this->generateQRCodeWithAPI($qrText, $size);
        if ($qrImage) {
            return $qrImage;
        }
        
        // Method 3: Try using direct library instantiation
        $qrImage = $this->generateQRCodeDirect($qrText, $size);
        if ($qrImage) {
            return $qrImage;
        }
        
        Log::error('All QR code generation methods failed', ['code' => $qrText]);
        return null;
    }
    
    /**
     * Generate QR code using SimpleSoftwareIO library via facade
     */
    private function generateQRCodeWithFacade($qrText, $size)
    {
        try {
            if (!class_exists('\SimpleSoftwareIO\QrCode\Facades\QrCode')) {
                return null;
            }
            
            // Use facade directly - simplest method
            $qrCodePng = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                ->size($size)
                ->margin(4)
                ->errorCorrection('H')
                ->generate($qrText);
            
            if (empty($qrCodePng) || strlen($qrCodePng) < 100) {
                return null;
            }
            
            // Create image from PNG data directly
            $qrImage = @imagecreatefromstring($qrCodePng);
            if ($qrImage) {
                imagealphablending($qrImage, false);
                imagesavealpha($qrImage, true);
                Log::info('QR code generated successfully with library facade');
                return $qrImage;
            }
        } catch (\Exception $e) {
            Log::warning('Library facade method failed', ['error' => $e->getMessage()]);
        }
        
        return null;
    }
    
    /**
     * Generate QR code using web API as fallback
     */
    private function generateQRCodeWithAPI($qrText, $size)
    {
        try {
            // Use QR Server API (free, no API key required, reliable)
            $url = 'https://api.qrserver.com/v1/create-qr-code/?';
            $params = http_build_query([
                'size' => $size . 'x' . $size,
                'data' => $qrText,
                'format' => 'png',
                'margin' => 4,
                'ecc' => 'H' // High error correction
            ]);
            
            $context = stream_context_create([
                'http' => [
                    'timeout' => 10,
                    'method' => 'GET',
                    'header' => 'User-Agent: Laravel QR Code Generator'
                ]
            ]);
            
            $qrCodePng = @file_get_contents($url . $params, false, $context);
            
            if ($qrCodePng && strlen($qrCodePng) > 100) {
                $qrImage = @imagecreatefromstring($qrCodePng);
                if ($qrImage) {
                    imagealphablending($qrImage, false);
                    imagesavealpha($qrImage, true);
                    Log::info('QR code generated successfully with API');
                    return $qrImage;
                }
            }
        } catch (\Exception $e) {
            Log::warning('API method failed', ['error' => $e->getMessage()]);
        }
        
        return null;
    }
    
    /**
     * Generate QR code using direct library instantiation
     */
    private function generateQRCodeDirect($qrText, $size)
    {
        try {
            if (!class_exists('\SimpleSoftwareIO\QrCode\Generator')) {
                return null;
            }
            
            $generator = new \SimpleSoftwareIO\QrCode\Generator();
            $qrCodePng = $generator->format('png')
                ->size($size)
                ->margin(4)
                ->errorCorrection('H')
                ->generate($qrText);
            
            if (empty($qrCodePng) || strlen($qrCodePng) < 100) {
                return null;
            }
            
            // Create image from PNG data
            $qrImage = @imagecreatefromstring($qrCodePng);
            if ($qrImage) {
                imagealphablending($qrImage, false);
                imagesavealpha($qrImage, true);
                Log::info('QR code generated successfully with direct instantiation');
                return $qrImage;
            }
        } catch (\Exception $e) {
            Log::warning('Direct instantiation method failed', ['error' => $e->getMessage()]);
        }
        
        return null;
    }
    
    
    /**
     * Generate a simple QR code using basic GD functions as fallback
     */
    private function generateSimpleQRCode($text, $size)
    {
        try {
            // Create a simple pattern-based QR code representation
            $qrImage = imagecreatetruecolor($size, $size);
            $white = imagecolorallocate($qrImage, 255, 255, 255);
            $black = imagecolorallocate($qrImage, 0, 0, 0);
            
            // Fill with white
            imagefill($qrImage, 0, 0, $white);
            
            // Create a simple pattern based on the text hash
            $hash = md5($text);
            $cellSize = $size / 25; // 25x25 grid
            
            for ($i = 0; $i < 25; $i++) {
                for ($j = 0; $j < 25; $j++) {
                    $charIndex = ($i * 25 + $j) % strlen($hash);
                    $charValue = ord($hash[$charIndex]);
                    
                    // Create pattern based on character value
                    if (($charValue + $i + $j) % 3 == 0) {
                        $x1 = (int)($j * $cellSize);
                        $y1 = (int)($i * $cellSize);
                        $x2 = (int)(($j + 1) * $cellSize);
                        $y2 = (int)(($i + 1) * $cellSize);
                        imagefilledrectangle($qrImage, $x1, $y1, $x2, $y2, $black);
                    }
                }
            }
            
            // Add finder patterns (corners)
            $finderSize = (int)($cellSize * 7);
            $this->drawFinderPattern($qrImage, 0, 0, $finderSize, $black, $white);
            $this->drawFinderPattern($qrImage, $size - $finderSize, 0, $finderSize, $black, $white);
            $this->drawFinderPattern($qrImage, 0, $size - $finderSize, $finderSize, $black, $white);
            
            Log::info('Simple QR code generated as fallback');
            return $qrImage;
        } catch (\Exception $e) {
            Log::error('Failed to generate simple QR code', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
     * Draw QR code finder pattern (the square patterns in corners)
     */
    private function drawFinderPattern($image, $x, $y, $size, $black, $white)
    {
        // Outer square (black)
        imagefilledrectangle($image, $x, $y, $x + $size - 1, $y + $size - 1, $black);
        
        // Inner white square
        $innerOffset = (int)($size / 7);
        imagefilledrectangle($image, 
            $x + $innerOffset, 
            $y + $innerOffset, 
            $x + $size - $innerOffset - 1, 
            $y + $size - $innerOffset - 1, 
            $white);
        
        // Center black square
        $centerOffset = (int)($size / 3);
        imagefilledrectangle($image, 
            $x + $centerOffset, 
            $y + $centerOffset, 
            $x + $size - $centerOffset - 1, 
            $y + $size - $centerOffset - 1, 
            $black);
    }

    /**
     * Insert couple photos if available
     */
    private function insertCouplePhotos($image, $data, $width, $height, $placeholders)
    {
        // Check for couple photos in event data - try multiple field names
        $eventData = $data['event_data'] ?? [];
        $hostEventData = $data['host_event_data'] ?? [];
        
        // Try to find any image field (couple_photo_main, couple_photo, photo, event_banner)
        $couplePhoto = $eventData['couple_photo_main'] ?? 
                      $eventData['couple_photo'] ?? 
                      $eventData['couple_photo_secondary'] ??
                      $eventData['photo'] ??
                      $eventData['event_banner'] ??
                      $hostEventData['couple_photo_main'] ?? 
                      $hostEventData['couple_photo'] ?? 
                      $hostEventData['couple_photo_secondary'] ??
                      $hostEventData['photo'] ??
                      $hostEventData['event_banner'] ?? null;
        
        if (!$couplePhoto) {
            return;
        }
        
        $photoPath = storage_path('app/public/event_uploads/' . $couplePhoto);
        
        if (!file_exists($photoPath)) {
            Log::warning("Image file not found: {$photoPath}");
            return;
        }
        
        // Get photo position from placeholders or use default
        $photoSize = $placeholders['couple_photo']['size'] ?? 200;
        $photoX = $placeholders['couple_photo']['x'] ?? ($width / 2 - $photoSize / 2);
        $photoY = $placeholders['couple_photo']['y'] ?? ($height * 0.35);
        
        // Load and resize photo
        $photoImage = $this->loadAndResizeImage($photoPath, $photoSize, $photoSize);
        
        if ($photoImage) {
            // Create circular mask for photo
            $maskedPhoto = $this->createCircularImage($photoImage, $photoSize);
            
            // Copy photo onto template
            imagecopy($image, $maskedPhoto, $photoX, $photoY, 0, 0, $photoSize, $photoSize);
            
            imagedestroy($photoImage);
            imagedestroy($maskedPhoto);
        }
    }

    /**
     * Load and resize image
     */
    private function loadAndResizeImage($path, $targetWidth, $targetHeight)
    {
        $imageInfo = getimagesize($path);
        if (!$imageInfo) {
            return null;
        }
        
        $imageType = $imageInfo[2];
        $sourceImage = null;
        
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($path);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($path);
                break;
            case IMAGETYPE_GIF:
                $sourceImage = imagecreatefromgif($path);
                break;
            default:
                return null;
        }
        
        if (!$sourceImage) {
            return null;
        }
        
        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);
        
        // Create resized image
        $resized = imagecreatetruecolor($targetWidth, $targetHeight);
        imagecopyresampled($resized, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);
        
        imagedestroy($sourceImage);
        
        return $resized;
    }

    /**
     * Create circular image mask
     */
    private function createCircularImage($image, $size)
    {
        $circular = imagecreatetruecolor($size, $size);
        imagealphablending($circular, false);
        imagesavealpha($circular, true);
        
        $transparent = imagecolorallocatealpha($circular, 0, 0, 0, 127);
        imagefill($circular, 0, 0, $transparent);
        
        $center = $size / 2;
        $radius = $size / 2;
        
        for ($x = 0; $x < $size; $x++) {
            for ($y = 0; $y < $size; $y++) {
                $distance = sqrt(pow($x - $center, 2) + pow($y - $center, 2));
                if ($distance <= $radius) {
                    $color = imagecolorat($image, $x, $y);
                    imagesetpixel($circular, $x, $y, $color);
                }
            }
        }
        
        return $circular;
    }

    /**
     * Get font path
     */
    private function getFontPath()
    {
        $fontPaths = [
            public_path('fonts/EpundaSans-Regular.ttf'),
            public_path('fonts/EpundaSans.ttf'),
            public_path('fonts/Poppins-Regular.ttf'),
            public_path('fonts/Roboto-Regular.ttf'),
            public_path('fonts/Arial.ttf'),
            public_path('fonts/arial.ttf'),
            '/System/Library/Fonts/Arial.ttf',
            'C:\Windows\Fonts\arial.ttf',
        ];
        
        foreach ($fontPaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        // Fallback: use built-in font (will need different text function)
        return null;
    }

    /**
     * Get text style spec for different roles on the card.
     * Returns array: [fontPath, fontSize, lineHeight, uppercase, colorRgb]
     */
    private function getTextStyle(string $role, $placeholders = null)
    {
        // Default base font and fallbacks
        $baseFont = $this->getFontPath();

        // Default colors
        $defaultColor = ['r' => 101, 'g' => 67, 'b' => 33]; // brown-ish used across card

        // Allow template placeholders to override styles if provided
        $override = null;
        if (is_array($placeholders) && isset($placeholders[$role])) {
            $override = $placeholders[$role];
        }

        // Style map for common roles
        $styles = [
            'heading' => ['font' => $baseFont, 'size' => 22, 'line' => 36, 'upper' => false, 'color' => $defaultColor],
            'guest_name' => ['font' => $baseFont, 'size' => 36, 'line' => 48, 'upper' => true, 'color' => $defaultColor],
            'subheading' => ['font' => $baseFont, 'size' => 20, 'line' => 32, 'upper' => false, 'color' => $defaultColor],
            'children' => ['font' => $baseFont, 'size' => 20, 'line' => 32, 'upper' => false, 'color' => $defaultColor],
            'details' => ['font' => $baseFont, 'size' => 18, 'line' => 30, 'upper' => false, 'color' => $defaultColor],
            'closing' => ['font' => $baseFont, 'size' => 18, 'line' => 30, 'upper' => false, 'color' => $defaultColor],
        ];

        $spec = $styles[$role] ?? $styles['details'];

        // Apply overrides from placeholders if present
        if ($override) {
            if (!empty($override['font_path']) && file_exists($override['font_path'])) {
                $spec['font'] = $override['font_path'];
            }
            if (!empty($override['font_size'])) {
                $spec['size'] = (int)$override['font_size'];
            }
            if (!empty($override['line_height'])) {
                $spec['line'] = (int)$override['line_height'];
            }
            if (isset($override['uppercase'])) {
                $spec['upper'] = (bool)$override['uppercase'];
            }
            if (!empty($override['color'])) {
                $spec['color'] = $this->hexToRgb($override['color']);
            }
        }

        return [
            'font' => $spec['font'],
            'size' => $spec['size'],
            'line' => $spec['line'],
            'upper' => $spec['upper'],
            'color' => $spec['color'],
        ];
    }

    /**
     * Prepare invitation data
     */
    private function prepareInvitationData(Invitation $invitation)
    {
        $event = $invitation->event;
        $host = $event->host ?? null;
        
        // Format time to remove seconds if present
        $time = $event->time ?? '18:00';
        if (strlen($time) > 5 && strpos($time, ':') !== false) {
            $timeParts = explode(':', $time);
            if (count($timeParts) >= 2) {
                $time = $timeParts[0] . ':' . $timeParts[1]; // Remove seconds
            }
        }
        
        // Ensure invitation code exists - generate if missing
        $invitationCode = $invitation->guest->invitation_code ?? '';
        if (empty($invitationCode)) {
            // Generate a unique 5-digit code if missing
            $maxAttempts = 100;
            $attempts = 0;
            do {
                $code = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
                $exists = \App\Models\Guest::where('invitation_code', $code)
                    ->where('event_id', $event->id)
                    ->exists();
                $attempts++;
                if ($attempts >= $maxAttempts) {
                    $code = substr(time(), -4) . rand(0, 9);
                    break;
                }
            } while ($exists);
            
            // Save the generated code to the guest
            $invitation->guest->update(['invitation_code' => $code]);
            $invitationCode = $code;
            
            \Log::info('Generated missing invitation code for guest', [
                'guest_id' => $invitation->guest->id,
                'invitation_code' => $invitationCode
            ]);
        }
        
        // Get groom name from host event data or event data
        $groomName = $host->event_config_data['groom_name'] ?? $event->custom_data['groom_name'] ?? 'Bwana';
        
        // Get guest type
        $guestType = $invitation->guest->guest_type ?? 'single';
        $guestTypeText = $guestType === 'double' ? 'Double' : 'Single';
        
        // Format date in Swahili format
        $eventDateFormatted = $event->date->format('d/m/Y');
        
        return [
            'guest_name' => $invitation->guest->full_name,
            'event_title' => $event->title,
            'event_date' => $event->date->format('F d, Y'),
            'event_date_swahili' => $eventDateFormatted,
            'event_time' => $time,
            'venue' => $event->venue,
            'venue_address' => $event->venue_address,
            'groom_name' => $groomName,
            'guest_type' => $guestType,
            'guest_type_text' => $guestTypeText,
            'description' => $invitation->whatsapp_message ?? 'Familia ya bwana dean garlus inakukaribisha ',
            'qr_code' => $invitationCode,
            'qr_code_value' => $invitation->slug ? route('invitation.show', $invitation->slug) : $invitationCode,
            'qr_code_path' => $invitation->qr_code_path,
            'invitation_slug' => $invitation->slug,
            'invitation_verification_url' => $invitation->slug ? route('invitation.show', $invitation->slug) : null,
            'event_data' => $event->custom_data ?? [],
            'host_event_data' => $host->event_config_data ?? [],
            'invitation_id' => $invitation->id,
        ];
    }

    /**
     * Generate fallback image if template is not available
     */
    private function generateFallback($invitationData)
    {
        $width = 900;
        $height = 1350;
        
        // Create simple white background
        $image = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $white);
        
        // Add basic text
        $black = imagecolorallocate($image, 0, 0, 0);
        $fontPath = $this->getFontPath();
        
        if ($fontPath) {
            imagettftext($image, 32, 0, $width / 2, 100, $black, $fontPath, $invitationData['guest_name']);
            imagettftext($image, 24, 0, $width / 2, 200, $black, $fontPath, $invitationData['event_title']);
            imagettftext($image, 20, 0, $width / 2, 300, $black, $fontPath, $invitationData['event_date']);
        }
        
        return $image;
    }

    /**
     * Generate PNG response
     */
    public function generatePNGResponse(Invitation $invitation)
    {
        $image = $this->generate($invitation);
        
        // Generate filename
        $guestName = str_replace(' ', '_', $invitation->guest->full_name);
        $filename = 'invitation_' . $guestName . '_' . time() . '.png';
        
        // Start output buffering
        ob_start();
        imagepng($image, null, 9); // Maximum quality
        $imageContent = ob_get_contents();
        ob_end_clean();
        
        // Clean up
        imagedestroy($image);
        
        // Return response
        return response($imageContent)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Generate PNG response that is displayed inline (for preview in browser)
     */
    public function generatePNGInlineResponse(Invitation $invitation)
    {
        $image = $this->generate($invitation);

        // Generate filename (for content-disposition header; still inline)
        $guestName = str_replace(' ', '_', $invitation->guest->full_name);
        $filename = 'invitation_' . $guestName . '_' . time() . '.png';

        // Start output buffering
        ob_start();
        imagepng($image, null, 9); // Maximum quality
        $imageContent = ob_get_contents();
        ob_end_clean();

        // Clean up
        imagedestroy($image);

        // Return response with inline disposition so browser opens image
        return response($imageContent)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    /**
     * Generate JPEG response
     */
    public function generateJPEGResponse(Invitation $invitation)
    {
        $image = $this->generate($invitation);
        
        // Generate filename
        $guestName = str_replace(' ', '_', $invitation->guest->full_name);
        $filename = 'invitation_' . $guestName . '_' . time() . '.jpg';
        
        // Start output buffering
        ob_start();
        imagejpeg($image, null, 95); // 95% quality
        $imageContent = ob_get_contents();
        ob_end_clean();
        
        // Clean up
        imagedestroy($image);
        
        // Return response
        return response($imageContent)
            ->header('Content-Type', 'image/jpeg')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
