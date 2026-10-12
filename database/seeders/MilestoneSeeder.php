<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Milestone;

class MilestoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $milestones = [
            [
                'title' => 'Weddings & Receptions',
                'slug' => 'wedding',
                'badge' => 'Most Popular',
                'tagline' => 'Royal Celebrations',
                'icon' => '💒',
                'description' => 'Send elegant digital wedding cards, manage committee contributions, seat tables, and track instant RSVPs.',
                'tags' => ['Digital Cards', 'RSVP Seating', 'Pledge Tracker'],
                'image_url' => 'media/categories/wedding.jpg',
                'button_text' => 'Explore Wedding Features',
                'button_url' => 'categories?cat=wedding',
                'theme_color' => 'purple',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Conferences & Galas',
                'slug' => 'corporate',
                'badge' => 'Corporate',
                'tagline' => 'Summits & Galas',
                'icon' => '💼',
                'description' => 'Professional delegate registration, badge issuance, VIP QR gate clearance, and instant attendance reports.',
                'tags' => ['VIP QR Passes', 'Delegate Check-in', 'Analytics'],
                'image_url' => 'media/categories/corporate.jpg',
                'button_text' => 'Explore Corporate Features',
                'button_url' => 'categories?cat=corporate',
                'theme_color' => 'blue',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Birthdays & Parties',
                'slug' => 'birthday',
                'badge' => 'Celebrations',
                'tagline' => 'Memorable Nights',
                'icon' => '🎂',
                'description' => 'Fun, vibrant invitation designs with live gift wishlists, interactive directions, and playlist suggestions.',
                'tags' => ['Live Wishlist', 'Party Themes', 'Map Directions'],
                'image_url' => 'media/categories/birthday.jpg',
                'button_text' => 'Explore Party Features',
                'button_url' => 'categories?cat=birthday',
                'theme_color' => 'pink',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Send-Off & Farewell',
                'slug' => 'send_off',
                'badge' => 'Special',
                'tagline' => 'Farewell Banquets',
                'icon' => '✈️',
                'description' => 'Celebrate farewell traditions with personalized cards, photo memory galleries, and guest coordination.',
                'tags' => ['Photo Memory', 'Custom Cards', 'Gift Pledges'],
                'image_url' => 'media/categories/sendoff.jpg',
                'button_text' => 'Explore Send-Off',
                'button_url' => 'categories?cat=send_off',
                'theme_color' => 'teal',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Kitchen Party & Showers',
                'slug' => 'kitchen_party',
                'badge' => 'Tradition',
                'tagline' => 'Cultural Traditions',
                'icon' => '🍽️',
                'description' => 'Send themed invitations, coordinate gift contributions, and ensure a seamless entry for every guest.',
                'tags' => ['Themed Invites', 'Bridal Showers', 'Gift Coordination'],
                'image_url' => 'media/categories/kitchen_party.jpg',
                'button_text' => 'Explore Kitchen Parties',
                'button_url' => 'categories?cat=kitchen_party',
                'theme_color' => 'amber',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'Concerts & Festivals',
                'slug' => 'concert',
                'badge' => 'Entertainment',
                'tagline' => 'Live Music & Shows',
                'icon' => '🎤',
                'description' => 'High-volume ticketing, rapid scanning app for gate staff, and live venue attendance dashboard.',
                'tags' => ['Fast QR Scan', 'Bulk Tickets', 'Live Gates'],
                'image_url' => 'media/categories/concert.jpg',
                'button_text' => 'Explore Concerts',
                'button_url' => 'categories?cat=concert',
                'theme_color' => 'violet',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($milestones as $item) {
            Milestone::updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}
