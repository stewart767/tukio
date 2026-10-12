<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'template_type',
        'price',
        'currency',
        'is_paid',
        'version',
        'features',
        'description',
        'html_content',
        'css_content',
        'view_path',
        'assets_path',
        'preview_image',
        'is_active',
        'created_by',
        'host_id',
        'placeholder_config',
        'artwork_path',
        'artwork_width',
        'artwork_height',
        'analysis_status',
        'approval_status',
        'analysis_metadata',
    ];

    protected static function booted(): void
    {
        static::creating(function (Template $template) {
            if (empty($template->slug)) {
                $baseSlug = Str::slug($template->name);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }
                $template->slug = $slug;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_paid' => 'boolean',
            'price' => 'decimal:2',
            'features' => 'array',
            'placeholder_config' => 'array',
            'analysis_metadata' => 'array',
            'artwork_width' => 'integer',
            'artwork_height' => 'integer',
        ];
    }

    /**
     * Get the fields associated with this template.
     */
    public function fields()
    {
        return $this->hasMany(TemplateField::class)->orderBy('sort_order', 'asc');
    }

    /**
     * Get the events using this template.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the invitations created with this template.
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * Get the user who created the template.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the host assigned to this custom template (if any).
     */
    public function host()
    {
        return $this->belongsTo(Host::class, 'host_id');
    }

    /**
     * Check if the template requires payment.
     */
    public function isPaid(): bool
    {
        return (bool) $this->is_paid || (float) $this->price > 0;
    }

    /**
     * Check if template is custom.
     */
    public function isCustom(): bool
    {
        return $this->template_type === 'custom';
    }

    /**
     * Check if template is premium.
     */
    public function isPremium(): bool
    {
        return $this->template_type === 'premium';
    }

    /**
     * Get formatted price label.
     */
    public function getFormattedPriceAttribute(): string
    {
        if (!$this->isPaid()) {
            return 'Free';
        }
        $currency = $this->currency ?: 'TZS';
        return $currency . ' ' . number_format((float) $this->price, 0);
    }

    /**
     * Get badge color class for template type.
     */
    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->template_type) {
            'premium' => 'bg-amber-100 text-amber-800 border-amber-200',
            'custom' => 'bg-purple-100 text-purple-800 border-purple-200',
            default => 'bg-emerald-100 text-emerald-800 border-emerald-200',
        };
    }

    /**
     * Get default features if empty.
     */
    public function getFeaturesListAttribute(): array
    {
        if (!empty($this->features) && is_array($this->features)) {
            return $this->features;
        }

        return [
            'Digital Web Invitation',
            'Dynamic Guest Name',
            'RSVP System',
            'Live Countdown Timer',
            'Personalized QR Pass',
            'Google Maps Location',
            'Photo Gallery',
            'PDF Download',
            'WhatsApp Sharing',
        ];
    }

    /**
     * Check if template was generated via AI artwork layout detection.
     */
    public function isAiDetected(): bool
    {
        return !empty($this->artwork_path) || ($this->analysis_status === 'completed');
    }

    /**
     * Check if administrator has approved this template.
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Get URL to the high-resolution artwork image.
     */
    public function getArtworkUrlAttribute(): ?string
    {
        if ($this->artwork_path) {
            return asset('storage/' . $this->artwork_path);
        }
        if ($this->preview_image) {
            return asset('storage/' . $this->preview_image);
        }
        return null;
    }

    /**
     * Get calculated aspect ratio (width / height) or default to 0.707 (A5 portrait).
     */
    public function getAspectRatioAttribute(): float
    {
        if ($this->artwork_width && $this->artwork_height && $this->artwork_height > 0) {
            return round($this->artwork_width / $this->artwork_height, 4);
        }
        return 0.707;
    }

    /**
     * Get normalized fields dictionary from placeholder_config.
     */
    public function getNormalizedFields(): array
    {
        $config = $this->placeholder_config ?? [];
        if (!is_array($config)) {
            $config = json_decode($config, true) ?: [];
        }

        // Return fields mapping if structured with normalized coordinates
        if (isset($config['fields']) && is_array($config['fields'])) {
            return $config['fields'];
        }

        // Direct associative array of field_key => field_spec
        $fields = [];
        foreach ($config as $key => $val) {
            if (is_array($val) && isset($val['x'], $val['y'])) {
                $fields[$key] = $val;
            }
        }
        return $fields;
    }
}

