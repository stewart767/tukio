<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Host;
use App\Models\Template;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HostCardTemplateTest extends TestCase
{
    use RefreshDatabase;

    protected Host $host;
    protected Event $event;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->host = Host::create([
            'full_name' => 'David & Sarah',
            'email' => 'host@example.com',
            'phone' => '255712345678',
            'password' => Hash::make('password123'),
            'event_type' => 'wedding',
            'number_of_guests' => 200,
            'total_cost' => 500000,
            'is_active' => true,
            'event_configured' => true,
        ]);

        $this->event = Event::create([
            'host_id' => $this->host->id,
            'title' => 'David & Sarah Royal Wedding',
            'description' => 'A royal celebration of love and unity.',
            'category' => 'wedding',
            'date' => now()->addDays(30)->format('Y-m-d'),
            'time' => '14:00',
            'venue' => 'Serena Hotel Ballroom',
            'address' => 'Dar es Salaam, Tanzania',
            'status' => 'upcoming',
        ]);
    }

    public function test_host_can_view_card_templates_index(): void
    {
        $response = $this->actingAs($this->host, 'host')
            ->get(route('host.card-templates.index'));

        $response->assertOk()
            ->assertViewIs('host.card-templates.index')
            ->assertSee('Custom Card Templates');
    }

    public function test_host_can_view_card_templates_create_form(): void
    {
        $response = $this->actingAs($this->host, 'host')
            ->get(route('host.card-templates.create'));

        $response->assertOk()
            ->assertViewIs('host.card-templates.create')
            ->assertSee('Upload Custom Card Artwork');
    }

    public function test_host_can_upload_card_artwork_and_initialize_template(): void
    {
        // 800x1200 image
        $file = UploadedFile::fake()->image('wedding_card.jpg', 800, 1200);

        $response = $this->actingAs($this->host, 'host')
            ->post(route('host.card-templates.upload'), [
                'name' => 'Royal Floral Card',
                'category' => 'wedding',
                'description' => 'Luxury floral invitation card with gold accents',
                'artwork' => $file,
            ]);

        $template = Template::where('host_id', $this->host->id)->first();
        $this->assertNotNull($template);
        $this->assertEquals('Royal Floral Card', $template->name);
        $this->assertEquals('custom', $template->template_type);
        $this->assertEquals('approved', $template->approval_status);
        $this->assertTrue($template->is_active);
        $this->assertNotEmpty($template->artwork_path);

        $response->assertRedirect(route('host.card-templates.editor', $template));
    }

    public function test_host_can_access_and_edit_their_own_template(): void
    {
        $template = Template::create([
            'name' => 'Host Private Card',
            'slug' => 'host-private-card',
            'category' => 'wedding',
            'template_type' => 'custom',
            'host_id' => $this->host->id,
            'artwork_width' => 800,
            'artwork_height' => 1200,
            'approval_status' => 'approved',
            'is_active' => true,
            'placeholder_config' => [
                'fields' => [
                    'guest_name' => [
                        'label' => 'Mgeni Rasmi',
                        'type' => 'guest_name',
                        'x' => 0.15,
                        'y' => 0.45,
                        'width' => 0.70,
                        'height' => 0.08,
                        'font_family' => 'Playfair Display',
                        'font_size' => 24,
                        'color' => '#1e1b4b',
                    ],
                ],
            ],
        ]);

        $response = $this->actingAs($this->host, 'host')
            ->get(route('host.card-templates.editor', $template));

        $response->assertOk()
            ->assertViewIs('host.card-templates.editor')
            ->assertSee('Host Private Card');
    }

    public function test_other_host_cannot_access_or_modify_another_hosts_template(): void
    {
        $otherHost = Host::create([
            'full_name' => 'Other Host',
            'email' => 'other@example.com',
            'phone' => '255788888888',
            'password' => Hash::make('password123'),
            'event_type' => 'birthday',
            'number_of_guests' => 50,
            'total_cost' => 100000,
            'is_active' => true,
            'event_configured' => true,
        ]);

        $template = Template::create([
            'name' => 'Host A Private Card',
            'slug' => 'host-a-private-card',
            'category' => 'wedding',
            'template_type' => 'custom',
            'host_id' => $this->host->id,
            'artwork_width' => 800,
            'artwork_height' => 1200,
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        // Attempting to access editor as otherHost must fail with 403
        $response = $this->actingAs($otherHost, 'host')
            ->get(route('host.card-templates.editor', $template));

        $response->assertStatus(403);
    }

    public function test_host_can_update_editor_fields_via_ajax(): void
    {
        $template = Template::create([
            'name' => 'Host Editable Card',
            'slug' => 'host-editable-card',
            'category' => 'wedding',
            'template_type' => 'custom',
            'host_id' => $this->host->id,
            'artwork_width' => 800,
            'artwork_height' => 1200,
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $newFields = [
            'guest_name' => [
                'label' => 'Mheshimiwa Mgeni',
                'type' => 'guest_name',
                'x' => 0.20,
                'y' => 0.40,
                'width' => 0.60,
                'height' => 0.09,
                'font_family' => 'Cinzel',
                'font_size' => 28,
                'color' => '#b45309',
                'alignment' => 'center',
            ],
            'qr_code' => [
                'label' => 'Entrance QR Pass',
                'type' => 'qr',
                'x' => 0.75,
                'y' => 0.75,
                'width' => 0.18,
                'height' => 0.18,
            ],
        ];

        $response = $this->actingAs($this->host, 'host')
            ->postJson(route('host.card-templates.editor.update', $template), [
                'fields' => $newFields,
            ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $template->refresh();
        $normalized = $template->getNormalizedFields();
        $this->assertArrayHasKey('guest_name', $normalized);
        $this->assertEquals('Cinzel', $normalized['guest_name']['font_family']);
        $this->assertEquals('#b45309', $normalized['guest_name']['color']);
        $this->assertArrayHasKey('qr_code', $normalized);
    }

    public function test_host_can_delete_their_custom_template(): void
    {
        $template = Template::create([
            'name' => 'Card To Delete',
            'slug' => 'card-to-delete',
            'category' => 'wedding',
            'template_type' => 'custom',
            'host_id' => $this->host->id,
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->host, 'host')
            ->delete(route('host.card-templates.destroy', $template));

        $response->assertRedirect(route('host.card-templates.index'));
        $this->assertDatabaseMissing('templates', ['id' => $template->id]);
    }

    public function test_host_can_preview_their_custom_template(): void
    {
        $template = Template::create([
            'name' => 'Card For Preview',
            'slug' => 'card-for-preview',
            'category' => 'wedding',
            'template_type' => 'custom',
            'host_id' => $this->host->id,
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->host, 'host')
            ->get(route('host.card-templates.preview', $template));

        $response->assertOk();
    }

    public function test_custom_template_is_highlighted_in_invitations_dispatch_screen(): void
    {
        $customTemplate = Template::create([
            'name' => 'Bride Custom Gold Card',
            'slug' => 'bride-custom-gold-card',
            'category' => 'wedding',
            'template_type' => 'custom',
            'host_id' => $this->host->id,
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->host, 'host')
            ->get(route('host.invitations.create'));

        $response->assertOk()
            ->assertSee('Bride Custom Gold Card')
            ->assertSee('Your Custom Card')
            ->assertSee('Edit Layout');
    }

    public function test_host_can_upload_couple_photo_and_configure_fit(): void
    {
        $template = Template::create([
            'name' => 'Couple Photo Card',
            'slug' => 'couple-photo-card',
            'category' => 'wedding',
            'template_type' => 'custom',
            'host_id' => $this->host->id,
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $photoFile = UploadedFile::fake()->image('couple.jpg', 600, 800);

        $response = $this->actingAs($this->host, 'host')
            ->postJson(route('host.card-templates.upload-photo', $template), [
                'photo' => $photoFile,
                'field_key' => 'couple_photo',
            ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'field_key' => 'couple_photo',
            ]);

        $template->refresh();
        $normalized = $template->getNormalizedFields();
        $this->assertArrayHasKey('couple_photo', $normalized);
        $this->assertNotNull($normalized['couple_photo']['photo_url']);
        $this->assertEquals('image', $normalized['couple_photo']['type']);
    }
}
