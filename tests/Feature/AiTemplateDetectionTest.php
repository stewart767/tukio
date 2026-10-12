<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Host;
use App\Models\Invitation;
use App\Models\Template;
use App\Models\User;
use App\Services\Invitations\QrPlacementAdvisor;
use App\Services\Invitations\TemplateAiDetectionService;
use App\Services\InvitationImageGenerator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AiTemplateDetectionTest extends TestCase
{
    use DatabaseMigrations;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->admin = User::factory()->create([
            'role' => 'super_admin',
            'email' => 'superadmin@example.com',
        ]);
    }

    public function test_admin_can_upload_artwork_and_trigger_detection(): void
    {
        $file = UploadedFile::fake()->image('wedding-card.png', 1200, 1600);

        $response = $this->actingAs($this->admin)->post(route('admin.templates.upload-ai'), [
            'name' => 'Royal Emerald Celebration',
            'category' => 'wedding',
            'template_type' => 'premium',
            'price' => 50000,
            'description' => 'Luxury Emerald wedding template with gold borders.',
            'artwork' => $file,
        ]);

        $response->assertStatus(302);

        $template = Template::where('name', 'Royal Emerald Celebration')->first();
        $this->assertNotNull($template);
        $this->assertEquals('wedding', $template->category);
        $this->assertEquals('premium', $template->template_type);
        $this->assertNotNull($template->artwork_path);
        $this->assertEquals(1200, $template->artwork_width);
        $this->assertEquals(1600, $template->artwork_height);
        $this->assertEquals('completed', $template->analysis_status);

        // Verify normalized fields were detected and saved
        $fields = $template->getNormalizedFields();
        $this->assertNotEmpty($fields);
        $this->assertArrayHasKey('guest_name', $fields);
        $this->assertArrayHasKey('qr_code', $fields);
        $this->assertArrayHasKey('invitation_text', $fields);

        // Verify coordinates are strictly normalized within [0.0, 1.0]
        foreach ($fields as $field) {
            $this->assertGreaterThanOrEqual(0.0, $field['x']);
            $this->assertLessThanOrEqual(1.0, $field['x']);
            $this->assertGreaterThanOrEqual(0.0, $field['y']);
            $this->assertLessThanOrEqual(1.0, $field['y']);
            $this->assertGreaterThan(0.0, $field['width']);
            $this->assertGreaterThan(0.0, $field['height']);
            $this->assertLessThanOrEqual(1.01, $field['x'] + $field['width']);
            $this->assertLessThanOrEqual(1.01, $field['y'] + $field['height']);
        }
    }

    public function test_upload_rejects_invalid_file_types_and_dimensions(): void
    {
        // 1. Text file rejected
        $textDoc = UploadedFile::fake()->create('document.txt', 50, 'text/plain');
        $response = $this->actingAs($this->admin)->post(route('admin.templates.upload-ai'), [
            'name' => 'Invalid Template',
            'category' => 'wedding',
            'template_type' => 'standard',
            'artwork' => $textDoc,
        ]);
        $response->assertSessionHasErrors(['artwork']);

        // 2. Too small image rejected
        $tinyImage = UploadedFile::fake()->image('tiny.png', 100, 100);
        $response2 = $this->actingAs($this->admin)->post(route('admin.templates.upload-ai'), [
            'name' => 'Tiny Template',
            'category' => 'wedding',
            'template_type' => 'standard',
            'artwork' => $tinyImage,
        ]);
        $response2->assertSessionHas('error');
    }

    public function test_qr_placement_advisor_recommends_non_overlapping_position(): void
    {
        $advisor = app(QrPlacementAdvisor::class);

        $mockFields = [
            'guest_name' => ['x' => 0.10, 'y' => 0.25, 'width' => 0.80, 'height' => 0.08],
            'invitation_text' => ['x' => 0.12, 'y' => 0.38, 'width' => 0.76, 'height' => 0.10],
            'event_title' => ['x' => 0.10, 'y' => 0.50, 'width' => 0.80, 'height' => 0.09],
            'event_date' => ['x' => 0.15, 'y' => 0.62, 'width' => 0.70, 'height' => 0.06],
        ];

        $recommendation = $advisor->recommendPosition($mockFields, null, 1200, 1600);

        $this->assertEquals('qr', $recommendation['type']);
        $this->assertGreaterThanOrEqual(0.12, $recommendation['width']);
        $this->assertGreaterThanOrEqual(0.70, $recommendation['y']); // Should prefer bottom
        $this->assertNotEmpty($recommendation['quiet_zone']);

        // Check that overlap with text is zero
        foreach ($mockFields as $f) {
            $overlap = $advisor->calculateOverlapArea($recommendation, $f);
            $this->assertEquals(0.0, $overlap, 'Recommended QR should not overlap critical text zones');
        }
    }

    public function test_visual_editor_page_and_field_update_endpoint(): void
    {
        $template = Template::create([
            'name' => 'Floral Romance Studio',
            'slug' => 'floral-romance-studio',
            'category' => 'wedding',
            'template_type' => 'standard',
            'price' => 0,
            'is_paid' => false,
            'artwork_width' => 1200,
            'artwork_height' => 1600,
            'analysis_status' => 'completed',
            'approval_status' => 'pending_review',
            'placeholder_config' => [
                'fields' => [
                    'guest_name' => [
                        'x' => 0.10, 'y' => 0.30, 'width' => 0.80, 'height' => 0.08,
                        'label' => 'Invitee Name', 'font_family' => 'Playfair Display',
                        'font_size' => 28, 'color' => '#654321', 'alignment' => 'center'
                    ],
                    'qr_code' => [
                        'x' => 0.75, 'y' => 0.75, 'width' => 0.16, 'height' => 0.16,
                        'type' => 'qr', 'label' => 'QR Pass'
                    ]
                ]
            ],
            'created_by' => $this->admin->id,
            'html_content' => '<!-- test -->',
        ]);

        // 1. Test Editor GET
        $response = $this->actingAs($this->admin)->get(route('admin.templates.editor', $template));
        $response->assertStatus(200);
        $response->assertSee('Floral Romance Studio');
        $response->assertSee('Save Coordinates');

        // 2. Test Editor POST (Updating coordinates)
        $newFields = [
            'guest_name' => [
                'x' => 0.12, 'y' => 0.32, 'width' => 0.76, 'height' => 0.09,
                'label' => 'Honored Guest', 'font_family' => 'Georgia',
                'font_size' => 30, 'color' => '#1a1a1a', 'alignment' => 'center'
            ],
            'qr_code' => [
                'x' => 0.78, 'y' => 0.78, 'width' => 0.15, 'height' => 0.15,
                'type' => 'qr', 'label' => 'Gate QR Pass'
            ]
        ];

        $updateResponse = $this->actingAs($this->admin)->postJson(route('admin.templates.editor.update', $template), [
            'fields' => $newFields,
            'approve' => true,
        ]);

        $updateResponse->assertStatus(200)
            ->assertJson(['success' => true]);

        $template->refresh();
        $this->assertEquals('approved', $template->approval_status);
        $this->assertTrue($template->is_active);

        $savedFields = $template->getNormalizedFields();
        $this->assertEquals(0.12, $savedFields['guest_name']['x']);
        $this->assertEquals('Honored Guest', $savedFields['guest_name']['label']);
    }

    public function test_invitation_image_generator_renders_with_normalized_fields(): void
    {
        // Create sample artwork file on public disk
        $image = imagecreatetruecolor(800, 1100);
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $white);
        ob_start();
        imagepng($image);
        $content = ob_get_clean();
        imagedestroy($image);

        Storage::disk('public')->put('templates/artwork/test-artwork.png', $content);

        $template = Template::create([
            'name' => 'Bespoke Traditional Ceremony',
            'slug' => 'bespoke-traditional',
            'category' => 'traditional',
            'template_type' => 'custom',
            'artwork_path' => 'templates/artwork/test-artwork.png',
            'artwork_width' => 800,
            'artwork_height' => 1100,
            'analysis_status' => 'completed',
            'approval_status' => 'approved',
            'placeholder_config' => [
                'fields' => [
                    'guest_name' => [
                        'x' => 0.10, 'y' => 0.25, 'width' => 0.80, 'height' => 0.08,
                        'font_family' => 'Playfair Display', 'font_size' => 24,
                        'color' => '#333333', 'alignment' => 'center'
                    ],
                    'event_title' => [
                        'x' => 0.10, 'y' => 0.40, 'width' => 0.80, 'height' => 0.08,
                        'font_family' => 'Cinzel', 'font_size' => 28,
                        'color' => '#654321', 'alignment' => 'center'
                    ],
                    'qr_code' => [
                        'x' => 0.75, 'y' => 0.75, 'width' => 0.16, 'height' => 0.16,
                        'type' => 'qr'
                    ]
                ]
            ],
            'created_by' => $this->admin->id,
            'html_content' => '<!-- test -->',
        ]);

        $host = Host::create([
            'user_id' => $this->admin->id,
            'full_name' => 'Mr. & Mrs. Kweka',
            'phone' => '+255711223344',
            'email' => 'host@example.com',
            'event_type' => 'wedding',
            'number_of_guests' => 120,
            'total_cost' => 150000,
            'password' => bcrypt('secret'),
        ]);

        $event = Event::create([
            'user_id' => $this->admin->id,
            'host_id' => $host->id,
            'title' => 'Sherehe ya Send-Off',
            'category' => 'traditional',
            'date' => now()->addDays(30)->format('Y-m-d'),
            'time' => '14:00',
            'venue' => 'Serena Hotel',
            'venue_address' => 'Dar es Salaam',
            'status' => 'active',
            'template_id' => $template->id,
        ]);

        $guest = Guest::create([
            'event_id' => $event->id,
            'full_name' => 'Mhe. January Makamba',
            'phone' => '+255788990011',
            'status' => 'pending',
            'invitation_code' => 'TEST-007',
        ]);

        $invitation = Invitation::create([
            'event_id' => $event->id,
            'guest_id' => $guest->id,
            'slug' => 'test-invitation-ai-' . uniqid(),
            'template_id' => $template->id,
            'status' => 'sent',
        ]);

        $generator = app(InvitationImageGenerator::class);
        $renderedGd = $generator->generate($invitation);

        $this->assertNotNull($renderedGd);
        $this->assertTrue(is_resource($renderedGd) || (is_object($renderedGd) && get_class($renderedGd) === 'GdImage'));
        $this->assertEquals(800, imagesx($renderedGd));
        $this->assertEquals(1100, imagesy($renderedGd));

        imagedestroy($renderedGd);
    }
}
