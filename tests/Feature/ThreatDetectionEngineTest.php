<?php

namespace Tests\Feature;

use App\Models\SecurityBlockedIp;
use App\Models\SecurityIncident;
use App\Models\SecuritySetting;
use App\Services\Security\IntrusionPreventionService;
use App\Services\Security\ThreatDetectionService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreatDetectionEngineTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        SecuritySetting::set('threat_detection_enabled', true, 'boolean');
        SecuritySetting::set('auto_block_enabled', true, 'boolean');
        SecuritySetting::set('auto_block_threshold_strikes', 2, 'integer');
        SecuritySetting::set('auto_block_duration_minutes', 30, 'integer');
        SecuritySetting::set('whitelisted_ips', ['10.0.0.99'], 'json');
        SecuritySetting::set('alert_whatsapp_enabled', false, 'boolean');
        SecuritySetting::set('alert_email_enabled', false, 'boolean');
    }

    /**
     * Test 1: Clean requests pass through without triggering detection.
     */
    public function test_clean_request_passes_without_incident(): void
    {
        $response = $this->get('/templates');

        $response->assertStatus(200);
        $this->assertEquals(0, SecurityIncident::count());
    }

    /**
     * Test 2: SQL Injection attack payload is detected and blocked.
     */
    public function test_sqli_payload_is_blocked_and_logged(): void
    {
        $response = $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.100'])
            ->get('/templates?search=' . urlencode("' UNION SELECT 1,2,3,4,5 FROM information_schema.tables--"));

        $response->assertStatus(403);

        $this->assertDatabaseHas('security_incidents', [
            'threat_type' => 'sqli',
            'severity' => 'critical',
            'source_ip' => '192.168.1.100',
        ]);
    }

    /**
     * Test 3: XSS attack payload is detected and blocked.
     */
    public function test_xss_payload_is_blocked_and_logged(): void
    {
        $response = $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.101'])
            ->get('/templates?q=' . urlencode('<script>alert("XSS")</script>'));

        $response->assertStatus(403);

        $this->assertDatabaseHas('security_incidents', [
            'threat_type' => 'xss',
            'severity' => 'high',
            'source_ip' => '192.168.1.101',
        ]);
    }

    /**
     * Test 4: Path traversal payload is detected and blocked.
     */
    public function test_path_traversal_payload_is_blocked(): void
    {
        $response = $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.102'])
            ->get('/templates?file=' . urlencode('../../etc/passwd'));

        $response->assertStatus(403);

        $this->assertDatabaseHas('security_incidents', [
            'threat_type' => 'path_traversal',
            'severity' => 'critical',
            'source_ip' => '192.168.1.102',
        ]);
    }

    /**
     * Test 5: Vulnerability scanner User-Agent is detected and blocked.
     */
    public function test_malicious_scanner_agent_is_blocked(): void
    {
        $response = $this->withHeaders(['User-Agent' => 'sqlmap/1.6.4#stable (https://sqlmap.org)'])
            ->withServerVariables(['REMOTE_ADDR' => '192.168.1.103'])
            ->get('/templates');

        $response->assertStatus(403);

        $this->assertDatabaseHas('security_incidents', [
            'threat_type' => 'scanner_probe',
            'source_ip' => '192.168.1.103',
        ]);
    }

    /**
     * Test 6: Critical / repeated attacks automatically trigger IP ban in IPS.
     */
    public function test_critical_attack_triggers_automated_ip_ban(): void
    {
        $attackerIp = '198.51.100.55';

        // 1. Send critical SQLi attack
        $this->withServerVariables(['REMOTE_ADDR' => $attackerIp])
            ->get('/templates?filter=' . urlencode("1' UNION SELECT user,password FROM users--"));

        // Verify IP is now in blocked IPs table
        $this->assertDatabaseHas('security_blocked_ips', [
            'ip_address' => $attackerIp,
        ]);

        $blockedRecord = SecurityBlockedIp::where('ip_address', $attackerIp)->first();
        $this->assertNotNull($blockedRecord);
        $this->assertTrue($blockedRecord->isCurrentlyBlocked());

        // 2. Subsequent request from that IP is blocked immediately at firewall level
        $subsequentResponse = $this->withServerVariables(['REMOTE_ADDR' => $attackerIp])
            ->get('/templates');

        $subsequentResponse->assertStatus(403);
        $subsequentResponse->assertSee('Ufikiaji Umezuiwa');
    }

    /**
     * Test 7: Whitelisted IP is never locked out.
     */
    public function test_whitelisted_ip_is_exempt(): void
    {
        $whitelistedIp = '10.0.0.99';

        $detector = app(ThreatDetectionService::class);
        $this->assertTrue($detector->isWhitelisted($whitelistedIp));

        $ipsService = app(IntrusionPreventionService::class);
        $this->assertFalse($ipsService->isIpBlocked($whitelistedIp));
    }

    /**
     * Test 8: Administrator can unblock a banned IP.
     */
    public function test_admin_can_unblock_ip(): void
    {
        $ipsService = app(IntrusionPreventionService::class);
        $bannedIp = '203.0.113.88';

        $ipsService->blockIp($bannedIp, 'Manual security quarantine', 60);
        $this->assertTrue($ipsService->isIpBlocked($bannedIp));

        $ipsService->unblockIp($bannedIp, 1);
        $this->assertFalse($ipsService->isIpBlocked($bannedIp));
    }
}

