@php
    $artworkUrl = $template?->artwork_url ?? ($d['cover_image'] ?? null);
    $aspectRatio = $template?->aspect_ratio ?? 0.672;
    $dynamicFields = $template ? $template->getNormalizedFields() : [];
@endphp

<!-- Font styles for Master Layout -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cinzel:wght@500;600;700;800;900&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Great+Vibes&family=Montserrat:wght@300;400;500;600;700&family=Pinyon+Script&display=swap" rel="stylesheet">

<div class="relative w-full {{ $aspectRatio > 1.1 ? 'max-w-4xl' : 'max-w-xl' }} mx-auto rounded-3xl overflow-hidden card-shadow border border-[#d4af37]/40 bg-[#faf7f0] select-none" style="aspect-ratio: {{ $aspectRatio }};">
    @if($artworkUrl)
        <img src="{{ $artworkUrl }}" alt="{{ $d['event_title'] ?? 'Card Artwork' }}" class="absolute inset-0 w-full h-full object-cover pointer-events-none">
    @endif

    @if(!empty($dynamicFields))
        @if($aspectRatio > 1.1)
            <!-- ================= MASTER LAYOUT FOR 2-PANEL / SPLIT DOUBLE CARDS ================= -->
            @php
                $fields = $dynamicFields;
                
                // 1. Couple Photo on Left Panel (Bounded 0% to 48%)
                $photoField = $fields['couple_photo'] ?? null;
                $couplePhotoSrc = $photoField['photo_url'] ?? ($d['couple_photo'] ?? null);
                $photoLeft = isset($photoField['x']) ? ($photoField['x'] * 100) : 1.0;
                $photoTop = isset($photoField['y']) ? ($photoField['y'] * 100) : 3.0;
                $photoW = isset($photoField['width']) ? ($photoField['width'] * 100) : 47.4;
                $photoH = isset($photoField['height']) ? ($photoField['height'] * 100) : 94.0;
                $photoFit = $photoField['fit'] ?? 'cover';
                $photoRadius = $photoField['border_radius'] ?? '8px';

                // 2. Data Bindings for the 10 Fixed Master Layout Sections
                $hostFamilyName = $d['groom_parents'] ?: 'MR & MRS SAIDI NAFTAL SAIDI';
                $hostOrigin = $d['groom_origin'] ?: 'OF TANGA';
                $guestName = $d['guest_name'] ?? ($fields['guest_name']['custom_text'] ?? 'MHE. JUMA MWINYI NA MWENZIO');
                $invitationMessage = $fields['invitation_text']['custom_text'] ?? ($d['invitation_message'] ?? 'TO THE WEDDING CEREMONY OF THEIR BELOVED SON');
                $coupleNames = $fields['couple_names']['custom_text'] ?? ($fields['event_title']['custom_text'] ?? ($d['couple_names'] ?? ($d['event_title'] ?? 'Dr. Sadiki & Vivian')));
                $eventDayDate = $fields['event_date']['custom_text'] ?? ($d['event_date_formatted'] ?? ($d['event_date_swahili'] ?? 'Thursday, December 3, 2026'));
                $eventDayOfWeek = strtoupper(explode(',', $eventDayDate)[0] ?? 'SUNDAY');
                
                $churchVenue = $fields['church_venue']['custom_text'] ?? ($d['church_venue'] ?? 'Kana Central SDA Church');
                $churchTime = $fields['church_time']['custom_text'] ?? ($d['church_time'] ?? 'at 13:00hrs');

                $receptionVenue = $fields['venue']['custom_text'] ?? ($fields['reception_venue']['custom_text'] ?? ($d['venue_name'] ?? 'Johari rotana'));
                $receptionTime = $fields['reception_time']['custom_text'] ?? ($d['reception_time'] ?? '18:00hrs');

                $rsvpContacts = $fields['rsvp_contacts']['custom_text'] ?? ($d['rsvp_contacts'] ?? 'Willy Kea - 0715 388 975 • Saumu Kinega - 0716 736 557');
                $packageLabel = $fields['package_label']['custom_text'] ?? ($d['package_label'] ?? (($d['pax'] ?? 1) > 1 ? 'DOUBLE' : 'VIP PASS'));
                $qrValue = $d['qr_code_value'] ?? ($d['verification_url'] ?? 'TUKIO360-ENTRY-PASS');
            @endphp

            <!-- LEFT PANEL: COUPLE PHOTO -->
            @if($couplePhotoSrc)
                <div class="absolute overflow-hidden shadow-2xl pointer-events-none" style="left: {{ $photoLeft }}%; top: {{ $photoTop }}%; width: {{ $photoW }}%; height: {{ $photoH }}%; border-radius: {{ $photoRadius }}; z-index: 10;">
                    <img src="{{ $couplePhotoSrc }}" alt="Couple Photo" class="w-full h-full" style="object-fit: {{ $photoFit }};">
                </div>
            @endif

            <!-- RIGHT PANEL: FIXED MASTER CONTENT LAYOUT (Strictly within Dark Section: 51.5% to 78.0%) -->
            <div class="absolute flex flex-col justify-between text-center select-none" style="left: 51.8%; top: 3.5%; width: 26.2%; height: 93%; z-index: 20;">
                
                <!-- 1. Family / Host Introduction -->
                <div class="space-y-0.5 leading-tight px-0.5">
                    <p class="text-[7px] sm:text-[8px] font-roman tracking-[0.18em] text-slate-300 uppercase">THE FAMILY OF</p>
                    <p class="text-[7.5px] sm:text-[9px] font-roman font-bold tracking-[0.14em] text-[#F3E5AB] uppercase">{{ $hostFamilyName }}</p>
                    <p class="text-[6.5px] sm:text-[7.5px] font-roman tracking-[0.14em] text-slate-300 uppercase">{{ $hostOrigin }}, REQUEST THE PLEASURE OF THE COMPANY OF</p>
                </div>

                <!-- 2. Main Person's Name (Prominently Displayed & Underlined) -->
                <div class="py-0.5 px-0.5">
                    <span class="inline-block text-xs sm:text-sm md:text-[15px] font-roman font-extrabold uppercase tracking-[0.16em] text-[#FFFFFF] border-b-2 border-[#D4AF37] pb-0.5 leading-snug drop-shadow-[0_2px_8px_rgba(0,0,0,0.9)] max-w-full break-words">
                        {{ $guestName }}
                    </span>
                </div>

                <!-- 3. Invitation Message Below Name -->
                <div class="px-1">
                    <p class="text-[7px] sm:text-[8px] font-garamond italic text-[#F8F9FA] leading-tight max-w-[96%] mx-auto break-words" style="font-family: 'Cormorant Garamond', Georgia, serif;">
                        {{ $invitationMessage }}
                    </p>
                </div>

                <!-- 4. Couple's Names in Elegant Gold Script Typography -->
                <div class="py-0.5">
                    <h3 class="text-xl sm:text-2xl md:text-[30px] text-[#F9D976] drop-shadow-[0_2px_12px_rgba(0,0,0,0.95)] leading-none" style="font-family: 'Great Vibes', 'Alex Brush', cursive;">
                        {{ $coupleNames }}
                    </h3>
                </div>

                <!-- 5. Wedding Day & Date Prominently with Calendar Icon & Gold Divider Lines -->
                <div class="space-y-0.5 py-0.5">
                    <p class="text-[6.5px] sm:text-[7.5px] font-roman tracking-[0.18em] text-slate-300 uppercase">
                        TO BE HELD ON {{ $eventDayOfWeek }}
                    </p>
                    <div class="flex items-center justify-center gap-1.5 px-1">
                        <div class="h-[1px] flex-1 bg-gradient-to-r from-transparent via-[#D4AF37]/70 to-[#D4AF37]"></div>
                        <div class="inline-flex items-center gap-1 text-[#F3E5AB]">
                            <svg class="w-3.5 h-3.5 shrink-0 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-[11px] sm:text-xs md:text-[13px] font-roman font-black tracking-[0.14em] text-[#F9D976] whitespace-nowrap">{{ $eventDayDate }}</span>
                        </div>
                        <div class="h-[1px] flex-1 bg-gradient-to-l from-transparent via-[#D4AF37]/70 to-[#D4AF37]"></div>
                    </div>
                </div>

                <!-- 6. Two Side-by-Side Sections (Church & Reception) -->
                <div class="grid grid-cols-2 gap-1 text-center pt-0.5 px-0.5">
                    <!-- Church Service -->
                    <div class="space-y-0.5">
                        <div class="w-4 h-4 sm:w-5 sm:h-5 mx-auto rounded-full bg-white/10 border border-[#D4AF37]/60 flex items-center justify-center text-[#F3E5AB] shadow-sm">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <p class="text-[6.5px] sm:text-[7.5px] font-roman uppercase tracking-wider text-slate-300">Service At</p>
                        <p class="text-[7px] sm:text-[8px] font-serif italic text-white font-bold leading-tight break-words">{{ $churchVenue }}</p>
                        <p class="text-[6px] sm:text-[7px] font-roman text-[#D4AF37]">{{ $churchTime }}</p>
                    </div>

                    <!-- Reception -->
                    <div class="space-y-0.5">
                        <div class="w-4 h-4 sm:w-5 sm:h-5 mx-auto rounded-full bg-white/10 border border-[#D4AF37]/60 flex items-center justify-center text-[#F3E5AB] shadow-sm">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <p class="text-[6.5px] sm:text-[7.5px] font-roman uppercase tracking-wider text-slate-300">Reception</p>
                        <p class="text-[7px] sm:text-[8px] font-serif italic text-white font-bold leading-tight break-words">{{ $receptionVenue }}</p>
                        <p class="text-[6px] sm:text-[7px] font-roman text-[#D4AF37]">{{ $receptionTime }}</p>
                    </div>
                </div>

                <!-- 7. RSVP Heading & Contacts -->
                <div class="pt-0.5 leading-tight">
                    <p class="text-[7px] sm:text-[8px] font-serif italic text-[#F3E5AB] font-bold">Rsvp to</p>
                    <p class="text-[6.5px] sm:text-[7.5px] font-mono text-slate-200 tracking-tight leading-tight break-words">{{ $rsvpContacts }}</p>
                </div>

                <!-- 8. Colour Theme Section -->
                <div class="flex items-center justify-center gap-1.5 pt-0.5">
                    <span class="text-[6.5px] sm:text-[7.5px] font-roman uppercase tracking-wider text-slate-400">Colors:</span>
                    <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-black border border-white/50 shadow-sm" title="Black"></span>
                    <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-[#D4AF37] border border-black/40 shadow-sm" title="Gold"></span>
                    <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-[#800020] border border-white/50 shadow-sm" title="Maroon"></span>
                    <span class="text-[6.5px] sm:text-[7.5px] font-roman text-slate-400 italic">(Black, Gold, Maroon)</span>
                </div>

                <!-- 9. QR Code (Bottom-Right) & 10. Company Branding (Bottom-Left) -->
                <div class="flex items-end justify-between pt-1 px-1">
                    <!-- 10. Company Branding Bottom Left -->
                    <div class="flex items-center gap-1.5 text-[7px] sm:text-[8px] font-roman text-slate-400 pb-1">
                        <div class="w-4 h-4 rounded-full bg-gradient-to-tr from-[#c59b27] to-[#F3E5AB] flex items-center justify-center text-[8px] font-black text-black shadow-sm">T</div>
                        <div class="text-left leading-none">
                            <p class="font-bold text-slate-200">Tukio 360</p>
                            <p class="text-[5.5px] text-slate-400 font-sans">smart way to invite</p>
                        </div>
                    </div>

                    <!-- 9. QR Code Bottom Right with Package Label -->
                    <div class="flex flex-col items-center">
                        <span class="px-2 py-0.5 mb-1 rounded bg-[#7A1C1C] border border-[#D4AF37]/50 text-[#F9D976] text-[6.5px] sm:text-[7.5px] font-black uppercase tracking-widest shadow-md">
                            {{ $packageLabel }}
                        </span>
                        <div class="w-14 h-14 sm:w-16 sm:h-16 md:w-[70px] md:h-[70px] bg-white p-1 rounded-xl shadow-2xl border-2 border-[#D4AF37] flex items-center justify-center cursor-pointer hover:scale-105 transition-transform" onclick="document.getElementById('qr-modal') && document.getElementById('qr-modal').classList.remove('hidden')">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(140)->margin(0)->generate($qrValue) !!}
                        </div>
                    </div>
                </div>

            </div>
        @else
            <!-- Standard dynamic overlay for single portrait cards -->
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
                            <div class="text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] text-[#E5C158] mb-1 flex items-center justify-center gap-1 drop-shadow-sm select-none">
                                <svg class="w-3 h-3 text-[#E5C158]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <span>QR PASS</span>
                            </div>
                            <div class="w-full flex-1 max-w-[140px] max-h-[140px] p-2 bg-white rounded-2xl shadow-2xl border-2 border-[#D4AF37] flex items-center justify-center cursor-pointer hover:scale-105 transition-transform" onclick="document.getElementById('qr-modal') && document.getElementById('qr-modal').classList.remove('hidden')">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(140)->margin(0)->generate($d['qr_code_value'] ?? ($d['verification_url'] ?? 'TUKIO360-ENTRY-PASS')) !!}
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
                        @endphp
                        <div class="absolute flex flex-col items-center justify-center overflow-hidden px-2 leading-snug" style="left: {{ $left }}%; top: {{ $top }}%; width: {{ $w }}%; height: {{ $h }}%; font-family: '{{ $font }}', serif; color: {{ $color }}; text-align: {{ $align }}; font-size: clamp(11px, 2.2vw, {{ $size }}px); z-index: 20;">
                            <span class="w-full font-bold break-words leading-tight" style="text-align: {{ $align }};">{{ $text }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
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
