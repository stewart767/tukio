<?php

namespace App\Services\Invitations;

use App\Models\Invitation;
use App\Models\Template;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View as ViewFacade;

class InvitationRenderer
{
    public function __construct(
        protected TemplateFieldResolver $fieldResolver,
        protected TemplateEngine $templateEngine
    ) {}

    /**
     * Render the digital web invitation for public viewing.
     */
    public function renderWeb(Invitation $invitation, array $overrides = []): View|Response
    {
        $template = $invitation->template ?? $invitation->event?->template;
        
        // If still no template, find default active template
        if (!$template) {
            $template = Template::where('is_active', true)->first();
        }

        $data = $this->fieldResolver->resolve(
            invitation: $invitation,
            guest: $invitation->guest,
            event: $invitation->event,
            template: $template,
            overrides: $overrides
        );

        return $this->renderTemplateView($template, $data, $invitation);
    }

    /**
     * Render template in preview mode with sample data.
     */
    public function renderPreview(Template $template, array $customOverrides = []): View|Response
    {
        $data = $this->fieldResolver->resolveSampleData($template);
        if (!empty($customOverrides)) {
            $data = array_merge($data, $customOverrides);
        }

        return $this->renderTemplateView($template, $data);
    }

    /**
     * Render and download PDF invitation document.
     */
    public function renderPdf(Invitation $invitation)
    {
        $template = $invitation->template ?? $invitation->event?->template;
        $data = $this->fieldResolver->resolve(
            invitation: $invitation,
            guest: $invitation->guest,
            event: $invitation->event,
            template: $template
        );

        // QR code base64 for dompdf embedding
        $qrPath = $invitation->qr_code_path ? storage_path('app/public/' . $invitation->qr_code_path) : null;
        $qrBase64 = null;
        if ($qrPath && File::exists($qrPath)) {
            $qrBase64 = 'data:image/png;base64,' . base64_encode(File::get($qrPath));
        }
        $data['qr_code_base64'] = $qrBase64;

        $pdfView = 'invitations.pdf.default';
        if (!ViewFacade::exists($pdfView)) {
            $pdfView = 'invitations.pdf-fallback';
        }

        $pdf = Pdf::loadView($pdfView, ['d' => $data, 'invitation' => $invitation])
            ->setPaper('a5', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        $filename = 'Invitation-' . \Illuminate\Support\Str::slug($data['guest_name'] ?: 'Guest') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Render and download sample PDF invitation document for preview.
     */
    public function renderSamplePdf(Template $template)
    {
        $data = $this->fieldResolver->resolveSampleData($template);
        $data['qr_code_base64'] = null;

        $pdfView = 'invitations.pdf.default';
        if (!ViewFacade::exists($pdfView)) {
            $pdfView = 'invitations.pdf-fallback';
        }

        $dummyInvitation = new Invitation([
            'id' => 9999,
            'slug' => 'sample-preview-' . $template->slug,
            'status' => 'pending',
            'rsvp_status' => 'pending',
            'plus_one_count' => 1,
        ]);

        $pdf = Pdf::loadView($pdfView, ['d' => $data, 'invitation' => $dummyInvitation])
            ->setPaper('a5', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        $filename = 'Sample-Invitation-' . \Illuminate\Support\Str::slug($template->name ?: 'Template') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Internal rendering coordinator between Blade template and database HTML.
     */
    protected function renderTemplateView(?Template $template, array $data, ?Invitation $invitation = null): View|Response
    {
        $viewPath = $template?->view_path;

        // 1. If template specifies a valid Blade view file, use it
        if ($viewPath && ViewFacade::exists($viewPath)) {
            return view($viewPath, [
                'd' => $data,
                'template' => $template,
                'invitation' => $invitation,
            ]);
        }

        // 2. Check if slug maps to an existing view in invitations.templates.{slug}.invitation
        if ($template?->slug) {
            $slugView = "invitations.templates.{$template->slug}.invitation";
            if (ViewFacade::exists($slugView)) {
                return view($slugView, [
                    'd' => $data,
                    'template' => $template,
                    'invitation' => $invitation,
                ]);
            }
        }

        // 3. Check fallback generic luxury template
        if (ViewFacade::exists('invitations.templates.luxury-gold.invitation')) {
            return view('invitations.templates.luxury-gold.invitation', [
                'd' => $data,
                'template' => $template,
                'invitation' => $invitation,
            ]);
        }

        // 4. Fallback to database dynamic HTML with safe placeholder replacement
        if ($template && !empty($template->html_content)) {
            $renderedHtml = $this->fieldResolver->replacePlaceholdersInHtml($template->html_content, $data);
            return response(view('invitations.generic-html', [
                'content' => $renderedHtml,
                'css' => $template->css_content,
                'd' => $data,
                'template' => $template,
            ])->render());
        }

        // 5. Ultimate fallback view
        return view('invitations.verify', [
            'invitation' => $invitation,
            'guest' => $invitation?->guest,
            'event' => $invitation?->event,
            'checkedIn' => (bool) optional($invitation?->guest)->attended,
        ]);
    }
}
