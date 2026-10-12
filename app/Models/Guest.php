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
    ];

    protected function casts(): array
    {
        return [
            'attended' => 'boolean',
            'attended_at' => 'datetime',
        ];
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
}
