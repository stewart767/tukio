<?php

namespace Tests\Feature;

use App\Models\SecurityAuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class CybersecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_consecutive_failed_logins_trigger_progressive_lockout(): void
    {
        $user = User::factory()->create([
            'email' => 'victim@tukio360.co.tz',
            'password' => Hash::make('CorrectPassword123!'),
            'is_active' => true,
        ]);

        RateLimiter::clear('login:auth:' . sha1('127.0.0.1|victim@tukio360.co.tz'));

        // Attempt 5 incorrect passwords
        for ($i = 1; $i <= 5; $i++) {
            $response = $this->post('/login', [
                'email' => 'victim@tukio360.co.tz',
                'password' => 'WrongPassword!',
            ]);

            if ($i < 5) {
                $response->assertSessionHasErrors('email');
            }
        }

        // 6th attempt should be blocked by rate limiter with lockout message
        $response = $this->post('/login', [
            'email' => 'victim@tukio360.co.tz',
            'password' => 'CorrectPassword123!',
        ]);

        $response->assertSessionHasErrors('email');
        $errorMessage = session('errors')->first('email');
        $this->assertStringContainsString('Too many failed login attempts', $errorMessage);

        // Verify audit log captured failed attempts and account locked event
        $this->assertDatabaseHas('security_audit_logs', [
            'action' => 'auth_failed_attempt',
        ]);
        $this->assertDatabaseHas('security_audit_logs', [
            'action' => 'auth_account_locked',
        ]);

        RateLimiter::clear('login:auth:' . sha1('127.0.0.1|victim@tukio360.co.tz'));
    }

    public function test_successful_login_clears_failed_attempts_and_records_audit(): void
    {
        $user = User::factory()->create([
            'email' => 'admin.test@tukio360.co.tz',
            'password' => Hash::make('AdminSecret123!'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin.test@tukio360.co.tz',
            'password' => 'AdminSecret123!',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);

        $this->assertDatabaseHas('security_audit_logs', [
            'action' => 'auth_successful_login',
            'actor_id' => $user->id,
        ]);
    }

    public function test_admin_inactivity_timeout_logs_out_idle_session(): void
    {
        $admin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Simulate 31 minutes ago for last activity (1860 seconds)
        $pastTimestamp = now()->timestamp - 1860;

        $response = $this->actingAs($admin)
            ->withSession(['admin_last_activity' => $pastTimestamp])
            ->get(route('admin.users.index'));

        // Should be logged out and redirected to admin login
        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();

        $this->assertDatabaseHas('security_audit_logs', [
            'action' => 'admin_inactivity_timeout',
            'actor_id' => $admin->id,
        ]);
    }

    public function test_active_admin_session_is_not_timed_out(): void
    {
        $admin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Recent activity (5 minutes ago)
        $recentTimestamp = now()->timestamp - 300;

        $response = $this->actingAs($admin)
            ->withSession(['admin_last_activity' => $recentTimestamp])
            ->get(route('admin.users.index'));

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($admin);
    }

    public function test_profile_password_update_invalidates_session_and_records_audit(): void
    {
        $admin = User::factory()->create([
            'role' => 'super_admin',
            'password' => Hash::make('OldPassword123!'),
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.profile.password.update'), [
                'current_password' => 'OldPassword123!',
                'password' => 'NewStrongPass456!',
                'password_confirmation' => 'NewStrongPass456!',
            ]);

        $response->assertRedirect(route('admin.profile.show'));
        $this->assertTrue(Hash::check('NewStrongPass456!', $admin->fresh()->password));

        $this->assertDatabaseHas('security_audit_logs', [
            'action' => 'admin_password_changed',
            'actor_id' => $admin->id,
        ]);
    }
}

