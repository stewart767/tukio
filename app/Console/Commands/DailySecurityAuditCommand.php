<?php

namespace App\Console\Commands;

use App\Models\SecurityAuditLog;
use App\Models\SecurityBlockedIp;
use App\Models\SecurityIncident;
use App\Models\SecuritySetting;
use App\Services\Security\SecurityAlertService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class DailySecurityAuditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tukio:security-audit {--alert : Force dispatch alert to WhatsApp and Email regardless of incident count}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform an automated 24-hour security audit, integrity scan, and generate executive SOC report';

    /**
     * Execute the console command.
     */
    public function handle(SecurityAlertService $alertService): int
    {
        $this->info('Starting Tukio360 Enterprise Daily Security Audit...');

        $now = Carbon::now();
        $twentyFourHoursAgo = $now->copy()->subHours(24);

        // 1. Ingress Threats Analysis (Past 24 Hours)
        $incidentsLast24h = SecurityIncident::where('created_at', '>=', $twentyFourHoursAgo)->get();
        $totalIncidents = $incidentsLast24h->count();
        $criticalCount = $incidentsLast24h->where('severity', 'critical')->count();
        $highCount = $incidentsLast24h->where('severity', 'high')->count();

        $threatTypeBreakdown = $incidentsLast24h->groupBy('threat_type')->map->count()->toArray();

        // 2. Firewall / Active Ban Telemetry
        $activeBans = SecurityBlockedIp::where(function ($query) {
            $query->whereNull('expires_at')
                ->orWhere('expires_at', '>', Carbon::now());
        })->count();

        $newBansLast24h = SecurityBlockedIp::where('created_at', '>=', $twentyFourHoursAgo)->count();

        // 3. Application Integrity Checks
        $integrityIssues = [];

        // Check A: APP_DEBUG in production
        if (app()->environment('production') && config('app.debug') === true) {
            $integrityIssues[] = 'CRITICAL: APP_DEBUG is set to TRUE in production environment!';
        }

        // Check B: .env file presence & security
        $envPath = base_path('.env');
        if (!File::exists($envPath)) {
            $integrityIssues[] = 'WARNING: .env file not found in application root.';
        }

        // Check C: Storage and cache directories write permissions
        if (!File::isWritable(storage_path())) {
            $integrityIssues[] = 'ERROR: storage directory is not writable by web server.';
        }
        if (!File::isWritable(base_path('bootstrap/cache'))) {
            $integrityIssues[] = 'ERROR: bootstrap/cache directory is not writable by web server.';
        }

        // Check D: Database connection check
        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo();
        } catch (\Throwable $e) {
            $integrityIssues[] = 'CRITICAL: Database connection failed: ' . $e->getMessage();
        }

        // 4. Output Summary Table to Console
        $this->table(
            ['Metric', 'Past 24 Hours Value'],
            [
                ['Total Intercepted Attacks', $totalIncidents],
                ['Critical Severity Incidents', $criticalCount],
                ['High Severity Incidents', $highCount],
                ['New Attacker IPs Banned', $newBansLast24h],
                ['Total Active Blocked IPs', $activeBans],
                ['Integrity Issues Found', count($integrityIssues)],
            ]
        );

        if (!empty($threatTypeBreakdown)) {
            $this->info('Threat Types Breakdown (24h):');
            foreach ($threatTypeBreakdown as $type => $count) {
                $this->line("  - {$type}: {$count}");
            }
        }

        if (!empty($integrityIssues)) {
            $this->error('Integrity Warnings:');
            foreach ($integrityIssues as $issue) {
                $this->warn("  [!] {$issue}");
            }
        } else {
            $this->info('System integrity checks: ALL PASS (100% Nominal).');
        }

        // 5. Record Audit Log
        $auditPayload = [
            'period' => '24h',
            'incidents_total' => $totalIncidents,
            'critical_count' => $criticalCount,
            'high_count' => $highCount,
            'threat_breakdown' => $threatTypeBreakdown,
            'active_bans' => $activeBans,
            'new_bans' => $newBansLast24h,
            'integrity_status' => empty($integrityIssues) ? 'nominal' : 'warnings_detected',
            'integrity_issues' => $integrityIssues,
        ];

        SecurityAuditLog::record(
            'daily_security_audit',
            $auditPayload,
            null,
            '127.0.0.1'
        );

        // 6. Multi-Channel Notification for Daily Summary
        $shouldAlert = $this->option('alert') || $criticalCount > 0 || !empty($integrityIssues);

        if ($shouldAlert) {
            $this->info('Dispatching Security Digest via WhatsApp Cloud API & Email...');
            
            // Format WhatsApp message
            $lines = [
                "🛡️ *Tukio360 Daily Security Audit Report*",
                "📅 Date: " . $now->toFormattedDateString(),
                "━━━━━━━━━━━━━━━━━━━━━",
                "• Attacks Intercepted (24h): {$totalIncidents}",
                "• Critical Threats: {$criticalCount}",
                "• Active Blacklisted IPs: {$activeBans}",
                "• Integrity Status: " . (empty($integrityIssues) ? "✅ NOMINAL" : "⚠️ ISSUES DETECTED"),
            ];

            if (!empty($integrityIssues)) {
                $lines[] = "━━━━━━━━━━━━━━━━━━━━━";
                $lines[] = "⚠️ *Integrity Alerts:*";
                foreach (array_slice($integrityIssues, 0, 3) as $issue) {
                    $lines[] = "• " . $issue;
                }
            }

            $lines[] = "━━━━━━━━━━━━━━━━━━━━━";
            $lines[] = "🔗 Review SOC Dashboard: " . url('/admin/security');

            $whatsappBody = implode("\n", $lines);

            $adminPhone = SecuritySetting::get('alert_whatsapp_recipient', config('services.whatsapp.alert_phone', ''));
            $whatsappEnabled = SecuritySetting::get('alert_whatsapp_enabled', true);

            if ($whatsappEnabled && !empty($adminPhone)) {
                try {
                    $cloudApiService = app(\App\Services\WhatsApp\WhatsAppCloudApiService::class);
                    $cloudApiService->sendTextMessage($adminPhone, $whatsappBody);
                    $this->info('WhatsApp security digest dispatched successfully.');
                } catch (\Throwable $e) {
                    $this->warn('WhatsApp digest delivery note: ' . $e->getMessage());
                }
            }
        }

        $this->info('Daily security audit completed successfully.');
        return self::SUCCESS;
    }
}

