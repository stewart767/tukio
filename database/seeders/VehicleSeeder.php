<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            // ============================================
            // 1. LUXURY BRIDE & GROOM CARS
            // ============================================
            [
                'name' => 'Mercedes-Benz S-Class S500 Maybach Edition',
                'slug' => 'mercedes-benz-s-class-maybach-edition',
                'category' => 'luxury_bride_groom',
                'brand' => 'Mercedes-Benz',
                'model_year' => '2023',
                'seating_capacity' => 4,
                'luggage_capacity' => '3 Bags',
                'transmission' => 'Automatic',
                'fuel_type' => 'Petrol',
                'color' => 'Pearl White',
                'price_per_day' => 750000,
                'price_per_hour' => 120000,
                'has_driver' => true,
                'has_fuel_included' => true,
                'has_ribbon_decoration' => true,
                'features' => [
                    'Professional Chauffeur in Black Suit',
                    'Bridal White Ribbon & Fresh Flower Bouquet',
                    'Rear Seat Massage & Recline',
                    'Chilled Champagne/Beverage Cooler',
                    'Panoramic Glass Sunroof for Photos',
                    'Red Carpet Arrival Mat',
                    'Burmester 3D Surround Sound System'
                ],
                'image_url' => 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1617788138017-80ad40651399?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1563720223185-11003d516935?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'
                ],
                'description' => 'The ultimate flagship luxury bridal sedan. Perfect for the bride and groom grand entrance. Features immaculate pearl white finish, luxury leather reclining massage seats, privacy curtains, and professional chauffeur service with ribbon deco.',
                'location' => 'Dar es Salaam, Tanzania',
                'is_available' => true,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Range Rover Autobiography LWB',
                'slug' => 'range-rover-autobiography-lwb',
                'category' => 'luxury_bride_groom',
                'brand' => 'Land Rover',
                'model_year' => '2023',
                'seating_capacity' => 4,
                'luggage_capacity' => '4 Bags',
                'transmission' => 'Automatic',
                'fuel_type' => 'Petrol',
                'color' => 'Santorini Black / White Duo',
                'price_per_day' => 850000,
                'price_per_hour' => 130000,
                'has_driver' => true,
                'has_fuel_included' => true,
                'has_ribbon_decoration' => true,
                'features' => [
                    'Executive Chauffeur Service',
                    'Executive Class Rear Seating',
                    'Deployable Side Steps for Bridal Gown Entry',
                    'Custom Floral Hood Ribbon & Side Mirrors',
                    'Soft Close Doors & Air Suspension',
                    'Meridian Signature 1600W Sound',
                    'Dual Screen Entertainment'
                ],
                'image_url' => 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1541348263662-e0c8de4259ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'
                ],
                'description' => 'Command the spotlight on your wedding day with the prestigious Range Rover Autobiography Long Wheelbase. Unrivalled road presence, ultra-spacious legroom for the largest wedding dresses, and silky smooth air-ride suspension.',
                'location' => 'Dar es Salaam & Bagamoyo, Tanzania',
                'is_available' => true,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Toyota Alphard Royal Lounge VIP Executive',
                'slug' => 'toyota-alphard-royal-lounge-vip',
                'category' => 'luxury_bride_groom',
                'brand' => 'Toyota',
                'model_year' => '2022',
                'seating_capacity' => 6,
                'luggage_capacity' => '5 Bags',
                'transmission' => 'Automatic',
                'fuel_type' => 'Hybrid',
                'color' => 'Crystal White Pearl',
                'price_per_day' => 500000,
                'price_per_hour' => 80000,
                'has_driver' => true,
                'has_fuel_included' => true,
                'has_ribbon_decoration' => true,
                'features' => [
                    'First-Class Airline Captain Ottoman Seats',
                    'Electric Footrests & Seat Heaters/Coolers',
                    'Twin Power Sliding Doors with Easy Step',
                    'Full Interior Ambient Mood Lighting (16 Colors)',
                    'Bridal Ribbon & Rose Petal Deco',
                    'Quiet Hybrid Electric Whisper Drive',
                    'Rear Privacy Curtains & Tinted Glass'
                ],
                'image_url' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'
                ],
                'description' => 'The bridal party favourite! The Toyota Alphard Royal Lounge provides the highest comfort in Tanzania for brides, maids of honor, and groomsmen. Effortless entry even with elaborate dresses and tiaras.',
                'location' => 'Dar es Salaam, Tanzania',
                'is_available' => true,
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Vintage Classic Beauford Open-Tourer Convertible',
                'slug' => 'vintage-classic-beauford-convertible',
                'category' => 'luxury_bride_groom',
                'brand' => 'Beauford',
                'model_year' => '1930s Replica',
                'seating_capacity' => 3,
                'luggage_capacity' => 'Vintage Trunk',
                'transmission' => 'Manual/Auto',
                'fuel_type' => 'Petrol',
                'color' => 'Old English White & Crimson',
                'price_per_day' => 900000,
                'price_per_hour' => 150000,
                'has_driver' => true,
                'has_fuel_included' => true,
                'has_ribbon_decoration' => true,
                'features' => [
                    'Iconic Retro 1930s Open-Top Wedding Styling',
                    'Vintage Uniformed Chauffeur with Cap',
                    'Handcrafted Leather Seats & Polished Chrome',
                    'White Satin Bows & Floral Garland Decor',
                    'Unforgettable Wedding Photoshoot Backdrop',
                    'Foldable Weatherproof Soft Hood'
                ],
                'image_url' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'
                ],
                'description' => 'Turn your wedding entrance into a royal fairytale. The Classic Beauford Open Tourer turns every head and creates breathtaking photo opportunities. Includes vintage-costumed driver and luxury floral arrangements.',
                'location' => 'Dar es Salaam & Zanzibar, Tanzania',
                'is_available' => true,
                'is_featured' => false,
                'sort_order' => 4,
            ],

            // ============================================
            // 2. GUEST CARS, COASTERS & BUSES
            // ============================================
            [
                'name' => 'Toyota Coaster Luxury Edition (30-Seater)',
                'slug' => 'toyota-coaster-luxury-edition-30-seater',
                'category' => 'guest_coaster_bus',
                'brand' => 'Toyota',
                'model_year' => '2023',
                'seating_capacity' => 30,
                'luggage_capacity' => 'Large Luggage Compartment',
                'transmission' => 'Manual',
                'fuel_type' => 'Diesel',
                'color' => 'Pure White with Gold Accents',
                'price_per_day' => 400000,
                'price_per_hour' => 60000,
                'has_driver' => true,
                'has_fuel_included' => true,
                'has_ribbon_decoration' => true,
                'features' => [
                    'Full Roof-Mounted Air Conditioning System',
                    'High-Back Reclining Fabric Seats with Armrests',
                    'Public Address (PA) Microphone for Announcements & Chants',
                    'Bluetooth / USB Party Sound System',
                    'Automatic Pneumatic Sliding Passenger Door',
                    'Experienced Certified Heavy-Duty Driver',
                    'Dedicated Luggage Storage Rack & Rear Boot'
                ],
                'image_url' => 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'
                ],
                'description' => 'The ultimate guest transport for wedding entourages, committee members, church-to-reception transfers, and family groups. Superb air conditioning, plush high-back seats, and seamless group coordination.',
                'location' => 'Dar es Salaam, Morogoro & Arusha',
                'is_available' => true,
                'is_featured' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Executive Charter Coach Bus (50-Seater VIP)',
                'slug' => 'executive-charter-coach-bus-50-seater',
                'category' => 'guest_coaster_bus',
                'brand' => 'Scania / Yutong Luxury',
                'model_year' => '2023',
                'seating_capacity' => 50,
                'luggage_capacity' => 'Underfloor Cargo Bays',
                'transmission' => 'Automatic',
                'fuel_type' => 'Diesel',
                'color' => 'Metallic Silver & Blue',
                'price_per_day' => 650000,
                'price_per_hour' => 95000,
                'has_driver' => true,
                'has_fuel_included' => true,
                'has_ribbon_decoration' => false,
                'features' => [
                    '50 Luxury Reclining Semi-Sleeper Seats',
                    'Individual AC Vents & Reading Lights',
                    'Overhead TV Monitors & DVD Entertainment',
                    'USB Charging Ports on Every Seat Pair',
                    'Massive Underfloor Luggage Compartments',
                    'Professional Tour & Intercity Driver',
                    'Onboard Ice Box & First Aid Kit'
                ],
                'image_url' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'
                ],
                'description' => 'Perfect for large wedding convoys, corporate delegates, church choirs, and inter-city guest transport (e.g., Dar to Morogoro / Dodoma / Bagamoyo). Ensures every guest travels in luxury and arrives together on time.',
                'location' => 'All Regions in Tanzania',
                'is_available' => true,
                'is_featured' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Toyota HiAce Super Custom VIP Van (15-Seater)',
                'slug' => 'toyota-hiace-super-custom-vip-15-seater',
                'category' => 'guest_coaster_bus',
                'brand' => 'Toyota',
                'model_year' => '2022',
                'seating_capacity' => 15,
                'luggage_capacity' => 'Medium Boot Space',
                'transmission' => 'Automatic',
                'fuel_type' => 'Diesel',
                'color' => 'White / Pearl Duo',
                'price_per_day' => 250000,
                'price_per_hour' => 45000,
                'has_driver' => true,
                'has_fuel_included' => true,
                'has_ribbon_decoration' => true,
                'features' => [
                    'Double AC (Front & Rear Dual Zone)',
                    'Reclining Captain Style Seats',
                    'Smooth High-Roof Walkthrough Cabin',
                    'Tinted Privacy Glass & Window Curtains',
                    'Crisp Sound System with Aux/Bluetooth',
                    'Experienced Chauffeur'
                ],
                'image_url' => 'https://images.unsplash.com/photo-1559297434-fae8a1916a79?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1559297434-fae8a1916a79?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'
                ],
                'description' => 'Comfortable, agile, and economical minibus for bridesmaids, groomsmen, close family members, and photo session runs around town.',
                'location' => 'Dar es Salaam, Tanzania',
                'is_available' => true,
                'is_featured' => false,
                'sort_order' => 7,
            ],

            // ============================================
            // 3. VIP CONVOY & ESCORT SUVS
            // ============================================
            [
                'name' => 'Toyota Land Cruiser 300 V8 ZX VIP Convoy',
                'slug' => 'toyota-land-cruiser-300-v8-zx',
                'category' => 'vip_suv_convoy',
                'brand' => 'Toyota',
                'model_year' => '2023',
                'seating_capacity' => 7,
                'luggage_capacity' => '4 Bags',
                'transmission' => 'Automatic',
                'fuel_type' => 'Diesel Twin-Turbo',
                'color' => 'Prestige White Pearl',
                'price_per_day' => 600000,
                'price_per_hour' => 100000,
                'has_driver' => true,
                'has_fuel_included' => true,
                'has_ribbon_decoration' => true,
                'features' => [
                    'VIP Escort & Convoy Ready with Siren/Strobe options',
                    'Full Red/Black Leather Luxury Interior',
                    'Rear Seat Entertainment Screens',
                    'JBL 14-Speaker Premium Audio System',
                    'Cool Box Center Console for Refreshments',
                    'Professional Security-Trained Driver'
                ],
                'image_url' => 'https://images.unsplash.com/photo-1594502184342-2e12f877aa73?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1594502184342-2e12f877aa73?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'
                ],
                'description' => 'The undisputed king of convoys. Ideal for VIP parents, chief guests, government dignitaries, and wedding convoy lead cars. Robust power combined with peerless luxury.',
                'location' => 'Dar es Salaam & Dodoma, Tanzania',
                'is_available' => true,
                'is_featured' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Toyota Prado TXL Black Edition Convoy SUV',
                'slug' => 'toyota-prado-txl-black-edition',
                'category' => 'vip_suv_convoy',
                'brand' => 'Toyota',
                'model_year' => '2022',
                'seating_capacity' => 7,
                'luggage_capacity' => '4 Bags',
                'transmission' => 'Automatic',
                'fuel_type' => 'Diesel',
                'color' => 'Gloss Black / White Pearl',
                'price_per_day' => 350000,
                'price_per_hour' => 60000,
                'has_driver' => true,
                'has_fuel_included' => true,
                'has_ribbon_decoration' => true,
                'features' => [
                    'Convoy Package (Matching Black or White Fleet)',
                    '7 Comfortable Leather Seats',
                    'Dual Climate Control & Cool Box',
                    'Wedding Ribbons & Chrome Accents',
                    'Courteous Uniformed Chauffeur'
                ],
                'image_url' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'
                ],
                'description' => 'Ideal for building a uniform, sleek matching convoy of 3 to 10 vehicles behind the bride & groom car. High reliability and commanding presence.',
                'location' => 'Dar es Salaam, Tanzania',
                'is_available' => true,
                'is_featured' => false,
                'sort_order' => 9,
            ],
        ];

        foreach ($vehicles as $data) {
            Vehicle::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
