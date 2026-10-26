<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SampleCardTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $adminId = $admin?->id ?? 1;

        $sourceDir = 'C:/Users/admin/Downloads/templates samples';
        $targetArtworkDir = storage_path('app/public/templates/artwork');
        $targetPreviewDir = storage_path('app/public/templates/previews');

        File::ensureDirectoryExists($targetArtworkDir);
        File::ensureDirectoryExists($targetPreviewDir);

        $definitions = [
            [
                'source_name' => 'Custom Luxury Wedding Invitations.jpg',
                'slug' => 'luxury-ivory-gold-nuptials',
                'name' => 'Royal Ivory & Gold Floral Nuptials',
                'category' => 'wedding',
                'template_type' => 'premium',
                'price' => 0.00,
                'description' => 'Kadi ya kifahari yenye nakshi za dhahabu, maua meupe na muundo wa kifalme kwa ajili ya harusi kuu.',
                'fields' => [
                    'guest_name' => ['x' => 0.1, 'y' => 0.14, 'width' => 0.8, 'height' => 0.07, 'alignment' => 'center', 'color' => '#8B6508', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.9rem, 2.2vw, 1.4rem)', 'letter_spacing' => '0.05em', 'text_transform' => 'uppercase'],
                    'invitation_text' => ['x' => 0.15, 'y' => 0.22, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#5C4A32', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.7rem, 1.6vw, 0.95rem)', 'default_value' => 'Wanakukaribisha kwa heshima kubwa kwenye sherehe ya harusi yao'],
                    'couple_names' => ['x' => 0.1, 'y' => 0.30, 'width' => 0.8, 'height' => 0.13, 'alignment' => 'center', 'color' => '#1C1917', 'font_family' => 'Great Vibes', 'font_size' => 'clamp(1.8rem, 4.5vw, 2.8rem)'],
                    'event_date' => ['x' => 0.15, 'y' => 0.44, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#3D2F1D', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.8rem, 1.8vw, 1.1rem)'],
                    'venue' => ['x' => 0.15, 'y' => 0.51, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#5C4A32', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.6vw, 0.95rem)'],
                    'table_number' => ['x' => 0.15, 'y' => 0.58, 'width' => 0.7, 'height' => 0.05, 'alignment' => 'center', 'color' => '#8B6508', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.75rem, 1.6vw, 1rem)'],
                    'qr_code' => ['x' => 0.37, 'y' => 0.65, 'width' => 0.26, 'height' => 0.18, 'type' => 'qr', 'alignment' => 'center'],
                    'pin_code' => ['x' => 0.2, 'y' => 0.85, 'width' => 0.6, 'height' => 0.05, 'alignment' => 'center', 'color' => '#1C1917', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.8vw, 1.05rem)'],
                    'pax' => ['x' => 0.2, 'y' => 0.90, 'width' => 0.6, 'height' => 0.04, 'alignment' => 'center', 'color' => '#8B6508', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.65rem, 1.4vw, 0.85rem)'],
                ]
            ],
            [
                'source_name' => 'Sage Green & White Wedding Invitation Template _ Elegant Garden Floral Wedding.jpg',
                'slug' => 'sage-green-botanical-garden',
                'name' => 'Sage Green & Botanical Garden Wedding',
                'category' => 'wedding',
                'template_type' => 'standard',
                'price' => 0.00,
                'description' => 'Mwaliko wa kisasa wenye majani asilia ya sage green na maua meupe yanayoleta utulivu na haiba ya bustani.',
                'fields' => [
                    'guest_name' => ['x' => 0.1, 'y' => 0.12, 'width' => 0.8, 'height' => 0.07, 'alignment' => 'center', 'color' => '#2D4A2D', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.85rem, 2vw, 1.3rem)', 'letter_spacing' => '0.05em'],
                    'invitation_text' => ['x' => 0.15, 'y' => 0.20, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#4A6B4A', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.7rem, 1.6vw, 0.95rem)', 'default_value' => 'Kwa furaha na heshima kubwa mnakaribishwa kwenye sherehe ya harusi ya'],
                    'couple_names' => ['x' => 0.1, 'y' => 0.28, 'width' => 0.8, 'height' => 0.14, 'alignment' => 'center', 'color' => '#142914', 'font_family' => 'Great Vibes', 'font_size' => 'clamp(1.9rem, 4.8vw, 3rem)'],
                    'event_date' => ['x' => 0.15, 'y' => 0.43, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#1E3A1E', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.8rem, 1.8vw, 1.1rem)'],
                    'venue' => ['x' => 0.15, 'y' => 0.50, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#3D5C3D', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.6vw, 0.95rem)'],
                    'table_number' => ['x' => 0.15, 'y' => 0.57, 'width' => 0.7, 'height' => 0.05, 'alignment' => 'center', 'color' => '#2D4A2D', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.75rem, 1.6vw, 1rem)'],
                    'qr_code' => ['x' => 0.37, 'y' => 0.64, 'width' => 0.26, 'height' => 0.18, 'type' => 'qr', 'alignment' => 'center'],
                    'pin_code' => ['x' => 0.2, 'y' => 0.84, 'width' => 0.6, 'height' => 0.05, 'alignment' => 'center', 'color' => '#142914', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.8vw, 1.05rem)'],
                    'pax' => ['x' => 0.2, 'y' => 0.89, 'width' => 0.6, 'height' => 0.04, 'alignment' => 'center', 'color' => '#4A6B4A', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.65rem, 1.4vw, 0.85rem)'],
                ]
            ],
            [
                'source_name' => '💙 Navy Blue & Pearl Floral Frame _ Elegant Aesthetic Design.jpg',
                'slug' => 'royal-navy-pearl-floral',
                'name' => 'Royal Navy Blue & Pearl Floral Nuptials',
                'category' => 'wedding',
                'template_type' => 'premium',
                'price' => 0.00,
                'description' => 'Mwaliko wa bluu ya kifalme (Navy Blue) iliyopambwa kwa shanga za lulu na maua ya dhahabu.',
                'fields' => [
                    'guest_name' => ['x' => 0.1, 'y' => 0.13, 'width' => 0.8, 'height' => 0.07, 'alignment' => 'center', 'color' => '#E5C158', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.85rem, 2vw, 1.3rem)', 'letter_spacing' => '0.06em', 'text_shadow' => '0 2px 4px rgba(0,0,0,0.8)'],
                    'invitation_text' => ['x' => 0.15, 'y' => 0.21, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#E2E8F0', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.7rem, 1.6vw, 0.95rem)', 'text_shadow' => '0 1px 3px rgba(0,0,0,0.8)'],
                    'couple_names' => ['x' => 0.1, 'y' => 0.29, 'width' => 0.8, 'height' => 0.14, 'alignment' => 'center', 'color' => '#FFFFFF', 'font_family' => 'Great Vibes', 'font_size' => 'clamp(1.9rem, 4.8vw, 3.1rem)', 'text_shadow' => '0 3px 6px rgba(0,0,0,0.9)'],
                    'event_date' => ['x' => 0.15, 'y' => 0.44, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#E5C158', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.8rem, 1.8vw, 1.1rem)', 'text_shadow' => '0 1px 3px rgba(0,0,0,0.8)'],
                    'venue' => ['x' => 0.15, 'y' => 0.51, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#F8FAFC', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.6vw, 0.95rem)', 'text_shadow' => '0 1px 3px rgba(0,0,0,0.8)'],
                    'table_number' => ['x' => 0.15, 'y' => 0.58, 'width' => 0.7, 'height' => 0.05, 'alignment' => 'center', 'color' => '#E5C158', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.75rem, 1.6vw, 1rem)'],
                    'qr_code' => ['x' => 0.37, 'y' => 0.65, 'width' => 0.26, 'height' => 0.18, 'type' => 'qr', 'alignment' => 'center'],
                    'pin_code' => ['x' => 0.2, 'y' => 0.85, 'width' => 0.6, 'height' => 0.05, 'alignment' => 'center', 'color' => '#FFFFFF', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.8vw, 1.05rem)'],
                    'pax' => ['x' => 0.2, 'y' => 0.90, 'width' => 0.6, 'height' => 0.04, 'alignment' => 'center', 'color' => '#E5C158', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.65rem, 1.4vw, 0.85rem)'],
                ]
            ],
            [
                'source_name' => 'Victorian Floral Burgundy Framed Lined and Unlined Writing Papers--Instant Download.jpg',
                'slug' => 'victorian-burgundy-rose-gala',
                'name' => 'Victorian Burgundy & Rose Gold Gala',
                'category' => 'send_off',
                'template_type' => 'premium',
                'price' => 0.00,
                'description' => 'Mwaliko mzuri wa rangi ya divai (Burgundy) yenye nakshi za kipekee kwa sherehe za Send-off na Harusi.',
                'fields' => [
                    'guest_name' => ['x' => 0.1, 'y' => 0.13, 'width' => 0.8, 'height' => 0.07, 'alignment' => 'center', 'color' => '#7A1C33', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.85rem, 2vw, 1.25rem)'],
                    'invitation_text' => ['x' => 0.15, 'y' => 0.21, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#5C2D3A', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.7rem, 1.6vw, 0.95rem)'],
                    'couple_names' => ['x' => 0.1, 'y' => 0.29, 'width' => 0.8, 'height' => 0.13, 'alignment' => 'center', 'color' => '#3B0F1C', 'font_family' => 'Great Vibes', 'font_size' => 'clamp(1.8rem, 4.5vw, 2.8rem)'],
                    'event_date' => ['x' => 0.15, 'y' => 0.43, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#7A1C33', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.8rem, 1.8vw, 1.1rem)'],
                    'venue' => ['x' => 0.15, 'y' => 0.50, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#4A1525', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.6vw, 0.95rem)'],
                    'table_number' => ['x' => 0.15, 'y' => 0.57, 'width' => 0.7, 'height' => 0.05, 'alignment' => 'center', 'color' => '#8B263E', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.75rem, 1.6vw, 1rem)'],
                    'qr_code' => ['x' => 0.37, 'y' => 0.64, 'width' => 0.26, 'height' => 0.18, 'type' => 'qr', 'alignment' => 'center'],
                    'pin_code' => ['x' => 0.2, 'y' => 0.84, 'width' => 0.6, 'height' => 0.05, 'alignment' => 'center', 'color' => '#3B0F1C', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.8vw, 1.05rem)'],
                    'pax' => ['x' => 0.2, 'y' => 0.89, 'width' => 0.6, 'height' => 0.04, 'alignment' => 'center', 'color' => '#7A1C33', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.65rem, 1.4vw, 0.85rem)'],
                ]
            ],
            [
                'source_name' => 'Green Velvet Islamic Background with Gold Border.jpg',
                'slug' => 'emerald-velvet-gold-nikah',
                'name' => 'Emerald Velvet & Gold Border (Nikah / Walima)',
                'category' => 'traditional',
                'template_type' => 'premium',
                'price' => 0.00,
                'description' => 'Mwaliko mzuri wa kitamaduni na kidini wenye rangi ya kijani kibichi cha velvet na mipaka ya dhahabu.',
                'fields' => [
                    'guest_name' => ['x' => 0.1, 'y' => 0.13, 'width' => 0.8, 'height' => 0.07, 'alignment' => 'center', 'color' => '#FFD700', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.85rem, 2vw, 1.3rem)', 'letter_spacing' => '0.06em'],
                    'invitation_text' => ['x' => 0.15, 'y' => 0.21, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#F1F5F9', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.7rem, 1.6vw, 0.95rem)'],
                    'couple_names' => ['x' => 0.1, 'y' => 0.29, 'width' => 0.8, 'height' => 0.14, 'alignment' => 'center', 'color' => '#FFD700', 'font_family' => 'Great Vibes', 'font_size' => 'clamp(1.9rem, 4.8vw, 3.1rem)'],
                    'event_date' => ['x' => 0.15, 'y' => 0.44, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#FFFFFF', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.8rem, 1.8vw, 1.1rem)'],
                    'venue' => ['x' => 0.15, 'y' => 0.51, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#E2E8F0', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.6vw, 0.95rem)'],
                    'table_number' => ['x' => 0.15, 'y' => 0.58, 'width' => 0.7, 'height' => 0.05, 'alignment' => 'center', 'color' => '#FFD700', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.75rem, 1.6vw, 1rem)'],
                    'qr_code' => ['x' => 0.37, 'y' => 0.65, 'width' => 0.26, 'height' => 0.18, 'type' => 'qr', 'alignment' => 'center'],
                    'pin_code' => ['x' => 0.2, 'y' => 0.85, 'width' => 0.6, 'height' => 0.05, 'alignment' => 'center', 'color' => '#FFFFFF', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.8vw, 1.05rem)'],
                    'pax' => ['x' => 0.2, 'y' => 0.90, 'width' => 0.6, 'height' => 0.04, 'alignment' => 'center', 'color' => '#FFD700', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.65rem, 1.4vw, 0.85rem)'],
                ]
            ],
            [
                'source_name' => 'Елегантний червоно-золотий шаблон для привітань _ Розкішна рамка для тексту.jpg',
                'slug' => 'crimson-red-royal-gold',
                'name' => 'Crimson Red & Royal Gold Gala',
                'category' => 'send_off',
                'template_type' => 'premium',
                'price' => 0.00,
                'description' => 'Mwaliko wa rangi nyekundu ya kifalme iliyopambwa kwa dhahabu safi (Royal Crimson & Gold).',
                'fields' => [
                    'guest_name' => ['x' => 0.1, 'y' => 0.13, 'width' => 0.8, 'height' => 0.07, 'alignment' => 'center', 'color' => '#D4AF37', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.85rem, 2vw, 1.3rem)'],
                    'invitation_text' => ['x' => 0.15, 'y' => 0.21, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#FFF5EA', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.7rem, 1.6vw, 0.95rem)'],
                    'couple_names' => ['x' => 0.1, 'y' => 0.29, 'width' => 0.8, 'height' => 0.14, 'alignment' => 'center', 'color' => '#FFDF79', 'font_family' => 'Great Vibes', 'font_size' => 'clamp(1.9rem, 4.8vw, 3.1rem)'],
                    'event_date' => ['x' => 0.15, 'y' => 0.44, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#FFFFFF', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.8rem, 1.8vw, 1.1rem)'],
                    'venue' => ['x' => 0.15, 'y' => 0.51, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#FEE2E2', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.6vw, 0.95rem)'],
                    'table_number' => ['x' => 0.15, 'y' => 0.58, 'width' => 0.7, 'height' => 0.05, 'alignment' => 'center', 'color' => '#D4AF37', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.75rem, 1.6vw, 1rem)'],
                    'qr_code' => ['x' => 0.37, 'y' => 0.65, 'width' => 0.26, 'height' => 0.18, 'type' => 'qr', 'alignment' => 'center'],
                    'pin_code' => ['x' => 0.2, 'y' => 0.85, 'width' => 0.6, 'height' => 0.05, 'alignment' => 'center', 'color' => '#FFFFFF', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.8vw, 1.05rem)'],
                    'pax' => ['x' => 0.2, 'y' => 0.90, 'width' => 0.6, 'height' => 0.04, 'alignment' => 'center', 'color' => '#D4AF37', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.65rem, 1.4vw, 0.85rem)'],
                ]
            ],
            [
                'source_name' => 'Beige, Brown,Black coloured Birthday wishes.jpg',
                'slug' => 'modern-mocha-birthday-jubilee',
                'name' => 'Modern Mocha & Gold Birthday Jubilee',
                'category' => 'birthday',
                'template_type' => 'standard',
                'price' => 0.00,
                'description' => 'Mwaliko maalum wa kumbukizi ya kuzaliwa (Birthday) wenye muonekano wa kahawia (Mocha) na dhahabu.',
                'fields' => [
                    'guest_name' => ['x' => 0.1, 'y' => 0.13, 'width' => 0.8, 'height' => 0.07, 'alignment' => 'center', 'color' => '#6B4226', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.85rem, 2vw, 1.3rem)'],
                    'invitation_text' => ['x' => 0.15, 'y' => 0.21, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#4A3528', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.7rem, 1.6vw, 0.95rem)', 'default_value' => 'Unakaribishwa kwa furaha kusherehekea kumbukizi ya kuzaliwa ya'],
                    'event_title' => ['x' => 0.1, 'y' => 0.29, 'width' => 0.8, 'height' => 0.13, 'alignment' => 'center', 'color' => '#2E1C14', 'font_family' => 'Great Vibes', 'font_size' => 'clamp(1.8rem, 4.5vw, 2.9rem)'],
                    'event_date' => ['x' => 0.15, 'y' => 0.43, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#6B4226', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.8rem, 1.8vw, 1.1rem)'],
                    'venue' => ['x' => 0.15, 'y' => 0.50, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#2E1C14', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.6vw, 0.95rem)'],
                    'table_number' => ['x' => 0.15, 'y' => 0.57, 'width' => 0.7, 'height' => 0.05, 'alignment' => 'center', 'color' => '#6B4226', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.75rem, 1.6vw, 1rem)'],
                    'qr_code' => ['x' => 0.37, 'y' => 0.64, 'width' => 0.26, 'height' => 0.18, 'type' => 'qr', 'alignment' => 'center'],
                    'pin_code' => ['x' => 0.2, 'y' => 0.84, 'width' => 0.6, 'height' => 0.05, 'alignment' => 'center', 'color' => '#2E1C14', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.8vw, 1.05rem)'],
                    'pax' => ['x' => 0.2, 'y' => 0.89, 'width' => 0.6, 'height' => 0.04, 'alignment' => 'center', 'color' => '#6B4226', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.65rem, 1.4vw, 0.85rem)'],
                ]
            ],
            [
                'source_name' => 'Cute Birthday Wishes Card💜.jpg',
                'slug' => 'chic-lavender-purple-birthday',
                'name' => 'Chic Lavender & Purple Birthday Celebration',
                'category' => 'birthday',
                'template_type' => 'standard',
                'price' => 0.00,
                'description' => 'Mwaliko mzuri na mchangamfu wa birthday wenye rangi ya zambarau (Lavender) na mapambo ya kisasa.',
                'fields' => [
                    'guest_name' => ['x' => 0.1, 'y' => 0.13, 'width' => 0.8, 'height' => 0.07, 'alignment' => 'center', 'color' => '#681A82', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.85rem, 2vw, 1.3rem)'],
                    'invitation_text' => ['x' => 0.15, 'y' => 0.21, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#4A1C5C', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.7rem, 1.6vw, 0.95rem)'],
                    'event_title' => ['x' => 0.1, 'y' => 0.29, 'width' => 0.8, 'height' => 0.13, 'alignment' => 'center', 'color' => '#3B154A', 'font_family' => 'Great Vibes', 'font_size' => 'clamp(1.8rem, 4.5vw, 2.9rem)'],
                    'event_date' => ['x' => 0.15, 'y' => 0.43, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#681A82', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.8rem, 1.8vw, 1.1rem)'],
                    'venue' => ['x' => 0.15, 'y' => 0.50, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#3B154A', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.6vw, 0.95rem)'],
                    'table_number' => ['x' => 0.15, 'y' => 0.57, 'width' => 0.7, 'height' => 0.05, 'alignment' => 'center', 'color' => '#7A2299', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.75rem, 1.6vw, 1rem)'],
                    'qr_code' => ['x' => 0.37, 'y' => 0.64, 'width' => 0.26, 'height' => 0.18, 'type' => 'qr', 'alignment' => 'center'],
                    'pin_code' => ['x' => 0.2, 'y' => 0.84, 'width' => 0.6, 'height' => 0.05, 'alignment' => 'center', 'color' => '#3B154A', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.8vw, 1.05rem)'],
                    'pax' => ['x' => 0.2, 'y' => 0.89, 'width' => 0.6, 'height' => 0.04, 'alignment' => 'center', 'color' => '#681A82', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.65rem, 1.4vw, 0.85rem)'],
                ]
            ],
            [
                'source_name' => 'elegant, pink & gold, floral invitation template 🌷✨💕.jpg',
                'slug' => 'blush-pink-gold-kitchen-party',
                'name' => 'Blush Pink & Gold Floral Kitchen Party',
                'category' => 'kitchen_party',
                'template_type' => 'standard',
                'price' => 0.00,
                'description' => 'Mwaliko mwanana wa rangi ya waridi na dhahabu (Blush Pink & Gold) uliotengenezwa mahususi kwa ajili ya Kitchen Party na Bridal Shower.',
                'fields' => [
                    'guest_name' => ['x' => 0.1, 'y' => 0.13, 'width' => 0.8, 'height' => 0.07, 'alignment' => 'center', 'color' => '#9E425E', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.85rem, 2vw, 1.3rem)'],
                    'invitation_text' => ['x' => 0.15, 'y' => 0.21, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#5C2739', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.7rem, 1.6vw, 0.95rem)'],
                    'couple_names' => ['x' => 0.1, 'y' => 0.29, 'width' => 0.8, 'height' => 0.13, 'alignment' => 'center', 'color' => '#3B1220', 'font_family' => 'Great Vibes', 'font_size' => 'clamp(1.8rem, 4.5vw, 2.9rem)'],
                    'event_date' => ['x' => 0.15, 'y' => 0.43, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#9E425E', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.8rem, 1.8vw, 1.1rem)'],
                    'venue' => ['x' => 0.15, 'y' => 0.50, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#4A1D2D', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.6vw, 0.95rem)'],
                    'table_number' => ['x' => 0.15, 'y' => 0.57, 'width' => 0.7, 'height' => 0.05, 'alignment' => 'center', 'color' => '#9E425E', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.75rem, 1.6vw, 1rem)'],
                    'qr_code' => ['x' => 0.37, 'y' => 0.64, 'width' => 0.26, 'height' => 0.18, 'type' => 'qr', 'alignment' => 'center'],
                    'pin_code' => ['x' => 0.2, 'y' => 0.84, 'width' => 0.6, 'height' => 0.05, 'alignment' => 'center', 'color' => '#3B1220', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.8vw, 1.05rem)'],
                    'pax' => ['x' => 0.2, 'y' => 0.89, 'width' => 0.6, 'height' => 0.04, 'alignment' => 'center', 'color' => '#9E425E', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.65rem, 1.4vw, 0.85rem)'],
                ]
            ],
            [
                'source_name' => 'baby breath flower theme.jpg',
                'slug' => 'babys-breath-romantic-floral',
                'name' => "Baby's Breath Romantic Floral Nuptials",
                'category' => 'wedding',
                'template_type' => 'standard',
                'price' => 0.00,
                'description' => "Mwaliko wa kisasa wenye maua madogo madogo meupe ya Baby's Breath yanayoashiria upendo safi na wa kudumu.",
                'fields' => [
                    'guest_name' => ['x' => 0.1, 'y' => 0.13, 'width' => 0.8, 'height' => 0.07, 'alignment' => 'center', 'color' => '#374151', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.85rem, 2vw, 1.3rem)'],
                    'invitation_text' => ['x' => 0.15, 'y' => 0.21, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#4B5563', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.7rem, 1.6vw, 0.95rem)'],
                    'couple_names' => ['x' => 0.1, 'y' => 0.29, 'width' => 0.8, 'height' => 0.13, 'alignment' => 'center', 'color' => '#111827', 'font_family' => 'Great Vibes', 'font_size' => 'clamp(1.8rem, 4.5vw, 2.9rem)'],
                    'event_date' => ['x' => 0.15, 'y' => 0.43, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#374151', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.8rem, 1.8vw, 1.1rem)'],
                    'venue' => ['x' => 0.15, 'y' => 0.50, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#1F2937', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.6vw, 0.95rem)'],
                    'table_number' => ['x' => 0.15, 'y' => 0.57, 'width' => 0.7, 'height' => 0.05, 'alignment' => 'center', 'color' => '#4B5563', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.75rem, 1.6vw, 1rem)'],
                    'qr_code' => ['x' => 0.37, 'y' => 0.64, 'width' => 0.26, 'height' => 0.18, 'type' => 'qr', 'alignment' => 'center'],
                    'pin_code' => ['x' => 0.2, 'y' => 0.84, 'width' => 0.6, 'height' => 0.05, 'alignment' => 'center', 'color' => '#111827', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.8vw, 1.05rem)'],
                    'pax' => ['x' => 0.2, 'y' => 0.89, 'width' => 0.6, 'height' => 0.04, 'alignment' => 'center', 'color' => '#6B7280', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.65rem, 1.4vw, 0.85rem)'],
                ]
            ],
            [
                'source_name' => '🌾Pretty BoHo Rococco Stationary WallPaper for your Phone🌺.jpg',
                'slug' => 'boho-earth-botanical-reception',
                'name' => 'Boho Earth Tone & Botanical Reception',
                'category' => 'traditional',
                'template_type' => 'standard',
                'price' => 0.00,
                'description' => 'Mwaliko mzuri wa mtindo wa Bohemian wenye rangi za asili (Terracotta & Earth tones) unaofaa kwa sherehe za kitamaduni na harusi.',
                'fields' => [
                    'guest_name' => ['x' => 0.1, 'y' => 0.13, 'width' => 0.8, 'height' => 0.07, 'alignment' => 'center', 'color' => '#7A3F20', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.85rem, 2vw, 1.3rem)'],
                    'invitation_text' => ['x' => 0.15, 'y' => 0.21, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#5C3119', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.7rem, 1.6vw, 0.95rem)'],
                    'couple_names' => ['x' => 0.1, 'y' => 0.29, 'width' => 0.8, 'height' => 0.13, 'alignment' => 'center', 'color' => '#2E180B', 'font_family' => 'Great Vibes', 'font_size' => 'clamp(1.8rem, 4.5vw, 2.9rem)'],
                    'event_date' => ['x' => 0.15, 'y' => 0.43, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#7A3F20', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.8rem, 1.8vw, 1.1rem)'],
                    'venue' => ['x' => 0.15, 'y' => 0.50, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#3D2415', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.6vw, 0.95rem)'],
                    'table_number' => ['x' => 0.15, 'y' => 0.57, 'width' => 0.7, 'height' => 0.05, 'alignment' => 'center', 'color' => '#7A3F20', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.75rem, 1.6vw, 1rem)'],
                    'qr_code' => ['x' => 0.37, 'y' => 0.64, 'width' => 0.26, 'height' => 0.18, 'type' => 'qr', 'alignment' => 'center'],
                    'pin_code' => ['x' => 0.2, 'y' => 0.84, 'width' => 0.6, 'height' => 0.05, 'alignment' => 'center', 'color' => '#2E180B', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.8vw, 1.05rem)'],
                    'pax' => ['x' => 0.2, 'y' => 0.89, 'width' => 0.6, 'height' => 0.04, 'alignment' => 'center', 'color' => '#5C3119', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.65rem, 1.4vw, 0.85rem)'],
                ]
            ],
            [
                'source_name' => 'wedding 3.jpg',
                'slug' => 'vintage-laurel-gold-nuptials',
                'name' => 'Vintage Laurel & Rose Nuptials',
                'category' => 'wedding',
                'template_type' => 'standard',
                'price' => 0.00,
                'description' => 'Mwaliko wa kisasa wenye muundo wa shada la maua (Laurel Crest) linaloashiria ushindi na maisha marefu ya ndoa.',
                'fields' => [
                    'guest_name' => ['x' => 0.1, 'y' => 0.13, 'width' => 0.8, 'height' => 0.07, 'alignment' => 'center', 'color' => '#4A5035', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.85rem, 2vw, 1.3rem)'],
                    'invitation_text' => ['x' => 0.15, 'y' => 0.21, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#3D422C', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.7rem, 1.6vw, 0.95rem)'],
                    'couple_names' => ['x' => 0.1, 'y' => 0.29, 'width' => 0.8, 'height' => 0.13, 'alignment' => 'center', 'color' => '#1F2218', 'font_family' => 'Great Vibes', 'font_size' => 'clamp(1.8rem, 4.5vw, 2.9rem)'],
                    'event_date' => ['x' => 0.15, 'y' => 0.43, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#4A5035', 'font_family' => 'Playfair Display', 'font_size' => 'clamp(0.8rem, 1.8vw, 1.1rem)'],
                    'venue' => ['x' => 0.15, 'y' => 0.50, 'width' => 0.7, 'height' => 0.06, 'alignment' => 'center', 'color' => '#2A2E20', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.6vw, 0.95rem)'],
                    'table_number' => ['x' => 0.15, 'y' => 0.57, 'width' => 0.7, 'height' => 0.05, 'alignment' => 'center', 'color' => '#5C6346', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.75rem, 1.6vw, 1rem)'],
                    'qr_code' => ['x' => 0.37, 'y' => 0.64, 'width' => 0.26, 'height' => 0.18, 'type' => 'qr', 'alignment' => 'center'],
                    'pin_code' => ['x' => 0.2, 'y' => 0.84, 'width' => 0.6, 'height' => 0.05, 'alignment' => 'center', 'color' => '#1F2218', 'font_family' => 'Montserrat', 'font_size' => 'clamp(0.75rem, 1.8vw, 1.05rem)'],
                    'pax' => ['x' => 0.2, 'y' => 0.89, 'width' => 0.6, 'height' => 0.04, 'alignment' => 'center', 'color' => '#3D422C', 'font_family' => 'Cinzel', 'font_size' => 'clamp(0.65rem, 1.4vw, 0.85rem)'],
                ]
            ]
        ];

        foreach ($definitions as $def) {
            $srcPath = $this->findSourceFile($sourceDir, $def['source_name']);
            if (!$srcPath || !File::exists($srcPath)) {
                continue;
            }

            $imageInfo = @getimagesize($srcPath);
            $width = $imageInfo[0] ?? 736;
            $height = $imageInfo[1] ?? 1104;

            $ext = pathinfo($srcPath, PATHINFO_EXTENSION);
            $cleanFilename = $def['slug'] . '.' . $ext;

            $destArtworkPath = $targetArtworkDir . DIRECTORY_SEPARATOR . $cleanFilename;
            $destPreviewPath = $targetPreviewDir . DIRECTORY_SEPARATOR . $cleanFilename;

            File::copy($srcPath, $destArtworkPath);
            File::copy($srcPath, $destPreviewPath);

            $relativeArtworkPath = 'templates/artwork/' . $cleanFilename;
            $relativePreviewPath = 'templates/previews/' . $cleanFilename;

            $template = Template::firstOrNew(['slug' => $def['slug']]);
            $template->name = $def['name'];
            $template->category = $def['category'];
            $template->template_type = $def['template_type'];
            $template->price = $def['price'];
            $template->currency = 'TZS';
            $template->is_paid = false;
            $template->version = '2.0';
            $template->description = $def['description'];
            $template->html_content = '<div class="card-overlay-container"></div>';
            $template->css_content = '';
            $template->preview_image = $relativePreviewPath;
            $template->artwork_path = $relativeArtworkPath;
            $template->artwork_width = $width;
            $template->artwork_height = $height;
            $template->view_path = 'invitations.templates.artwork-overlay.invitation';
            $template->placeholder_config = ['fields' => $def['fields']];
            $template->analysis_status = 'completed';
            $template->approval_status = 'approved';
            $template->is_active = true;
            $template->created_by = $adminId;
            $template->features = [
                'Personalized Guest Name',
                'High-Resolution Card Design',
                'Dynamic QR Code Pass',
                '4-Digit Kitochi PIN Check-in',
                'Table Number Display',
                'Interactive Attendance (RSVP)',
                'Google Maps Location Integration',
                'Instant PDF Download',
                'WhatsApp & SMS Friendly'
            ];
            $template->save();
        }
    }

    protected function findSourceFile(string $dir, string $filename): ?string
    {
        if (!File::isDirectory($dir)) {
            return null;
        }

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() === $filename) {
                return $file->getRealPath();
            }
        }
        return null;
    }
}
