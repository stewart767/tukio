<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityAuditLog;
use App\Models\SecurityBlockedIp;
use App\Models\SecurityIncident;
use App\Models\SecurityIncidentNote;
use App\Models\SecuritySetting;
use App\Models\SecurityVulnerability;
use App\Models\User;
use App\Services\Security\IntrusionPreventionService;
use App\Services\Security\SecurityReportService;
use App\Services\Security\ServerHealthService;
use App\Services\Security\ThreatDetectionService;
use App\Services\Security\VulnerabilityService;
use App\Services\Security\WazuhService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecurityDashboardController extends Controller
{
    public function __construct(
        protected IntrusionPreventionService $ipsService,
        protected ThreatDetectionService $threatDetector,
        protected WazuhService $wazuhService,
        protected ServerHealthService $healthService,
        protected VulnerabilityService $vulnService,
        protected SecurityReportService $reportService
    ) {}

    /**
     * Display the comprehensive Security Operations Center (SOC) dashboard.
     */
    public function index(Request $request): View
    {
        // 1. Core Threat & Telemetry Metrics
        $stats = [
            'total_incidents' => SecurityIncident::count(),
            'critical_threats' => SecurityIncident::where('severity', 'critical')->count(),
            'high_threats' => SecurityIncident::where('severity', 'high')->count(),
            'active_threats' => SecurityIncident::whereIn('status', ['new', 'investigating', 'active'])->count(),
            'blocked_ips_count' => SecurityBlockedIp::count(),
            'attacks_today' => SecurityIncident::whereDate('created_at', today())->count(),
            'open_vulnerabilities' => SecurityVulnerability::where('status', 'open')->count(),
            'failed_logins_today' => SecurityAuditLog::where('action', 'auth_failed_attempt')->whereDate('created_at', today())->count(),
        ];

        // 2. Incident Stream Filter Query
        $incidentQuery = SecurityIncident::with(['assignedAdmin', 'notes.user'])->latest();

        if ($severityFilter = $request->get('severity')) {
            $incidentQuery->where('severity', $severityFilter);
        }

        if ($threatTypeFilter = $request->get('threat_type')) {
            $incidentQuery->where('threat_type', $threatTypeFilter);
        }

        if ($statusFilter = $request->get('status')) {
            $incidentQuery->where('status', $statusFilter);
        }

        if ($ipSearch = $request->get('ip')) {
            $incidentQuery->where('source_ip', 'like', "%{$ipSearch}%");
        }

        $incidents = $incidentQuery->paginate(15, ['*'], 'incidents_page')->withQueryString();

        // 3. Blocked IPs with repeat offender attack frequencies
        $blockedIps = SecurityBlockedIp::with('incident')->latest()->get();

        // 4. Audit Trail
        $auditLogs = SecurityAuditLog::latest()->take(25)->get();

        // 5. Threat Breakdown by Category
        $threatCategories = SecurityIncident::selectRaw('threat_type, count(*) as count')
            ->groupBy('threat_type')
            ->pluck('count', 'threat_type')
            ->toArray();

        // 6. Security Configuration Settings
        $settings = [
            'threat_detection_enabled' => SecuritySetting::get('threat_detection_enabled', true),
            'auto_block_enabled' => SecuritySetting::get('auto_block_enabled', true),
            'auto_block_duration_minutes' => (int) SecuritySetting::get('auto_block_duration_minutes', 30),
            'auto_block_threshold_strikes' => (int) SecuritySetting::get('auto_block_threshold_strikes', 3),
            'whitelisted_ips' => implode(', ', (array) SecuritySetting::get('whitelisted_ips', ['127.0.0.1', '::1'])),
            'alert_whatsapp_enabled' => SecuritySetting::get('alert_whatsapp_enabled', true),
            'alert_whatsapp_recipient' => SecuritySetting::get('alert_whatsapp_recipient', config('services.whatsapp.alert_phone', '')),
            'alert_email_enabled' => SecuritySetting::get('alert_email_enabled', true),
            'alert_email_recipient' => SecuritySetting::get('alert_email_recipient', config('mail.from.address', '')),
            'wazuh_host' => config('services.wazuh.host', '127.0.0.1'),
            'wazuh_port' => config('services.wazuh.port', 55000),
            'wazuh_enabled' => config('services.wazuh.enabled', false),
        ];

        // 7. Wazuh SIEM & FIM Telemetry
        $wazuhStatus = $this->wazuhService->getStatus();
        $wazuhAgents = $this->wazuhService->getAgents(10);
        $wazuhSyscheck = $this->wazuhService->getSyscheckEvents(10);
        $wazuhSca = $this->wazuhService->getScaFindings(10);

        // 8. Real-time Server Health & Infrastructure Telemetry
        $serverHealth = $this->healthService->getTelemetry();

        // 9. Vulnerabilities Register (CVEs & Dependencies)
        $vulnerabilities = SecurityVulnerability::latest()->paginate(15, ['*'], 'vulns_page');

        // 10. Administrator list for incident assignment
        $administrators = User::whereIn('role', ['super_admin', 'event_admin'])->get();

        return view('admin.security.index', compact(
            'stats',
            'incidents',
            'blockedIps',
            'auditLogs',
            'threatCategories',
            'settings',
            'wazuhStatus',
            'wazuhAgents',
            'wazuhSyscheck',
            'wazuhSca',
            'serverHealth',
            'vulnerabilities',
            'administrators'
        ));
    }

    /**
     * Manually ban an IP address.
     */
    public function blockIp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ip_address' => 'required|ip',
            'reason' => 'required|string|max:255',
            'duration_minutes' => 'nullable|integer|min:5|max:43200',
            'is_permanent' => 'nullable|boolean',
        ]);

        $duration = (int) ($validated['duration_minutes'] ?? 30);
        $isPermanent = $request->boolean('is_permanent');

        $this->ipsService->blockIp(
            $validated['ip_address'],
            $validated['reason'],
            $duration,
            $isPermanent,
            null,
            (string) auth()->id()
        );

        return redirect()->route('admin.security.index')->with('success', "Anwani ya IP {$validated['ip_address']} imezuiwa kikamilifu.");
    }

    /**
     * Unban an IP address.
     */
    public function unblockIp(string $ip): RedirectResponse
    {
        $this->ipsService->unblockIp($ip, auth()->id());

        return redirect()->route('admin.security.index')->with('success', "Anwani ya IP {$ip} imeondolewa kwenye kizuizi.");
    }

    /**
     * Add investigation note to an incident.
     */
    public function addIncidentNote(Request $request, SecurityIncident $incident): RedirectResponse
    {
        $validated = $request->validate([
            'note' => 'required|string|max:2000',
            'action_taken' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:new,investigating,contained,fix_in_progress,resolved,closed,false_positive',
        ]);

        SecurityIncidentNote::create([
            'incident_id' => $incident->id,
            'user_id' => auth()->id(),
            'note' => $validated['note'],
            'action_taken' => $validated['action_taken'] ?? null,
        ]);

        if (!empty($validated['status'])) {
            $incident->update(['status' => $validated['status']]);
        }

        SecurityAuditLog::record(
            'incident_investigation_note_added',
            [
                'incident_uuid' => $incident->incident_uuid,
                'action_taken' => $validated['action_taken'] ?? null,
                'new_status' => $validated['status'] ?? $incident->status,
            ],
            auth()->id()
        );

        return redirect()->back()->with('success', 'Uchunguzi wa tukio umehifadhiwa kikamilifu.');
    }

    /**
     * Assign incident to an administrator.
     */
    public function assignIncident(Request $request, SecurityIncident $incident): RedirectResponse
    {
        $validated = $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $incident->update([
            'assigned_to' => $validated['assigned_to'] ?? null,
            'status' => $incident->status === 'new' ? 'investigating' : $incident->status,
        ]);

        SecurityAuditLog::record(
            'incident_assigned',
            [
                'incident_uuid' => $incident->incident_uuid,
                'assigned_to_user_id' => $validated['assigned_to'] ?? null,
            ],
            auth()->id()
        );

        return redirect()->back()->with('success', 'Mchambuzi wa usalama amepangiwa tukio hili.');
    }

    /**
     * Update incident lifecycle status.
     */
    public function updateIncidentStatus(Request $request, SecurityIncident $incident): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:new,investigating,contained,fix_in_progress,resolved,closed,false_positive',
            'remediation_notes' => 'nullable|string|max:2000',
        ]);

        $updates = [
            'status' => $validated['status'],
            'remediation_notes' => $validated['remediation_notes'] ?? $incident->remediation_notes,
        ];

        if (in_array($validated['status'], ['resolved', 'closed'], true)) {
            $updates['resolved_by'] = auth()->id();
            $updates['resolved_at'] = now();
            if ($validated['status'] === 'closed') {
                $updates['closed_at'] = now();
            }
        }

        $incident->update($updates);

        if ($validated['status'] === 'false_positive' && $incident->containment_action === 'ip_temporary_ban') {
            $this->ipsService->unblockIp($incident->source_ip, auth()->id());
        }

        SecurityAuditLog::record(
            'incident_status_transition',
            [
                'incident_uuid' => $incident->incident_uuid,
                'new_status' => $validated['status'],
            ],
            auth()->id()
        );

        return redirect()->back()->with('success', "Hali ya tukio imesasishwa kuwa: {$validated['status']}.");
    }

    /**
     * Mark an incident as resolved (legacy shortcut).
     */
    public function resolveIncident(Request $request, SecurityIncident $incident): RedirectResponse
    {
        return $this->updateIncidentStatus(new Request([
            'status' => 'resolved',
            'remediation_notes' => $request->input('notes', 'Resolved by administrator.'),
        ]), $incident);
    }

    /**
     * Mark an incident as false positive (legacy shortcut).
     */
    public function markFalsePositive(Request $request, SecurityIncident $incident): RedirectResponse
    {
        return $this->updateIncidentStatus(new Request([
            'status' => 'false_positive',
            'remediation_notes' => $request->input('notes', 'Marked as false positive by administrator.'),
        ]), $incident);
    }

    /**
     * Run Composer / Wazuh vulnerability scan.
     */
    public function scanVulnerabilities(): RedirectResponse
    {
        $result = $this->vulnService->runComposerAudit();

        return redirect()->route('admin.security.index', ['tab' => 'vulns'])
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    /**
     * Update vulnerability status.
     */
    public function updateVulnerability(Request $request, SecurityVulnerability $vulnerability): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:open,investigating,mitigated,patched,false_positive',
            'notes' => 'nullable|string|max:1000',
        ]);

        $this->vulnService->updateStatus($vulnerability, $validated['status'], $validated['notes'] ?? null, auth()->id());

        return redirect()->back()->with('success', 'Hali ya hatari ya usalama (CVE) imesasishwa.');
    }

    /**
     * Export Security Incidents to CSV.
     */
    public function exportIncidents(): StreamedResponse
    {
        return $this->reportService->exportIncidentsCsv();
    }

    /**
     * Export Blocked IPs to CSV.
     */
    public function exportBlockedIps(): StreamedResponse
    {
        return $this->reportService->exportBlockedIpsCsv();
    }

    /**
     * Export Audit Logs to CSV.
     */
    public function exportAuditLogs(): StreamedResponse
    {
        return $this->reportService->exportAuditLogsCsv();
    }

    /**
     * Update security settings and thresholds.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'threat_detection_enabled' => 'nullable|boolean',
            'auto_block_enabled' => 'nullable|boolean',
            'auto_block_duration_minutes' => 'required|integer|min:5|max:1440',
            'auto_block_threshold_strikes' => 'required|integer|min:1|max:20',
            'whitelisted_ips' => 'nullable|string',
            'alert_whatsapp_enabled' => 'nullable|boolean',
            'alert_whatsapp_recipient' => 'nullable|string',
            'alert_email_enabled' => 'nullable|boolean',
            'alert_email_recipient' => 'nullable|email',
        ]);

        SecuritySetting::set('threat_detection_enabled', $request->boolean('threat_detection_enabled'), 'boolean');
        SecuritySetting::set('auto_block_enabled', $request->boolean('auto_block_enabled'), 'boolean');
        SecuritySetting::set('auto_block_duration_minutes', (int) $validated['auto_block_duration_minutes'], 'integer');
        SecuritySetting::set('auto_block_threshold_strikes', (int) $validated['auto_block_threshold_strikes'], 'integer');

        $whitelist = array_filter(array_map('trim', explode(',', (string) ($validated['whitelisted_ips'] ?? ''))));
        SecuritySetting::set('whitelisted_ips', array_values($whitelist), 'json');

        SecuritySetting::set('alert_whatsapp_enabled', $request->boolean('alert_whatsapp_enabled'), 'boolean');
        SecuritySetting::set('alert_whatsapp_recipient', trim((string) ($validated['alert_whatsapp_recipient'] ?? '')), 'string');
        SecuritySetting::set('alert_email_enabled', $request->boolean('alert_email_enabled'), 'boolean');
        SecuritySetting::set('alert_email_recipient', trim((string) ($validated['alert_email_recipient'] ?? '')), 'string');

        SecurityAuditLog::record(
            'security_settings_updated',
            $validated,
            auth()->id()
        );

        return redirect()->route('admin.security.index')->with('success', 'Mipangilio ya ulinzi na arifa imesasishwa kikamilifu.');
    }
}
