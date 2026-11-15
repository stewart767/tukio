<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppWebhookEvent extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_webhook_events';

    protected $fillable = [
        'waba_id',
        'phone_number_id',
        'event_type',
        'wamid',
        'payload',
        'is_processed',
        'processed_at',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'is_processed' => 'boolean',
            'processed_at' => 'datetime',
        ];
    }
}

