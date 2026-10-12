<?php

namespace App\Jobs;

use App\Models\Invitation;
use App\Services\InvitationQrService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateInvitationQrCode implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $invitationId)
    {
    }

    public function handle(InvitationQrService $invitationQrService): void
    {
        $invitation = Invitation::find($this->invitationId);

        if (!$invitation) {
            return;
        }

        $invitationQrService->ensureQrCode($invitation);
    }
}
