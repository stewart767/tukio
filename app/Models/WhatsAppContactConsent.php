<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppContactConsent extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_contact_consents';

    protected $fillable = [
        'host_id',
        'phone',
        'status',
        'opted_in_at',
        'opted_out_at',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'opted_in_at' => 'datetime',
            'opted_out_at' => 'datetime',
        ];
    }

    /**
     * Get the host/tenant owning this consent record.
     */
    public function host(): BelongsTo
    {
        return $this->belongsTo(Host::class);
    }

    /**
     * Check if contact is opted in.
     */
    public function isOptedIn(): bool
    {
        return $this->status === 'opted_in';
    }

    public function getPhoneNumberAttribute(): ?string
    {
        return $this->phone;
    }

    public function getHasOptedOutAttribute(): bool
    {
        return $this->status === 'opted_out';
    }

    public function getOptInSourceAttribute(): ?string
    {
        return $this->source;
    }
}

