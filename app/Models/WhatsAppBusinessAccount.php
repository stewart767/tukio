<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WhatsAppBusinessAccount extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_business_accounts';

    protected $fillable = [
        'host_id',
        'waba_id',
        'account_name',
        'currency',
        'timezone_id',
        'message_template_namespace',
        'access_token',
        'token_type',
        'token_expires_at',
        'status',
        'is_webhook_subscribed',
        'metadata',
    ];

    protected $hidden = [
        'access_token',
    ];

    protected function casts(): array
    {
        return [
            'access_token' => 'encrypted',
            'token_expires_at' => 'datetime',
            'is_webhook_subscribed' => 'boolean',
            'metadata' => 'array',
        ];
    }

    /**
     * Get the host/tenant owning this WABA.
     */
    public function host(): BelongsTo
    {
        return $this->belongsTo(Host::class);
    }

    /**
     * Get all phone numbers under this WABA.
     */
    public function phoneNumbers(): HasMany
    {
        return $this->hasMany(WhatsAppPhoneNumber::class, 'whatsapp_business_account_id');
    }

    /**
     * Get the primary/default phone number under this WABA.
     */
    public function defaultPhoneNumber(): HasOne
    {
        return $this->hasOne(WhatsAppPhoneNumber::class, 'whatsapp_business_account_id')
            ->where('is_default', true);
    }

    /**
     * Get all synchronized templates for this WABA.
     */
    public function templates(): HasMany
    {
        return $this->hasMany(WhatsAppMessageTemplate::class, 'whatsapp_business_account_id');
    }

    public function messageTemplates(): HasMany
    {
        return $this->templates();
    }

    public function getNameAttribute(): ?string
    {
        return $this->account_name;
    }

    /**
     * Check if account is actively connected.
     */
    public function isConnected(): bool
    {
        return $this->status === 'active' && !empty($this->access_token);
    }
}
