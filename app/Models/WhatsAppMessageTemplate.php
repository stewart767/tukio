<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppMessageTemplate extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_message_templates';

    protected $fillable = [
        'whatsapp_business_account_id',
        'host_id',
        'meta_template_id',
        'name',
        'language',
        'category',
        'status',
        'components',
        'rejected_reason',
    ];

    protected function casts(): array
    {
        return [
            'components' => 'array',
        ];
    }

    /**
     * Get the associated WhatsApp Business Account.
     */
    public function businessAccount(): BelongsTo
    {
        return $this->belongsTo(WhatsAppBusinessAccount::class, 'whatsapp_business_account_id');
    }

    /**
     * Get the host/tenant owning this template.
     */
    public function host(): BelongsTo
    {
        return $this->belongsTo(Host::class);
    }

    /**
     * Check if template is approved by Meta for sending.
     */
    public function isApproved(): bool
    {
        return strtoupper($this->status) === 'APPROVED';
    }
}

