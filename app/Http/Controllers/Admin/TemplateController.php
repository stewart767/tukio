<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplateField;
use App\Services\Invitations\InvitationRenderer;
use App\Services\Invitations\TemplateEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Services\Invitations\TemplateAiDetectionService;

class TemplateController extends Controller
{
    public function __construct(
        protected TemplateEngine $templateEngine,
        protected InvitationRenderer $invitationRenderer,
        protected TemplateAiDetectionService $aiDetectionService
    ) {}

    /**
     * Display a listing of templates with filters.
     */
    public function index(Request $request)
    {
        $query = Template::with(['creator', 'fields'])->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('template_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $templates = $query->paginate(12)->withQueryString();

        $categories = [
            'wedding' => 'Wedding',
            'birthday' => 'Birthday',
            'kitchen_party' => 'Kitchen Party',
            'send_off' => 'Send Off',
            'corporate' => 'Corporate Event',
            'traditional' => 'Traditional Ceremony',
            'other' => 'Other Milestone',
        ];

        return view('admin.templates.index', compact('templates', 'categories'));
    }

    /**
     * Show the form for creating a new template.
     */
    public function create()
    {
        $categories = [
            'wedding' => 'Wedding',
            'birthday' => 'Birthday',
            'kitchen_party' => 'Kitchen Party',
            'send_off' => 'Send Off',
            'corporate' => 'Corporate Event',
            'traditional' => 'Traditional Ceremony',
            'other' => 'Other Milestone',
        ];

        $standardFields = $this->templateEngine->getStandardFieldsDefinition();

        return view('admin.templates.create', compact('categories', 'standardFields'));
    }

    /**
     * Store a newly created template in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:100|unique:templates,slug',
            'category' => 'required|string|max:50',
            'template_type' => 'required|in:standard,premium,custom',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'version' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'template_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'view_path' => 'nullable|string|max:255',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'fields' => 'nullable|array',
            'fields.*' => 'string|max:100',
        ]);

        $imagePath = null;
        if ($request->hasFile('template_image')) {
            $imagePath = $request->file('template_image')->store('template-images', 'public');
        }

        $price = (float) ($request->price ?? 0.00);
        $isPaid = $price > 0 || $request->template_type === 'premium';

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $baseSlug = $slug;
        $counter = 1;
        while (Template::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $template = Template::create([
            'name' => $request->name,
            'slug' => $slug,
            'category' => $request->category,
            'template_type' => $request->template_type,
            'price' => $price,
            'currency' => $request->currency ?: 'TZS',
            'is_paid' => $isPaid,
            'version' => $request->version ?: '1.0',
            'description' => $request->description,
            'features' => $request->features ?: $this->templateEngine->getDefaultFeatures(),
            'html_content' => $request->html_content ?: "<!-- Template {$slug} -->",
            'css_content' => $request->css_content,
            'view_path' => $request->view_path ?: "invitations.templates.{$slug}.invitation",
            'preview_image' => $imagePath,
            'is_active' => true,
            'created_by' => auth()->id(),
            'placeholder_config' => $request->fields ?? [],
        ]);

        // Sync template fields
        $fieldsList = $request->fields ?? $this->templateEngine->getDefaultFields();
        $this->templateEngine->syncTemplateFields($template, $fieldsList);

        return redirect()->route('admin.templates.index')->with('success', "Template '{$template->name}' created successfully!");
    }

    /**
     * Display template details.
     */
    public function show(Template $template)
    {
        $template->load(['creator', 'fields']);
        return view('admin.templates.show', compact('template'));
    }

    /**
     * Show form for editing template.
     */
    public function edit(Template $template)
    {
        $categories = [
            'wedding' => 'Wedding',
            'birthday' => 'Birthday',
            'kitchen_party' => 'Kitchen Party',
            'send_off' => 'Send Off',
            'corporate' => 'Corporate Event',
            'traditional' => 'Traditional Ceremony',
            'other' => 'Other Milestone',
        ];

        $standardFields = $this->templateEngine->getStandardFieldsDefinition();
        $activeFieldKeys = $template->fields->pluck('field_key')->toArray();

        return view('admin.templates.edit', compact('template', 'categories', 'standardFields', 'activeFieldKeys'));
    }

    /**
     * Update template in storage.
     */
    public function update(Request $request, Template $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:100|unique:templates,slug,' . $template->id,
            'category' => 'required|string|max:50',
            'template_type' => 'required|in:standard,premium,custom',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'version' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'template_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'view_path' => 'nullable|string|max:255',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'fields' => 'nullable|array',
            'fields.*' => 'string|max:100',
        ]);

