<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Host;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\Template;
use App\Models\Vehicle;
use App\Models\CarBooking;

class ExampleDataSeeder extends Seeder
{
    /**
     * Seed realistic example hosts (tenants), events, guests, invitations, and car bookings.
     */
    public function run(): void
    {
        // 1. Fetch reference users & templates
        $superAdmin = User::where('role', 'super_admin')->first();
        $staffUser = User::where('role', 'staff')->first();

        $weddingTemplate = Template::where('slug', 'luxury-ivory-gold-nuptials')->first() 
            ?? Template::where('category', 'wedding')->first();
        $birthdayTemplate = Template::where('category', 'birthday')->first();
        $kitchenPartyTemplate = Template::where('category', 'kitchen_party')->first();
        $sendOffTemplate = Template::where('category', 'send_off')->first();
        $corporateTemplate = Template::where('category', 'corporate')->first();

        $maybachVehicle = Vehicle::where('category', 'luxury_bride_groom')->first();
        $alphardVehicle = Vehicle::where('category', 'luxury_bride_groom')->where('name', 'like', '%Alphard%')->first() ?? $maybachVehicle;
        $coasterVehicle = Vehicle::where('category', 'guest_coaster_bus')->first();
        $landCruiserVehicle = Vehicle::where('category', 'vip_suv_convoy')->first();
        $coachVehicle = Vehicle::where('category', 'guest_coaster_bus')->where('name', 'like', '%Coach%')->first() ?? $coasterVehicle;

        // -------------------------------------------------------------
        // EXAMPLE 1: WEDDING HOST (Tenant)
        // -------------------------------------------------------------
        $weddingHost = Host::updateOrCreate(
            ['email' => 'wedding@test.com'],
            [
                'full_name' => 'Michael & Jessica Kimaro',
                'phone' => '+255712345671',
                'event_type' => 'wedding',
                'number_of_guests' => 300,
                'total_cost' => 600000.00,
                'password' => 'password123',
                'is_active' => true,
                'event_configured' => true,
                'event_config_data' => [
                    'wedding_title' => 'The Royal Wedding of Michael & Jessica',
                    'groom_name' => 'Michael Kimaro',
                    'bride_name' => 'Jessica Msuya',
                    'wedding_date' => now()->addDays(28)->format('Y-m-d'),
                    'venue' => 'Serena Hotel Diamond Ballroom, Dar es Salaam',
                    'venue_address' => 'Ohio Street, City Centre, Dar es Salaam',
                    'contact_person_name' => 'Hon. John Kimaro',
                    'contact_person_phone' => '+255754111222',
                    'description' => 'A royal celebration of love, culture, and unity with families and cherished friends.',
                ],
            ]
        );

        $weddingEvent = Event::updateOrCreate(
            ['host_id' => $weddingHost->id],
            [
                'user_id' => $superAdmin?->id,
                'title' => 'The Royal Wedding of Michael & Jessica',
                'description' => 'Join us to celebrate the union of Michael Kimaro and Jessica Msuya.',
                'category' => 'wedding',
                'date' => now()->addDays(28)->format('Y-m-d'),
                'time' => '18:00:00',
                'venue' => 'Serena Hotel Diamond Ballroom, Dar es Salaam',
                'venue_address' => 'Ohio Street, City Centre, Dar es Salaam',
                'template_id' => $weddingTemplate?->id,
                'status' => 'active',
                'event_password' => 'WED26',
                'gate_pin' => '4829',
                'lipa_namba' => '5544332',
                'account_name' => 'HARUSI YA MICHAEL NA JESSICA',
                'treasurer_phone' => '+255754123456',
                'guest_count' => 300,
                'total_cost' => 600000.00,
                'staff_id' => $staffUser?->id,
                'custom_data' => $weddingHost->event_config_data,
            ]
        );

        // Assign staff and all 3 admin accounts to the event
        $allAdminAndStaffIds = User::whereIn('role', ['super_admin', 'staff'])->pluck('id')->toArray();
        if (!empty($allAdminAndStaffIds)) {
            $weddingEvent->staffMembers()->syncWithoutDetaching($allAdminAndStaffIds);
        }

        // Guests & Invitations for Wedding
        $weddingGuests = [
            ['name' => 'Hon. Juma Mwinyi', 'phone' => '255711000001', 'type' => 'double', 'table' => 'Meza VIP 1', 'pin' => '4821', 'status' => 'active', 'attended' => false],
            ['name' => 'Dr. Grace Massawe', 'phone' => '255711000002', 'type' => 'single', 'table' => 'Meza VIP 2', 'pin' => '4822', 'status' => 'attended', 'attended' => true, 'attended_at' => now()->subMinutes(45)],
            ['name' => 'Mr. Emmanuel & Sarah Temu', 'phone' => '255711000003', 'type' => 'double', 'table' => 'Meza Na. 3 (Familia)', 'pin' => '4823', 'status' => 'active', 'attended' => false],
            ['name' => 'Eng. Peter Lyimo', 'phone' => '255711000004', 'type' => 'single', 'table' => 'Meza Na. 4 (Kazini)', 'pin' => '4824', 'status' => 'active', 'attended' => false],
            ['name' => 'Asha Bakari (Kitochi SMS)', 'phone' => '255711000005', 'type' => 'single', 'table' => 'Meza Na. 5 (Marafiki)', 'pin' => '4825', 'status' => 'active', 'attended' => false],
        ];

        foreach ($weddingGuests as $idx => $gData) {
            $code = '1000' . ($idx + 1);
            $pax = $gData['type'] === 'double' ? 2 : 1;
            $guest = Guest::updateOrCreate(
                ['event_id' => $weddingEvent->id, 'phone' => $gData['phone']],
                [
                    'full_name' => $gData['name'],
                    'guest_type' => $gData['type'],
                    'status' => $gData['status'],
                    'attended' => $gData['attended'],
                    'attended_at' => $gData['attended_at'] ?? null,
                    'qr_code' => Str::random(12),
                    'invitation_code' => $code,
                    'pin_code' => $gData['pin'],
                    'table_number' => $gData['table'],
                    'pax' => $pax,
                    'pax_checked_in' => $gData['attended'] ? $pax : 0,
                    'amount_pledged' => 100000.00,
                    'amount_paid' => 100000.00,
                    'payment_method' => 'cash',
                    'payment_status' => 'paid',
                    'rsvp_status' => $gData['attended'] ? 'confirmed' : 'pending',
                    'category' => 'friends',
                ]
            );

            $invitationSlug = 'wedding-invitation-' . $guest->id . '-' . Str::random(8);
            Invitation::updateOrCreate(
                ['event_id' => $weddingEvent->id, 'guest_id' => $guest->id],
                [
                    'slug' => $invitationSlug,
                    'type' => 'whatsapp',
                    'template_id' => $weddingTemplate?->id,
                    'status' => $gData['attended'] ? 'opened' : 'delivered',
                    'sent_at' => now()->subDays(2),
                    'delivered_at' => now()->subDays(2)->addMinutes(5),
                    'opened_at' => $gData['attended'] ? now()->subHours(1) : now()->subHours(6),
                    'whatsapp_message' => "Dear {$guest->full_name}, You are cordially invited to The Royal Wedding of Michael & Jessica at Serena Hotel Ballroom.",
                ]
            );
        }

        // Wedding Car Booking
        if ($maybachVehicle) {
            CarBooking::updateOrCreate(
                ['booking_reference' => 'MC-CAR-WED-001'],
                [
                    'vehicle_id' => $maybachVehicle->id,
                    'host_id' => $weddingHost->id,
                    'event_id' => $weddingEvent->id,
                    'customer_name' => 'Michael Kimaro',
                    'customer_phone' => '+255712345671',
                    'customer_email' => 'wedding@test.com',
                    'event_type' => 'Wedding',
                    'start_date' => now()->addDays(28)->format('Y-m-d'),
                    'end_date' => now()->addDays(28)->format('Y-m-d'),
                    'days_count' => 1,
                    'pickup_location' => 'Mbezi Beach VIP Villa, Dar es Salaam',
                    'dropoff_location' => 'Serena Hotel Diamond Ballroom',
                    'pickup_time' => '01:00 PM',
                    'guest_count' => 300,
                    'need_decoration' => true,
                    'need_guest_coasters' => true,
                    'guest_coasters_count' => 2,
                    'special_requests' => 'White floral arrangement ribbons and chauffeur in black tuxedo.',
                    'total_price' => $maybachVehicle->daily_rate_tzs * 1,
                    'status' => 'confirmed',
                    'driver_name' => 'Rashid Mussa (Senior VIP Chauffeur)',
                    'driver_phone' => '+255755100200',
                    'admin_notes' => 'Pre-cleaned, ribbon decorations verified for bride and groom convoy.',
                ]
            );
        }

        // -------------------------------------------------------------
        // EXAMPLE 2: BIRTHDAY CELEBRATION HOST (Tenant)
        // -------------------------------------------------------------
        $birthdayHost = Host::updateOrCreate(
            ['email' => 'birthday@test.com'],
            [
                'full_name' => 'Amani Zawadi',
                'phone' => '+255712345672',
                'event_type' => 'birthday',
                'number_of_guests' => 100,
                'total_cost' => 200000.00,
                'password' => 'password123',
                'is_active' => true,
                'event_configured' => true,
                'event_config_data' => [
                    'event_title' => 'Amani\'s 30th Golden Jubilee Birthday',
                    'full_name' => 'Amani Zawadi',
                    'birthday_date' => now()->addDays(14)->format('Y-m-d'),
                    'venue' => 'Cape Town Fish Market Rooftop, Masaki',
                    'venue_address' => 'Toure Drive, Masaki, Dar es Salaam',
                    'contact_person_name' => 'Amani Zawadi',
                    'contact_person_phone' => '+255712345672',
                    'description' => 'Celebrate three decades of life, joy, laughter, and high energy with the tribe!',
                ],
            ]
        );

        $birthdayEvent = Event::updateOrCreate(
            ['host_id' => $birthdayHost->id],
            [
                'user_id' => $superAdmin?->id,
                'title' => 'Amani\'s 30th Golden Jubilee Birthday',
                'description' => 'A night of elegance, live music, cocktails, and celebration of 30 remarkable years.',
                'category' => 'birthday',
                'date' => now()->addDays(14)->format('Y-m-d'),
                'time' => '19:30:00',
                'venue' => 'Cape Town Fish Market Rooftop, Masaki',
                'venue_address' => 'Toure Drive, Masaki, Dar es Salaam',
                'template_id' => $birthdayTemplate?->id,
                'status' => 'active',
                'event_password' => 'BDY30',
                'guest_count' => 100,
                'total_cost' => 200000.00,
                'staff_id' => $staffUser?->id,
                'custom_data' => $birthdayHost->event_config_data,
            ]
        );

        if ($staffUser) {
            $birthdayEvent->staffMembers()->syncWithoutDetaching([$staffUser->id]);
        }

        // Birthday Guests & Invitations
        $birthdayGuests = [
            ['name' => 'Faraja Mwamba', 'phone' => '255712000001', 'type' => 'single', 'status' => 'active', 'attended' => false],
            ['name' => 'Denis & Brenda Kavishe', 'phone' => '255712000002', 'type' => 'double', 'status' => 'active', 'attended' => false],
            ['name' => 'Tariq Al-Mansoor', 'phone' => '255712000003', 'type' => 'single', 'status' => 'active', 'attended' => false],
        ];

        foreach ($birthdayGuests as $idx => $gData) {
            $code = '2000' . ($idx + 1);
            $guest = Guest::updateOrCreate(
                ['event_id' => $birthdayEvent->id, 'phone' => $gData['phone']],
                [
                    'full_name' => $gData['name'],
                    'guest_type' => $gData['type'],
                    'status' => $gData['status'],
                    'attended' => $gData['attended'],
                    'qr_code' => Str::random(12),
                    'invitation_code' => $code,
                ]
            );

            $invitationSlug = 'bdy-invitation-' . $guest->id . '-' . Str::random(8);
            Invitation::updateOrCreate(
                ['event_id' => $birthdayEvent->id, 'guest_id' => $guest->id],
                [
                    'slug' => $invitationSlug,
                    'type' => 'whatsapp',
                    'template_id' => $birthdayTemplate?->id,
                    'status' => 'delivered',
                    'sent_at' => now()->subDays(1),
                    'delivered_at' => now()->subDays(1)->addMinutes(10),
                    'whatsapp_message' => "Dear {$guest->full_name}, You are invited to celebrate Amani's 30th Birthday at Cape Town Fish Market Rooftop.",
                ]
            );
        }

        // Birthday Car Booking
        if ($alphardVehicle) {
            CarBooking::updateOrCreate(
                ['booking_reference' => 'MC-CAR-BDY-002'],
                [
                    'vehicle_id' => $alphardVehicle->id,
                    'host_id' => $birthdayHost->id,
                    'event_id' => $birthdayEvent->id,
                    'customer_name' => 'Amani Zawadi',
                    'customer_phone' => '+255712345672',
                    'customer_email' => 'birthday@test.com',
                    'event_type' => 'Birthday',
                    'start_date' => now()->addDays(14)->format('Y-m-d'),
                    'end_date' => now()->addDays(14)->format('Y-m-d'),
                    'days_count' => 1,
                    'pickup_location' => 'Oysterbay Penthouse Suites',
                    'dropoff_location' => 'Cape Town Fish Market Rooftop',
                    'pickup_time' => '06:00 PM',
                    'guest_count' => 100,
                    'need_decoration' => false,
                    'total_price' => $alphardVehicle->daily_rate_tzs * 1,
                    'status' => 'confirmed',
                    'driver_name' => 'Hamis Juma',
                    'driver_phone' => '+255768222333',
                    'admin_notes' => 'Birthday VIP transport confirmed.',
                ]
            );
        }

        // -------------------------------------------------------------
        // EXAMPLE 3: KITCHEN PARTY HOST (Tenant)
        // -------------------------------------------------------------
        $kitchenPartyHost = Host::updateOrCreate(
            ['email' => 'kitchenparty@test.com'],
            [
                'full_name' => 'Neema & Kelvin Traditional',
                'phone' => '+255712345673',
                'event_type' => 'kitchen_party',
                'number_of_guests' => 150,
                'total_cost' => 300000.00,
                'password' => 'password123',
                'is_active' => true,
                'event_configured' => true,
                'event_config_data' => [
                    'event_title' => 'Neema\'s Traditional Kitchen Party & Henna Night',
                    'bride_name' => 'Neema Makundi',
                    'event_date' => now()->addDays(20)->format('Y-m-d'),
                    'venue' => 'Mlimani City Conference Hall A, Dar es Salaam',
                    'venue_address' => 'Sam Nujoma Road, Ubungo, Dar es Salaam',
                    'contact_person_name' => 'Mama Neema',
                    'contact_person_phone' => '+255713444555',
                    'description' => 'A colorful traditional send-forth and kitchen blessing party honoring our bride-to-be.',
                ],
            ]
        );

        $kitchenPartyEvent = Event::updateOrCreate(
            ['host_id' => $kitchenPartyHost->id],
            [
                'user_id' => $superAdmin?->id,
                'title' => 'Neema\'s Traditional Kitchen Party & Henna Night',
                'description' => 'Traditional kitchen party blessings and celebrations.',
                'category' => 'kitchen_party',
                'date' => now()->addDays(20)->format('Y-m-d'),
                'time' => '16:00:00',
                'venue' => 'Mlimani City Conference Hall A, Dar es Salaam',
                'venue_address' => 'Sam Nujoma Road, Ubungo, Dar es Salaam',
                'template_id' => $kitchenPartyTemplate?->id,
                'status' => 'active',
                'event_password' => 'KP202',
                'guest_count' => 150,
                'total_cost' => 300000.00,
                'staff_id' => $staffUser?->id,
                'custom_data' => $kitchenPartyHost->event_config_data,
            ]
        );

        if ($staffUser) {
            $kitchenPartyEvent->staffMembers()->syncWithoutDetaching([$staffUser->id]);
        }

        // Kitchen Party Guests
        $kpGuests = [
            ['name' => 'Bi. Fatma Zahra', 'phone' => '255713000001', 'type' => 'single', 'status' => 'active'],
            ['name' => 'Mama Rehema & Friends', 'phone' => '255713000002', 'type' => 'double', 'status' => 'active'],
            ['name' => 'Zainab Salim', 'phone' => '255713000003', 'type' => 'single', 'status' => 'active'],
        ];

        foreach ($kpGuests as $idx => $gData) {
            $code = '3000' . ($idx + 1);
            $guest = Guest::updateOrCreate(
                ['event_id' => $kitchenPartyEvent->id, 'phone' => $gData['phone']],
                [
                    'full_name' => $gData['name'],
                    'guest_type' => $gData['type'],
                    'status' => $gData['status'],
                    'qr_code' => Str::random(12),
                    'invitation_code' => $code,
                ]
            );

            Invitation::updateOrCreate(
                ['event_id' => $kitchenPartyEvent->id, 'guest_id' => $guest->id],
                [
                    'slug' => 'kp-invitation-' . $guest->id . '-' . Str::random(8),
                    'type' => 'whatsapp',
                    'template_id' => $kitchenPartyTemplate?->id,
                    'status' => 'delivered',
                    'sent_at' => now()->subDays(1),
                    'delivered_at' => now()->subDays(1)->addMinutes(3),
                    'whatsapp_message' => "Karibu sana kwenye Kitchen Party ya Neema Makundi pale Mlimani City Hall.",
                ]
            );
        }

        // -------------------------------------------------------------
        // EXAMPLE 4: SEND-OFF GALA HOST (Tenant)
        // -------------------------------------------------------------
        $sendOffHost = Host::updateOrCreate(
            ['email' => 'sendoff@test.com'],
            [
                'full_name' => 'Doreen Tarimo',
                'phone' => '+255712345674',
                'event_type' => 'send_off',
                'number_of_guests' => 250,
                'total_cost' => 500000.00,
                'password' => 'password123',
                'is_active' => true,
                'event_configured' => true,
                'event_config_data' => [
                    'event_title' => 'Doreen\'s Grand Send-Off Gala Night',
                    'bride_name' => 'Doreen Tarimo',
                    'event_date' => now()->addDays(24)->format('Y-m-d'),
                    'venue' => 'Julius Nyerere International Convention Centre (JNICC)',
                    'venue_address' => 'Shaaban Robert Street, Dar es Salaam',
                    'contact_person_name' => 'Ambassador Tarimo',
                    'contact_person_phone' => '+255714777888',
                    'description' => 'A glamorous send-off banquet in honor of our cherished daughter Doreen.',
                ],
            ]
        );

        $sendOffEvent = Event::updateOrCreate(
            ['host_id' => $sendOffHost->id],
            [
                'user_id' => $superAdmin?->id,
                'title' => 'Doreen\'s Grand Send-Off Gala Night',
                'description' => 'Glamorous send-off ceremony at JNICC.',
                'category' => 'send_off',
                'date' => now()->addDays(24)->format('Y-m-d'),
                'time' => '18:30:00',
                'venue' => 'Julius Nyerere International Convention Centre (JNICC)',
                'venue_address' => 'Shaaban Robert Street, Dar es Salaam',
                'template_id' => $sendOffTemplate?->id,
                'status' => 'active',
                'event_password' => 'SND99',
                'guest_count' => 250,
                'total_cost' => 500000.00,
                'staff_id' => $staffUser?->id,
                'custom_data' => $sendOffHost->event_config_data,
            ]
        );

        if ($staffUser) {
            $sendOffEvent->staffMembers()->syncWithoutDetaching([$staffUser->id]);
        }

        // Send-off Guests
        $soGuests = [
            ['name' => 'Hon. Samuel Sitta Jr.', 'phone' => '255714000001', 'type' => 'double', 'status' => 'active'],
            ['name' => 'Dr. Veronica Ndosi', 'phone' => '255714000002', 'type' => 'single', 'status' => 'active'],
            ['name' => 'Kassim & Salma Majaliwa', 'phone' => '255714000003', 'type' => 'double', 'status' => 'active'],
        ];

        foreach ($soGuests as $idx => $gData) {
            $code = '4000' . ($idx + 1);
            $guest = Guest::updateOrCreate(
                ['event_id' => $sendOffEvent->id, 'phone' => $gData['phone']],
                [
                    'full_name' => $gData['name'],
                    'guest_type' => $gData['type'],
                    'status' => $gData['status'],
                    'qr_code' => Str::random(12),
                    'invitation_code' => $code,
                ]
            );

            Invitation::updateOrCreate(
                ['event_id' => $sendOffEvent->id, 'guest_id' => $guest->id],
                [
                    'slug' => 'sendoff-invitation-' . $guest->id . '-' . Str::random(8),
                    'type' => 'whatsapp',
                    'template_id' => $sendOffTemplate?->id,
                    'status' => 'delivered',
                    'sent_at' => now()->subDays(2),
                    'delivered_at' => now()->subDays(2)->addMinutes(12),
                    'whatsapp_message' => "Warm greetings {$guest->full_name}, You are invited to Doreen's Grand Send-Off Gala at JNICC Dar es Salaam.",
                ]
            );
        }

        // Send-Off Car Booking
        if ($landCruiserVehicle) {
            CarBooking::updateOrCreate(
                ['booking_reference' => 'MC-CAR-SND-003'],
                [
                    'vehicle_id' => $landCruiserVehicle->id,
                    'host_id' => $sendOffHost->id,
                    'event_id' => $sendOffEvent->id,
                    'customer_name' => 'Doreen Tarimo',
                    'customer_phone' => '+255712345674',
                    'customer_email' => 'sendoff@test.com',
                    'event_type' => 'Send Off',
                    'start_date' => now()->addDays(24)->format('Y-m-d'),
                    'end_date' => now()->addDays(24)->format('Y-m-d'),
                    'days_count' => 1,
                    'pickup_location' => 'Mikocheni Resident Villa',
                    'dropoff_location' => 'JNICC Dar es Salaam',
                    'pickup_time' => '04:30 PM',
                    'guest_count' => 250,
                    'need_decoration' => true,
                    'total_price' => $landCruiserVehicle->daily_rate_tzs * 1,
                    'status' => 'confirmed',
                    'driver_name' => 'Moses Massawe',
                    'driver_phone' => '+255788444111',
                    'admin_notes' => 'VIP escort convoy vehicle.',
                ]
            );
        }

        // -------------------------------------------------------------
        // EXAMPLE 5: CORPORATE GALA HOST (Tenant)
        // -------------------------------------------------------------
        $corporateHost = Host::updateOrCreate(
            ['email' => 'corporate@test.com'],
            [
                'full_name' => 'Vodacom Enterprise Solutions',
                'phone' => '+255712345675',
                'event_type' => 'corporate',
                'number_of_guests' => 500,
                'total_cost' => 1000000.00,
                'password' => 'password123',
                'is_active' => true,
                'event_configured' => true,
                'event_config_data' => [
                    'event_title' => 'Vodacom Annual Executive Gala & Awards 2026',
                    'company_name' => 'Vodacom Tanzania PLC',
                    'event_date' => now()->addDays(40)->format('Y-m-d'),
                    'venue' => 'Hyatt Regency The Kilimanjaro Ballroom',
                    'venue_address' => 'Kivukoni Front, Dar es Salaam',
                    'contact_person_name' => 'Corporate Affairs Directorate',
                    'contact_person_phone' => '+255754000111',
                    'description' => 'Celebrating excellence, innovation, and top corporate achievements for FY 2025/2026.',
                ],
            ]
        );

        $corporateEvent = Event::updateOrCreate(
            ['host_id' => $corporateHost->id],
            [
                'user_id' => $superAdmin?->id,
                'title' => 'Vodacom Annual Executive Gala & Awards 2026',
                'description' => 'Vodacom corporate awards night and networking gala.',
                'category' => 'corporate',
                'date' => now()->addDays(40)->format('Y-m-d'),
                'time' => '18:00:00',
                'venue' => 'Hyatt Regency The Kilimanjaro Ballroom',
                'venue_address' => 'Kivukoni Front, Dar es Salaam',
                'template_id' => $corporateTemplate?->id,
                'status' => 'active',
                'event_password' => 'VOD26',
                'guest_count' => 500,
                'total_cost' => 1000000.00,
                'staff_id' => $staffUser?->id,
                'custom_data' => $corporateHost->event_config_data,
            ]
        );

        if ($staffUser) {
            $corporateEvent->staffMembers()->syncWithoutDetaching([$staffUser->id]);
        }

        // Corporate Guests
        $corpGuests = [
            ['name' => 'Managing Director - Philip Besiimire', 'phone' => '255715000001', 'type' => 'single', 'status' => 'active'],
            ['name' => 'Director of Corporate Affairs & Guest', 'phone' => '255715000002', 'type' => 'double', 'status' => 'active'],
            ['name' => 'TCRA Board Representatives', 'phone' => '255715000003', 'type' => 'double', 'status' => 'active'],
        ];

        foreach ($corpGuests as $idx => $gData) {
            $code = '5000' . ($idx + 1);
            $guest = Guest::updateOrCreate(
                ['event_id' => $corporateEvent->id, 'phone' => $gData['phone']],
                [
                    'full_name' => $gData['name'],
                    'guest_type' => $gData['type'],
                    'status' => $gData['status'],
                    'qr_code' => Str::random(12),
                    'invitation_code' => $code,
                ]
            );

            Invitation::updateOrCreate(
                ['event_id' => $corporateEvent->id, 'guest_id' => $guest->id],
                [
                    'slug' => 'corp-invitation-' . $guest->id . '-' . Str::random(8),
                    'type' => 'whatsapp',
                    'template_id' => $corporateTemplate?->id,
                    'status' => 'delivered',
                    'sent_at' => now()->subDays(3),
                    'delivered_at' => now()->subDays(3)->addMinutes(1),
                    'whatsapp_message' => "Official Invitation: {$guest->full_name} is invited to the Vodacom Executive Gala 2026 at Hyatt Regency Dar es Salaam.",
                ]
            );
        }

        // Corporate Bus Booking
        if ($coachVehicle) {
            CarBooking::updateOrCreate(
                ['booking_reference' => 'MC-CAR-CORP-004'],
                [
                    'vehicle_id' => $coachVehicle->id,
                    'host_id' => $corporateHost->id,
                    'event_id' => $corporateEvent->id,
                    'customer_name' => 'Vodacom Corporate Team',
                    'customer_phone' => '+255712345675',
                    'customer_email' => 'corporate@test.com',
                    'event_type' => 'Corporate',
                    'start_date' => now()->addDays(40)->format('Y-m-d'),
                    'end_date' => now()->addDays(40)->format('Y-m-d'),
                    'days_count' => 1,
                    'pickup_location' => 'Vodacom Tower, Mlimani City, Dar es Salaam',
                    'dropoff_location' => 'Hyatt Regency The Kilimanjaro',
                    'pickup_time' => '05:00 PM',
                    'guest_count' => 50,
                    'need_decoration' => false,
                    'total_price' => $coachVehicle->daily_rate_tzs * 1,
                    'status' => 'confirmed',
                    'driver_name' => 'Bakari Said',
                    'driver_phone' => '+255713999888',
                    'admin_notes' => 'Corporate staff shuttle.',
                ]
            );
        }

        // -------------------------------------------------------------
        // EXAMPLE 6: UNCONFIGURED NEW HOST (For Testing Onboarding Wizard)
        // -------------------------------------------------------------
        Host::updateOrCreate(
            ['email' => 'newhost@test.com'],
            [
                'full_name' => 'Baraka Mwangi',
                'phone' => '+255712345676',
                'event_type' => 'wedding',
                'number_of_guests' => 200,
                'total_cost' => 400000.00,
                'password' => 'password123',
                'is_active' => true,
                'event_configured' => false,
                'event_config_data' => null,
            ]
        );

        // -------------------------------------------------------------
        // AUTOMATED QR GENERATION FOR ALL SEEDED INVITATIONS
        // -------------------------------------------------------------
        $qrService = app(\App\Services\InvitationQrService::class);
        foreach (Invitation::all() as $invitation) {
            try {
                $qrService->ensureQrCode($invitation);
            } catch (\Throwable $e) {
                // Non-blocking
            }
        }
    }
}
