<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'brand',
        'model_year',
        'seating_capacity',
        'luggage_capacity',
        'transmission',
        'fuel_type',
        'color',
        'price_per_day',
        'price_per_hour',
        'has_driver',
        'has_fuel_included',
        'has_ribbon_decoration',
        'features',
        'image_url',
        'gallery',
        'description',
        'location',
        'is_available',
        'is_featured',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price_per_day' => 'decimal:2',
            'price_per_hour' => 'decimal:2',
            'has_driver' => 'boolean',
            'has_fuel_included' => 'boolean',
            'has_ribbon_decoration' => 'boolean',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'features' => 'array',
            'gallery' => 'array',
        ];
    }

    /**
     * Boot function to generate slug automatically.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vehicle) {
            if (empty($vehicle->slug)) {
                $vehicle->slug = Str::slug($vehicle->name) . '-' . Str::random(5);
            }
        });
    }

    /**
     * Vehicle bookings relation.
     */
    public function bookings()
    {
        return $this->hasMany(CarBooking::class);
    }

    /**
     * Scope for available vehicles.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope for featured vehicles.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        if (!empty($category) && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * Human-friendly category label.
     */
    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'luxury_bride_groom' => 'Luxury Bride & Groom',
            'guest_coaster_bus' => 'Guest Coasters & Buses',
            'vip_suv_convoy' => 'VIP SUV & Convoy',
            'host_logistics' => 'Host & Event Logistics',
            default => ucwords(str_replace('_', ' ', $this->category ?? 'Fleet')),
        };
    }

    /**
     * Category badge color styling.
     */
    public function getCategoryBadgeAttribute(): array
    {
        return match ($this->category) {
            'luxury_bride_groom' => [
                'bg' => 'bg-pink-50 text-pink-700 border-pink-200',
                'pill' => 'bg-gradient-to-r from-pink-500 to-rose-500 text-white',
                'icon' => '👰🤵',
                'label' => 'Bride & Groom Luxury',
            ],
            'guest_coaster_bus' => [
                'bg' => 'bg-amber-50 text-amber-700 border-amber-200',
                'pill' => 'bg-gradient-to-r from-amber-500 to-orange-500 text-white',
                'icon' => '🚌',
                'label' => 'Guest Coasters & Bus',
            ],
            'vip_suv_convoy' => [
                'bg' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                'pill' => 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white',
                'icon' => '👑',
                'label' => 'VIP Convoy SUV',
            ],
            default => [
                'bg' => 'bg-slate-50 text-slate-700 border-slate-200',
                'pill' => 'bg-slate-700 text-white',
                'icon' => '🚗',
                'label' => 'Event Transport',
            ],
        };
    }

    /**
     * Formatted price string.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'TZS ' . number_format($this->price_per_day, 0, '.', ',');
    }

    /**
     * Resolves the full URL for the vehicle photo, supporting local uploads, external URLs, and fallbacks.
     */
    public function getImageUrlFormattedAttribute(): string
    {
        if (empty($this->image_url)) {
            return 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
        }

        if (str_starts_with($this->image_url, 'http://') || str_starts_with($this->image_url, 'https://') || str_starts_with($this->image_url, '//')) {
            return $this->image_url;
        }

        return asset($this->image_url);
    }
}
