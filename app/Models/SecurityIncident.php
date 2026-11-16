<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityIncident extends Model
{
    use HasFactory;

    protected $table = 'security_incidents';

    protected $fillable = [
        'incident_uuid',
        'threat_type',
        'severity',
        'confidence_score',
        'source_ip',
        'user_agent',
        'request_method',
        'request_url',
        'payload_sample',
        'detected_rule',
        'target_guard',
        'user_id',
        'host_id',
        'containment_action',
        'status',
        'resolved_by',
        'resolved_at',
        'investigation_notes',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'confidence_score' => 'integer',
            'metadata' => 'array',
            'resolved_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (SecurityIncident $incident) {
            if (empty($incident->incident_uuid)) {
                $incident->incident_uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function getRequestPathAttribute(): string
    {
        return (string) ($this->request_url ?? '/');
    }

    public function getMatchedRuleAttribute(): ?string
    {
        return $this->detected_rule;
    }

    public function getHttpMethodAttribute(): string
    {
        return $this->request_method ?? 'GET';
    }

    public function getPayloadAttribute(): mixed
    {
        if (empty($this->payload_sample)) {
            return null;
        }

        $decoded = json_decode($this->payload_sample, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : ['raw' => $this->payload_sample];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(Host::class);
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function isCritical(): bool
    {
        return $this->severity === 'critical';
    }

    public function isHigh(): bool
    {
        return $this->severity === 'high';
    }
}

