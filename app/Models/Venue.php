<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'location',
        'capacity',
        'badge',
        'description',
        'amenities',
        'image_url',
        'price_range',
        'inquiry_button_text',
        'inquiry_subject',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'amenities' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Resolves the full URL for the venue photo, supporting local uploads, external URLs, and fallbacks.
     */
    public function getImageUrlFormattedAttribute(): string
    {
        if (empty($this->image_url)) {
            return 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
        }

        if (str_starts_with($this->image_url, 'http://') || str_starts_with($this->image_url, 'https://') || str_starts_with($this->image_url, '//')) {
            return $this->image_url;
        }

        return asset($this->image_url);
    }
}
