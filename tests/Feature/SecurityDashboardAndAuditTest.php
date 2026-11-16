<?php

namespace Tests\Feature;

use App\Models\SecurityAuditLog;
use App\Models\SecurityBlockedIp;
use App\Models\SecurityIncident;
use App\Models\SecuritySetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SecurityDashboardAndAuditTest extends TestCase
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

    public function test_admin_can_view_soc_dashboard(): void
    {
        SecurityIncident::create([
            'source_ip' => '198.51.100.1',
            'request_url' => '/login',
            'request_method' => 'POST',
            'threat_type' => 'sqli',
            'severity' => 'critical',
            'status' => 'active',
            'detected_rule' => 'UNION SELECT',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.security.index'));

        $response->assertStatus(200);
        $response->assertSee('Security Operations Center (SOC)');
        $response->assertSee('198.51.100.1');
        $response->assertSee('UNION SELECT');
    }

    public function test_unauthorized_user_cannot_access_soc_dashboard(): void
    {
        $regularUser = User::factory()->create([
            'role' => 'event_admin',
        ]);

        $response = $this->actingAs($regularUser)
            ->get(route('admin.security.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_manually_ban_ip(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.security.block-ip'), [
                'ip_address' => '203.0.113.50',
                'reason' => 'Targeted credential stuffing attempt',
                'duration_minutes' => 60,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('security_blocked_ips', [
            'ip_address' => '203.0.113.50',
            'reason' => 'Targeted credential stuffing attempt',
        ]);

        $this->assertDatabaseHas('security_audit_logs', [
            'action' => 'ip_blocked',
        ]);
    }

    public function test_admin_can_lift_ip_ban(): void
    {
        SecurityBlockedIp::create([
            'ip_address' => '203.0.113.99',
            'reason' => 'Manual ban test',
            'threat_count' => 1,
            'blocked_at' => now(),
            'expires_at' => now()->addMinutes(30),
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.security.unblock-ip', '203.0.113.99'));

        $response->assertRedirect();
        $this->assertDatabaseMissing('security_blocked_ips', [
            'ip_address' => '203.0.113.99',
        ]);

        $this->assertDatabaseHas('security_audit_logs', [
            'action' => 'ip_unblocked',
        ]);
    }

    public function test_admin_can_resolve_and_mark_false_positive(): void
    {
        $incident = SecurityIncident::create([
            'source_ip' => '198.51.100.22',
            'request_url' => '/search',
            'request_method' => 'GET',
            'threat_type' => 'xss',
            'severity' => 'medium',
            'status' => 'active',
            'detected_rule' => '<script>',
        ]);

        $this->actingAs($this->adminUser)
            ->post(route('admin.security.resolve', $incident));

        $this->assertEquals('resolved', $incident->fresh()->status);

        $this->actingAs($this->adminUser)
            ->post(route('admin.security.false-positive', $incident));

        $this->assertEquals('false_positive', $incident->fresh()->status);
    }

    public function test_admin_can_update_defense_settings(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.security.settings'), [
                'threat_detection_enabled' => '1',
                'auto_block_enabled' => '1',
                'auto_block_duration_minutes' => 45,
                'auto_block_threshold_strikes' => 5,
                'whitelisted_ips' => '127.0.0.1, 10.0.0.1',
                'alert_whatsapp_enabled' => '1',
                'alert_whatsapp_recipient' => '255711223344',
                'alert_email_enabled' => '1',
                'alert_email_recipient' => 'security@tukio360.co.tz',
            ]);

        $response->assertRedirect();
        $this->assertEquals(45, SecuritySetting::get('auto_block_duration_minutes'));
        $this->assertEquals(5, SecuritySetting::get('auto_block_threshold_strikes'));
        $this->assertContains('10.0.0.1', SecuritySetting::get('whitelisted_ips'));
    }

    public function test_daily_security_audit_command(): void
    {
        SecurityIncident::create([
            'source_ip' => '198.51.100.33',
            'request_url' => '/admin',
            'request_method' => 'GET',
            'threat_type' => 'scanner_probe',
            'severity' => 'low',
            'status' => 'active',
            'detected_rule' => 'Recon probe',
        ]);

        $exitCode = Artisan::call('tukio:security-audit');

        $this->assertEquals(0, $exitCode);
        $this->assertDatabaseHas('security_audit_logs', [
            'action' => 'daily_security_audit',
        ]);
    }
}
