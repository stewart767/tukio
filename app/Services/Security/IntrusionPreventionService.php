<?php

namespace App\Services\Security;

use App\Models\SecurityAuditLog;
use App\Models\SecurityBlockedIp;
use App\Models\SecurityIncident;
use App\Models\SecuritySetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class IntrusionPreventionService
{
    public function __construct(
        protected SecurityAlertService $alertService
    ) {}

    /**
     * Check if an IP address is currently blocked by the IPS.
     */
    public function isIpBlocked(string $ip): bool
    {
        // 1. Whitelisted IPs are never blocked
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'], true)) {
            return false;
        }

        $whitelisted = SecuritySetting::get('whitelisted_ips', []);
        if (is_array($whitelisted) && in_array($ip, $whitelisted, true)) {
            return false;
        }

        // 2. High-speed cache check (10-second TTL)
        $cacheKey = "ips:blocked:{$ip}";
        return Cache::remember($cacheKey, 10, function () use ($ip) {
            $record = SecurityBlockedIp::where('ip_address', $ip)->first();
            if (!$record) {
                return false;
            }

            if ($record->is_permanent) {
                return true;
            }

            return $record->blocked_until !== null && $record->blocked_until->isFuture();
        });
    }

    /**
     * Ingest an incident detection, evaluate containment, and trigger alert.
     */
    public function recordAndContain(array $detection): SecurityIncident
    {
        $ip = $detection['source_ip'];
        $severity = $detection['severity'] ?? 'medium';

        // 1. Create the SecurityIncident record
        $incident = SecurityIncident::create($detection);

        // 2. Evaluate auto-containment (IP Blocking)
        $autoBlockEnabled = SecuritySetting::get('auto_block_enabled', true);
        if ($autoBlockEnabled && !in_array($ip, ['127.0.0.1', '::1', 'localhost'], true)) {
            $strikeCount = SecurityIncident::where('source_ip', $ip)
                ->where('created_at', '>=', now()->subMinutes(15))
                ->count();

            $threshold = (int) SecuritySetting::get('auto_block_threshold_strikes', 3);
            $durationMinutes = (int) SecuritySetting::get('auto_block_duration_minutes', 30);

            // Critical threat bans immediately on 1st strike; High severity bans at threshold
            if ($severity === 'critical' || $strikeCount >= $threshold) {
                $this->blockIp(
                    $ip,
                    "Automated IPS containment: {$detection['detected_rule']} (Severity: {$severity})",
                    $durationMinutes,
                    false,
                    $incident->id,
                    'auto_ips'
                );

                $incident->update(['containment_action' => 'ip_temporary_ban']);
            }
        }

        // 3. Dispatch real-time alert (WhatsApp / Email / Dashboard)
        try {
            $this->alertService->dispatchAlert($incident);
        } catch (\Throwable $e) {
            Log::error('[SecurityIPS] Failed to dispatch security alert: ' . $e->getMessage());
        }

        return $incident;
    }

    /**
     * Block an IP address.
     */
    public function blockIp(
        string $ip,
        string $reason,
        int $durationMinutes = 30,
        bool $isPermanent = false,
        ?int $incidentId = null,
        string $blockedBy = 'auto_ips'
    ): SecurityBlockedIp {
        $blockedUntil = $isPermanent ? null : now()->addMinutes($durationMinutes);

        $existing = SecurityBlockedIp::where('ip_address', $ip)->first();
        $threatCount = $existing ? ($existing->threat_count + 1) : 1;

        $record = SecurityBlockedIp::updateOrCreate(
            ['ip_address' => $ip],
            [
                'reason' => $reason,
                'blocked_until' => $blockedUntil,
                'is_permanent' => $isPermanent,
                'incident_id' => $incidentId,
                'blocked_by' => $blockedBy,
                'threat_count' => $threatCount,
            ]
        );

        Cache::put("ips:blocked:{$ip}", true, $isPermanent ? 86400 : ($durationMinutes * 60));

        SecurityAuditLog::create([
            'actor_type' => $blockedBy === 'auto_ips' ? 'system' : 'user',
            'actor_id' => is_numeric($blockedBy) ? (int) $blockedBy : null,
            'action' => 'ip_blocked',
            'resource_type' => 'SecurityBlockedIp',
            'resource_id' => (string) $record->id,
            'ip_address' => $ip,
            'details' => [
                'reason' => $reason,
                'duration_minutes' => $durationMinutes,
                'is_permanent' => $isPermanent,
                'incident_id' => $incidentId,
            ],
        ]);

        Log::warning("[SecurityIPS] IP Address {$ip} has been BLOCKED. Reason: {$reason}");

        return $record;
    }

    /**
     * Unblock an IP address.
     */
    public function unblockIp(string $ip, ?int $adminUserId = null): bool
    {
        $record = SecurityBlockedIp::where('ip_address', $ip)->first();
        if ($record) {
            $record->delete();
        }

        Cache::forget("ips:blocked:{$ip}");

        SecurityAuditLog::create([
            'actor_type' => 'user',
            'actor_id' => $adminUserId,
            'action' => 'ip_unblocked',
            'resource_type' => 'SecurityBlockedIp',
            'resource_id' => $record ? (string) $record->id : null,
            'ip_address' => $ip,
            'details' => ['unblocked_by' => $adminUserId],
        ]);

        Log::info("[SecurityIPS] IP Address {$ip} has been UNBLOCKED by administrator {$adminUserId}");

        return true;
    }
}

