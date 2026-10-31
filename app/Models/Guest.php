<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'full_name',
        'guest_type',
        'phone',
        'status',
        'attended',
        'attended_at',
        'qr_code',
        'invitation_code',
        'pin_code',
        'table_number',
        'pax',
        'pax_checked_in',
        'amount_pledged',
        'amount_paid',
        'payment_method',
        'payment_status',
        'payment_reference',
        'rsvp_status',
        'category',
        'checked_in_by',
    ];

    protected function casts(): array
    {
        return [
            'attended' => 'boolean',
            'attended_at' => 'datetime',
            'pax' => 'integer',
            'pax_checked_in' => 'integer',
            'amount_pledged' => 'decimal:2',
            'amount_paid' => 'decimal:2',
        ];
    }

    /**
     * Generate a unique 4-digit numeric PIN for simple SMS / kitochi check-in.
     */
    public static function generatePinCode(int $eventId): string
    {
        do {
            $pin = str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
        } while (static::where('event_id', $eventId)->where('pin_code', $pin)->exists());

        return $pin;
    }

    /**
     * Check if guest is fully checked in (all pax checked in).
     */
    public function isFullyCheckedIn(): bool
    {
        return $this->attended && ($this->pax_checked_in >= max(1, (int) $this->pax));
    }

    /**
     * Get remaining pax available to check in.
     */
    public function remainingPax(): int
    {
        $total = max(1, (int) $this->pax);
        $checked = (int) $this->pax_checked_in;
        return max(0, $total - $checked);
    }

    /**
     * Get the event that owns the guest.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the invitation for the guest.
     */
    public function invitation()
    {
        return $this->hasOne(Invitation::class);
    }

    /**
     * Get the RSVP record for the guest.
     */
    public function rsvp()
    {
        return $this->hasOne(EventRsvp::class);
    }
}
