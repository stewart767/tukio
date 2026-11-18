@php
    $couplePhoto = $d['couple_photo'] ?? null;
    $coupleNames = $d['couple_names'] ?? ($d['event_title'] ?? 'Dr. Sadiki & Vivian');
    $guestName = $d['guest_name'] ?? 'DORIS';
    $eventDate = $d['event_date_formatted'] ?? 'Sunday, July 6, 2025';
    $dateFormatted = '06.07.2025';
    if (!empty($d['countdown_date'])) {
        $dateFormatted = \Carbon\Carbon::parse($d['countdown_date'])->format('d.m.Y');
    }
    $venue = $d['venue_name'] ?? 'Regal Naiveral Hall';
    $parentsText = $d['groom_parents'] ?? ($d['parents_names'] ?? 'MR & MRS SAIDI NAFTAL SAIDI');
    $churchName = $d['church_venue'] ?? 'Kana Central SDA Church';
@endphp

<div class="relative w-full max-w-4xl mx-auto rounded-3xl overflow-hidden shadow-2xl border border-[#d4af37]/50 select-none bg-[#0c0e14] text-white" style="aspect-ratio: 1.42;">
    <!-- Two-Panel Grid -->
    <div class="grid grid-cols-2 w-full h-full">

        <!-- ================= LEFT PANEL: COUPLE PHOTOGRAPHY & SIGNATURE ================= -->
        <div class="relative w-full h-full overflow-hidden bg-slate-900 border-r border-[#d4af37]/30">
            @if($couplePhoto)
                <img src="{{ $couplePhoto }}" alt="{{ $coupleNames }}" class="absolute inset-0 w-full h-full object-cover">
            @else
                <div class="absolute inset-0 w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 p-6 text-center text-slate-400">
                    <svg class="w-12 h-12 mb-2 text-[#d4af37]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-xs font-bold text-[#d4af37]">Couple Photo Area</span>
                </div>
            @endif

            <!-- Golden Glitter Overlay Gradient -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent pointer-events-none"></div>

            <!-- Bottom Calligraphy Names & Date Badge on Left Panel -->
            <div class="absolute bottom-4 inset-x-4 text-center z-10 space-y-1">
                <h3 class="font-script text-2xl sm:text-4xl text-[#F3E5AB] tracking-wide drop-shadow-[0_2px_8px_rgba(0,0,0,0.9)] leading-tight" style="font-family: 'Great Vibes', 'Pinyon Script', cursive;">
                    {{ $coupleNames }}
                </h3>
                <div class="flex items-center justify-center gap-2 text-[9px] sm:text-[11px] font-roman tracking-[0.2em] text-[#E5C158] uppercase">
                    <span>SUN</span>
                    <span class="text-[#d4af37] font-bold text-xs sm:text-sm">06</span>
                    <span>JULY 2025</span>
                </div>
            </div>
        </div>

        <!-- ================= RIGHT PANEL: LUXURY BLACK & GOLD PROGRAM ================= -->
        <div class="relative w-full h-full overflow-hidden bg-[#0d0f14] flex flex-col justify-between p-3.5 sm:p-5 text-center leading-tight">

            <!-- Background Texture: Marble Tear Edge on Far Right -->
            <div class="absolute inset-y-0 right-0 w-[24%] bg-gradient-to-l from-slate-200 via-slate-100 to-slate-300 opacity-90 pointer-events-none" style="background-image: radial-gradient(circle at 50% 50%, rgba(200,210,230,0.8), rgba(240,245,255,0.95));"></div>
            <!-- Gold Molten Tear Line -->
            <div class="absolute inset-y-0 right-[23.5%] w-[4px] bg-gradient-to-b from-[#f9d976] via-[#e5c158] to-[#c59b27] shadow-[0_0_12px_rgba(249,217,118,0.8)] pointer-events-none"></div>

            <!-- Content Column (Constrained inside dark section) -->
            <div class="relative z-10 w-[78%] flex flex-col justify-between h-full space-y-1 sm:space-y-1.5 py-1">

                <!-- 1. Parents Announcement -->
                <div class="space-y-0.5">
                    <p class="text-[7.5px] sm:text-[9px] font-roman tracking-[0.14em] text-slate-300 uppercase">The Family Of</p>
                    <p class="text-[8.5px] sm:text-[10px] font-roman font-extrabold tracking-[0.12em] text-[#F3E5AB] uppercase">
                        {{ $parentsText }}
                    </p>
                    <p class="text-[7px] sm:text-[8px] font-roman tracking-[0.12em] text-slate-300 uppercase">
                        Request The Pleasure Of The Company Of
                    </p>
                </div>

                <!-- 2. Guest of Honor (Dynamic Personalized Invitee Name) -->
                <div class="py-0.5">
                    <span class="inline-block text-xs sm:text-sm md:text-base font-black uppercase tracking-[0.18em] text-[#FFFFFF] border-b-2 border-[#D4AF37] pb-0.5 drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)]">
                        {{ $guestName }}
                    </span>
                </div>

                <!-- 3. Ceremony Intro -->
                <div class="space-y-0.5">
                    <p class="text-[7px] sm:text-[8px] font-roman tracking-[0.14em] text-slate-300 uppercase">To The Wedding Ceremony</p>
                    <p class="text-[7px] sm:text-[8px] font-roman tracking-[0.14em] text-slate-300 uppercase">Of Their Beloved Son &amp; Daughter</p>
                </div>

                <!-- 4. Hero Couple Calligraphy Script -->
                <div class="py-0.5">
                    <h2 class="font-script text-xl sm:text-3xl text-[#F9D976] drop-shadow-[0_2px_10px_rgba(0,0,0,0.9)] leading-tight" style="font-family: 'Great Vibes', 'Pinyon Script', cursive;">
                        {{ $coupleNames }}
                    </h2>
                </div>

                <!-- 5. Date Frame with Calendar/Clock Icon -->
                <div class="space-y-0.5">
                    <p class="text-[7px] sm:text-[8px] font-roman tracking-[0.16em] text-slate-300 uppercase">To Be Held On Sunday</p>
                    <div class="inline-flex items-center justify-center gap-1.5 px-3 py-0.5 rounded-lg border border-[#D4AF37]/80 bg-black/40 text-[#F3E5AB]">
                        <svg class="w-3 h-3 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2 2m0-2l-2 2"/></svg>
                        <span class="text-xs sm:text-sm font-roman font-black tracking-[0.15em]">{{ $dateFormatted }}</span>
                    </div>
                </div>

                <!-- 6. Two-Column Schedule / Program (Service & Reception) -->
                <div class="grid grid-cols-2 gap-2 text-center pt-0.5">
                    <!-- Church Service -->
                    <div class="space-y-0.5">
                        <div class="w-5 h-5 mx-auto rounded-full bg-white/10 border border-[#D4AF37]/60 flex items-center justify-center text-[#F3E5AB]">
                            <svg class="w-3 h-3 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <p class="text-[7px] sm:text-[8px] font-roman uppercase tracking-wider text-slate-300">Service At</p>
                        <p class="text-[7.5px] sm:text-[9px] font-serif italic text-white font-semibold leading-tight">{{ $churchName }}</p>
                        <p class="text-[6.5px] sm:text-[7.5px] font-roman text-[#D4AF37]">at 13:00hrs</p>
                    </div>

                    <!-- Reception -->
                    <div class="space-y-0.5">
                        <div class="w-5 h-5 mx-auto rounded-full bg-white/10 border border-[#D4AF37]/60 flex items-center justify-center text-[#F3E5AB]">
                            <svg class="w-3 h-3 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <p class="text-[7px] sm:text-[8px] font-roman uppercase tracking-wider text-slate-300">Reception</p>
                        <p class="text-[7.5px] sm:text-[9px] font-serif italic text-white font-semibold leading-tight">{{ $venue }}</p>
                        <p class="text-[6.5px] sm:text-[7.5px] font-roman text-[#D4AF37]">18:00hrs</p>
                    </div>
                </div>

                <!-- 7. RSVP Contacts -->
                <div class="pt-0.5 text-center leading-tight">
                    <p class="text-[7px] sm:text-[8px] font-serif italic text-[#F3E5AB] font-bold">Rsvp to</p>
                    <p class="text-[6.5px] sm:text-[7.5px] font-mono text-slate-200">Willy Kea: 0715 388 975 &bull; Saumu: 0716 736 557</p>
                </div>

                <!-- 8. Theme Colors Swatches -->
                <div class="flex items-center justify-center gap-1.5 pt-0.5">
                    <span class="text-[6.5px] sm:text-[7.5px] font-roman uppercase tracking-wider text-slate-400">Colors:</span>
                    <span class="w-3 h-3 rounded-full bg-black border border-white/40 shadow-sm" title="Black"></span>
                    <span class="w-3 h-3 rounded-full bg-[#D4AF37] border border-black/40 shadow-sm" title="Gold"></span>
                    <span class="w-3 h-3 rounded-full bg-[#800020] border border-white/40 shadow-sm" title="Maroon"></span>
                    <span class="text-[6.5px] sm:text-[7.5px] font-roman text-slate-400 italic">(Black, Gold, Maroon)</span>
                </div>

            </div>

            <!-- ================= GATE PASS QR CODE (Positioned Bottom Right) ================= -->
            <div class="absolute bottom-3 right-3 sm:bottom-4 sm:right-4 z-20 flex flex-col items-center">
                <!-- DOUBLE / VIP Badge -->
                <span class="px-2 py-0.5 mb-1 rounded bg-[#800020] text-white text-[7px] sm:text-[8px] font-black uppercase tracking-widest shadow-md">
                    DOUBLE
                </span>
                <!-- High-Resolution Scannable QR Code -->
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white p-1 rounded-xl shadow-2xl border-2 border-[#D4AF37] flex items-center justify-center cursor-pointer hover:scale-105 transition-transform" onclick="document.getElementById('qr-modal') && document.getElementById('qr-modal').classList.remove('hidden')">
                    @php
                        $qrFilePath = !empty($d['qr_code_path']) ? storage_path('app/public/' . $d['qr_code_path']) : null;
                        $qrExists = $qrFilePath && file_exists($qrFilePath);
                    @endphp
                    @if($qrExists && !empty($d['qr_code_url']))
                        <img src="{{ $d['qr_code_url'] }}" alt="QR Pass" class="w-full h-full object-contain">
                    @elseif(!empty($d['qr_code_base64']))
                        <img src="{{ $d['qr_code_base64'] }}" alt="QR Pass" class="w-full h-full object-contain">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center p-0.5 bg-white">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->margin(0)->generate($d['qr_code_value'] ?? ($d['verification_url'] ?? 'TUKIO360-ENTRY-PASS')) !!}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer Branding -->
            <div class="absolute bottom-1.5 left-4 z-10 text-[6.5px] sm:text-[7.5px] font-roman text-slate-500 tracking-wider">
                Tukio 360 &bull; Smart Way to Invite
            </div>

        </div>

    </div>
</div>
