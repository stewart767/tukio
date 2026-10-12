<?php

namespace App\Services\Invitations;

use App\Models\Template;
use App\Models\TemplateField;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TemplateEngine
{
    protected string $templatesPath;

    public function __construct()
    {
        $this->templatesPath = resource_path('views/invitations/templates');
    }

    /**
     * Get path to templates directory.
     */
    public function getTemplatesPath(): string
    {
        return $this->templatesPath;
    }

    /**
     * Discover all file-based templates in resources/views/invitations/templates/
     */
    public function discoverFileTemplates(): array
    {
        if (!File::exists($this->templatesPath)) {
            File::makeDirectory($this->templatesPath, 0755, true);
            return [];
        }

        $directories = File::directories($this->templatesPath);
        $templates = [];

        foreach ($directories as $dir) {
            $slug = basename($dir);
            $manifestPath = $dir . DIRECTORY_SEPARATOR . 'manifest.json';
            $bladePath = $dir . DIRECTORY_SEPARATOR . 'invitation.blade.php';
            $cssPath = $dir . DIRECTORY_SEPARATOR . 'style.css';

            $manifest = [];
            if (File::exists($manifestPath)) {
                $content = File::get($manifestPath);
                $manifest = json_decode($content, true) ?: [];
            }

            $templates[$slug] = [
                'slug' => $slug,
                'name' => $manifest['name'] ?? Str::headline($slug),
                'category' => $manifest['category'] ?? 'wedding',
                'template_type' => $manifest['template_type'] ?? 'standard',
                'price' => (float) ($manifest['price'] ?? 0.00),
                'currency' => $manifest['currency'] ?? 'TZS',
                'is_paid' => (bool) ($manifest['is_paid'] ?? false),
                'version' => $manifest['version'] ?? '1.0',
                'description' => $manifest['description'] ?? "Visual dynamic invitation layout for {$slug}",
                'features' => $manifest['features'] ?? $this->getDefaultFeatures(),
                'fields' => $manifest['fields'] ?? $this->getDefaultFields(),
                'has_blade' => File::exists($bladePath),
                'has_css' => File::exists($cssPath),
                'view_path' => "invitations.templates.{$slug}.invitation",
                'dir_path' => $dir,
            ];
        }

        return $templates;
    }

    /**
     * Sync discovered file templates with database.
     */
    public function syncTemplatesWithDatabase(?int $userId = null): int
    {
        $discovered = $this->discoverFileTemplates();
        $adminId = $userId ?? User::where('role', 'super_admin')->value('id') ?? 1;
        $syncedCount = 0;

        foreach ($discovered as $slug => $data) {
            $template = Template::firstOrNew(['slug' => $slug]);

            $isNew = !$template->exists;
            $template->name = $data['name'];
            $template->category = in_array($data['category'], ['wedding', 'birthday', 'kitchen_party', 'send_off', 'corporate', 'traditional', 'other']) ? $data['category'] : 'wedding';
            $template->template_type = $data['template_type'] ?? 'standard';
            $template->price = $data['price'] ?? 0.00;
            $template->currency = $data['currency'] ?? 'TZS';
            $template->is_paid = ($data['price'] > 0 || !empty($data['is_paid']));
            $template->version = $data['version'] ?? '1.0';
            $template->description = $data['description'] ?? $template->description;
            $template->features = $data['features'] ?? $this->getDefaultFeatures();
            $template->view_path = $data['view_path'];
            $template->is_active = true;

            if ($isNew) {
                $template->created_by = $adminId;
                $template->html_content = "<!-- Rendered from {$data['view_path']} -->";
            }

            $template->save();

            // Sync template fields
            $this->syncTemplateFields($template, $data['fields'] ?? []);
            $syncedCount++;
        }

        return $syncedCount;
    }

    /**
     * Sync fields for a given template.
     */
    public function syncTemplateFields(Template $template, array $fieldKeys): void
    {
        $standardMap = $this->getStandardFieldsDefinition();
        $sort = 0;

        foreach ($fieldKeys as $key) {
            $meta = $standardMap[$key] ?? [
                'label' => Str::headline($key),
                'type' => 'text',
                'is_required' => false,
            ];

            TemplateField::updateOrCreate(
                [
                    'template_id' => $template->id,
                    'field_key' => $key,
                ],
                [
                    'field_label' => $meta['label'],
                    'field_type' => $meta['type'],
                    'is_required' => $meta['is_required'] ?? false,
                    'sort_order' => $sort++,
                    'default_value' => $meta['default_value'] ?? null,
                ]
            );
        }
    }

    /**
     * Duplicate an existing template.
     */
    public function duplicateTemplate(Template $template, ?string $newName = null): Template
    {
        $cloned = $template->replicate(['slug']);
        $cloned->name = $newName ?: ($template->name . ' (Copy)');
        
        $baseSlug = Str::slug($cloned->name);
        $slug = $baseSlug;
        $counter = 1;
        while (Template::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }
        $cloned->slug = $slug;
        $cloned->version = '1.0';
        $cloned->created_by = auth()->id() ?? $template->created_by;
        $cloned->save();

        // Copy fields
        foreach ($template->fields as $field) {
            $newField = $field->replicate();
            $newField->template_id = $cloned->id;
            $newField->save();
        }

        return $cloned;
    }

    /**
     * Standard field definitions registry.
     */
    public function getStandardFieldsDefinition(): array
    {
        return [
            'event_title' => ['label' => 'Event Title', 'type' => 'text', 'is_required' => true],
            'event_description' => ['label' => 'Event Description', 'type' => 'textarea', 'is_required' => false],
            'event_date' => ['label' => 'Event Date', 'type' => 'date', 'is_required' => true],
            'event_time' => ['label' => 'Event Time', 'type' => 'time', 'is_required' => true],
            'event_category' => ['label' => 'Category', 'type' => 'text', 'is_required' => false],
            'bride_name' => ['label' => 'Bride Name', 'type' => 'text', 'is_required' => false],
            'groom_name' => ['label' => 'Groom Name', 'type' => 'text', 'is_required' => false],
            'couple_names' => ['label' => 'Couple Names', 'type' => 'text', 'is_required' => false],
            'host_name' => ['label' => 'Host / Parents Name', 'type' => 'text', 'is_required' => false],
            'venue_name' => ['label' => 'Venue Name', 'type' => 'text', 'is_required' => true],
            'venue_address' => ['label' => 'Venue Address', 'type' => 'text', 'is_required' => false],
            'venue_map_url' => ['label' => 'Google Maps Link', 'type' => 'url', 'is_required' => false],
            'guest_name' => ['label' => 'Guest Name (Personalized)', 'type' => 'guest_name', 'is_required' => false],
            'guest_code' => ['label' => 'Guest Code', 'type' => 'text', 'is_required' => false],
            'guest_table' => ['label' => 'Guest Table Assignment', 'type' => 'text', 'is_required' => false],
            'guest_plus_one' => ['label' => 'Plus One Count', 'type' => 'text', 'is_required' => false],
            'qr_code' => ['label' => 'QR Code Entry Pass', 'type' => 'qr', 'is_required' => true],
            'countdown' => ['label' => 'Countdown Timer', 'type' => 'countdown', 'is_required' => false],
            'rsvp' => ['label' => 'RSVP Interactive Section', 'type' => 'rsvp', 'is_required' => false],
            'gallery' => ['label' => 'Photo Gallery', 'type' => 'gallery', 'is_required' => false],
            'couple_photo' => ['label' => 'Couple Portrait', 'type' => 'image', 'is_required' => false],
            'cover_image' => ['label' => 'Cover / Banner Image', 'type' => 'image', 'is_required' => false],
            'whatsapp_share' => ['label' => 'WhatsApp Share Action', 'type' => 'url', 'is_required' => false],
            'phone' => ['label' => 'Contact Phone', 'type' => 'text', 'is_required' => false],
        ];
    }

    public function getDefaultFeatures(): array
    {
        return [
            'Digital Web Invitation',
            'Guest Personalization',
            'Interactive RSVP',
            'Live Countdown',
            'Secure QR Pass',
            'Google Maps Location',
            'Photo Gallery',
            'PDF Download',
            'WhatsApp Instant Share',
        ];
    }

    public function getDefaultFields(): array
    {
        return [
            'event_title', 'event_date', 'event_time', 'bride_name', 'groom_name',
            'couple_names', 'venue_name', 'venue_address', 'guest_name', 'guest_table',
            'qr_code', 'countdown', 'rsvp', 'gallery', 'venue_map_url'
        ];
    }
}
