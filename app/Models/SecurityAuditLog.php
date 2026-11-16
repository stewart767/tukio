<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityAuditLog extends Model
{
    use HasFactory;

    protected $table = 'security_audit_logs';

    protected $fillable = [
        'actor_type',
        'actor_id',
        'action',
        'resource_type',
        'resource_id',
        'ip_address',
        'user_agent',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
        ];
    }

    public static function record(string $action, array $details = [], ?int $userId = null, ?string $ip = null): self
    {
        return self::create([
            'actor_type' => $userId ? 'user' : 'system',
            'actor_id' => $userId,
            'action' => $action,
            'ip_address' => $ip ?? (request()->ip() ?? '127.0.0.1'),
            'user_agent' => request()->userAgent(),
            'details' => $details,
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}

