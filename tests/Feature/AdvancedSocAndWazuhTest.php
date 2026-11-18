<?php

namespace Tests\Feature;

use App\Models\SecurityIncident;
use App\Models\SecurityVulnerability;
use App\Models\User;
use App\Services\Security\ServerHealthService;
use App\Services\Security\WazuhService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdvancedSocAndWazuhTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_view_advanced_soc_dashboard(): void
    {
        $incident = SecurityIncident::create([
            'incident_uuid' => (string) \Illuminate\Support\Str::uuid(),
            'source_ip' => '198.51.100.99',
            'request_url' => '/api/v1/users',
            'request_method' => 'GET',
            'threat_type' => 'sqli',
            'severity' => 'critical',
            'status' => 'new',
            'detected_rule' => 'UNION SELECT Extraction',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.security.index'));

        $response->assertStatus(200);
        $response->assertSee('Security Operations Center (SOC)');
        $response->assertSee('Wazuh SIEM');
        $response->assertSee('198.51.100.99');
        $response->assertSee('UNION SELECT Extraction');
    }

    public function test_admin_can_add_investigation_note_and_transition_status(): void
    {
        $incident = SecurityIncident::create([
            'incident_uuid' => (string) \Illuminate\Support\Str::uuid(),
            'source_ip' => '203.0.113.15',
            'request_url' => '/admin/config',
            'request_method' => 'POST',
            'threat_type' => 'scanner_probe',
            'severity' => 'high',
            'status' => 'new',
            'detected_rule' => 'Nikto Scanner Probe',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.security.incident.note', $incident), [
                'note' => 'Identified automated recon probe from known cloud proxy. Quarantining IP.',
                'action_taken' => 'containment_applied',
                'status' => 'contained',
            ]);

        $response->assertRedirect();
        $this->assertEquals('contained', $incident->fresh()->status);
        $this->assertDatabaseHas('security_incident_notes', [
            'incident_id' => $incident->id,
            'note' => 'Identified automated recon probe from known cloud proxy. Quarantining IP.',
        ]);
        $this->assertDatabaseHas('security_audit_logs', [
            'action' => 'incident_investigation_note_added',
        ]);
    }

    public function test_admin_can_assign_incident_to_analyst(): void
    {
        $analyst = User::factory()->create([
            'role' => 'event_admin',
            'is_active' => true,
        ]);

        $incident = SecurityIncident::create([
            'incident_uuid' => (string) \Illuminate\Support\Str::uuid(),
            'source_ip' => '192.0.2.77',
            'request_url' => '/login',
            'request_method' => 'POST',
            'threat_type' => 'brute_force',
            'severity' => 'medium',
            'status' => 'new',
            'detected_rule' => 'Credential Stuffing Pattern',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.security.incident.assign', $incident), [
                'assigned_to' => $analyst->id,
            ]);

        $response->assertRedirect();
        $this->assertEquals($analyst->id, $incident->fresh()->assigned_to);
        $this->assertEquals('investigating', $incident->fresh()->status);
    }

    public function test_wazuh_service_standby_and_diagnostics(): void
    {
        $wazuhService = app(WazuhService::class);
        $status = $wazuhService->getStatus();

        $this->assertIsArray($status);
        $this->assertArrayHasKey('status', $status);
        $this->assertArrayHasKey('connected', $status);
        $this->assertFalse($status['connected']); // Standby until live server configuration
    }

    public function test_server_health_service_telemetry(): void
    {
        $healthService = app(ServerHealthService::class);
        $telemetry = $healthService->getTelemetry();

        $this->assertArrayHasKey('cpu', $telemetry);
        $this->assertArrayHasKey('memory', $telemetry);
        $this->assertArrayHasKey('disk', $telemetry);
        $this->assertArrayHasKey('database', $telemetry);
        $this->assertArrayHasKey('ssl', $telemetry);
        $this->assertArrayHasKey('firewall', $telemetry);

        $this->assertGreaterThan(0, $telemetry['disk']['percentage']);
    }

    public function test_vulnerability_management_and_status_update(): void
    {
        $vuln = SecurityVulnerability::create([
            'cve_id' => 'CVE-2026-99999',
            'title' => 'Sample Dependency Vulnerability',
            'package_name' => 'vendor/sample-package',
            'installed_version' => '1.0.0',
            'severity' => 'high',
            'cvss_score' => 7.8,
            'status' => 'open',
            'source' => 'composer_audit',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.security.vulnerabilities.status', $vuln), [
                'status' => 'patched',
                'notes' => 'Upgraded package to patched release v1.0.1.',
            ]);

        $response->assertRedirect();
        $this->assertEquals('patched', $vuln->fresh()->status);
        $this->assertNotNull($vuln->fresh()->resolved_at);
    }

    public function test_admin_can_export_csv_reports(): void
    {
        SecurityIncident::create([
            'incident_uuid' => (string) \Illuminate\Support\Str::uuid(),
            'source_ip' => '198.51.100.12',
            'request_url' => '/export-test',
            'request_method' => 'GET',
            'threat_type' => 'xss',
            'severity' => 'medium',
            'status' => 'new',
            'detected_rule' => '<script> probe',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.security.export.incidents'));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
        $this->assertStringContainsString('198.51.100.12', $response->streamedContent());
    }
}

