<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityAuditLog;
use App\Models\SecurityBlockedIp;
use App\Models\SecurityIncident;
use App\Models\SecuritySetting;
use App\Services\Security\IntrusionPreventionService;
use App\Services\Security\ThreatDetectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SecurityDashboardController extends Controller
{
    public function __construct(
        protected IntrusionPreventionService $ipsService,
        protected ThreatDetectionService $threatDetector
    ) {}

    /**
     * Display the Security Operations Center (SOC) dashboard.
     */
    public function index(Request $request): View
    {
        // 1. Core Threat Metrics
        $stats = [
            'total_incidents' => SecurityIncident::count(),
            'critical_threats' => SecurityIncident::where('severity', 'critical')->count(),
            'high_threats' => SecurityIncident::where('severity', 'high')->count(),
            'active_threats' => SecurityIncident::where('status', 'active')->count(),
            'blocked_ips_count' => SecurityBlockedIp::count(),
            'attacks_today' => SecurityIncident::whereDate('created_at', today())->count(),
        ];

        // 2. Incident Filter Query
        $incidentQuery = SecurityIncident::latest();

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

        // 3. Blocked IPs list
        $blockedIps = SecurityBlockedIp::with('incident')->latest()->get();

        // 4. Audit Trail
        $auditLogs = SecurityAuditLog::latest()->take(20)->get();

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
        ];

        return view('admin.security.index', compact(
            'stats',
            'incidents',
            'blockedIps',
            'auditLogs',
            'threatCategories',
            'settings'
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
     * Mark an incident as resolved.
     */
    public function resolveIncident(Request $request, SecurityIncident $incident): RedirectResponse
    {
        $incident->update([
            'status' => 'resolved',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
            'investigation_notes' => $request->input('notes', 'Resolved by administrator.'),
        ]);

        SecurityAuditLog::create([
            'actor_type' => 'user',
            'actor_id' => auth()->id(),
            'action' => 'incident_resolved',
            'resource_type' => 'SecurityIncident',
            'resource_id' => (string) $incident->id,
            'ip_address' => request()->ip(),
            'details' => ['incident_uuid' => $incident->incident_uuid],
        ]);

        return redirect()->route('admin.security.index')->with('success', "Tukio {$incident->incident_uuid} limetiwa alama ya kutatuliwa (Resolved).");
    }

    /**
     * Mark an incident as false positive.
     */
    public function markFalsePositive(Request $request, SecurityIncident $incident): RedirectResponse
    {
        $incident->update([
            'status' => 'false_positive',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
            'investigation_notes' => $request->input('notes', 'Marked as false positive by administrator.'),
        ]);

        // If IP was blocked due to this incident, unblock it
        if ($incident->containment_action === 'ip_temporary_ban') {
            $this->ipsService->unblockIp($incident->source_ip, auth()->id());
        }

        SecurityAuditLog::create([
            'actor_type' => 'user',
            'actor_id' => auth()->id(),
            'action' => 'incident_false_positive',
            'resource_type' => 'SecurityIncident',
            'resource_id' => (string) $incident->id,
            'ip_address' => request()->ip(),
            'details' => ['incident_uuid' => $incident->incident_uuid],
        ]);

        return redirect()->route('admin.security.index')->with('success', "Tukio {$incident->incident_uuid} limetiwa alama ya False Positive.");
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

        // Parse whitelisted IPs
        $whitelist = array_filter(array_map('trim', explode(',', (string) ($validated['whitelisted_ips'] ?? ''))));
        SecuritySetting::set('whitelisted_ips', array_values($whitelist), 'json');

        SecuritySetting::set('alert_whatsapp_enabled', $request->boolean('alert_whatsapp_enabled'), 'boolean');
        SecuritySetting::set('alert_whatsapp_recipient', trim((string) ($validated['alert_whatsapp_recipient'] ?? '')), 'string');
        SecuritySetting::set('alert_email_enabled', $request->boolean('alert_email_enabled'), 'boolean');
        SecuritySetting::set('alert_email_recipient', trim((string) ($validated['alert_email_recipient'] ?? '')), 'string');

        SecurityAuditLog::create([
            'actor_type' => 'user',
            'actor_id' => auth()->id(),
            'action' => 'security_settings_updated',
            'ip_address' => $request->ip(),
            'details' => $validated,
        ]);

        return redirect()->route('admin.security.index')->with('success', 'Mipangilio ya ulinzi na arifa imesasishwa kikamilifu.');
    }
}

