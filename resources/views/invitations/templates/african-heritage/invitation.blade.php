<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $d['event_title'] }} — {{ $d['guest_name'] }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at 50% 0%, #3D1C10 0%, #200E08 70%, #150905 100%);
            color: #FEFAE0;
            min-height: 100vh;
        }

        .font-heading {
            font-family: 'Cinzel', serif;
        }

        .earth-gradient-text {
            background: linear-gradient(135deg, #FEFAE0 0%, #DDA15E 50%, #BC6C25 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="antialiased selection:bg-amber-600 selection:text-white">

    <div class="max-w-2xl mx-auto px-4 py-8 sm:py-16 space-y-12">
        
        <!-- Header Motif -->
        <div class="text-center space-y-3">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-amber-900/40 border border-amber-600/40 text-amber-400 text-2xl shadow-lg">
                🌍
            </div>
            
            <p class="text-xs uppercase tracking-[0.3em] font-extrabold text-amber-300">
                {{ $d['host_name'] }}
            </p>
            <p class="text-xs italic text-amber-100/70">
                Inakualika kwa heshima na taadhima kwenye sherehe ya
            </p>
        </div>

        <!-- Main Card -->
        <div class="rounded-3xl bg-amber-950/40 backdrop-blur-xl border border-amber-600/30 p-6 sm:p-10 shadow-2xl space-y-8 text-center">
            
            <div class="space-y-3">
                <span class="text-xs uppercase tracking-widest text-amber-950 font-black bg-amber-400 px-3.5 py-1 rounded-full">
                    {{ strtoupper($d['event_category']) }}
                </span>

                <h1 class="text-3xl sm:text-5xl font-black font-heading earth-gradient-text leading-tight pt-2">
                    {{ $d['couple_names'] }}
                </h1>

                <p class="text-xs sm:text-sm text-amber-100/80 max-w-md mx-auto pt-1 leading-relaxed">
                    {{ $d['event_description'] }}
                </p>
            </div>

            <!-- Couple Portrait (if any) -->
            @if($d['couple_photo'])
                <div class="w-36 h-36 mx-auto rounded-full overflow-hidden border-4 border-amber-500 shadow-xl">
                    <img src="{{ $d['couple_photo'] }}" alt="{{ $d['couple_names'] }}" class="w-full h-full object-cover">
                </div>
            @endif

            <!-- Personalized Guest Name -->
            <div class="py-4 border-y border-amber-700/40">
                <x-invitation.guest-name 
                    :name="$d['guest_name']" 
                    :table="$d['guest_table']" 
                    :type="$d['guest_type']" 
                    prefix="MWALIKO MAHUSUSI KWA" />
            </div>

            <!-- Date and Venue Grid -->
            <div class="grid grid-cols-2 gap-3 text-center">
                <div class="p-4 rounded-2xl bg-black/30 border border-amber-700/30">
                    <span class="text-xs text-amber-400 font-bold block">TAREHE NA MUDA</span>
                    <span class="text-sm font-bold text-white mt-1 block">{{ $d['event_date_swahili'] }}</span>
                    <span class="text-xs text-amber-200">Saa {{ $d['event_time'] }}</span>
                </div>

                <div class="p-4 rounded-2xl bg-black/30 border border-amber-700/30">
                    <span class="text-xs text-amber-400 font-bold block">ENEO NA UKUMBI</span>
                    <span class="text-sm font-bold text-white mt-1 block">{{ $d['venue_name'] }}</span>
                    <span class="text-xs text-amber-200">{{ $d['venue_address'] }}</span>
                </div>
            </div>

            <!-- Countdown Timer -->
            <x-invitation.countdown 
                :target="$d['countdown_target_iso']" 
                label="SIKU ZILIZOBAKI KUFANYIKA" />

            <!-- QR Code Pass -->
            <x-invitation.qr-code 
                :qr-url="$d['qr_code_url']" 
                :code="$d['invitation_code']" 
                :guest-name="$d['guest_name']" 
                :table="$d['guest_table']" />

        </div>

        <!-- Venue Location Component -->
        <x-invitation.map 
            :venue="$d['venue_name']" 
            :address="$d['venue_address']" 
            :map-url="$d['venue_map_url']" 
            :embed-url="$d['venue_embed_map_url']" />

        <!-- Photo Gallery -->
        @if(!empty($d['gallery']))
            <x-invitation.gallery 
                :images="$d['gallery']" 
                title="KUMBUKUMBU NA MATUKIO" />
        @endif

        <!-- RSVP Component -->
        <x-invitation.rsvp 
            :submit-url="$d['rsvp_submit_url']" 
            :current-status="$d['rsvp_status']" 
            :rsvp-at="$d['rsvp_at']" 
            :guest-name="$d['guest_name']" />

        <!-- Footer -->
        <div class="text-center text-xs text-amber-400/60 space-y-1 pb-10">
            <p>Tukio 360 &bull; African Heritage Edition</p>
        </div>

    </div>

</body>
</html>
