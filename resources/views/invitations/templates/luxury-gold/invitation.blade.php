<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $d['event_title'] }} — {{ $d['guest_name'] }}</title>
    
    <!-- Meta tags for social sharing -->
    <meta property="og:title" content="{{ $d['event_title'] }}">
    <meta property="og:description" content="Unaalikwa rasmi: {{ $d['couple_names'] }} - {{ $d['event_date_swahili'] }}">
    @if($d['couple_photo'])
        <meta property="og:image" content="{{ $d['couple_photo'] }}">
    @endif

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Great+Vibes&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at 50% 0%, #17382c 0%, #081711 75%, #040c09 100%);
            color: #FAF8F5;
            min-height: 100vh;
        }

        .font-heading {
            font-family: 'Cormorant Garamond', Georgia, serif;
        }

        .font-script {
            font-family: 'Great Vibes', cursive;
        }

        .gold-gradient-text {
            background: linear-gradient(135deg, #FFF6D5 0%, #E5C365 40%, #D4AF37 70%, #AA771C 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gold-border-glow {
            border: 1px solid rgba(212, 175, 55, 0.35);
            box-shadow: 0 0 35px rgba(212, 175, 55, 0.12);
        }
    </style>
</head>
<body class="antialiased selection:bg-amber-400 selection:text-slate-950">

    <!-- Watermark notice if unpaid preview -->
    @if(!empty($d['watermark']))
        <div class="sticky top-0 z-50 bg-rose-600/90 backdrop-blur-md text-white py-2 text-center text-xs font-black tracking-widest uppercase shadow-lg">
            {{ $d['watermark'] }}
        </div>
    @endif

    <!-- Main Container -->
    <div class="max-w-2xl mx-auto px-4 py-8 sm:py-16 space-y-12">

        <!-- Top Header & Emblem -->
        <div class="text-center space-y-3">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full border border-amber-400/30 bg-amber-400/10 backdrop-blur-md text-amber-300 shadow-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
            
            <p class="text-xs uppercase tracking-[0.3em] font-semibold text-amber-200/80">
                {{ $d['host_name'] }}
            </p>
            <p class="text-xs italic text-slate-300">
                Inayo heshima na furaha kubwa kukualika wewe na familia yako kwenye
            </p>
        </div>

        <!-- Main Invitation Card Container -->
        <div class="relative rounded-3xl p-6 sm:p-10 bg-slate-900/60 backdrop-blur-2xl border border-amber-400/20 shadow-2xl space-y-8 gold-border-glow">
            
            <!-- Event Title & Couple -->
            <div class="text-center space-y-3">
                <span class="text-xs uppercase tracking-[0.25em] text-amber-300 font-bold bg-amber-400/10 px-3 py-1 rounded-full border border-amber-400/20">
                    {{ strtoupper($d['event_category']) }} CELEBRATION
                </span>

                <h1 class="text-4xl sm:text-6xl font-normal font-script gold-gradient-text leading-tight pt-2">
                    {{ $d['couple_names'] }}
                </h1>

                <p class="text-xs sm:text-sm text-slate-300 max-w-md mx-auto leading-relaxed pt-2">
                    {{ $d['event_description'] }}
                </p>
            </div>

            <!-- Couple Portrait (if available) -->
            @if($d['couple_photo'])
                <div class="w-36 h-36 sm:w-44 sm:h-44 mx-auto rounded-full overflow-hidden border-4 border-amber-400/60 shadow-2xl p-1 bg-amber-400/20">
                    <img src="{{ $d['couple_photo'] }}" alt="{{ $d['couple_names'] }}" class="w-full h-full object-cover rounded-full">
                </div>
            @endif

            <!-- Guest Personalization Block -->
            <div class="py-4 border-y border-amber-400/20">
                <x-invitation.guest-name 
                    :name="$d['guest_name']" 
                    :table="$d['guest_table']" 
                    :type="$d['guest_type']" />
            </div>

            <!-- Date, Time & Venue Key Highlights -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-center">
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                    <div class="flex items-center justify-center text-amber-400 mb-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="text-xs uppercase tracking-widest text-slate-400">Tarehe (Date)</div>
                    <div class="font-bold text-sm sm:text-base text-amber-100 mt-0.5">{{ $d['event_date_swahili'] }}</div>
                    <div class="text-[11px] text-slate-400">Saa {{ $d['event_time'] }} Jioni</div>
                </div>

                <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                    <div class="flex items-center justify-center text-amber-400 mb-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="text-xs uppercase tracking-widest text-slate-400">Ukumbi (Venue)</div>
                    <div class="font-bold text-sm sm:text-base text-amber-100 mt-0.5">{{ $d['venue_name'] }}</div>
                    <div class="text-[11px] text-slate-400">{{ $d['venue_address'] }}</div>
                </div>
            </div>

            <!-- Countdown Timer Component -->
            <x-invitation.countdown 
                :target="$d['countdown_target_iso']" 
                label="Muda Uliobaki (Countdown)" />

            <!-- QR Code Entry Pass -->
            <div class="pt-2 text-center">
                <x-invitation.qr-code 
                    :qr-url="$d['qr_code_url']" 
                    :code="$d['invitation_code']" 
                    :guest-name="$d['guest_name']" 
                    :table="$d['guest_table']" />
            </div>

        </div>

        <!-- Venue Location & Map -->
        <x-invitation.map 
            :venue="$d['venue_name']" 
            :address="$d['venue_address']" 
            :map-url="$d['venue_map_url']" 
            :embed-url="$d['venue_embed_map_url']" />

        <!-- Photo Gallery (if images provided) -->
        @if(!empty($d['gallery']))
            <x-invitation.gallery 
                :images="$d['gallery']" 
                title="PICHA NA MATUKIO" />
        @endif

        <!-- Interactive RSVP Section -->
        <x-invitation.rsvp 
            :submit-url="$d['rsvp_submit_url']" 
            :current-status="$d['rsvp_status']" 
            :rsvp-at="$d['rsvp_at']" 
            :guest-name="$d['guest_name']" />

        <!-- Bottom Action Bar (PDF Download, WhatsApp Share, Calendar) -->
        <div class="p-6 rounded-3xl bg-slate-900/80 border border-amber-400/20 backdrop-blur-xl shadow-xl flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
            <div>
                <h4 class="font-bold text-sm text-white">Pakua Kadi au Shiriki</h4>
                <p class="text-xs text-slate-400">Download printable PDF or share via WhatsApp</p>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-3">
                @if($d['pdf_download_url'] && $d['pdf_download_url'] !== '#')
                    <a href="{{ $d['pdf_download_url'] }}" class="inline-flex items-center gap-2 py-2.5 px-4 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all">
                        <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Download PDF</span>
                    </a>
                @endif

                <a href="{{ $d['whatsapp_share_url'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md transition-all">
                    <span>WhatsApp</span>
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-slate-500 space-y-1 pb-10">
            <p>Imetengenezwa na <span class="font-bold text-amber-400">Tukio 360</span> &bull; Digital Invitation Foundry</p>
            <p>&copy; {{ date('Y') }} Tukio 360. Haki Zote Zimehifadhiwa.</p>
        </div>

    </div>

</body>
</html>
