<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class EventRsvp extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_website_id',
        'event_id',
        'guest_id',
        'full_name',
        'phone',
        'email',
        'attendance_status',
        'plus_one_count',
        'accompanying_guest_names',
        'dietary_requirements',
        'dietary_details',
        'arrival_departure_info',
        'special_requests',
        'custom_answers',
        'confirmation_code',
        'qr_code_path',
        'is_checked_in',
        'checked_in_at',
        'invitation_sent_at',
        'invitation_channel',
        'ip_address',
        'user_agent',
    ];

    protected static function booted(): void
    {
        static::creating(function (EventRsvp $rsvp) {
            if (empty($rsvp->confirmation_code)) {
                $rsvp->confirmation_code = static::generateUniqueCode($rsvp->event_id);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'plus_one_count' => 'integer',
            'is_checked_in' => 'boolean',
            'checked_in_at' => 'datetime',
            'invitation_sent_at' => 'datetime',
            'accompanying_guest_names' => 'array',
            'custom_answers' => 'array',
        ];
    }

    /**
     * Relationship: The website this RSVP belongs to.
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(EventWebsite::class, 'event_website_id');
    }

    /**
     * Relationship: The event this RSVP belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relationship: Linked Guest record.
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function isAttending(): bool
    {
        return $this->attendance_status === 'attending';
    }

    public function isDeclined(): bool
    {
        return $this->attendance_status === 'declined';
    }

    public function isTentative(): bool
    {
        return $this->attendance_status === 'tentative';
    }

    public function getTotalPartySizeAttribute(): int
    {
        if (!$this->isAttending()) {
            return 0;
        }

        return 1 + max(0, (int) $this->plus_one_count);
    }

    public static function generateUniqueCode(?int $eventId = null): string
    {
        do {
            $code = 'RSVP-' . strtoupper(Str::random(6));
        } while (static::where('confirmation_code', $code)->exists());

        return $code;
    }
}
