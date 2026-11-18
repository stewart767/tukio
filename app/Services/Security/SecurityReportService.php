<?php

namespace App\Services\Security;

use App\Models\SecurityAuditLog;
use App\Models\SecurityBlockedIp;
use App\Models\SecurityIncident;
use App\Models\SecurityVulnerability;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecurityReportService
{
    /**
     * Export security incidents to CSV.
     */
    public function exportIncidentsCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="tukio360_security_incidents_' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Incident UUID', 'Created At', 'Threat Type', 'Severity', 'Source IP', 'Method', 'URL', 'Rule', 'Status', 'Containment Action']);

            SecurityIncident::latest()->chunk(200, function ($incidents) use ($handle) {
                foreach ($incidents as $inc) {
                    fputcsv($handle, [
                        $inc->incident_uuid,
                        $inc->created_at->toDateTimeString(),
                        $inc->threat_type,
                        $inc->severity,
                        $inc->source_ip,
                        $inc->request_method,
                        $inc->request_url,
                        $inc->detected_rule,
                        $inc->status,
                        $inc->containment_action,
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export blocked IP addresses to CSV.
     */
    public function exportBlockedIpsCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="tukio360_blocked_ips_' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['IP Address', 'Reason', 'Strikes', 'Blocked At', 'Blocked Until', 'Permanent', 'Blocked By']);

            SecurityBlockedIp::latest()->chunk(200, function ($ips) use ($handle) {
                foreach ($ips as $ip) {
                    fputcsv($handle, [
                        $ip->ip_address,
                        $ip->reason,
                        $ip->threat_count,
                        $ip->created_at->toDateTimeString(),
                        $ip->blocked_until ? $ip->blocked_until->toDateTimeString() : 'Never (Permanent)',
                        $ip->is_permanent ? 'Yes' : 'No',
                        $ip->blocked_by,
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export security audit logs to CSV.
     */
    public function exportAuditLogsCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="tukio360_security_audit_logs_' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Timestamp', 'Action', 'Actor Type', 'Actor ID', 'IP Address', 'User Agent', 'Details']);

            SecurityAuditLog::latest()->chunk(200, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $log->created_at->toDateTimeString(),
                        $log->action,
                        $log->actor_type,
                        $log->actor_id,
                        $log->ip_address,
                        $log->user_agent,
                        json_encode($log->details),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}

