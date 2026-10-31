<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventWebsite;
use App\Models\EventRsvp;
use App\Models\Guest;
use App\Models\Host;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class EventWebsiteTest extends TestCase
{
    use DatabaseMigrations;

    protected Host $host;
    protected Event $event;
    protected EventWebsite $website;

    protected function setUp(): void
    {
        parent::setUp();

        $this->host = Host::create([
            'full_name' => 'Alice Mrema',
            'email' => 'alice@example.com',
            'phone' => '255712345678',
            'password' => Hash::make('secret123'),
            'event_type' => 'birthday',
            'number_of_guests' => 150,
            'total_cost' => 300000,
            'is_active' => true,
            'event_configured' => true,
        ]);

        $this->event = Event::create([
            'host_id' => $this->host->id,
            'title' => "Alice's 50th Birthday Celebrations",
            'description' => 'Fifty years of grace, resilience, love and laughter.',
            'category' => 'birthday',
            'date' => now()->addDays(30)->format('Y-m-d'),
            'time' => '18:00',
            'venue' => 'Hyatt Regency Dar es Salaam',
            'venue_address' => 'Kivukoni Front, Dar es Salaam',
            'status' => 'active',
            'guest_count' => 150,
            'total_cost' => 300000,
        ]);

        $this->website = $this->event->ensureWebsite();
    }

    public function test_event_website_initialization_with_13_default_sections(): void
    {
        $this->assertNotNull($this->website);
        $this->assertEquals("Alice's 50th Birthday Celebrations", $this->website->title);
        $this->assertEquals('alices-50th-birthday-celebrations', $this->website->slug);

        // Verify all 13 standard sections exist
        $sections = $this->website->sections;
        $this->assertCount(13, $sections);

        $keys = $sections->pluck('section_key')->toArray();
        $this->assertContains('hero', $keys);
        $this->assertContains('countdown', $keys);
        $this->assertContains('about', $keys);
        $this->assertContains('details', $keys);
        $this->assertContains('schedule', $keys);
        $this->assertContains('venue', $keys);
        $this->assertContains('dress_code', $keys);
        $this->assertContains('travel', $keys);
        $this->assertContains('accommodation', $keys);
        $this->assertContains('faq', $keys);
        $this->assertContains('rsvp', $keys);
        $this->assertContains('gallery', $keys);
        $this->assertContains('contact', $keys);
    }

    public function test_public_event_website_displays_successfully_for_published_events(): void
    {
        $this->website->update(['is_published' => true]);

        $response = $this->get('/e/' . $this->website->slug);

        $response->assertStatus(200);
        $response->assertSee($this->website->title);
        $response->assertSee('Countdown to the Celebration');
        $response->assertSee('Venue & Location');
        $response->assertSee('RSVP & Registration');
    }

    public function test_public_event_website_blocks_draft_events_for_unauthenticated_guests(): void
    {
        $this->website->update(['is_published' => false]);

        // Public visitor receives 404
        $response = $this->get('/e/' . $this->website->slug);
        $response->assertStatus(404);

        // Host in preview mode can view
        $this->actingAs($this->host, 'host');
        $previewResponse = $this->get('/e/' . $this->website->slug . '/preview');
        $previewResponse->assertStatus(200);
        $previewResponse->assertSee('PREVIEW MODE');
    }

    public function test_guest_rsvp_submission_creates_rsvp_and_syncs_guest_record(): void
    {
        $this->website->update(['is_published' => true, 'max_plus_ones' => 2]);

        $payload = [
            'full_name' => 'Sarah Joseph',
            'phone' => '0714999888',
            'email' => 'sarah@example.com',
            'attendance_status' => 'attending',
            'plus_one_count' => 1,
            'dietary_requirements' => 'Halal',
            'special_requests' => 'Looking forward to the celebration!',
        ];

        $response = $this->postJson('/e/' . $this->website->slug . '/rsvp', $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'rsvp' => [
                'full_name' => 'Sarah Joseph',
                'attendance_status' => 'attending',
                'plus_one_count' => 1,
                'total_party_size' => 2,
            ],
        ]);

        // Verify EventRsvp record
        $this->assertDatabaseHas('event_rsvps', [
            'event_website_id' => $this->website->id,
            'full_name' => 'Sarah Joseph',
            'phone' => '255714999888',
            'attendance_status' => 'attending',
            'plus_one_count' => 1,
        ]);

        // Verify synced Guest record exists in Tukio360
        $this->assertDatabaseHas('guests', [
            'event_id' => $this->event->id,
            'full_name' => 'Sarah Joseph',
            'phone' => '255714999888',
            'guest_type' => 'double',
        ]);
    }

    public function test_guest_rsvp_duplicate_phone_updates_existing_record_when_allowed(): void
    {
        $this->website->update(['is_published' => true, 'allow_guest_updates' => true]);

        // First submission: Attending
        $this->postJson('/e/' . $this->website->slug . '/rsvp', [
            'full_name' => 'David Kimaro',
            'phone' => '0755112233',
            'attendance_status' => 'attending',
        ]);

        $this->assertDatabaseHas('event_rsvps', [
            'phone' => '255755112233',
            'attendance_status' => 'attending',
        ]);

        // Second submission: Changed mind to Declined
        $updateResponse = $this->postJson('/e/' . $this->website->slug . '/rsvp', [
            'full_name' => 'David Kimaro',
            'phone' => '0755112233',
            'attendance_status' => 'declined',
        ]);

        $updateResponse->assertStatus(200);
        $this->assertDatabaseHas('event_rsvps', [
            'phone' => '255755112233',
            'attendance_status' => 'declined',
        ]);
        // Only 1 record should exist, not duplicate
        $this->assertEquals(1, EventRsvp::where('phone', '255755112233')->count());
    }

    public function test_rsvp_deadline_enforcement(): void
    {
        $this->website->update([
            'is_published' => true,
            'rsvp_deadline' => now()->subDay(), // Past deadline
        ]);

        $response = $this->postJson('/e/' . $this->website->slug . '/rsvp', [
            'full_name' => 'Late Guest',
            'phone' => '0788111222',
            'attendance_status' => 'attending',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_honeypot_spam_protection(): void
    {
        $this->website->update(['is_published' => true]);

        $response = $this->postJson('/e/' . $this->website->slug . '/rsvp', [
            'full_name' => 'Spam Bot',
            'phone' => '0711000000',
            'attendance_status' => 'attending',
            'spambot_trap' => 'http://spam-site.com',
        ]);

        $response->assertStatus(422);
    }

    public function test_host_can_update_website_settings_and_theme(): void
    {
        $this->actingAs($this->host, 'host');

        $response = $this->post('/host/website/general', [
            'title' => "Alice's Golden 50th Gala",
            'slug' => 'alice-golden-50',
            'category' => 'birthday',
            'theme' => 'luxury_gold',
            'countdown_style' => 'flip',
            'max_plus_ones' => 2,
            'allow_guest_updates' => 1,
            'require_phone' => 1,
            'is_published' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('event_websites', [
            'id' => $this->website->id,
            'title' => "Alice's Golden 50th Gala",
            'slug' => 'alice-golden-50',
            'countdown_style' => 'flip',
            'is_published' => 1,
        ]);
    }

    public function test_host_can_toggle_door_check_in_for_rsvp(): void
    {
        $this->actingAs($this->host, 'host');

        $rsvp = EventRsvp::create([
            'event_website_id' => $this->website->id,
            'event_id' => $this->event->id,
            'full_name' => 'Baraka John',
            'phone' => '255767888999',
            'attendance_status' => 'attending',
            'is_checked_in' => false,
        ]);

        $response = $this->postJson('/host/website/rsvps/' . $rsvp->id . '/checkin');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_checked_in' => true,
        ]);

        $this->assertTrue($rsvp->fresh()->is_checked_in);
    }

    public function test_host_can_export_rsvps_to_csv(): void
    {
        $this->actingAs($this->host, 'host');

        EventRsvp::create([
            'event_website_id' => $this->website->id,
            'event_id' => $this->event->id,
            'full_name' => 'Grace Temba',
            'phone' => '255711222333',
            'attendance_status' => 'attending',
        ]);

        $response = $this->get('/host/website/rsvps/export/excel');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_staff_scanner_can_scan_rsvp_confirmation_code(): void
    {
        // Create staff user
        $staff = User::create([
            'name' => 'Staff Usher',
            'email' => 'staff@tukio360.co.tz',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
        ]);

        // Assign staff to event
        $this->event->staffMembers()->attach($staff->id);

        $rsvp = EventRsvp::create([
            'event_website_id' => $this->website->id,
            'event_id' => $this->event->id,
            'full_name' => 'Mariam Ally',
            'phone' => '255788555444',
            'attendance_status' => 'attending',
            'is_checked_in' => false,
        ]);

        // Create linked Guest
        $guest = Guest::create([
            'event_id' => $this->event->id,
            'full_name' => 'Mariam Ally',
            'phone' => '255788555444',
            'invitation_code' => '54321',
            'status' => 'active',
            'attended' => false,
        ]);
        $rsvp->update(['guest_id' => $guest->id]);

        $this->actingAs($staff);

        $scanResponse = $this->postJson('/staff/scan', [
            'qr_code' => $rsvp->confirmation_code,
        ]);

        $scanResponse->assertStatus(200);
        $scanResponse->assertJson([
            'success' => true,
            'message' => 'Guest checked in successfully!',
        ]);

        $this->assertTrue($guest->fresh()->attended);
        $this->assertTrue($rsvp->fresh()->is_checked_in);
    }
}
