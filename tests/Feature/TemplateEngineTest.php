<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Host;
use App\Models\Invitation;
use App\Models\Template;
use App\Models\User;
use App\Services\Invitations\TemplateEngine;
use App\Services\Invitations\TemplateFieldResolver;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TemplateEngineTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed users and templates into test DB
        $this->seed(\Database\Seeders\UserSeeder::class);
        $this->seed(\Database\Seeders\TemplateEngineSeeder::class);
    }

    public function test_template_engine_discovers_and_syncs_templates(): void
    {
        $engine = app(TemplateEngine::class);
        $discovered = $engine->discoverFileTemplates();

        $this->assertArrayHasKey('luxury-gold', $discovered);
        $this->assertArrayHasKey('modern-black', $discovered);
        $this->assertArrayHasKey('floral-romance', $discovered);
        $this->assertArrayHasKey('african-heritage', $discovered);

        $synced = $engine->syncTemplatesWithDatabase();
        $this->assertGreaterThanOrEqual(4, $synced);

        $luxuryTemplate = Template::where('slug', 'luxury-gold')->first();
        $this->assertNotNull($luxuryTemplate);
        $this->assertTrue($luxuryTemplate->fields()->count() > 0);
    }

    public function test_template_field_resolver_resolves_standard_fields(): void
    {
        $resolver = app(TemplateFieldResolver::class);
        $template = Template::where('slug', 'luxury-gold')->first();
        $this->assertNotNull($template);
        
        $sampleData = $resolver->resolveSampleData($template);

        $this->assertArrayHasKey('event_title', $sampleData);
        $this->assertArrayHasKey('guest_name', $sampleData);
        $this->assertArrayHasKey('venue_name', $sampleData);
        $this->assertArrayHasKey('qr_code_value', $sampleData);
        $this->assertArrayHasKey('countdown_target_iso', $sampleData);
        $this->assertArrayHasKey('rsvp_submit_url', $sampleData);
        $this->assertEquals('wedding', $sampleData['event_category']);
    }

    public function test_public_invitation_route_and_rsvp_submission(): void
    {
        $template = Template::where('slug', 'luxury-gold')->first();
        $user = User::first();
        $host = Host::create([
            'user_id' => $user->id,
            'full_name' => 'Host Family',
            'phone' => '+255700111222',
            'email' => 'host@example.com',
            'event_type' => 'wedding',
            'number_of_guests' => 100,
            'total_cost' => 100000,
            'password' => bcrypt('password'),
        ]);
        $event = Event::create([
            'user_id' => $user->id,
            'host_id' => $host->id,
            'title' => 'Sample Wedding',
            'category' => 'wedding',
            'date' => now()->addDays(20)->format('Y-m-d'),
            'time' => '14:00',
            'venue' => 'Grand Hall',
            'venue_address' => 'Sample St',
            'status' => 'active',
            'template_id' => $template->id,
        ]);
        $guest = Guest::create([
            'event_id' => $event->id,
            'full_name' => 'Jane Doe',
            'phone' => '+255700333444',
            'status' => 'pending',
            'invitation_code' => 'JANE-001',
        ]);

        $slug = 'test-invitation-' . uniqid();
        $invitation = Invitation::create([
            'event_id' => $event->id,
            'guest_id' => $guest->id,
            'slug' => $slug,
            'template_id' => $template->id,
            'status' => 'sent',
            'rsvp_status' => 'pending',
        ]);

        // 1. Test Web Invitation rendering
        $response = $this->get('/invitation/' . $invitation->slug);
        $response->assertStatus(200);

        // 2. Test RSVP Submission
        $rsvpResponse = $this->postJson('/invitation/' . $invitation->slug . '/rsvp', [
            'rsvp_status' => 'attending',
            'plus_one_count' => 1,
            'rsvp_notes' => 'Congratulations on your wedding!',
        ]);

        $rsvpResponse->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
            'rsvp_status' => 'attending',
            'plus_one_count' => 1,
        ]);
    }

    public function test_admin_template_preview_and_duplicate(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $template = Template::where('slug', 'luxury-gold')->first();

        // Test Live Preview Route
        $response = $this->actingAs($admin)->get(route('admin.templates.preview', $template));
        $response->assertStatus(200);

        // Test Duplicate Route
        $dupResponse = $this->actingAs($admin)->post(route('admin.templates.duplicate', $template));
        $dupResponse->assertStatus(302);

        $cloned = Template::where('name', 'like', '%Copy%')->latest()->first();
        $this->assertNotNull($cloned);
        $this->assertTrue($cloned->fields()->count() > 0);
    }

    public function test_sample_preview_rsvp_and_pdf_handling(): void
    {
        $template = Template::where('slug', 'luxury-gold')->first();

        // 1. Test public route with sample preview slug
        $viewResponse = $this->get('/invitation/sample-preview-' . $template->slug);
        $viewResponse->assertStatus(200);

        // 2. Test sample preview RSVP submission without DB model
        $rsvpResponse = $this->postJson('/invitation/sample-preview-' . $template->slug . '/rsvp', [
            'rsvp_status' => 'attending',
            'plus_one_count' => 1,
            'rsvp_notes' => 'Testing preview RSVP',
        ]);
        $rsvpResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_preview' => true,
            ]);

        // 3. Test sample preview PDF generation
        $pdfResponse = $this->get('/invitation/sample-preview-' . $template->slug . '/pdf');
        $pdfResponse->assertStatus(200);
        $this->assertEquals('application/pdf', $pdfResponse->headers->get('content-type'));
    }
}
