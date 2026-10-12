<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTypeConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'fields_config',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'fields_config' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the field configuration for a specific event type
     */
    public static function getFieldsForType($eventType)
    {
        $config = self::where('event_type', $eventType)->where('is_active', true)->first();
        return $config ? $config->fields_config : [];
    }

    /**
     * Get all available event types with their configurations
     */
    public static function getAllEventTypes()
    {
        return self::where('is_active', true)->get()->keyBy('event_type');
    }
}
