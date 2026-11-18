@php
    $artworkUrl = $template?->artwork_url ?? ($d['cover_image'] ?? null);
    $aspectRatio = $template?->aspect_ratio ?? 0.672;
    $dynamicFields = $template ? $template->getNormalizedFields() : [];
@endphp

<div class="relative w-full {{ $aspectRatio > 1.1 ? 'max-w-4xl' : 'max-w-xl' }} mx-auto rounded-3xl overflow-hidden card-shadow border border-[#d4af37]/40 bg-[#faf7f0] select-none" style="aspect-ratio: {{ $aspectRatio }};">
    @if($artworkUrl)
        <img src="{{ $artworkUrl }}" alt="{{ $d['event_title'] ?? 'Card Artwork' }}" class="absolute inset-0 w-full h-full object-cover pointer-events-none">
    @endif

    @if(!empty($dynamicFields))
        <!-- Dynamic Overlay Fields configured via Visual Editor -->
        <div class="absolute inset-0 w-full h-full">
            @foreach($dynamicFields as $key => $field)
                @php
                    $left = ($field['x'] ?? 0) * 100;
                    $top = ($field['y'] ?? 0) * 100;
                    $w = ($field['width'] ?? 0.2) * 100;
                    $h = ($field['height'] ?? 0.1) * 100;
                    $type = $field['type'] ?? 'text';
                @endphp

                @if($type === 'qr' || $key === 'qr_code')
                    <div class="absolute flex flex-col items-center justify-center pointer-events-auto" style="left: {{ $left }}%; top: {{ $top }}%; width: {{ $w }}%; height: {{ $h }}%; z-index: 25;">
                        <!-- QR PASS Label -->
                        <div class="text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] text-[#E5C158] mb-1 flex items-center justify-center gap-1 drop-shadow-sm select-none">
                            <svg class="w-3 h-3 text-[#E5C158]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            <span>QR PASS</span>
                        </div>
                        <!-- Scannable White QR Code Frame with Gold Border -->
                        <div class="w-full flex-1 max-w-[140px] max-h-[140px] p-2 bg-white rounded-2xl shadow-2xl border-2 border-[#D4AF37] flex items-center justify-center cursor-pointer hover:scale-105 transition-transform" onclick="document.getElementById('qr-modal') && document.getElementById('qr-modal').classList.remove('hidden')">
                            @php
                                $qrFilePath = !empty($d['qr_code_path']) ? storage_path('app/public/' . $d['qr_code_path']) : null;
                                $qrImageAvailable = $qrFilePath && file_exists($qrFilePath);
                            @endphp
                            @if($qrImageAvailable && !empty($d['qr_code_url']))
                                <img src="{{ $d['qr_code_url'] }}" alt="QR Pass" class="w-full h-full object-contain">
                            @elseif(!empty($d['qr_code_base64']))
                                <img src="{{ $d['qr_code_base64'] }}" alt="QR Pass" class="w-full h-full object-contain">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-white p-0.5">
                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(140)->margin(0)->generate($d['qr_code_value'] ?? ($d['verification_url'] ?? 'TUKIO360-ENTRY-PASS')) !!}
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif($type === 'image' || str_contains($key, 'photo'))
                    @php
                        $photoSrc = $field['photo_url'] ?? ($d['couple_photo'] ?? null);
                        $fit = $field['fit'] ?? 'cover';
                        $radius = $field['border_radius'] ?? '8px';
                        $bWidth = $field['border_width'] ?? 0;
                        $bColor = $field['border_color'] ?? '#c59b27';
                    @endphp
                    @if($photoSrc)
                        <div class="absolute overflow-hidden shadow-lg" style="left: {{ $left }}%; top: {{ $top }}%; width: {{ $w }}%; height: {{ $h }}%; border-radius: {{ $radius }}; {{ $bWidth > 0 ? "border: {$bWidth}px solid {$bColor};" : '' }} z-index: 15;">
                            <img src="{{ $photoSrc }}" alt="Couple Photo" class="w-full h-full" style="object-fit: {{ $fit }};">
                        </div>
                    @endif
                @else
                    @php
                        $customText = !empty($field['custom_text']) ? $field['custom_text'] : null;
                        $text = $customText ?: match ($key) {
                            'guest_name' => $d['guest_name'] ?? 'Mgeni Rasmi',
                            'invitation_text' => $d['invitation_message'] ?? ($field['label'] ?? ''),
                            'event_title' => $d['event_title'] ?? ($d['couple_names'] ?? 'Wedding Celebration'),
                            'event_date' => $d['event_date_formatted'] ?? ($d['event_date_swahili'] ?? ''),
                            'venue' => $d['venue_name'] ?? '',
                            default => $field['label'] ?? ''
                        };
                        $font = $field['font_family'] ?? 'Playfair Display';
                        $size = $field['font_size'] ?? 22;
                        $color = $field['color'] ?? '#ffffff';
                        $align = $field['alignment'] ?? 'center';
                        $isHeading = ($key === 'guest_name');
                        $isTitle = ($key === 'event_title' || strtoupper(trim($text)) === 'HARUSI');
                        $isDate = ($key === 'event_date');
                        $isVenue = ($key === 'venue');
                    @endphp
                    <div class="absolute flex flex-col items-center justify-center overflow-hidden px-2 leading-snug" style="left: {{ $left }}%; top: {{ $top }}%; width: {{ $w }}%; height: {{ $h }}%; font-family: '{{ $font }}', serif; color: {{ $color }}; text-align: {{ $align }}; font-size: clamp(11px, 2.2vw, {{ $size }}px); z-index: 20;">
                        @if($isHeading)
                            <span class="w-full font-black uppercase tracking-[0.06em] break-words leading-tight" style="color: {{ $color }}; text-shadow: 0 2px 8px rgba(0,0,0,0.8), 0 0 12px rgba(212,175,55,0.3); font-size: 1.08em;">
                                {{ $text }}
                            </span>
                        @elseif($isTitle)
                            <div class="w-full flex items-center justify-center gap-2">
                                <span class="h-[1px] w-6 bg-gradient-to-r from-transparent to-[#F9D976]"></span>
                                <span class="font-extrabold uppercase tracking-[0.22em] break-words" style="color: {{ $color }}; text-shadow: 0 2px 10px rgba(0,0,0,0.9), 0 0 15px rgba(249,217,118,0.5); font-size: 1.15em;">
                                    {{ $text }}
                                </span>
                                <span class="h-[1px] w-6 bg-gradient-to-l from-transparent to-[#F9D976]"></span>
                            </div>
                        @elseif($isDate)
                            <div class="w-full flex items-center justify-center gap-1.5 font-medium tracking-wide" style="color: {{ $color }}; text-shadow: 0 1px 4px rgba(0,0,0,0.8);">
                                <svg class="w-3.5 h-3.5 shrink-0 text-[#E5C158]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>{{ $text }}</span>
                            </div>
                        @elseif($isVenue)
                            <div class="w-full flex items-center justify-center gap-1.5 font-medium tracking-wide" style="color: {{ $color }}; text-shadow: 0 1px 4px rgba(0,0,0,0.8);">
                                <svg class="w-3.5 h-3.5 shrink-0 text-[#E5C158]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ $text }}</span>
                            </div>
                        @else
                            <span class="w-full font-medium break-words leading-relaxed" style="text-align: {{ $align }}; text-shadow: 0 1px 3px rgba(0,0,0,0.8);">
                                {{ $text }}
                            </span>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <!-- Safe Center Text Zone (Fallback for static legacy floral cards) -->
        <div class="absolute inset-y-[11.5%] inset-x-[15%] flex flex-col items-center justify-center text-center space-y-1.5 sm:space-y-2 z-10">

            <!-- 1. Scripture / Blessing Header -->
            <p class="text-[7.5px] sm:text-[9px] font-garamond italic font-semibold text-[#5a4628] leading-tight max-w-[92%]">
                {{ $d['blessing_verse'] }}
            </p>

            <div class="w-12 h-[1px] bg-[#c59b27]/50"></div>

            <!-- 2. Parents Announcement -->
            <div class="space-y-0.5 leading-snug">
                @if(!empty($d['groom_parents']))
                    <div class="text-[9px] sm:text-[11px] font-roman font-extrabold tracking-[0.06em] text-[#0d3826] uppercase">
                        {{ $d['groom_parents'] }}
                    </div>
                @endif
                @if(!empty($d['groom_origin']))
                    <div class="text-[7px] sm:text-[8.5px] font-garamond italic font-semibold text-[#6b532e]">
                        {{ $d['groom_origin'] }}
                    </div>
                @endif

                @if(!empty($d['groom_parents']) && !empty($d['bride_parents']))
                    <div class="flex items-center justify-center gap-2 py-0.5">
                        <span class="w-6 h-[1px] bg-[#c59b27]/70"></span>
                        <span class="text-[7.5px] sm:text-[9px] font-roman font-bold text-[#9a7418] tracking-widest">NA</span>
                        <span class="w-6 h-[1px] bg-[#c59b27]/70"></span>
                    </div>
                @endif

                @if(!empty($d['bride_parents']))
                    <div class="text-[9px] sm:text-[11px] font-roman font-extrabold tracking-[0.06em] text-[#0d3826] uppercase">
                        {{ $d['bride_parents'] }}
                    </div>
                @endif
                @if(!empty($d['bride_origin']))
                    <div class="text-[7px] sm:text-[8.5px] font-garamond italic font-semibold text-[#6b532e]">
                        {{ $d['bride_origin'] }}
                    </div>
                @endif
            </div>

            <!-- 3. Warm Salutation & Honored Guest -->
            <div class="space-y-1 w-full flex flex-col items-center pt-0.5">
                <p class="text-[8.5px] sm:text-[10.5px] font-garamond italic font-semibold text-[#2c2216] leading-tight">
                    Kwa furaha, upendo na heshima kubwa, wanayo furaha kukualika
                </p>

                <div class="w-[92%] py-1 border-y border-[#c59b27]/75 bg-[#fffdf8]/60">
                    <div class="text-[10px] sm:text-[12.5px] font-roman font-extrabold tracking-[0.06em] text-[#0d3826] uppercase truncate px-1">
                        {{ $d['guest_name'] }}
                    </div>
                </div>

                <p class="text-[8.5px] sm:text-[10.5px] font-garamond italic font-semibold text-[#2c2216] leading-tight">
                    Kujumuika nao katika sherehe ya harusi ya watoto wao wapendwa
                </p>
            </div>

            <!-- 4. Couple Names in Luxury Calligraphy -->
            <div class="py-0.5">
                <h1 class="text-3xl sm:text-[42px] font-script text-[#0d3826] leading-none" style="text-shadow: 0 1px 2px rgba(197,155,39,0.28);">
                    {{ $d['couple_names'] }}
                </h1>
                <div class="flex items-center justify-center gap-2 mt-1">
                    <span class="w-10 sm:w-14 h-[1px] bg-gradient-to-r from-transparent to-[#c59b27]"></span>
                    <span class="text-[#c59b27] text-[9px] leading-none">&hearts;</span>
                    <span class="w-10 sm:w-14 h-[1px] bg-gradient-to-l from-transparent to-[#c59b27]"></span>
                </div>
            </div>

            <!-- 5. Event Schedule -->
            <div class="w-[94%] space-y-1 text-left border-y border-[#c59b27]/40 py-1.5 px-2 bg-[#fffdf9]/55 rounded-lg">
                <div class="flex items-center gap-2">
                    <span class="w-3.5 text-center text-[#9a7418] text-[9px] sm:text-[11px] shrink-0">&#128197;</span>
                    <span class="w-[1.5px] h-2.5 bg-[#c59b27] shrink-0"></span>
                    <span class="text-[8px] sm:text-[10px] font-garamond font-bold text-[#1e1810] leading-tight">
                        Siku &amp; Tarehe: <span class="text-[#0d3826]">{{ $d['event_date_swahili'] }}</span>
                    </span>
                </div>

                @if(!empty($d['ceremony_details']))
                    <div class="flex items-center gap-2">
                        <span class="w-3.5 text-center text-[#9a7418] text-[9px] sm:text-[11px] shrink-0">&#9962;</span>
                        <span class="w-[1.5px] h-2.5 bg-[#c59b27] shrink-0"></span>
                        <span class="text-[8px] sm:text-[10px] font-garamond font-bold text-[#1e1810] leading-tight">
                            Ibada ya Ndoa: <span class="font-semibold">{{ $d['ceremony_details'] }}</span>
                        </span>
                    </div>
                @endif

                <div class="flex items-center gap-2">
                    <span class="w-3.5 text-center text-[#9a7418] text-[9px] sm:text-[11px] shrink-0">&#128205;</span>
                    <span class="w-[1.5px] h-2.5 bg-[#c59b27] shrink-0"></span>
                    <span class="text-[8px] sm:text-[10px] font-garamond font-bold text-[#1e1810] leading-tight">
                        Ukumbi: <span class="text-[#0d3826]">{{ $d['venue_name'] }}</span>
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="w-3.5 text-center text-[#9a7418] text-[9px] sm:text-[11px] shrink-0">&#128338;</span>
                    <span class="w-[1.5px] h-2.5 bg-[#c59b27] shrink-0"></span>
                    <span class="text-[8px] sm:text-[10px] font-garamond font-bold text-[#1e1810] leading-tight">
                        Kuanzia Saa {{ $d['event_time'] }} Usiku @if(!empty($d['dress_code'])) &bull; <span class="text-[#0d3826]">Rangi: {{ $d['dress_code'] }}</span> @endif
                    </span>
                </div>
            </div>

            <!-- 6. Honor Quote & RSVP -->
            <div class="space-y-0.5 leading-tight">
                <p class="text-[7.5px] sm:text-[9px] font-garamond italic font-semibold text-[#4a391f]">
                    &ldquo;Ushiriki wako ndio heshima na furaha yetu kuu.&rdquo;
                </p>
                @if(!empty($d['rsvp_phone']))
                    <p class="text-[7px] sm:text-[8.5px] font-modern font-semibold text-[#0d3826]">
                        RSVP / Mawasiliano: {{ $d['rsvp_phone'] }}
                    </p>
                @endif
            </div>

            <!-- 7. Bottom Emerald Green Bar WITH Live Visible QR Code -->
            <div class="w-[95%] pt-0.5">
                <div onclick="document.getElementById('qr-modal').classList.remove('hidden')" title="Bonyeza kukuza QR Code" class="cursor-pointer w-full bg-[#0d3826] hover:bg-[#09271a] transition-all text-white rounded-xl p-1.5 sm:p-2 border border-[#c59b27] shadow-md flex items-center justify-between gap-2">
                    <!-- Visible Scannable QR Code on Card -->
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white p-0.5 rounded-lg border border-[#c59b27] shrink-0 flex items-center justify-center">
                        <img src="{{ $d['qr_code_url'] }}" alt="QR Code" class="w-full h-full object-contain">
                    </div>

                    <!-- Table, Pax & PIN Details -->
                    <div class="flex-1 grid grid-cols-3 divide-x divide-[#c59b27]/40 text-center">
                        <div class="px-1">
                            <div class="text-[6px] sm:text-[7px] uppercase tracking-wider text-[#e2c275] font-semibold">Meza</div>
                            <div class="text-[8.5px] sm:text-[10.5px] font-bold text-white leading-tight truncate">{{ $d['table_number'] }}</div>
                        </div>
                        <div class="px-1">
                            <div class="text-[6px] sm:text-[7px] uppercase tracking-wider text-[#e2c275] font-semibold">Kadi ya Watu</div>
                            <div class="text-[8.5px] sm:text-[10.5px] font-bold text-white leading-tight">{{ $d['pax'] }}</div>
                        </div>
                        <div class="px-1">
                            <div class="text-[6px] sm:text-[7px] uppercase tracking-wider text-[#e2c275] font-semibold">Gate PIN</div>
                            <div class="text-[8.5px] sm:text-[10.5px] font-mono font-bold text-[#f5d77f] leading-tight">{{ $d['pin_code'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>
