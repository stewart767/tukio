<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $d['event_title'] ?? 'Wedding Invitation' }} &bull; {{ $d['guest_name'] ?? 'Mgeni Rasmi' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800;900&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Pinyon+Script&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .font-garamond { font-family: 'Cormorant Garamond', Georgia, serif; }
        .font-script { font-family: 'Pinyon Script', cursive; }
        .font-roman { font-family: 'Cinzel', serif; }
        .font-modern { font-family: 'Montserrat', sans-serif; }

        .card-shadow {
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4), 0 0 40px rgba(168, 85, 247, 0.15);
        }
    </style>
</head>
<body class="min-h-screen bg-[#f3ffe3] text-slate-900 antialiased font-modern py-8 px-4 sm:px-6" style="background-color: #f3ffe3;">

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

        <!-- Centerpiece: data-driven invitation card -->
        <div class="flex justify-center">
            @include('invitations.templates.artwork-overlay._card')
        </div>

        <!-- Venue Location & Directions Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white/90 backdrop-blur-md border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm">
            <div class="space-y-3">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Ukumbi na Mahali (Venue)</span>
                <h3 class="text-xl font-bold font-serif-luxe text-slate-900">{{ $d['venue_name'] ?? 'Venue' }}</h3>
                <p class="text-xs text-slate-600 leading-relaxed">{{ $d['venue_address'] ?? '' }}</p>
                <div class="pt-2">
                    <a href="{{ $d['venue_map_url'] ?? '#' }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-xs font-bold text-white shadow-sm transition-all">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Fungua Google Maps</span>
                    </a>
                </div>
            </div>

            <div class="space-y-3 border-t md:border-t-0 md:border-l border-slate-200 pt-4 md:pt-0 md:pl-6">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Ratiba ya Tukio</span>
                <div class="text-lg font-bold text-slate-900">{{ $d['event_date_formatted'] ?? ($d['event_date_swahili'] ?? 'Tarehe ya Tukio') }}</div>
                <div class="text-xs text-slate-600">Muda: <span class="font-bold text-slate-900">{{ $d['event_time'] ?? '14:00' }}</span> &bull; Meza: <span class="font-bold text-slate-900">{{ $d['guest_table'] ?? 'General Seating' }}</span></div>
                <div class="text-xs text-emerald-800 font-semibold bg-emerald-50 border border-emerald-200 rounded-lg p-2.5">
                    Tafadhali wasilisha QR Code au PIN yako langoni kwa wahudumu (Scanner) kwa uthibitisho wa haraka.
                </div>
            </div>
        </div>
    </div>

    <!-- Official Gate QR Pass Modal -->
    <div id="qr-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 backdrop-blur-sm p-4">
        <div class="bg-white border border-[#c59b27]/50 rounded-3xl p-6 sm:p-8 max-w-sm w-full shadow-2xl text-center space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <h3 class="text-xs font-bold font-roman tracking-wider text-[#0d3826] uppercase">Tukio 360 &bull; Gate Pass</h3>
                </div>
                <button type="button" onclick="document.getElementById('qr-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 text-xl font-bold">&times;</button>
            </div>

            <div class="py-2">
                <div class="w-48 h-48 mx-auto bg-white p-2 rounded-2xl border-2 border-[#c59b27]/60 shadow-inner flex items-center justify-center">
                    @if(!empty($d['qr_code_url']))
                        <img src="{{ $d['qr_code_url'] }}" alt="QR Pass" class="w-full h-full object-contain">
                    @elseif(!empty($d['qr_code_base64']))
                        <img src="{{ $d['qr_code_base64'] }}" alt="QR Pass" class="w-full h-full object-contain">
                    @else
                        <div class="text-xs font-mono font-bold text-slate-700">QR CODE PASS</div>
                    @endif
                </div>
            </div>

            <div class="space-y-1">
                <div class="text-sm font-roman font-bold text-[#0d3826] uppercase tracking-wide">
                    {{ $d['guest_name'] ?? '' }}
                </div>
                <div class="text-xs text-slate-500 font-garamond italic">Tafadhali onyesha msimbo huu langoni kwa scanner</div>
            </div>

            <div class="grid grid-cols-3 gap-2 bg-[#0d3826] text-white rounded-xl p-2.5 border border-[#c59b27]">
                <div>
                    <div class="text-[7.5px] text-[#e2c275] uppercase font-semibold">Meza</div>
                    <div class="text-xs font-bold">{{ $d['table_number'] ?? 'VIP 1' }}</div>
                </div>
                <div>
                    <div class="text-[7.5px] text-[#e2c275] uppercase font-semibold">Watu</div>
                    <div class="text-xs font-bold">{{ $d['pax'] ?? 2 }}</div>
                </div>
                <div>
                    <div class="text-[7.5px] text-[#e2c275] uppercase font-semibold">PIN</div>
                    <div class="text-xs font-mono font-bold text-[#f5d77f]">{{ $d['pin_code'] ?? '4821' }}</div>
                </div>
            </div>

            <button type="button" onclick="document.getElementById('qr-modal').classList.add('hidden')" class="w-full py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-700">
                Funga
            </button>
        </div>
    </div>

    <!-- Interactive RSVP Modal -->
    <div id="rsvp-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-5 text-slate-800">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <h3 class="text-lg font-bold font-serif-luxe text-slate-900">Thibitisha Ushiriki Wako (RSVP)</h3>
                <button type="button" onclick="document.getElementById('rsvp-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 text-xl font-bold">&times;</button>
            </div>

            <form id="rsvp-form" onsubmit="submitRsvp(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hali ya Mahudhurio</label>
                    <select id="rsvp_status" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 focus:ring-2 focus:ring-emerald-500">
                        <option value="attending">Ndiyo, nitahudhuria (Nitakuwepo)</option>
                        <option value="declined">Hapana, sitoweza kufika</option>
                        <option value="tentative">Sina uhakika bado</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Idadi ya Watu Unaombatana Nao</label>
                    <input type="number" id="plus_one_count" min="0" max="4" value="0" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Ujumbe kwa Waandaaji</label>
                    <textarea id="rsvp_notes" rows="2" placeholder="Salamu, hongera au ujumbe..." class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('rsvp-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 text-xs font-bold text-slate-600 hover:bg-slate-200">Ghairi</button>
                    <button type="submit" id="btn-submit-rsvp" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-xs font-bold text-white shadow-md">Tuma Jibu</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function submitRsvp(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-submit-rsvp');
        btn.disabled = true;

        const payload = {
            rsvp_status: document.getElementById('rsvp_status').value,
            plus_one_count: parseInt(document.getElementById('plus_one_count').value) || 0,
            rsvp_notes: document.getElementById('rsvp_notes').value,
        };

        const rsvpUrl = '{{ $d['rsvp_submit_url'] ?? '#' }}';
        if (!rsvpUrl || rsvpUrl === '#') {
            alert('RSVP submitted successfully (Preview Mode)!');
            document.getElementById('rsvp-modal').classList.add('hidden');
            btn.disabled = false;
            return;
        }

        fetch(rsvpUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            if (data.success) {
                alert(data.message || 'Asante! Uthibitisho wako umepokelewa.');
                document.getElementById('rsvp-modal').classList.add('hidden');
            } else {
                alert('Hitilafu: ' + (data.message || 'Haikuweza kuhifadhi.'));
            }
        })
        .catch(err => {
            btn.disabled = false;
            alert('Error: ' + err.message);
        });
    }
    </script>
</body>
</html>
