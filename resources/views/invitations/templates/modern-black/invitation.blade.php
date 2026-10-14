<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $d['event_title'] }} — {{ $d['guest_name'] }}</title>

    <!-- Meta tags -->
    <meta property="og:title" content="{{ $d['event_title'] }}">
    <meta property="og:description" content="{{ $d['event_description'] }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Syne:wght@700;800;900&display=swap');

        body {
            font-family: 'Outfit', sans-serif;
            background: #090A0F;
            color: #F8FAFC;
        }

        .font-heading {
            font-family: 'Syne', sans-serif;
        }

        .neon-accent {
            background: linear-gradient(135deg, #6366F1 0%, #A855F7 50%, #EC4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Watermark notice if unpaid preview -->
    @if(!empty($d['watermark']))
        <div class="sticky top-0 z-50 bg-rose-600 text-white py-2 text-center text-xs font-black tracking-widest uppercase">
            {{ $d['watermark'] }}
        </div>
    @endif

    <div class="max-w-2xl mx-auto px-4 py-10 sm:py-16 space-y-12">
        
        <!-- Header Tag -->
        <div class="text-center space-y-3">
            <div class="inline-block px-3.5 py-1 rounded-full border border-amber-500/30 bg-amber-500/10 text-amber-300 text-xs font-semibold tracking-wider">
                Mwaliko Maalum
            </div>

            <h1 class="text-3xl sm:text-5xl font-extrabold font-heading tracking-tight leading-tight pt-2">
                {{ $d['event_title'] }}
            </h1>

            <p class="text-xs sm:text-sm text-slate-400 max-w-md mx-auto">
                {{ $d['event_description'] }}
            </p>
        </div>

        <!-- Main Obsidian Card -->
        <div class="rounded-3xl bg-slate-900/90 border border-slate-800 p-6 sm:p-10 shadow-2xl space-y-8 backdrop-blur-xl">
            
            <!-- Guest Badge -->
            <div class="p-4 rounded-2xl bg-indigo-950/40 border border-indigo-500/30 text-center">
                <x-invitation.guest-name 
                    :name="$d['guest_name']" 
                    :table="$d['guest_table']" 
                    :type="$d['guest_type']" 
                    prefix="ADMIT ONE VIP" />
            </div>

            <!-- Event Date & Time Grid -->
            <div class="grid grid-cols-2 gap-4 text-center">
                <div class="p-4 rounded-2xl bg-black/40 border border-slate-800">
                    <span class="text-xs text-slate-500 font-bold block">DATE</span>
                    <span class="text-base font-bold text-white mt-1 block">{{ $d['event_date_formatted'] }}</span>
                </div>

                <div class="p-4 rounded-2xl bg-black/40 border border-slate-800">
                    <span class="text-xs text-slate-500 font-bold block">TIME</span>
                    <span class="text-base font-bold text-white mt-1 block">{{ $d['event_time'] }} HRS</span>
                </div>
            </div>

            <!-- Venue Details -->
            <div class="p-4 rounded-2xl bg-black/40 border border-slate-800 text-center">
                <span class="text-xs text-slate-500 font-bold block">LOCATION &amp; VENUE</span>
                <span class="text-lg font-bold text-white mt-0.5 block">{{ $d['venue_name'] }}</span>
                <span class="text-xs text-slate-400 block mt-0.5">{{ $d['venue_address'] }}</span>
            </div>

            <!-- Countdown Timer -->
            <x-invitation.countdown 
                :target="$d['countdown_target_iso']" 
                label="SUMMIT COUNTDOWN" />

            <!-- QR Verification Pass -->
            <div class="text-center pt-2">
                <x-invitation.qr-code 
                    :qr-url="$d['qr_code_url']" 
                    :code="$d['invitation_code']" 
                    :guest-name="$d['guest_name']" 
                    :table="$d['guest_table']" />
            </div>

        </div>

        <!-- Venue Navigation Component -->
        <x-invitation.map 
            :venue="$d['venue_name']" 
            :address="$d['venue_address']" 
            :map-url="$d['venue_map_url']" 
            :embed-url="$d['venue_embed_map_url']" />

        <!-- RSVP Component -->
        <x-invitation.rsvp 
            :submit-url="$d['rsvp_submit_url']" 
            :current-status="$d['rsvp_status']" 
            :rsvp-at="$d['rsvp_at']" 
            :guest-name="$d['guest_name']" />

        <!-- Bottom Actions -->
        <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
            <div>
                <h4 class="font-bold text-sm text-white">Save Digital Pass</h4>
                <p class="text-xs text-slate-400">Download for offline check-in</p>
            </div>

            <div class="flex items-center gap-3">
                @if($d['pdf_download_url'] && $d['pdf_download_url'] !== '#')
                    <a href="{{ $d['pdf_download_url'] }}" class="py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition-all">
                        Download PDF Pass
                    </a>
                @endif

                <a href="{{ $d['whatsapp_share_url'] }}" target="_blank" rel="noopener noreferrer" class="py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all">
                    WhatsApp Share
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-slate-600 space-y-1 pb-10">
            <p>Powered by <span class="text-slate-400 font-bold">Tukio 360</span> &bull; Executive Event Platform</p>
        </div>

    </div>

</body>
</html>
