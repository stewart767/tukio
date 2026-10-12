<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venue;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $venues = [
            [
                'name' => 'Grand Ballrooms & Halls',
                'slug' => 'grand-ballrooms-and-halls',
                'category' => 'Indoor Ballrooms',
                'location' => 'Dar es Salaam',
                'capacity' => '500 – 2,000 Guests',
                'badge' => '🏛️ Indoor Ballrooms',
                'description' => 'Opulent indoor banquet halls with high-ceiling crystal chandeliers, acoustic wall panels, private bridal suites, and dedicated banquet catering kitchens.',
                'amenities' => ['Climate Controlled', 'VIP Lounges', 'Stage Lighting', 'Audio/Visual Setup', 'Secure Parking'],
                'image_url' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'price_range' => 'From TZS 2,500,000 / event',
                'inquiry_button_text' => 'Inquire About Ballrooms',
                'inquiry_subject' => 'Venue Inquiry - Grand Ballrooms',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Beachfront & Garden Terraces',
                'slug' => 'beachfront-and-garden-terraces',
                'category' => 'Beach & Garden',
                'location' => 'Zanzibar / Dar es Salaam',
                'capacity' => '200 – 1,500 Guests',
                'badge' => '🏖️ Beach & Garden',
                'description' => 'Spectacular seaside ocean-view lawns and tropical palm gardens tailored for sunset wedding vows, cocktail lounges, and romantic night receptions.',
                'amenities' => ['Ocean View', 'Open Air Lawn', 'Sunset Backdrop', 'Tenting Compatible', 'Power Generators'],
                'image_url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'price_range' => 'From TZS 1,800,000 / event',
                'inquiry_button_text' => 'Inquire Beach Venues',
                'inquiry_subject' => 'Venue Inquiry - Beachfront Terraces',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Convention & Expo Centers',
                'slug' => 'convention-and-expo-centers',
                'category' => 'Conference Centers',
                'location' => 'Arusha / Dar es Salaam',
                'capacity' => '100 – 3,000 Delegates',
                'badge' => '💼 Conference',
                'description' => 'World-class convention spaces equipped with translation booths, high-speed fiber internet, multi-screen projection, and breakout committee rooms.',
                'amenities' => ['Audio/Visual Systems', 'Exhibition Floor', 'Secure Parking', 'Simultaneous Translation', 'High-Speed WiFi'],
                'image_url' => 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'price_range' => 'From TZS 3,000,000 / day',
                'inquiry_button_text' => 'Inquire Conventions',
                'inquiry_subject' => 'Venue Inquiry - Convention Centers',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($venues as $venue) {
            Venue::updateOrCreate(
                ['slug' => $venue['slug']],
                $venue
            );
        }
    }
}
