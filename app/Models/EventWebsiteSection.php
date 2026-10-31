<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventWebsiteSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_website_id',
        'section_key',
        'title',
        'subtitle',
        'is_enabled',
        'sort_order',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'sort_order' => 'integer',
            'content' => 'array',
        ];
    }

    /**
     * Relationship: The website this section belongs to.
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(EventWebsite::class, 'event_website_id');
    }
}

