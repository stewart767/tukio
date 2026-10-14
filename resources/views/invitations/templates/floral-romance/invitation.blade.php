<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $d['event_title'] }} — {{ $d['guest_name'] }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Alex+Brush&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #FFF1F2 0%, #FFE4E6 50%, #FCE7F3 100%);
            color: #4C0519;
            min-height: 100vh;
        }

        .font-script {
            font-family: 'Alex Brush', cursive;
        }

        .font-serif {
            font-family: 'Playfair Display', Georgia, serif;
        }
    </style>
</head>
<body class="antialiased selection:bg-rose-300 selection:text-rose-950">

    <div class="max-w-2xl mx-auto px-4 py-8 sm:py-14 space-y-10">
        
        <!-- Header Floral Ornament -->
        <div class="text-center space-y-2">
            <div class="inline-flex justify-center mb-1 text-rose-400">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 7a5 5 0 1 0 5 5 5 5 0 0 0 -5 -5z"/></svg>
            </div>
            <p class="text-xs uppercase tracking-[0.25em] font-bold text-rose-800">
                {{ $d['host_name'] }}
            </p>
            <p class="text-xs italic text-rose-700/80">
                Inakualika kwa moyo wote kwenye sherehe ya
            </p>
        </div>

        <!-- Main Card -->
        <div class="rounded-3xl bg-white/80 backdrop-blur-md border border-rose-200/80 p-6 sm:p-10 shadow-xl space-y-8 text-center relative overflow-hidden">
            
            <div class="space-y-2">
                <span class="text-xs uppercase tracking-widest text-rose-700 font-extrabold bg-rose-100/80 px-3 py-1 rounded-full">
                    {{ strtoupper($d['event_category']) }}
                </span>
                
                <h1 class="text-4xl sm:text-6xl font-normal font-script text-rose-900 leading-tight pt-2">
                    {{ $d['couple_names'] }}
                </h1>

                <p class="text-xs sm:text-sm text-rose-800/80 max-w-md mx-auto pt-1 leading-relaxed font-serif">
                    "{{ $d['event_description'] }}"
                </p>
            </div>

            <!-- Couple Photo (if any) -->
            @if($d['couple_photo'])
                <div class="w-36 h-36 mx-auto rounded-full overflow-hidden border-4 border-rose-300 shadow-md">
                    <img src="{{ $d['couple_photo'] }}" alt="{{ $d['couple_names'] }}" class="w-full h-full object-cover">
                </div>
            @endif

            <!-- Personalized Guest Name -->
            <div class="py-4 border-y border-rose-100">
                <x-invitation.guest-name 
                    :name="$d['guest_name']" 
                    :table="$d['guest_table']" 
                    :type="$d['guest_type']" 
                    prefix="SPECIAL INVITATION FOR"
                    class="text-rose-900" />
            </div>

            <!-- Event Details -->
            <div class="grid grid-cols-2 gap-3 text-center">
                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-100">
                    <span class="text-xs text-rose-600 font-bold block">DATE &amp; TIME</span>
                    <span class="text-sm font-bold text-rose-950 mt-1 block">{{ $d['event_date_swahili'] }}</span>
                    <span class="text-xs text-rose-700">Saa {{ $d['event_time'] }}</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-100">
                    <span class="text-xs text-rose-600 font-bold block">VENUE</span>
                    <span class="text-sm font-bold text-rose-950 mt-1 block">{{ $d['venue_name'] }}</span>
                    <span class="text-xs text-rose-700">{{ $d['venue_address'] }}</span>
                </div>
            </div>

            <!-- Countdown Timer -->
            <div class="text-rose-900">
                <x-invitation.countdown 
                    :target="$d['countdown_target_iso']" 
                    label="SHEREHE INAANZA BAADA YA" />
            </div>

            <!-- QR Code Pass -->
            <x-invitation.qr-code 
                :qr-url="$d['qr_code_url']" 
                :code="$d['invitation_code']" 
                :guest-name="$d['guest_name']" 
                :table="$d['guest_table']" />

        </div>

        <!-- Venue Navigation Component -->
        <x-invitation.map 
            :venue="$d['venue_name']" 
            :address="$d['venue_address']" 
            :map-url="$d['venue_map_url']" 
            :embed-url="$d['venue_embed_map_url']"
            class="bg-white/70 text-rose-900 border-rose-200" />

        <!-- Photo Gallery (if images provided) -->
        @if(!empty($d['gallery']))
            <x-invitation.gallery 
                :images="$d['gallery']" 
                title="MEMORIES &amp; MOMENTS"
                class="text-rose-900" />
        @endif

        <!-- RSVP Component -->
        <x-invitation.rsvp 
            :submit-url="$d['rsvp_submit_url']" 
            :current-status="$d['rsvp_status']" 
            :rsvp-at="$d['rsvp_at']" 
            :guest-name="$d['guest_name']" 
            class="bg-rose-900/90 text-white" />

        <!-- Footer -->
        <div class="text-center text-xs text-rose-800/70 space-y-1 pb-10">
            <p>Tukio 360 &bull; Floral Digital Invitation</p>
        </div>

    </div>

</body>
</html>
