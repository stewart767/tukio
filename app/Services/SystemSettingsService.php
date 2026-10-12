<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SystemSettingsService
{
    public const CACHE_KEY = 'system_settings';
    public const CACHE_TTL = 3600; // seconds

    /**
     * Retrieve all cached settings as key/value pairs.
     */
    public function all(): array
    {
        try {
            if (!$this->settingsTableExists()) {
                return [];
            }

            return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
                try {
                    return Setting::query()
                        ->get()
                        ->mapWithKeys(function (Setting $setting) {
                            return [$setting->key => $setting->casted_value];
                        })
                        ->toArray();
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Failed to retrieve settings from database', [
                        'error' => $e->getMessage(),
                    ]);
                    return [];
                }
            });
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to check settings table existence', [
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * Forget cached settings.
     */
    public function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Retrieve a single setting.
     */
    public function get(string $key, $default = null)
    {
        return Arr::get($this->all(), $key, $default);
    }

    /**
     * Persist a setting value.
     */
    public function set(string $key, $value, string $section = 'general', ?string $type = null): void
    {
        Setting::setValue($key, $value, $section, $type);
        $this->forget();
    }

    /**
     * Populate runtime configuration with values stored in DB.
     */
    public function hydrateRuntimeConfig(): void
    {
        $appName = $this->get('system.name');
        $timezone = $this->get('system.timezone');

        if ($appName) {
            Config::set('app.name', $appName);
        }

        if ($timezone) {
            Config::set('app.timezone', $timezone);
            @date_default_timezone_set($timezone);
        }

        // Mail settings
        $mailHost = $this->get('mail.host');
        $mailPort = $this->get('mail.port');
        
        $mail = [
            'mail.mailers.smtp.host' => $mailHost,
            'mail.mailers.smtp.port' => $mailPort,
            'mail.mailers.smtp.encryption' => $this->get('mail.encryption'),
            'mail.mailers.smtp.username' => $this->get('mail.username'),
            'mail.mailers.smtp.password' => $this->get('mail.password'),
            'mail.mailers.smtp.timeout' => $this->get('mail.timeout'),
            'mail.from.address' => $this->get('mail.from_address'),
            'mail.from.name' => $this->get('mail.from_name') ?? $appName,
        ];

        foreach ($mail as $configKey => $value) {
            if (!is_null($value)) {
                Config::set($configKey, $value);
            }
        }

        // Set default mailer to 'smtp' (lowercase) if SMTP settings are configured
        // Otherwise, use the default from config or 'log' for testing
        if (!empty($mailHost) && !empty($mailPort)) {
            Config::set('mail.default', 'smtp');
        } else {
            // If no SMTP config, ensure we use a valid mailer (log is safe for development)
            $currentDefault = Config::get('mail.default', 'log');
            // Normalize to lowercase to prevent "Mailer [SMTP] is not defined" errors
            if (strtolower($currentDefault) === 'smtp' && empty($mailHost)) {
                Config::set('mail.default', 'log');
            } else {
                Config::set('mail.default', strtolower($currentDefault));
            }
        }

        // Flutterwave payment settings
        $flutterwave = array_filter([
            'public_key' => $this->get('payment.flutterwave.public_key'),
            'secret_key' => $this->get('payment.flutterwave.secret_key'),
            'encryption_key' => $this->get('payment.flutterwave.encryption_key'),
            'currency' => $this->get('payment.flutterwave.currency'),
            'environment' => $this->get('payment.flutterwave.environment', 'production'),
        ]);

        if (!empty($flutterwave)) {
            Config::set('services.flutterwave', array_merge(
                Config::get('services.flutterwave', []),
                $flutterwave
            ));
        }

        // SMS (Beem) settings
        $sms = array_filter([
            'api_key' => $this->get('sms.beem.api_key'),
            'secret_key' => $this->get('sms.beem.secret_key'),
            'sender_id' => $this->get('sms.beem.sender_id'),
            'callback_url' => $this->get('sms.beem.callback_url'),
        ]);

        if (!empty($sms)) {
            Config::set('services.sms.beem', array_merge(
                Config::get('services.sms.beem', []),
                $sms
            ));
        }

        // WhatsApp gateway (Meta or Beem)
        $whatsAppOverrides = array_filter([
            'access_token' => $this->get('whatsapp.meta.access_token'),
            'phone_number_id' => $this->get('whatsapp.meta.phone_number_id'),
            'graph_api_version' => $this->get('whatsapp.meta.graph_api_version'),
            'webhook_verify_token' => $this->get('whatsapp.meta.webhook_verify_token'),
        ]);

        if (!empty($whatsAppOverrides)) {
            Config::set('services.whatsapp', array_merge(
                Config::get('services.whatsapp', []),
                $whatsAppOverrides
            ));
        }

        // Beem WhatsApp overrides
        $beemOverrides = array_filter([
            'api_key' => $this->get('whatsapp.beem.api_key'),
            'secret_key' => $this->get('whatsapp.beem.secret_key'),
            'from' => $this->get('whatsapp.beem.from'),
            'broadcast_base' => $this->get('whatsapp.beem.broadcast_base'),
            'chat_base' => $this->get('whatsapp.beem.chat_base'),
        ]);

        if (!empty($beemOverrides)) {
            Config::set('services.beem', array_merge(
                Config::get('services.beem', []),
                $beemOverrides
            ));
        }
    }

    /**
     * Normalize a phone number to standard international format (e.g. 2557xxxxxxxx).
     */
    public function normalizePhone(?string $phone, string $default = '255662199071'): string
    {
        if (empty($phone)) {
            return $default;
        }

        $clean = preg_replace('/[^0-9]/', '', (string)$phone);

        if (empty($clean)) {
            return $default;
        }

        if (str_starts_with($clean, '0')) {
            $clean = '255' . substr($clean, 1);
        } elseif (strlen($clean) === 9 && in_array(substr($clean, 0, 1), ['6', '7'])) {
            $clean = '255' . $clean;
        }

        return $clean;
    }

    /**
     * Gather public-facing settings for landing pages.
     */
    public function getPublicPayload(): array
    {
        $logoPath = $this->get('system.logo');
        $faviconPath = $this->get('system.favicon');

        $rawPhone = $this->get('system.phone');
        $rawWhatsapp = $this->get('system.whatsapp')
            ?? $this->get('contact.whatsapp')
            ?? $this->get('whatsapp.support_phone')
            ?? $this->get('whatsapp.beem.from')
            ?? $rawPhone
            ?? config('services.beem.from');

        $cleanPhone = $this->normalizePhone($rawPhone, '255662199071');
        $cleanWhatsapp = $this->normalizePhone($rawWhatsapp, '255662199071');

        return [
            'name' => $this->get('system.name', 'Tukio 360'),
            'tagline' => $this->get('system.tagline', 'Digital Invitation Management System'),
            'logo_url' => $logoPath ? $this->storageUrl($logoPath) : null,
            'favicon_url' => $faviconPath ? $this->storageUrl($faviconPath) : null,
            'phone' => $rawPhone,
            'phone_clean' => $cleanPhone,
            'whatsapp' => $rawWhatsapp,
            'whatsapp_clean' => $cleanWhatsapp,
            'whatsapp_url' => 'https://wa.me/' . $cleanWhatsapp,
            'email' => $this->get('system.email'),
            'address' => $this->get('system.address'),
            'social' => $this->get('system.social', []),
            'currency' => $this->get('system.currency', 'TZS'),
            'timezone' => $this->get('system.timezone', config('app.timezone')),
        ];
    }

    /**
     * Gather payment settings (Selcom Pesa & gateways).
     */
    public function getPaymentSettings(): array
    {
        $qrPath = $this->get('payment.selcom.qr_code', 'images/payments/selcom-qr.jpg');
        $qrUrl = str_starts_with($qrPath, 'http') ? $qrPath : asset($qrPath);

        return [
            'selcom_number' => $this->get('payment.selcom.number', '5525108767899'),
            'selcom_beneficiary' => $this->get('payment.selcom.beneficiary', 'Stewart Amiri Seifu'),
            'selcom_qr_code' => $qrPath,
            'selcom_qr_url' => $qrUrl,
            'price_per_guest' => (float) $this->get('payment.price_per_guest', 2000.00),
            'currency' => $this->get('payment.currency', 'TZS'),
            'flutterwave_enabled' => (bool) $this->get('payment.flutterwave.enabled', false),
            'flutterwave_public_key' => $this->get('payment.flutterwave.public_key'),
            'flutterwave_secret_key' => $this->get('payment.flutterwave.secret_key'),
            'flutterwave_encryption_key' => $this->get('payment.flutterwave.encryption_key'),
            'flutterwave_currency' => $this->get('payment.flutterwave.currency', 'TZS'),
            'flutterwave_environment' => $this->get('payment.flutterwave.environment', 'production'),
        ];
    }

    protected function storageUrl(string $path): string
    {
        $normalized = preg_replace('#^public/#', '', $path);
        $normalized = ltrim($normalized, '/');

        // Use asset() for consistent relative URLs that work with .htaccess routing
        return asset('storage/' . $normalized);
    }

    protected function settingsTableExists(): bool
    {
        try {
            // Check database connection first
            \Illuminate\Support\Facades\DB::connection()->getPdo();
            return Schema::hasTable((new Setting())->getTable());
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::debug('Database connection or table check failed', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}

