<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $d['event_title'] ?? 'Wedding Invitation' }} &bull; {{ $d['guest_name'] ?? 'Mgeni Rasmi' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;900&family=Great+Vibes&family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .font-serif-luxe { font-family: 'Playfair Display', Georgia, serif; }
        .font-script { font-family: 'Great Vibes', cursive; }
        .font-roman { font-family: 'Cinzel', serif; }
        .font-modern { font-family: 'Montserrat', sans-serif; }

        .card-shadow {
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4), 0 0 40px rgba(168, 85, 247, 0.15);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-purple-950 text-slate-100 antialiased font-modern py-8 px-4 sm:px-6">

    <div class="max-w-4xl mx-auto space-y-8">
        
        <!-- Top Status Bar & Quick Actions -->
        <div class="flex items-center justify-between bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4 shadow-lg">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold tracking-wider text-purple-200">Kadi ya Mwaliko</span>
            </div>

            <div class="flex items-center gap-2">
                @if(!empty($d['pdf_download_url']) && $d['pdf_download_url'] !== '#')
                    <a href="{{ $d['pdf_download_url'] }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/20 hover:bg-white/30 text-xs font-bold text-white transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Download PDF</span>
                    </a>
                @endif
                <button type="button" onclick="document.getElementById('rsvp-modal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-xs font-bold text-white shadow-md transition-all">
                    <span>RSVP Now</span>
                </button>
            </div>
        </div>

        <!-- Centerpiece: The Visual Invitation Card with Normalized Overlays -->
        <div class="flex justify-center">
            @php
                $artworkUrl = $template?->artwork_url ?? ($d['cover_image'] ?? null);
                $aspectRatio = $template?->aspect_ratio ?? 0.707;
                $fields = $template?->getNormalizedFields() ?? [];
            @endphp

            <div class="relative w-full max-w-xl rounded-3xl overflow-hidden card-shadow border border-white/20 bg-slate-900 select-none" style="aspect-ratio: {{ $aspectRatio }};">
                @if($artworkUrl)
                    <img src="{{ $artworkUrl }}" alt="{{ $d['event_title'] ?? 'Card Artwork' }}" class="absolute inset-0 w-full h-full object-cover pointer-events-none">
                @else
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-900 via-indigo-950 to-slate-950"></div>
                @endif

                <!-- Dynamic Normalized Fields Overlay Layer -->
                @foreach($fields as $fieldKey => $f)
                    @php
                        $left = ($f['x'] ?? 0) * 100;
                        $top = ($f['y'] ?? 0) * 100;
                        $widthPct = ($f['width'] ?? 0.8) * 100;
                        $heightPct = ($f['height'] ?? 0.08) * 100;
                        $align = $f['alignment'] ?? 'center';
                        $color = $f['color'] ?? '#ffffff';
                        $fontFamily = match($f['font_family'] ?? '') {
                            'Cinzel' => "'Cinzel', serif",
                            'Great Vibes' => "'Great Vibes', cursive",
                            'Montserrat' => "'Montserrat', sans-serif",
                            'Inter' => "'Inter', sans-serif",
                            default => "'Playfair Display', Georgia, serif"
                        };
                        $type = $f['type'] ?? 'text';
                    @endphp

                    <div class="absolute flex items-center {{ $align === 'center' ? 'justify-center text-center' : ($align === 'right' ? 'justify-end text-right' : 'justify-start text-left') }} p-1 overflow-hidden"
                         style="left: {{ $left }}%; top: {{ $top }}%; width: {{ $widthPct }}%; height: {{ $heightPct }}%;">
                        
                        @if($type === 'qr' || $fieldKey === 'qr_code')
                            <div class="bg-white p-1 rounded-xl shadow-lg border border-slate-200 aspect-square h-full flex items-center justify-center">
                                @if(!empty($d['qr_code_url']))
                                    <img src="{{ $d['qr_code_url'] }}" alt="QR Pass" class="h-full w-full object-contain">
                                @elseif(!empty($d['qr_code_base64']))
                                    <img src="{{ $d['qr_code_base64'] }}" alt="QR Pass" class="h-full w-full object-contain">
                                @else
                                    <div class="text-[9px] text-slate-800 font-mono font-bold">QR PASS</div>
                                @endif
                            </div>
                        @elseif($type === 'image' || $fieldKey === 'couple_photo')
                            @if(!empty($d['couple_photo']))
                                <img src="{{ $d['couple_photo'] }}" alt="Couple" class="h-full w-full object-cover rounded-xl shadow-md border-2 border-white/80">
                            @endif
                        @elseif(empty($f['is_static']))
                            @php
                                $val = match($fieldKey) {
                                    'guest_name' => $d['guest_name'] ?? 'Mpendwa Mgeni Wetu',
                                    'invitation_text' => $d['invitation_message'] ?? ($d['whatsapp_message'] ?? 'Inayofuraha kukualika kwenye sherehe...'),
                                    'event_title' => $d['event_title'] ?? ($d['couple_names'] ?? 'Wedding Celebration'),
                                    'event_date' => $d['event_date_formatted'] ?? ($d['event_date_swahili'] ?? 'Tarehe Rasmi'),
                                    'venue' => $d['venue_name'] ?? ($d['venue_address'] ?? 'Ukumbi wa Sherehe'),
                                    default => $f['default_value'] ?? ''
                                };
                            @endphp
                            <div class="w-full font-bold leading-tight" style="font-family: {!! $fontFamily !!}; color: {{ $color }};">
                                {{ $val }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Venue Location & Directions Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white/10 backdrop-blur-md border border-white/10 rounded-3xl p-6 sm:p-8 shadow-xl">
            <div class="space-y-3">
                <span class="text-xs font-bold text-purple-300 uppercase tracking-wider">Venue &amp; Location</span>
                <h3 class="text-xl font-bold font-serif-luxe text-white">{{ $d['venue_name'] ?? 'Venue' }}</h3>
                <p class="text-xs text-slate-300 leading-relaxed">{{ $d['venue_address'] ?? '' }}</p>
                <div class="pt-2">
                    <a href="{{ $d['venue_map_url'] ?? '#' }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-xs font-bold text-white shadow-md transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Open in Google Maps</span>
                    </a>
                </div>
            </div>

            <div class="space-y-3 border-t md:border-t-0 md:border-l border-white/10 pt-4 md:pt-0 md:pl-6">
                <span class="text-xs font-bold text-purple-300 uppercase tracking-wider">Event Schedule</span>
                <div class="text-lg font-bold text-white">{{ $d['event_date_formatted'] ?? ($d['event_date_swahili'] ?? 'Tarehe ya Tukio') }}</div>
                <div class="text-xs text-slate-300">Muda: {{ $d['event_time'] ?? '14:00' }} &bull; Meza: {{ $d['guest_table'] ?? 'General Seating' }}</div>
                <div class="text-xs text-emerald-400 font-semibold">Tafadhali wasilisha QR Code yako langoni kwa uthibitisho wa haraka.</div>
            </div>
        </div>
    </div>

    <!-- Interactive RSVP Modal -->
    <div id="rsvp-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-slate-900 border border-purple-500/30 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-5 text-white">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <h3 class="text-lg font-bold font-serif-luxe">Confirm Your Attendance (RSVP)</h3>
                <button type="button" onclick="document.getElementById('rsvp-modal').classList.add('hidden')" class="text-slate-400 hover:text-white">&times;</button>
            </div>

            <form id="rsvp-form" onsubmit="submitRsvp(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Attendance Status</label>
                    <select id="rsvp_status" required class="w-full bg-slate-800 border border-white/20 rounded-xl px-3 py-2 text-xs text-white">
                        <option value="attending">Yes, I will attend (Nitakuwepo)</option>
                        <option value="declined">Sorry, cannot attend (Sitoweza kufika)</option>
                        <option value="tentative">Tentative (Sina uhakika bado)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Plus-One Companion Count</label>
                    <input type="number" id="plus_one_count" min="0" max="4" value="0" class="w-full bg-slate-800 border border-white/20 rounded-xl px-3 py-2 text-xs text-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Message for the Host</label>
                    <textarea id="rsvp_notes" rows="2" placeholder="Salamu na pongezi..." class="w-full bg-slate-800 border border-white/20 rounded-xl px-3 py-2 text-xs text-white"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('rsvp-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-800 text-xs font-bold text-slate-300">Cancel</button>
                    <button type="submit" id="btn-submit-rsvp" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-xs font-bold text-white shadow-md">Submit RSVP</button>
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
