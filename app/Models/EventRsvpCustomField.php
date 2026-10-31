<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRsvpCustomField extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_website_id',
        'field_key',
        'field_label',
        'field_type',
        'options',
        'is_required',
        'is_enabled',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'is_required' => 'boolean',
            'is_enabled' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Relationship: The website this custom field belongs to.
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(EventWebsite::class, 'event_website_id');
    }
}

