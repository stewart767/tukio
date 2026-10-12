<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'badge',
        'tagline',
        'icon',
        'description',
        'tags',
        'image_url',
        'button_text',
        'button_url',
        'theme_color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the theme color configuration or CSS classes.
     */
    public function getThemeClassesAttribute(): array
    {
        return match ($this->theme_color) {
            'purple' => [
                'badge_bg' => 'bg-white/90 text-purple-800',
                'border_hover' => 'hover:border-purple-400/80',
                'tag_bg' => 'bg-purple-50 text-purple-700',
                'dot' => 'bg-pink-400',
                'primary' => 'text-purple-600',
            ],
            'blue' => [
                'badge_bg' => 'bg-white/90 text-blue-800',
                'border_hover' => 'hover:border-blue-400/80',
                'tag_bg' => 'bg-blue-50 text-blue-700',
                'dot' => 'bg-blue-400',
                'primary' => 'text-blue-600',
            ],
            'pink' => [
                'badge_bg' => 'bg-white/90 text-pink-800',
                'border_hover' => 'hover:border-pink-400/80',
                'tag_bg' => 'bg-pink-50 text-pink-700',
                'dot' => 'bg-rose-400',
                'primary' => 'text-pink-600',
            ],
            'teal' => [
                'badge_bg' => 'bg-white/90 text-teal-800',
                'border_hover' => 'hover:border-teal-400/80',
                'tag_bg' => 'bg-teal-50 text-teal-700',
                'dot' => 'bg-teal-400',
                'primary' => 'text-teal-600',
            ],
            'amber' => [
                'badge_bg' => 'bg-white/90 text-amber-800',
                'border_hover' => 'hover:border-amber-400/80',
                'tag_bg' => 'bg-amber-50 text-amber-700',
                'dot' => 'bg-amber-400',
                'primary' => 'text-amber-600',
            ],
            'violet' => [
                'badge_bg' => 'bg-white/90 text-violet-800',
                'border_hover' => 'hover:border-violet-400/80',
                'tag_bg' => 'bg-violet-50 text-violet-700',
                'dot' => 'bg-violet-400',
                'primary' => 'text-violet-600',
            ],
            default => [
                'badge_bg' => 'bg-white/90 text-indigo-800',
                'border_hover' => 'hover:border-indigo-400/80',
                'tag_bg' => 'bg-indigo-50 text-indigo-700',
                'dot' => 'bg-indigo-400',
                'primary' => 'text-indigo-600',
            ],
        };
    }

    /**
     * Resolves the full URL for the milestone banner, supporting local uploads, external URLs, and fallbacks.
     */
    public function getImageUrlFormattedAttribute(): string
    {
        if (empty($this->image_url)) {
            return asset('media/categories/wedding.jpg');
        }

        if (str_starts_with($this->image_url, 'http://') || str_starts_with($this->image_url, 'https://') || str_starts_with($this->image_url, '//')) {
            return $this->image_url;
        }

        return asset($this->image_url);
    }
}
