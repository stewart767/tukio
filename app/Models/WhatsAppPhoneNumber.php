<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsAppPhoneNumber extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_phone_numbers';

    protected $fillable = [
        'whatsapp_business_account_id',
        'host_id',
        'phone_number_id',
        'display_phone_number',
        'verified_name',
        'quality_rating',
        'code_verification_status',
        'is_default',
        'status',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'metadata' => 'array',
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
     * Get the host/tenant owning this phone number.
     */
    public function host(): BelongsTo
    {
        return $this->belongsTo(Host::class);
    }

    /**
     * Get messages sent/received via this phone number.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(WhatsAppMessage::class, 'whatsapp_phone_number_id');
    }

    /**
     * Check if the phone number is verified and ready for live messaging.
     */
    public function isVerified(): bool
    {
        return strtoupper($this->code_verification_status) === 'VERIFIED';
    }
}

