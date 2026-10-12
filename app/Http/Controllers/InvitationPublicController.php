<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Guest;
use App\Services\Invitations\InvitationRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvitationPublicController extends Controller
{
    public function __construct(
        protected InvitationRenderer $invitationRenderer
    ) {}

    /**
     * Show digital invitation.
     */
    public function show(string $slug)
    {
        $invitation = Invitation::with(['guest', 'event.host', 'event.template', 'template'])
            ->where('slug', $slug)
            ->first();

        if (!$invitation) {
            // Check if guest invitation_code matches slug
            $guest = Guest::with(['event.host', 'event.template', 'invitation.template'])
                ->where('invitation_code', $slug)
                ->first();

            if ($guest && $guest->invitation) {
                $invitation = $guest->invitation;
            } elseif ($guest && $guest->event) {
                // Create or resolve invitation for guest
                $invitation = Invitation::firstOrCreate(
                    [
                        'event_id' => $guest->event_id,
                        'guest_id' => $guest->id,
                    ],
                    [
                        'slug' => (string) \Illuminate\Support\Str::uuid(),
                        'template_id' => $guest->event->template_id,
                        'status' => 'sent',
                    ]
                );
            }
        }

        if (!$invitation) {
            // Check if it's a sample preview link e.g. sample-preview-{template-slug}
            if (str_starts_with($slug, 'sample-') || str_starts_with($slug, 'preview-')) {
                $templateSlug = str_starts_with($slug, 'sample-preview-')
                    ? substr($slug, strlen('sample-preview-'))
                    : preg_replace('/^(sample|preview)-/', '', $slug);

                $template = \App\Models\Template::where('slug', $templateSlug)->first()
                    ?? \App\Models\Template::where('is_active', true)->first();

                if ($template) {
                    return $this->invitationRenderer->renderPreview($template);
                }
            }

            abort(404, 'Invitation not found or invalid link.');
        }

        // Mark as opened if first time
        if (!$invitation->opened_at) {
            $invitation->update(['opened_at' => now(), 'status' => 'opened']);
        }

        return $this->invitationRenderer->renderWeb($invitation);
    }

    /**
     * Process Guest RSVP submission.
     */
    public function rsvp(Request $request, string $slug)
    {
        $request->validate([
            'rsvp_status' => 'required|in:attending,declined,tentative',
            'plus_one_count' => 'nullable|integer|min:0|max:10',
            'rsvp_notes' => 'nullable|string|max:1000',
        ]);

        // Support preview / sample mode without requiring database record
        if (str_starts_with($slug, 'sample-') || str_starts_with($slug, 'preview-') || $request->header('X-Preview-Mode')) {
            $message = match ($request->rsvp_status) {
                'attending' => 'Hali ya majaribio (Preview): Asante sana! Uthibitisho wako wa kuhudhuria umerekodiwa kikamilifu.',
                'declined' => 'Hali ya majaribio (Preview): Asante kwa kutoa taarifa ya kutohudhuria.',
                default => 'Hali ya majaribio (Preview): Taarifa yako ya RSVP imehifadhiwa kikamilifu.'
            };

            return response()->json([
                'success' => true,
                'message' => $message,
                'rsvp_status' => $request->rsvp_status,
                'rsvp_at' => now()->format('M d, Y H:i'),
                'is_preview' => true,
            ]);
        }

        $invitation = Invitation::with('guest')->where('slug', $slug)->first();

        if (!$invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Kadi ya mualiko haikupatikana au kiungo si sahihi.',
            ], 404);
        }

        // Update Invitation record
        $invitation->update([
            'rsvp_status' => $request->rsvp_status,
            'rsvp_at' => now(),
            'plus_one_count' => (int) ($request->plus_one_count ?? 0),
            'rsvp_notes' => $request->rsvp_notes,
        ]);

        $message = match ($request->rsvp_status) {
            'attending' => 'Asante sana! Tumepokea uthibitisho wako kuwa utajumuika nasi.',
            'declined' => 'Asante kwa kutoa taarifa. Tunashukuru kwa pongezi na salamu zako.',
            default => 'Taarifa yako ya RSVP imehifadhiwa kikamilifu.'
        };

        return response()->json([
            'success' => true,
            'message' => $message,
            'rsvp_status' => $invitation->rsvp_status,
            'rsvp_at' => $invitation->rsvp_at->format('M d, Y H:i'),
        ]);
    }

    /**
     * Download PDF invitation document.
     */
    public function pdf(string $slug)
    {
        // Support sample / preview PDF download
        if (str_starts_with($slug, 'sample-') || str_starts_with($slug, 'preview-')) {
            $templateSlug = str_starts_with($slug, 'sample-preview-')
                ? substr($slug, strlen('sample-preview-'))
                : preg_replace('/^(sample|preview)-/', '', $slug);

            $template = \App\Models\Template::where('slug', $templateSlug)->first()
                ?? \App\Models\Template::where('is_active', true)->first();

            if ($template) {
                return $this->invitationRenderer->renderSamplePdf($template);
            }
        }

        $invitation = Invitation::with(['guest', 'event', 'template'])
            ->where('slug', $slug)
            ->first();

        if (!$invitation) {
            abort(404, 'Invitation not found or invalid link.');
        }

        return $this->invitationRenderer->renderPdf($invitation);
    }
}
