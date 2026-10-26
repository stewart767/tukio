<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SystemSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class SettingController extends Controller
{
    public function __construct(
        protected SystemSettingsService $settings
    ) {}

    public function index()
    {
        $data = [
            'system' => [
                'name' => $this->settings->get('system.name', 'Tukio 360'),
                'tagline' => $this->settings->get('system.tagline', 'Digital Invitation Management System'),
                'timezone' => $this->settings->get('system.timezone', config('app.timezone', 'Africa/Nairobi')),
                'currency' => $this->settings->get('system.currency', 'TZS'),
                'address' => $this->settings->get('system.address'),
                'email' => $this->settings->get('system.email'),
                'phone' => $this->settings->get('system.phone'),
                'whatsapp' => $this->settings->get('system.whatsapp'),
                'social' => $this->settings->get('system.social', []),
                'logo' => $this->settings->get('system.logo'),
                'favicon' => $this->settings->get('system.favicon'),
            ],
            'contact' => [
                'email' => $this->settings->get('contact.email'),
                'phone' => $this->settings->get('contact.phone'),
                'whatsapp' => $this->settings->get('contact.whatsapp'),
                'address' => $this->settings->get('contact.address'),
            ],
            'footer' => [
                'links' => $this->settings->get('footer.links', []),
            ],
            'mail' => [
                'host' => $this->settings->get('mail.host'),
                'port' => $this->settings->get('mail.port'),
                'username' => $this->settings->get('mail.username'),
                'password' => $this->settings->get('mail.password'),
                'encryption' => $this->settings->get('mail.encryption'),
                'from_address' => $this->settings->get('mail.from_address'),
                'from_name' => $this->settings->get('mail.from_name'),
            ],
            'payment' => [
                'selcom_number' => $this->settings->get('payment.selcom.number', '5525108767899'),
                'selcom_beneficiary' => $this->settings->get('payment.selcom.beneficiary', 'Stewart Amiri Seifu'),
                'selcom_qr_code' => $this->settings->get('payment.selcom.qr_code', 'images/payments/selcom-qr.jpg'),
                'price_per_guest' => $this->settings->get('payment.price_per_guest', 2000),
                'public_key' => $this->settings->get('payment.flutterwave.public_key'),
                'secret_key' => $this->settings->get('payment.flutterwave.secret_key'),
                'encryption_key' => $this->settings->get('payment.flutterwave.encryption_key'),
                'currency' => $this->settings->get('payment.flutterwave.currency', 'TZS'),
                'environment' => $this->settings->get('payment.flutterwave.environment', 'production'),
                'enabled' => (bool) $this->settings->get('payment.flutterwave.enabled', false),
            ],
            'sms' => [
                'api_key' => $this->settings->get('sms.beem.api_key'),
                'secret_key' => $this->settings->get('sms.beem.secret_key'),
                'sender_id' => $this->settings->get('sms.beem.sender_id'),
                'callback_url' => $this->settings->get('sms.beem.callback_url'),
            ],
            'whatsapp' => [
                'support_phone' => $this->settings->get('whatsapp.support_phone', $this->settings->get('system.whatsapp') ?? $this->settings->get('whatsapp.beem.from')),
                'meta' => [
                    'access_token' => $this->settings->get('whatsapp.meta.access_token'),
                    'phone_number_id' => $this->settings->get('whatsapp.meta.phone_number_id'),
                    'graph_api_version' => $this->settings->get('whatsapp.meta.graph_api_version', 'v18.0'),
                    'webhook_verify_token' => $this->settings->get('whatsapp.meta.webhook_verify_token'),
                ],
                'beem' => [
                    'api_key' => $this->settings->get('whatsapp.beem.api_key'),
                    'secret_key' => $this->settings->get('whatsapp.beem.secret_key'),
                    'from' => $this->settings->get('whatsapp.beem.from'),
                    'broadcast_base' => $this->settings->get('whatsapp.beem.broadcast_base'),
                    'chat_base' => $this->settings->get('whatsapp.beem.chat_base'),
                ],
            ],
        ];

        $timezones = \DateTimeZone::listIdentifiers();
        $currencies = ['TZS', 'USD', 'KES', 'UGX'];

        $backups = collect(Storage::disk('local')->files('backups'))
            ->filter(fn ($file) => str_ends_with($file, '.sqlite'))
            ->map(function ($file) {
                $path = Storage::disk('local')->path($file);

                return [
                    'name' => basename($file),
                    'size' => $this->formatBytes(filesize($path)),
                    'date' => Carbon::createFromTimestamp(filemtime($path))->timezone(config('app.timezone'))->toDayDateTimeString(),
                ];
            })
            ->sortByDesc('date')
            ->values()
            ->all();

        return view('admin.settings.index', compact('data', 'timezones', 'backups', 'currencies'));
    }

    public function updateSystem(Request $request)
    {
        $validated = $request->validate([
            'system_name' => ['required', 'string', 'max:255'],
            'system_tagline' => ['nullable', 'string', 'max:255'],
            'timezone' => ['required', 'timezone'],
            'currency' => ['required', 'string', 'max:6'],
            'address' => ['nullable', 'string', 'max:500'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'logo' => [File::image()->max(2048)],
            'favicon' => [File::image()->max(1024)],
            'social.facebook' => ['nullable', 'url'],
            'social.instagram' => ['nullable', 'url'],
            'social.twitter' => ['nullable', 'url'],
            'social.linkedin' => ['nullable', 'url'],
            'social.youtube' => ['nullable', 'url'],
            'social.whatsapp' => ['nullable', 'string', 'max:255'],
        ]);

        $this->settings->set('system.name', $validated['system_name'], 'system');
        $this->settings->set('system.tagline', $validated['system_tagline'] ?? null, 'system');
        $this->settings->set('system.timezone', $validated['timezone'], 'system');
        $this->settings->set('system.currency', strtoupper($validated['currency']), 'system');
        $this->settings->set('system.address', $validated['address'] ?? null, 'system');
        $this->settings->set('system.email', $validated['email'] ?? null, 'system');
        $this->settings->set('system.phone', $validated['phone'] ?? null, 'system');
        $this->settings->set('system.whatsapp', $validated['whatsapp'] ?? null, 'system');

        if ($request->hasFile('logo')) {
            $this->deleteStoredFile($this->settings->get('system.logo'));
            $logoPath = $request->file('logo')->store('settings', 'public');
            $this->settings->set('system.logo', $logoPath, 'system');
        }

        if ($request->hasFile('favicon')) {
            $this->deleteStoredFile($this->settings->get('system.favicon'));
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            $this->settings->set('system.favicon', $faviconPath, 'system');
        }

        // Handle logo removal
        if ($request->has('remove_logo') && $request->input('remove_logo') === '1') {
            $this->deleteStoredFile($this->settings->get('system.logo'));
            $this->settings->set('system.logo', null, 'system');
        }

        // Handle favicon removal
        if ($request->has('remove_favicon') && $request->input('remove_favicon') === '1') {
            $this->deleteStoredFile($this->settings->get('system.favicon'));
            $this->settings->set('system.favicon', null, 'system');
        }

        $social = collect($request->input('social', []))
            ->filter(fn ($value) => !empty($value))
            ->toArray();

        $this->settings->set('system.social', $social, 'system', 'json');

        $this->settings->hydrateRuntimeConfig();

        return redirect()->route('admin.settings.index')->with('success', 'System settings updated successfully.');
    }

    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'host' => ['required', 'string', 'max:255'],
            'port' => ['required', 'integer'],
            'encryption' => ['nullable', Rule::in(['tls', 'ssl'])],
            'username' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:255'],
            'from_address' => ['required', 'email', 'max:255'],
            'from_name' => ['nullable', 'string', 'max:255'],
        ]);

        $this->settings->set('mail.host', $validated['host'], 'mail');
        $this->settings->set('mail.port', $validated['port'], 'mail');
        $this->settings->set('mail.encryption', $validated['encryption'] ?? null, 'mail');
        $this->settings->set('mail.username', $validated['username'] ?? null, 'mail');
        $this->settings->set('mail.password', $validated['password'] ?? null, 'mail');
        $this->settings->set('mail.from_address', $validated['from_address'], 'mail');
        $this->settings->set('mail.from_name', $validated['from_name'] ?? $this->settings->get('system.name'), 'mail');

        $this->settings->hydrateRuntimeConfig();

        return redirect()->route('admin.settings.index')->with('success', 'Email settings updated successfully.');
    }

    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'selcom_number' => ['nullable', 'string', 'max:50'],
            'selcom_beneficiary' => ['nullable', 'string', 'max:150'],
            'price_per_guest' => ['nullable', 'numeric', 'min:0'],
            'selcom_qr' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'public_key' => ['nullable', 'string'],
            'secret_key' => ['nullable', 'string'],
            'encryption_key' => ['nullable', 'string'],
            'currency' => ['nullable', 'string', 'max:6'],
            'environment' => ['nullable', Rule::in(['sandbox', 'production'])],
            'enabled' => ['nullable', 'boolean'],
        ]);

        if (!empty($validated['selcom_number'])) {
            $this->settings->set('payment.selcom.number', trim($validated['selcom_number']), 'payment');
        }

        if (!empty($validated['selcom_beneficiary'])) {
            $this->settings->set('payment.selcom.beneficiary', trim($validated['selcom_beneficiary']), 'payment');
        }

        if (isset($validated['price_per_guest'])) {
            $this->settings->set('payment.price_per_guest', (float) $validated['price_per_guest'], 'payment');
        }

        if ($request->hasFile('selcom_qr')) {
            $file = $request->file('selcom_qr');
            $filename = 'selcom-qr-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/payments', $filename);
            $this->settings->set('payment.selcom.qr_code', 'storage/payments/' . $filename, 'payment');
        }

        if (!empty($validated['public_key'])) {
            $this->settings->set('payment.flutterwave.public_key', $validated['public_key'], 'payment');
        }
        if (!empty($validated['secret_key'])) {
            $this->settings->set('payment.flutterwave.secret_key', $validated['secret_key'], 'payment');
        }
        if (!empty($validated['encryption_key'])) {
            $this->settings->set('payment.flutterwave.encryption_key', $validated['encryption_key'], 'payment');
        }
        if (!empty($validated['currency'])) {
            $this->settings->set('payment.flutterwave.currency', strtoupper($validated['currency']), 'payment');
        }
        if (!empty($validated['environment'])) {
            $this->settings->set('payment.flutterwave.environment', $validated['environment'], 'payment');
        }
        $this->settings->set('payment.flutterwave.enabled', (bool) ($validated['enabled'] ?? false), 'payment');

        $this->settings->hydrateRuntimeConfig();

        return redirect()->route('admin.settings.index')->with('success', 'Payment gateway & Selcom Pesa settings updated successfully.');
    }

    public function updateSms(Request $request)
    {
        $validated = $request->validate([
            'api_key' => ['required', 'string'],
            'secret_key' => ['required', 'string'],
            'sender_id' => ['required', 'string', 'max:15'],
            'callback_url' => ['nullable', 'url'],
        ]);

        $this->settings->set('sms.beem.api_key', $validated['api_key'], 'sms');
        $this->settings->set('sms.beem.secret_key', $validated['secret_key'], 'sms');
        $this->settings->set('sms.beem.sender_id', $validated['sender_id'], 'sms');
        $this->settings->set('sms.beem.callback_url', $validated['callback_url'] ?? null, 'sms');

        $this->settings->hydrateRuntimeConfig();

        return redirect()->route('admin.settings.index')->with('success', 'SMS Gateway settings updated successfully.');
    }

    public function testSms(Request $request, \App\Services\BulkSmsService $smsService)
    {
        $request->validate([
            'test_phone' => ['nullable', 'string', 'max:25'],
        ]);

        if ($request->filled('test_phone')) {
            $result = $smsService->sendSingle(
                $request->input('test_phone'),
                'Tukio 360: Huu ni ujumbe wa majaribio kuthibitisha kuwa Bulk SMS API inafanya kazi kikamilifu.'
            );
            if ($result['success']) {
                return redirect()->route('admin.settings.index')->with('success', 'Test SMS sent successfully!');
            }
            return redirect()->route('admin.settings.index')->with('error', 'Failed to send test SMS: ' . ($result['error'] ?? 'Unknown error'));
        }

        $result = $smsService->testConnection();
        if ($result['success']) {
            $balanceMsg = isset($result['balance']) ? " (Credit Balance: {$result['balance']})" : '';
            return redirect()->route('admin.settings.index')->with('success', $result['message'] . $balanceMsg);
        }

        return redirect()->route('admin.settings.index')->with('error', $result['message'] ?? 'Bulk SMS connection failed.');
    }

    public function updateWhatsapp(Request $request)
    {
        $validated = $request->validate([
            'support_phone' => ['nullable', 'string', 'max:50'],
            'meta.access_token' => ['nullable', 'string'],
            'meta.phone_number_id' => ['nullable', 'string'],
            'meta.graph_api_version' => ['nullable', 'string'],
            'meta.webhook_verify_token' => ['nullable', 'string'],

            'beem.api_key' => ['nullable', 'string'],
            'beem.secret_key' => ['nullable', 'string'],
            'beem.from' => ['nullable', 'string'],
            'beem.broadcast_base' => ['nullable', 'url'],
            'beem.chat_base' => ['nullable', 'url'],
        ]);

        if ($request->filled('support_phone')) {
            $this->settings->set('whatsapp.support_phone', $request->input('support_phone'), 'whatsapp');
            if (!$this->settings->get('system.whatsapp')) {
                $this->settings->set('system.whatsapp', $request->input('support_phone'), 'system');
            }
        } else {
            $this->settings->set('whatsapp.support_phone', null, 'whatsapp');
        }

        $meta = $validated['meta'] ?? [];
        $beem = $validated['beem'] ?? [];

        foreach ($meta as $key => $value) {
            $this->settings->set("whatsapp.meta.{$key}", $value, 'whatsapp');
        }

        foreach ($beem as $key => $value) {
            $this->settings->set("whatsapp.beem.{$key}", $value, 'whatsapp');
        }

        $this->settings->hydrateRuntimeConfig();

        return redirect()->route('admin.settings.index')->with('success', 'WhatsApp gateway settings updated successfully.');
    }

    public function createBackup()
    {
        $databasePath = $this->databaseFilePath();

        if (!file_exists($databasePath)) {
            return redirect()->back()->with('error', 'Database file not found for backup.');
        }

        $backupDirectory = storage_path('app/backups');
        if (!is_dir($backupDirectory)) {
            mkdir($backupDirectory, 0755, true);
        }

        $fileName = 'backup-' . now()->format('Ymd-His') . '.sqlite';
        $target = $backupDirectory . DIRECTORY_SEPARATOR . $fileName;

        copy($databasePath, $target);

        return Response::download($target);
    }

    public function downloadBackup(string $filename)
    {
        if (str_contains($filename, '..')) {
            abort(404);
        }

        $path = storage_path('app/backups/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return Response::download($path);
    }

    public function restoreBackup(Request $request)
    {
        $request->validate([
            'backup_file' => ['required', File::types(['sqlite'])->max(10240)],
        ]);

        $uploadedPath = $request->file('backup_file')->storeAs(
            'backups',
            'restore-' . now()->format('Ymd-His') . '.sqlite'
        );

        $source = storage_path('app/' . $uploadedPath);
        $databasePath = $this->databaseFilePath();

        copy($source, $databasePath);

        return redirect()->route('admin.settings.index')->with('success', 'Backup restored successfully.');
    }

    protected function deleteStoredFile(?string $path): void
    {
        if (!$path) {
            return;
        }

        $normalized = preg_replace('#^public/#', '', $path);

        if (Storage::disk('public')->exists($normalized)) {
            Storage::disk('public')->delete($normalized);
        }
    }

    protected function databaseFilePath(): string
    {
        $database = config('database.connections.sqlite.database');

        if (!$database) {
            throw new \RuntimeException('SQLite database path is not configured.');
        }

        if (Str::startsWith($database, ['/', '\\']) || preg_match('/^[A-Za-z]:\\\\/', $database)) {
            return $database;
        }

        return database_path($database);
    }

    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1024 ** $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function updateContactAndFooter(Request $request)
    {
        $validated = $request->validate([
            // Contact page settings
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_whatsapp' => ['nullable', 'string', 'max:50'],
            'contact_address' => ['nullable', 'string', 'max:500'],
            
            // Footer links - array of objects with label and url
            'footer_links' => ['nullable', 'array'],
            'footer_links.*.label' => ['required_with:footer_links.*', 'string', 'max:255'],
            'footer_links.*.url' => ['required_with:footer_links.*', 'string', 'max:500'],
        ]);

        // Update contact information
        $this->settings->set('contact.email', $validated['contact_email'] ?? null, 'contact');
        $this->settings->set('contact.phone', $validated['contact_phone'] ?? null, 'contact');
        $this->settings->set('contact.whatsapp', $validated['contact_whatsapp'] ?? null, 'contact');
        $this->settings->set('contact.address', $validated['contact_address'] ?? null, 'contact');

        // Update footer links - filter out empty entries
        $footerLinks = collect($validated['footer_links'] ?? [])
            ->filter(function ($link) {
                return !empty($link['label']) && !empty($link['url']);
            })
            ->values()
            ->toArray();

        $this->settings->set('footer.links', $footerLinks, 'footer', 'json');

        return redirect()->route('admin.settings.index')->with('success', 'Contact and footer settings updated successfully.');
    }
}

