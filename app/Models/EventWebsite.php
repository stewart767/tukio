<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class EventWebsite extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'slug',
        'title',
        'category',
        'tagline',
        'host_names',
        'event_date',
        'event_time',
        'venue_name',
        'venue_address',
        'google_maps_url',
        'google_maps_embed',
        'cover_image',
        'cover_video_url',
        'music_url',
        'music_title',
        'theme',
        'primary_color',
        'secondary_color',
        'accent_color',
        'background_color',
        'text_color',
        'font_heading',
        'font_body',
        'countdown_style',
        'rsvp_deadline',
        'max_plus_ones',
        'allow_guest_updates',
        'require_phone',
        'require_email',
        'is_published',
        'meta_description',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'rsvp_deadline' => 'datetime',
            'allow_guest_updates' => 'boolean',
            'require_phone' => 'boolean',
            'require_email' => 'boolean',
            'is_published' => 'boolean',
            'max_plus_ones' => 'integer',
            'settings' => 'array',
        ];
    }

    /**
     * Relationship: The event this digital website belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relationship: Reorderable sections.
     */
    public function sections(): HasMany
    {
        return $this->hasMany(EventWebsiteSection::class)->orderBy('sort_order', 'asc');
    }

    /**
     * Relationship: RSVPs submitted for this website.
     */
    public function rsvps(): HasMany
    {
        return $this->hasMany(EventRsvp::class);
    }

    /**
     * Relationship: Custom RSVP questions/fields.
     */
    public function customFields(): HasMany
    {
        return $this->hasMany(EventRsvpCustomField::class)->orderBy('sort_order', 'asc');
    }

    /**
     * Return public URL for this website.
     */
    public function getPublicUrlAttribute(): string
    {
        return url('/e/' . $this->slug);
    }

    /**
     * Check if RSVP is closed due to deadline.
     */
    public function isPastDeadline(): bool
    {
        if (!$this->rsvp_deadline) {
            return false;
        }

        return now()->isAfter($this->rsvp_deadline);
    }

    /**
     * Get a specific section by its key.
     */
    public function getSection(string $key): ?EventWebsiteSection
    {
        return $this->sections->firstWhere('section_key', $key);
    }

    /**
     * Check if a section is enabled.
     */
    public function isSectionEnabled(string $key): bool
    {
        $section = $this->getSection($key);
        return $section ? (bool) $section->is_enabled : false;
    }

    /**
     * Preset themes available for event websites.
     */
    public static function getThemePresets(): array
    {
        return [
            'luxury_gold' => [
                'name' => 'Luxury Birthday & Gala (Gold & Obsidian)',
                'primary' => '#D4AF37', // Metallic Gold
                'secondary' => '#1F2937', // Deep Charcoal
                'accent' => '#FBBF24', // Warm Amber Gold
                'background' => '#0F172A', // Midnight Slate
                'text' => '#F8FAFC',
                'heading_font' => 'Playfair Display',
                'body_font' => 'Inter',
                'badge' => 'Premium Signature',
            ],
            'elegant_wedding' => [
                'name' => 'Elegant Wedding (Ivory & Rose Gold)',
                'primary' => '#C48B71', // Rose Gold
                'secondary' => '#4A3E3D', // Muted Espresso
                'accent' => '#E8C5B8', // Soft Blush
                'background' => '#FCFAF8', // Silk Ivory
                'text' => '#292524',
                'heading_font' => 'Cormorant Garamond',
                'body_font' => 'Plus Jakarta Sans',
                'badge' => 'Romantic',
            ],
            'african_heritage' => [
                'name' => 'Traditional Wedding & Heritage',
                'primary' => '#D97706', // Ochre Gold
                'secondary' => '#78350F', // Rich Terracotta / Wood
                'accent' => '#B45309', // Deep Bronze
                'background' => '#FFFBEB', // Sandstone
                'text' => '#1C1917',
                'heading_font' => 'Cinzel',
                'body_font' => 'Inter',
                'badge' => 'Heritage',
            ],
            'corporate_celebration' => [
                'name' => 'Corporate Celebration & Gala',
                'primary' => '#2563EB', // Sapphire Blue
                'secondary' => '#0F172A', // Dark Navy
                'accent' => '#38BDF8', // Sky Accent
                'background' => '#F8FAFC', // Crisp White
                'text' => '#0F172A',
                'heading_font' => 'Montserrat',
                'body_font' => 'Inter',
                'badge' => 'Corporate',
            ],
            'burgundy_anniversary' => [
                'name' => 'Anniversary & Romantic Milestones',
                'primary' => '#9F1239', // Rich Ruby Burgundy
                'secondary' => '#4C0519', // Wine
                'accent' => '#F43F5E', // Rose Crimson
                'background' => '#FFF1F2', // Champagne Rose
                'text' => '#1E1B18',
                'heading_font' => 'Playfair Display',
                'body_font' => 'Plus Jakarta Sans',
                'badge' => 'Celebration',
            ],
            'emerald_elegance' => [
                'name' => 'Emerald Elegance & Soirée',
                'primary' => '#047857', // Emerald Jewel
                'secondary' => '#064E3B', // Deep Forest
                'accent' => '#10B981', // Mint Bright
                'background' => '#06281E', // Dark Malachite
                'text' => '#ECFDF5',
                'heading_font' => 'Cinzel',
                'body_font' => 'Inter',
                'badge' => 'Opulent',
            ],
        ];
    }

    /**
     * Resolve effective active theme configuration.
     */
    public function getActiveThemeConfig(): array
    {
        $presets = static::getThemePresets();
        $preset = $presets[$this->theme] ?? $presets['luxury_gold'];

        return [
            'key' => $this->theme,
            'name' => $preset['name'],
            'primary' => $this->primary_color ?: $preset['primary'],
            'secondary' => $this->secondary_color ?: $preset['secondary'],
            'accent' => $this->accent_color ?: $preset['accent'],
            'background' => $this->background_color ?: $preset['background'],
            'text' => $this->text_color ?: $preset['text'],
            'heading_font' => $this->font_heading ?: $preset['heading_font'],
            'body_font' => $this->font_body ?: $preset['body_font'],
            'badge' => $preset['badge'],
        ];
    }

    /**
     * Initialize all 13 standard sections with rich default configurations.
     */
    public function initDefaultSections(): void
    {
        $definitions = [
            [
                'section_key' => 'hero',
                'title' => $this->title,
                'subtitle' => $this->tagline ?: 'We warmly invite you to join us on this special celebration.',
                'sort_order' => 1,
                'is_enabled' => true,
                'content' => [
                    'badge' => 'Celebration of a Lifetime',
                    'rsvp_button_text' => 'RSVP Now',
                    'details_button_text' => 'View Itinerary',
                ],
            ],
            [
                'section_key' => 'countdown',
                'title' => 'Countdown to the Celebration',
                'subtitle' => 'Every second brings us closer to making unforgettable memories together.',
                'sort_order' => 2,
                'is_enabled' => true,
                'content' => [
                    'event_started_text' => 'The celebration has officially begun! Welcome!',
                    'event_ended_text' => 'Thank you all for being part of this magical celebration!',
                ],
            ],
            [
                'section_key' => 'about',
                'title' => 'About the Host & Celebration',
                'subtitle' => 'A journey of love, joy, achievements, and cherished moments.',
                'sort_order' => 3,
                'is_enabled' => true,
                'content' => [
                    'celebrant_name' => $this->host_names ?: 'Our Celebrant',
                    'celebrant_role' => 'Guest of Honor',
                    'story' => 'Fifty years of grace, resilience, love, and laughter. We gather to honor a magnificent journey and celebrate the incredible blessing of family, friendship, and shared moments that will last a lifetime.',
                    'quote' => '"The best things in life are the people we love, the places we\'ve been, and the memories we\'ve made along the way."',
                    'image' => null,
                ],
            ],
            [
                'section_key' => 'details',
                'title' => 'Celebration Details & Highlights',
                'subtitle' => 'Everything you need to know about the gathering.',
                'sort_order' => 4,
                'is_enabled' => true,
                'content' => [
                    'items' => [
                        [
                            'title' => 'Date & Arrival',
                            'description' => ($this->event_date ? $this->event_date->format('l, F j, Y') : 'Date to be confirmed') . ' starting at ' . ($this->event_time ?: '18:00'),
                            'icon' => 'calendar',
                        ],
                        [
                            'title' => 'Venue Setting',
                            'description' => $this->venue_name . ' - ' . $this->venue_address,
                            'icon' => 'location',
                        ],
                        [
                            'title' => 'Evening Program',
                            'description' => 'Red carpet arrival, welcome cocktail reception, dinner banquet, keynote toasts, and dancing until late.',
                            'icon' => 'sparkles',
                        ],
                    ],
                ],
            ],
            [
                'section_key' => 'schedule',
                'title' => 'Event Itinerary',
                'subtitle' => 'The day\'s program and timeline of festivities.',
                'sort_order' => 5,
                'is_enabled' => true,
                'content' => [
                    'items' => [
                        ['time' => '17:00 - 18:00', 'title' => 'Arrival & Red Carpet Welcome', 'description' => 'Guests arrive, photo moments at the step & repeat backdrop, welcome mocktails and champagne.'],
                        ['time' => '18:00 - 19:30', 'title' => 'Opening Ceremony & Toasts', 'description' => 'Celebrant entrance, prayer, opening speeches, and tribute video presentation.'],
                        ['time' => '19:30 - 21:00', 'title' => 'Gourmet Dinner Banquet', 'description' => 'A three-course curated dining experience accompanied by live acoustic jazz music.'],
                        ['time' => '21:00 - 21:30', 'title' => 'Cake Cutting & Champagne Toast', 'description' => 'Celebration cake cutting, presentation of gifts, and group anniversary toast.'],
                        ['time' => '21:30 - Late', 'title' => 'Music, Dance & Celebration', 'description' => 'The dance floor opens with live entertainment, DJ performances, and late-night treats.'],
                    ],
                ],
            ],
            [
                'section_key' => 'venue',
                'title' => 'Venue & Location',
                'subtitle' => 'Find your way to the celebration with ease.',
                'sort_order' => 6,
                'is_enabled' => true,
                'content' => [
                    'venue_name' => $this->venue_name,
                    'address' => $this->venue_address,
                    'parking_notes' => 'Complimentary valet and secure on-site parking available for all attendees.',
                    'maps_link' => $this->google_maps_url,
                ],
            ],
            [
                'section_key' => 'dress_code',
                'title' => 'Dress Code & Palette',
                'subtitle' => 'Dress to impress in accordance with our celebration theme.',
                'sort_order' => 7,
                'is_enabled' => true,
                'content' => [
                    'style' => 'Black Tie / High Glamour / African Elegance',
                    'notes' => 'We invite you to express your elegance. Feel free to incorporate accents from our celebration color palette.',
                    'colors' => [
                        ['name' => 'Metallic Gold', 'hex' => '#D4AF37'],
                        ['name' => 'Royal Emerald', 'hex' => '#0F4D3C'],
                        ['name' => 'Champagne Cream', 'hex' => '#F4E8D1'],
                        ['name' => 'Midnight Black', 'hex' => '#0D1117'],
                    ],
                ],
            ],
            [
                'section_key' => 'travel',
                'title' => 'Travel Information',
                'subtitle' => 'Guidance for guests traveling from other cities and abroad.',
                'sort_order' => 8,
                'is_enabled' => true,
                'content' => [
                    'airport' => 'Julius Nyerere International Airport (DAR)',
                    'airport_distance' => 'Approximately 25 minutes to venue by taxi.',
                    'directions' => 'Private airport transfers can be pre-arranged upon RSVP. Uber, Bolt, and certified hotel taxis are widely accessible.',
                ],
            ],
            [
                'section_key' => 'accommodation',
                'title' => 'Recommended Accommodations',
                'subtitle' => 'Preferred hotels nearby with curated hospitality.',
                'sort_order' => 9,
                'is_enabled' => true,
                'content' => [
                    'items' => [
                        [
                            'name' => 'Hyatt Regency Dar es Salaam (The Kilimanjaro)',
                            'distance' => 'On-site / 5 minutes away',
                            'phone' => '+255 764 701 234',
                            'booking_code' => 'TUKIO-CELEBRATION',
                            'website' => 'https://www.hyatt.com',
                        ],
                        [
                            'name' => 'Johari Rotana Hotel',
                            'distance' => '8 minutes away',
                            'phone' => '+255 659 070 800',
                            'booking_code' => 'TUKIO-SPECIAL',
                            'website' => 'https://www.rotana.com',
                        ],
                    ],
                ],
            ],
            [
                'section_key' => 'faq',
                'title' => 'Frequently Asked Questions',
                'subtitle' => 'Quick answers to common guest questions.',
                'sort_order' => 10,
                'is_enabled' => true,
                'content' => [
                    'items' => [
                        ['q' => 'Can I bring a plus one?', 'a' => 'Due to venue seating capacity, plus-ones are accommodated according to your designated invitation allocation. Please specify your accompanying guest during RSVP.'],
                        ['q' => 'Are children invited?', 'a' => 'To allow all our guests to relax and enjoy the evening, this will be an adults-only celebration unless specifically communicated on your invitation.'],
                        ['q' => 'What is the gift registry preference?', 'a' => 'Your presence at our celebration is the greatest gift of all. For those who insist, a gift card, envelope, or contribution towards our milestone fund is deeply appreciated.'],
                        ['q' => 'What if I have dietary restrictions?', 'a' => 'Our banquet menu includes vegetarian, halal, and gluten-sensitive choices. Please make sure to highlight any specific allergies in your RSVP submission.'],
                        ['q' => 'What is the RSVP deadline?', 'a' => 'Please confirm your attendance by ' . ($this->rsvp_deadline ? $this->rsvp_deadline->format('M j, Y') : '14 days prior to the event') . ' so we can finalize catering and seating allocations.'],
                    ],
                ],
            ],
            [
                'section_key' => 'rsvp',
                'title' => 'RSVP & Guest Registration',
                'subtitle' => 'Kindly respond at your earliest convenience to assist in our planning.',
                'sort_order' => 11,
                'is_enabled' => true,
                'content' => [
                    'headline' => 'Will you be joining us?',
                    'intro' => 'Please fill in the form below. Once registered, you will receive an instant digital confirmation pass for event check-in.',
                    'deadline_note' => $this->rsvp_deadline ? 'RSVP deadline: ' . $this->rsvp_deadline->format('l, F j, Y') : null,
                ],
            ],
            [
                'section_key' => 'gallery',
                'title' => 'Celebration Gallery & Memories',
                'subtitle' => 'Capturing moments of happiness, laughter, and timeless heritage.',
                'sort_order' => 12,
                'is_enabled' => true,
                'content' => [
                    'images' => [
                        ['url' => null, 'caption' => 'A journey of cherished memories'],
                        ['url' => null, 'caption' => 'Golden celebrations and milestones'],
                        ['url' => null, 'caption' => 'Family and friends uniting in love'],
                    ],
                ],
            ],
            [
                'section_key' => 'contact',
                'title' => 'Contact & Host Information',
                'subtitle' => 'Need assistance or have further questions? We are here for you.',
                'sort_order' => 13,
                'is_enabled' => true,
                'content' => [
                    'contact_name' => $this->host_names ?: 'Event Coordinator',
                    'contact_phone' => $this->event?->host?->phone ?: '+255 700 000 000',
                    'contact_email' => $this->event?->host?->email ?: 'info@tukio360.co.tz',
                    'whatsapp_direct' => true,
                ],
            ],
        ];

        foreach ($definitions as $def) {
            $this->sections()->updateOrCreate(
                ['section_key' => $def['section_key']],
                [
                    'title' => $def['title'],
                    'subtitle' => $def['subtitle'],
                    'sort_order' => $def['sort_order'],
                    'is_enabled' => $def['is_enabled'],
                    'content' => $def['content'],
                ]
            );
        }
    }
}

