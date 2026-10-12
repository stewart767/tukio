<?php

namespace App\Services;

use App\Models\Invitation;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvitationQrService
{
    public function ensureQrCode(Invitation $invitation): Invitation
    {
        if (!empty($invitation->qr_code_path) && Storage::disk('public')->exists($invitation->qr_code_path)) {
            return $invitation;
        }

        $verificationUrl = $this->getVerificationUrl($invitation);
        
        try {
            $relativePath = 'qrcodes/' . $invitation->slug . '.png';
            $content = QrCode::format('png')
                ->size(320)
                ->margin(2)
                ->errorCorrection('M')
                ->generate($verificationUrl);
        } catch (\Throwable $e) {
            $relativePath = 'qrcodes/' . $invitation->slug . '.svg';
            $content = QrCode::format('svg')
                ->size(320)
                ->margin(2)
                ->errorCorrection('M')
                ->generate($verificationUrl);
        }

        Storage::disk('public')->put($relativePath, $content);

        $invitation->update([
            'qr_code_path' => $relativePath,
            'qr_code_data' => json_encode([
                'slug' => $invitation->slug,
                'verification_url' => $verificationUrl,
            ]),
        ]);

        return $invitation->fresh();
    }

    public function getVerificationUrl(Invitation $invitation): string
    {
        return route('invitation.show', $invitation->slug);
    }

    public function extractSlugFromScanValue(string $value): ?string
    {
        $value = trim($value);

        if ($value === '') {
            return null;
        }

        if (preg_match('/^[a-f0-9\-]{30,}$/i', $value)) {
            return $value;
        }

        $path = parse_url($value, PHP_URL_PATH);
        if (!is_string($path) || $path === '') {
            return null;
        }

        $segments = array_values(array_filter(explode('/', trim($path, '/'))));
        if (count($segments) >= 2 && strtolower($segments[0]) === 'invitation') {
            return $segments[1];
        }

        return null;
    }
}