        $price = (float) ($request->price ?? 0.00);
        $isPaid = $price > 0 || $request->template_type === 'premium';

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'category' => $request->category,
            'template_type' => $request->template_type,
            'price' => $price,
            'currency' => $request->currency ?: 'TZS',
            'is_paid' => $isPaid,
            'version' => $request->version ?: '1.0',
            'description' => $request->description,
            'features' => $request->features ?: $template->features,
            'view_path' => $request->view_path ?: $template->view_path,
            'placeholder_config' => $request->fields ?? [],
        ];

        if ($request->hasFile('template_image')) {
            if ($template->preview_image) {
                Storage::disk('public')->delete($template->preview_image);
            }
            $data['preview_image'] = $request->file('template_image')->store('template-images', 'public');
        }

        if ($request->filled('html_content')) {
            $data['html_content'] = $request->html_content;
        }
        if ($request->filled('css_content')) {
            $data['css_content'] = $request->css_content;
        }

        $template->update($data);

        // Update fields
        if ($request->has('fields')) {
            $this->templateEngine->syncTemplateFields($template, $request->fields);
        }

        return redirect()->route('admin.templates.index')->with('success', "Template '{$template->name}' updated successfully!");
    }

    /**
     * Remove template from storage.
     */
    public function destroy(Template $template)
    {
        if ($template->preview_image) {
            Storage::disk('public')->delete($template->preview_image);
        }

        $template->delete();

        return redirect()->route('admin.templates.index')->with('success', 'Template deleted successfully!');
    }

    /**
     * Toggle template active status.
     */
    public function toggleActive(Template $template)
    {
        $template->update(['is_active' => !$template->is_active]);

        $status = $template->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.templates.index')->with('success', "Template {$status} successfully!");
    }

    /**
     * Live preview room for template with realistic dummy data.
     */
    public function preview(Template $template)
    {
        return $this->invitationRenderer->renderPreview($template);
    }

    /**
     * Duplicate an existing template.
     */
    public function duplicate(Template $template)
    {
        $cloned = $this->templateEngine->duplicateTemplate($template);
        return redirect()->route('admin.templates.edit', $cloned)->with('success', "Template cloned successfully as '{$cloned->name}'.");
    }

    /**
     * Sync file-based templates from resources/views/invitations/templates/
     */
    public function sync()
    {
        $synced = $this->templateEngine->syncTemplatesWithDatabase(auth()->id());
        return redirect()->route('admin.templates.index')->with('success', "Discovered and synchronized {$synced} visual templates into the platform.");
    }

    /**
     * Upload an invitation design artwork and start AI layout analysis.
     */
    public function uploadAiTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'template_type' => 'required|in:standard,premium,custom',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'artwork' => 'required|file|mimes:jpeg,png,jpg,webp|max:15360',
        ]);

        $file = $request->file('artwork');

        // Verify image dimensions
        $imageInfo = @getimagesize($file->getRealPath());
        if (!$imageInfo) {
            return back()->withInput()->with('error', 'The uploaded file is not a valid or readable image.');
        }

        $width = (int) $imageInfo[0];
        $height = (int) $imageInfo[1];
        if ($width < 400 || $height < 400) {
            return back()->withInput()->with('error', "Image dimensions ({$width}x{$height}) are too small. Minimum 400x400 required.");
        }

        // Store artwork
        $artworkPath = $file->store('templates/artwork', 'public');
        $previewPath = $file->store('templates/previews', 'public');

        $slug = Str::slug($request->name);
        $baseSlug = $slug;
        $counter = 1;
        while (Template::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $price = (float) ($request->price ?? 0.00);
        $isPaid = $price > 0 || $request->template_type === 'premium';

        $template = Template::create([
            'name' => $request->name,
            'slug' => $slug,
            'category' => $request->category,
            'template_type' => $request->template_type,
            'price' => $price,
            'currency' => $request->currency ?: 'TZS',
            'is_paid' => $isPaid,
            'version' => '1.0',
            'description' => $request->description,
            'preview_image' => $previewPath,
            'artwork_path' => $artworkPath,
            'artwork_width' => $width,
            'artwork_height' => $height,
            'analysis_status' => 'analyzing',
            'approval_status' => 'pending_review',
            'is_active' => false,
            'created_by' => auth()->id(),
            'features' => $this->templateEngine->getDefaultFeatures(),
            'html_content' => "<!-- AI-Assisted Template {$slug} -->",
            'view_path' => 'invitations.templates.artwork-overlay.invitation',
        ]);

        try {
            // Run AI analysis
            $analysisResult = $this->aiDetectionService->analyze($template);

            $template->update([
                'placeholder_config' => [
                    'fields' => $analysisResult['fields'],
                    'artwork_width' => $analysisResult['artwork_width'],
                    'artwork_height' => $analysisResult['artwork_height'],
                ],
                'analysis_metadata' => $analysisResult['metadata'],
                'analysis_status' => 'completed',
            ]);

            // Sync with template_fields table for relational access
            $this->syncDetectedFieldsToDatabase($template, $analysisResult['fields']);

            return redirect()->route('admin.templates.editor', $template)
                ->with('success', "Artwork analyzed successfully! Review the detected fields and QR placement in the visual editor.");
        } catch (\Throwable $e) {
            \Log::error('AI Template Analysis failed', [
                'template_id' => $template->id,
                'error' => $e->getMessage(),
            ]);

            $template->update([
                'analysis_status' => 'failed',
                'analysis_metadata' => [
                    'error' => $e->getMessage(),
                    'failed_at' => now()->toIso8601String(),
                ],
            ]);

            return redirect()->route('admin.templates.editor', $template)
                ->with('warning', 'Automated detection encountered a warning: ' . $e->getMessage() . '. You can manually configure layout zones.');
        }
    }

    /**
     * Show interactive visual template editor.
     */
    public function editor(Template $template)
    {
        $template->load('fields');

        // Get normalized fields
        $fields = $template->getNormalizedFields();
        if (empty($fields)) {
            // Run quick heuristic layout setup if never analyzed
            try {
                $analysis = $this->aiDetectionService->analyze($template);
                $fields = $analysis['fields'];
                $template->update([
                    'placeholder_config' => ['fields' => $fields],
                    'analysis_status' => 'completed',
                ]);
            } catch (\Throwable $e) {
                $fields = [];
            }
        }

        $sampleNames = [
            'short' => 'Anna Kim',
            'standard' => 'Hon. Josephat M. Mwita',
            'long' => 'Dr. & Mrs. Emmanuel Christopher Mwakipesile',
        ];

        return view('admin.templates.editor', compact('template', 'fields', 'sampleNames'));
    }

    /**
     * Update fields and coordinates from visual editor.
     */
    public function updateEditorFields(Request $request, Template $template)
    {
        $request->validate([
            'fields' => 'required|array',
            'approve' => 'nullable|boolean',
        ]);

        $sanitized = $this->aiDetectionService->sanitizeAndNormalizeFields($request->fields);

        // Keep QR code if submitted
        if (isset($request->fields['qr_code'])) {
            $qrData = $request->fields['qr_code'];
            $sanitized['qr_code'] = [
                'field_key' => 'qr_code',
                'label' => 'QR Pass Code',
                'type' => 'qr',
                'x' => round(max(0.0, min(0.85, (float)($qrData['x'] ?? 0.75))), 4),
                'y' => round(max(0.0, min(0.85, (float)($qrData['y'] ?? 0.75))), 4),
                'width' => round(max(0.10, min(0.35, (float)($qrData['width'] ?? 0.16))), 4),
                'height' => round(max(0.10, min(0.35, (float)($qrData['height'] ?? 0.16))), 4),
                'quiet_zone' => 0.015,
                'confidence' => 1.0,
                'status' => 'approved',
            ];
        }

        $updateData = [
            'placeholder_config' => [
                'fields' => $sanitized,
                'artwork_width' => $template->artwork_width,
                'artwork_height' => $template->artwork_height,
                'updated_at' => now()->toIso8601String(),
            ],
        ];

        if ($request->boolean('approve')) {
            $updateData['approval_status'] = 'approved';
            $updateData['is_active'] = true;
        }

        $template->update($updateData);

        // Sync with template_fields table
        $this->syncDetectedFieldsToDatabase($template, $sanitized);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $request->boolean('approve') ? 'Template approved and published!' : 'Field coordinates saved successfully!',
                'fields' => $sanitized,
                'approval_status' => $template->approval_status,
                'is_active' => $template->is_active,
            ]);
        }

        $msg = $request->boolean('approve')
            ? "Template '{$template->name}' approved and published successfully!"
            : "Template fields and coordinates saved successfully!";

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Re-run layout analysis on existing template.
     */
    public function reanalyze(Template $template)
    {
        try {
            $result = $this->aiDetectionService->analyze($template);

            $template->update([
                'placeholder_config' => [
                    'fields' => $result['fields'],
                    'artwork_width' => $result['artwork_width'],
                    'artwork_height' => $result['artwork_height'],
                ],
                'analysis_metadata' => $result['metadata'],
                'analysis_status' => 'completed',
            ]);

            $this->syncDetectedFieldsToDatabase($template, $result['fields']);

            return redirect()->route('admin.templates.editor', $template)
                ->with('success', 'Layout re-analysis completed! Fresh detections updated.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Re-analysis failed: ' . $e->getMessage());
        }
    }

    /**
     * Approve and publish template.
     */
    public function approve(Template $template)
    {
        $fields = $template->getNormalizedFields();
        if (empty($fields)) {
            return redirect()->back()->with('error', 'Cannot approve template without detected or configured fields.');
        }

        $template->update([
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        return redirect()->route('admin.templates.index')
            ->with('success', "Template '{$template->name}' is now approved and available to all event hosts!");
    }

    /**
     * Helper to sync normalized detected fields into template_fields records.
     */
    protected function syncDetectedFieldsToDatabase(Template $template, array $fields): void
    {
        $sort = 1;
        foreach ($fields as $key => $field) {
            TemplateField::updateOrCreate(
                [
                    'template_id' => $template->id,
                    'field_key' => $key,
                ],
                [
                    'field_label' => $field['label'] ?? ucwords(str_replace('_', ' ', $key)),
                    'field_type' => $field['type'] ?? 'text',
                    'is_required' => !empty($field['is_required']),
                    'sort_order' => $sort++,
                    'configuration' => $field,
                ]
            );
        }
    }
}

