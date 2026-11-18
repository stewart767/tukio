<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $d['event_title'] ?? 'Wedding Invitation' }} &bull; {{ $d['guest_name'] ?? 'Mgeni Rasmi' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800;900&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Great+Vibes&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .font-garamond { font-family: 'Cormorant Garamond', Georgia, serif; }
        .font-script { font-family: 'Great Vibes', cursive; }
        .font-roman { font-family: 'Cinzel', serif; }
        .font-modern { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-[#f3ffe3] text-slate-900 antialiased font-modern py-8 px-4 sm:px-6">

    <div class="max-w-4xl mx-auto space-y-8">
        
        <!-- Top Status Bar & Quick Actions -->
        <div class="flex items-center justify-between bg-white/90 backdrop-blur-md border border-slate-200/80 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-bold tracking-wider text-slate-700 uppercase">Kadi Rasmi ya Mwaliko</span>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" onclick="document.getElementById('qr-modal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#0d3826] hover:bg-[#072418] text-xs font-bold text-[#f5d77f] shadow-sm transition-all border border-[#c59b27]/60">
                    <svg class="w-3.5 h-3.5 text-[#c59b27]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    <span>QR Pass Langoni</span>
                </button>
                @if(!empty($d['pdf_download_url']) && $d['pdf_download_url'] !== '#')
                    <a href="{{ $d['pdf_download_url'] }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-xs font-bold text-white shadow-sm transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Download PDF</span>
                    </a>
                @endif
                <button type="button" onclick="document.getElementById('rsvp-modal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-xs font-bold text-white shadow-sm transition-all">
                    <span>Thibitisha (RSVP)</span>
                </button>
            </div>
        </div>

        <!-- Centerpiece: Royal Black & Gold Double Card -->
        <div class="flex justify-center">
            @include('invitations.templates.royal-black-gold-double._card')
        </div>

        <!-- Venue Location & Directions Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white/90 backdrop-blur-md border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm">
            <div class="space-y-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold">📍</div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-purple-700">Ukumbi na Mahali (Venue)</span>
                        <h4 class="text-lg font-extrabold text-slate-900">{{ $d['venue_name'] ?? 'Regal Naiveral Hall' }}</h4>
                    </div>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">{{ $d['venue_address'] ?? 'Regal Naiveral Banquet Hall, Dar es Salaam' }}</p>
                @if(!empty($d['venue_map_url']))
                    <a href="{{ $d['venue_map_url'] }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-purple-700 hover:text-purple-800 bg-purple-50 hover:bg-purple-100 px-3.5 py-2 rounded-xl border border-purple-200/80 transition-all">
                        <span>Fungua Google Maps</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                @endif
            </div>

            <div class="space-y-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">📅</div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Ratiba ya Tukio</span>
                        <h4 class="text-lg font-extrabold text-slate-900">{{ $d['event_date_formatted'] ?? 'Sunday, July 6, 2025' }}</h4>
                    </div>
                </div>
                <div class="rounded-2xl bg-emerald-50/60 border border-emerald-200/80 p-3.5 text-xs text-emerald-900 space-y-1">
                    <p class="font-bold">Muda: {{ $d['event_time'] ?? '1:00 PM' }} &bull; Meza: {{ $d['guest_table'] ?? 'VIP Table' }}</p>
                    <p class="text-[11px] text-emerald-700">Tafadhali wasilisha QR Code au PIN yako langoni kwa wahudumu (Scanner) kwa uthibitisho wa haraka.</p>
                </div>
            </div>
        </div>

    </div>

    <!-- QR Entry Pass Modal (Pop-up on click) -->
    <div id="qr-modal" class="hidden fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full text-center space-y-4 border border-[#d4af37]/60 shadow-2xl relative">
            <button type="button" onclick="document.getElementById('qr-modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="space-y-1">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black tracking-widest bg-emerald-100 text-emerald-700 uppercase">Msimbo wa Kuingilia</span>
                <h3 class="text-lg font-extrabold text-slate-900">{{ $d['guest_name'] ?? 'Mgeni Rasmi' }}</h3>
                <p class="text-xs text-slate-500">Wasilisha msimbo huu langoni ili uthibitishwe</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 flex justify-center">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->margin(1)->generate($d['qr_code_value'] ?? ($d['verification_url'] ?? 'TUKIO360-ENTRY-PASS')) !!}
            </div>
            <p class="text-[11px] font-mono text-slate-400">PIN: {{ $d['guest_pin'] ?? '7492' }}</p>
        </div>
    </div>

</body>
</html>
