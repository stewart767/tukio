<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityBlockedIp extends Model
{
    use HasFactory;

    protected $table = 'security_blocked_ips';

    protected $fillable = [
        'ip_address',
        'reason',
        'threat_count',
        'blocked_until',
        'is_permanent',
        'incident_id',
        'blocked_by',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'blocked_until' => 'datetime',
            'is_permanent' => 'boolean',
            'threat_count' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function incident(): BelongsTo
    {
        return $this->belongsTo(SecurityIncident::class, 'incident_id');
    }

    public function isPermanent(): bool
    {
        return (bool) $this->is_permanent || $this->blocked_until === null;
    }

    public function getExpiresAtAttribute(): mixed
    {
        return $this->blocked_until;
    }

    public function isCurrentlyBlocked(): bool
    {
        if ($this->isPermanent()) {
            return true;
        }

        return $this->blocked_until !== null && $this->blocked_until->isFuture();
    }
}

