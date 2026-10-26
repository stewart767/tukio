@php
    $artworkUrl = $template?->artwork_url ?? ($d['cover_image'] ?? null);
    $aspectRatio = $template?->aspect_ratio ?? 0.672;
@endphp

<div class="relative w-full max-w-lg rounded-3xl overflow-hidden card-shadow border border-[#d4af37]/40 bg-[#faf7f0] select-none" style="aspect-ratio: {{ $aspectRatio }};">
    @if($artworkUrl)
        <img src="{{ $artworkUrl }}" alt="{{ $d['event_title'] ?? 'Card Artwork' }}" class="absolute inset-0 w-full h-full object-cover pointer-events-none">
    @endif

    <!-- Safe Center Text Zone (Strictly within the floral borders, centered vertically & horizontally) -->
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
</div>
