<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'guest_id',
        'slug',
        'qr_code_path',
        'template_id',
        'template_version',
        'type',
        'status',
        'rsvp_status',
        'rsvp_at',
        'rsvp_notes',
        'plus_one_count',
        'custom_data',
        'sent_at',
        'delivered_at',
        'opened_at',
        'sms_code',
        'whatsapp_message',
        'whatsapp_message_id',
        'whatsapp_media_id',
        'whatsapp_sent_at',
        'whatsapp_delivered_at',
        'whatsapp_read_at',
        'whatsapp_error_message',
        'qr_code_data',
    ];

    protected static function booted(): void
    {
        static::creating(function (Invitation $invitation) {
            if (empty($invitation->slug)) {
                $invitation->slug = (string) Str::uuid();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'delivered_at' => 'datetime',
            'opened_at' => 'datetime',
            'rsvp_at' => 'datetime',
            'whatsapp_sent_at' => 'datetime',
            'whatsapp_delivered_at' => 'datetime',
            'whatsapp_read_at' => 'datetime',
            'custom_data' => 'array',
            'plus_one_count' => 'integer',
        ];
    }

    /**
     * Get the event that owns the invitation.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the guest for the invitation.
     */
    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    /**
     * Get the template for the invitation.
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Check if RSVP is attending.
     */
    public function isRsvpAttending(): bool
    {
        return $this->rsvp_status === 'attending';
    }

    /**
     * Check if RSVP is declined.
     */
    public function isRsvpDeclined(): bool
    {
        return $this->rsvp_status === 'declined';
    }

    /**
     * Get public verification/web URL.
     */
    public function getUrlAttribute(): string
    {
        return route('invitation.show', $this->slug);
    }
}
