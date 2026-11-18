<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Template;
use App\Models\TemplateField;
use App\Services\Invitations\InvitationRenderer;
use App\Services\Invitations\TemplateAiDetectionService;
use App\Services\Invitations\TemplateEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CardTemplateController extends Controller
{
    public function __construct(
        protected TemplateEngine $templateEngine,
        protected InvitationRenderer $invitationRenderer,
        protected TemplateAiDetectionService $aiDetectionService
    ) {}

    /**
     * Display a listing of custom card templates created by this host
     * along with available platform templates.
     */
    public function index()
    {
        $host = Auth::guard('host')->user();
        $event = Event::where('host_id', $host->id)->first();

        // Host's custom uploaded card templates
        $myTemplates = Template::where('host_id', $host->id)
            ->with('fields')
            ->latest()
            ->get();

        // Standard/premium catalog templates for inspiration or fallback
        $catalogTemplates = Template::where('is_active', true)
            ->whereNull('host_id')
            ->with('fields')
            ->latest()
            ->take(12)
            ->get();

        return view('host.card-templates.index', compact('myTemplates', 'catalogTemplates', 'event'));
    }

    /**
     * Show upload artwork interface for creating a custom card template.
     */
    public function create()
    {
        $host = Auth::guard('host')->user();
        $event = Event::where('host_id', $host->id)->first();

        return view('host.card-templates.create', compact('event', 'host'));
    }

    /**
     * Upload host card artwork, perform AI layout detection, and initialize template.
     */
    public function upload(Request $request)
    {
        $host = Auth::guard('host')->user();
        $event = Event::where('host_id', $host->id)->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'artwork' => 'required|file|mimes:jpeg,png,jpg,webp|max:15360',
        ]);

        $file = $request->file('artwork');

        // Verify valid image dimensions
        $imageInfo = @getimagesize($file->getRealPath());
        if (!$imageInfo) {
            return back()->withInput()->with('error', 'The uploaded file is not a valid or readable image.');
        }

        $width = (int) $imageInfo[0];
        $height = (int) $imageInfo[1];
        if ($width < 400 || $height < 400) {
            return back()->withInput()->with('error', "Image dimensions ({$width}x{$height}px) are too small. Minimum 400x400px required for sharp cards.");
        }

        // Store artwork and thumbnail preview
        $artworkPath = $file->store('templates/artwork', 'public');
        $previewPath = $file->store('templates/previews', 'public');

        $slug = Str::slug($request->name);
        $baseSlug = $slug;
        $counter = 1;
        while (Template::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $category = $request->category ?: ($event?->category ?: ($host->event_type ?: 'wedding'));

        $template = Template::create([
            'name' => $request->name,
            'slug' => $slug,
            'category' => $category,
            'template_type' => 'custom',
            'price' => 0.00,
            'currency' => 'TZS',
            'is_paid' => false,
            'version' => '1.0',
            'description' => $request->description ?: "Custom card design for {$request->name}",
            'preview_image' => $previewPath,
            'artwork_path' => $artworkPath,
            'artwork_width' => $width,
            'artwork_height' => $height,
            'analysis_status' => 'analyzing',
            'approval_status' => 'approved', // Host custom templates are approved for their own use
            'is_active' => true,
            'host_id' => $host->id,
            'created_by' => null,
            'features' => $this->templateEngine->getDefaultFeatures(),
            'html_content' => "<!-- Host Custom Template {$slug} -->",
            'view_path' => 'invitations.templates.artwork-overlay.invitation',
        ]);

        try {
            // Run AI layout detection to locate text lines and recommended QR position
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

            // Sync detected fields to template_fields table
            $this->syncDetectedFieldsToDatabase($template, $analysisResult['fields']);

            return redirect()->route('host.card-templates.editor', $template)
                ->with('success', 'Artwork uploaded and analyzed successfully! Drag and resize the text boxes or QR pass to match your card layout.');
        } catch (\Throwable $e) {
            Log::error('[Host Card Template] AI detection failed', [
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

            return redirect()->route('host.card-templates.editor', $template)
                ->with('warning', 'Card uploaded successfully. Automated AI detection encountered a warning, but you can position your text zones manually.');
        }
    }

    /**
     * Show interactive visual card editor for the host.
     */
    public function editor(Template $template)
    {
        $host = Auth::guard('host')->user();

        // Enforce ownership: host can only edit their own templates
        if ($template->host_id !== $host->id) {
            abort(403, 'Unauthorized access to this card template.');
        }

        $template->load('fields');

        // Retrieve normalized field zones
        $fields = $template->getNormalizedFields();
        if (empty($fields)) {
            try {
                $analysis = $this->aiDetectionService->analyze($template);
                $fields = $analysis['fields'];
                $template->update([
                    'placeholder_config' => ['fields' => $fields],
                    'analysis_status' => 'completed',
                ]);
            } catch (\Throwable $e) {
                $fields = [
                    'guest_name' => [
                        'label' => 'Mgeni Rasmi (Guest Name)',
                        'type' => 'guest_name',
                        'x' => 0.15,
                        'y' => 0.45,
                        'width' => 0.70,
                        'height' => 0.08,
                        'font_family' => 'Playfair Display',
                        'font_size' => 24,
                        'color' => '#1e1b4b',
                        'alignment' => 'center',
                        'is_editable' => true,
                    ],
                    'qr_code' => [
                        'label' => 'QR Pass Code',
                        'type' => 'qr',
                        'x' => 0.76,
                        'y' => 0.78,
                        'width' => 0.16,
                        'height' => 0.16,
                        'confidence' => 1.0,
                    ],
                ];
            }
        }

        $sampleNames = [
            'short' => 'Anna Kim',
            'standard' => 'Hon. Josephat M. Mwita',
            'long' => 'Dr. & Mrs. Emmanuel Christopher Mwakipesile',
        ];

        $event = Event::where('host_id', $host->id)->first();

        return view('host.card-templates.editor', compact('template', 'fields', 'sampleNames', 'event'));
    }

    /**
     * Update bounding boxes, coordinates, and styling properties from the visual editor.
     */
    public function updateEditorFields(Request $request, Template $template)
    {
        $host = Auth::guard('host')->user();

        if ($template->host_id !== $host->id) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized access to this card template.');
        }

        $request->validate([
            'fields' => 'required|array',
        ]);

        $sanitized = $this->aiDetectionService->sanitizeAndNormalizeFields($request->fields);

        // Preserve QR code position if submitted
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

        $template->update([
            'placeholder_config' => [
                'fields' => $sanitized,
                'artwork_width' => $template->artwork_width,
                'artwork_height' => $template->artwork_height,
                'updated_at' => now()->toIso8601String(),
            ],
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        // Sync with template_fields table
        $this->syncDetectedFieldsToDatabase($template, $sanitized);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Card template coordinates and styling saved successfully!',
                'fields' => $sanitized,
            ]);
        }

        return redirect()->back()->with('success', 'Card template coordinates and styling saved successfully!');
    }

    /**
     * Re-run layout analysis on existing template.
     */
    public function reanalyze(Template $template)
    {
        $host = Auth::guard('host')->user();

        if ($template->host_id !== $host->id) {
            abort(403, 'Unauthorized access to this card template.');
        }

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

            return redirect()->route('host.card-templates.editor', $template)
                ->with('success', 'Layout re-analysis completed! Fresh detections updated.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Re-analysis failed: ' . $e->getMessage());
        }
    }

    /**
     * Live preview room for host's card template with realistic dummy event data.
     */
    public function preview(Template $template)
    {
        $host = Auth::guard('host')->user();

        if ($template->host_id !== $host->id && !$template->is_active) {
            abort(403, 'Unauthorized access to this card template.');
        }

        $event = Event::where('host_id', $host->id)->first();
        $overrides = [];
        if ($event) {
            $overrides = [
                'event_title' => $event->title,
                'event_date_formatted' => $event->date ? $event->date->format('l, F j, Y') : null,
                'venue_name' => $event->venue,
                'venue_address' => $event->address,
            ];
        }

        return $this->invitationRenderer->renderPreview($template, $overrides);
    }

    /**
     * Delete custom card template.
     */
    public function destroy(Template $template)
    {
        $host = Auth::guard('host')->user();

        if ($template->host_id !== $host->id) {
            abort(403, 'Unauthorized access to this card template.');
        }

        if ($template->artwork_path) {
            Storage::disk('public')->delete($template->artwork_path);
        }
        if ($template->preview_image) {
            Storage::disk('public')->delete($template->preview_image);
        }

        $template->delete();

        return redirect()->route('host.card-templates.index')->with('success', "Card template '{$template->name}' deleted successfully.");
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

