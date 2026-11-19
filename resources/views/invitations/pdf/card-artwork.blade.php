<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invitation Card - {{ $d['guest_name'] ?? 'Guest' }}</title>
    <style>
        @page {
            margin: 0;
            size: a5 {{ ($template->aspect_ratio ?? 0.7) > 1.1 ? 'landscape' : 'portrait' }};
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #0b0f19;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        .card-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        .artwork-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        .zone {
            position: absolute;
            z-index: 10;
            display: block;
        }
        .zone-text {
            display: table;
            width: 100%;
            height: 100%;
        }
        .zone-text-cell {
            display: table-cell;
            vertical-align: middle;
            font-weight: bold;
            line-height: 1.25;
            word-wrap: break-word;
        }
        .qr-box {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 4px;
            text-align: center;
        }
        .photo-box {
            overflow: hidden;
        }
    </style>
</head>
<body>
    @php
        $artworkPath = $template->artwork_path ? storage_path('app/public/' . $template->artwork_path) : null;
        $artworkBase64 = null;
        if ($artworkPath && file_exists($artworkPath)) {
            $mime = mime_content_type($artworkPath) ?: 'image/jpeg';
            $artworkBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($artworkPath));
        }
        $fields = $template->getNormalizedFields();
    @endphp

    <div class="card-wrapper">
        @if($artworkBase64)
            <img src="{{ $artworkBase64 }}" class="artwork-bg" alt="Artwork">
        @endif

        @if(($template->aspect_ratio ?? 0.7) > 1.1)
            <!-- ================= MASTER LAYOUT FOR SPLIT DOUBLE CARDS (DOMPDF COMPATIBLE) ================= -->
            @php
                $photoField = $fields['couple_photo'] ?? null;
                $photoPath = $photoField['photo_path'] ?? null;
                if (!$photoPath && !empty($photoField['photo_url'])) {
                    $rel = preg_replace('#^https?://[^/]+/#i', '', $photoField['photo_url']);
                    $rel = preg_replace('#^/?storage/#i', '', ltrim($rel, '/'));
                    $photoPath = $rel;
                }
                $photoFull = $photoPath ? storage_path('app/public/' . $photoPath) : null;
                $photoBase64 = null;
                if ($photoFull && file_exists($photoFull)) {
                    $mime = mime_content_type($photoFull) ?: 'image/jpeg';
                    $photoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($photoFull));
                }

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

                $qrBase64 = $d['qr_code_base64'] ?? null;
                if (!$qrBase64) {
                    try {
                        $qrVal = $d['qr_code_value'] ?? ($d['verification_url'] ?? 'TUKIO360-ENTRY-PASS');
                        $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(240)->margin(0)->generate($qrVal);
                        $qrBase64 = 'data:image/png;base64,' . base64_encode($qrSvg);
                    } catch (\Throwable $e) {
                        $qrBase64 = null;
                    }
                }
            @endphp

            <!-- LEFT PANEL: COUPLE PHOTO -->
            @if($photoBase64)
                <div class="zone photo-box" style="left: 1.0%; top: 3.0%; width: 47.4%; height: 94.0%; border-radius: 8px;">
                    <img src="{{ $photoBase64 }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Couple Photo">
                </div>
            @endif

            <!-- RIGHT PANEL: MASTER CONTENT (Bound within 51.8% to 78.0%) -->
            <div class="zone" style="left: 51.8%; top: 3.5%; width: 26.2%; height: 93.0%; text-align: center; color: #ffffff;">
                <table style="width: 100%; height: 100%; border-collapse: collapse; text-align: center;">
                    <!-- 1. Family Introduction -->
                    <tr>
                        <td style="padding: 2px 0; vertical-align: top;">
                            <div style="font-size: 7px; letter-spacing: 2px; color: #cbd5e1; text-transform: uppercase;">THE FAMILY OF</div>
                            <div style="font-size: 8.5px; font-weight: bold; letter-spacing: 1px; color: #F3E5AB; text-transform: uppercase; margin-top: 1px;">{{ $hostFamilyName }}</div>
                            <div style="font-size: 6.5px; letter-spacing: 1px; color: #cbd5e1; text-transform: uppercase; margin-top: 1px;">{{ $hostOrigin }}, REQUEST THE PLEASURE OF THE COMPANY OF</div>
                        </td>
                    </tr>

                    <!-- 2. Main Person's Name -->
                    <tr>
                        <td style="padding: 4px 0; vertical-align: middle;">
                            <div style="display: inline-block; font-size: 13px; font-weight: 900; letter-spacing: 1.5px; color: #ffffff; text-transform: uppercase; border-bottom: 2px solid #D4AF37; padding-bottom: 2px;">
                                {{ $guestName }}
                            </div>
                        </td>
                    </tr>

                    <!-- 3. Invitation Message -->
                    <tr>
                        <td style="padding: 2px 5px; vertical-align: middle;">
                            <div style="font-size: 7.5px; font-style: italic; color: #f8fafc; line-height: 1.2;">
                                {{ $invitationMessage }}
                            </div>
                        </td>
                    </tr>

                    <!-- 4. Couple's Names -->
                    <tr>
                        <td style="padding: 4px 0; vertical-align: middle;">
                            <div style="font-size: 20px; font-weight: bold; color: #F9D976; letter-spacing: 1px; font-family: 'Georgia', serif;">
                                {{ $coupleNames }}
                            </div>
                        </td>
                    </tr>

                    <!-- 5. Wedding Day & Date -->
                    <tr>
                        <td style="padding: 4px 0; vertical-align: middle;">
                            <div style="font-size: 6.5px; letter-spacing: 1.5px; color: #cbd5e1; text-transform: uppercase; margin-bottom: 2px;">
                                TO BE HELD ON {{ $eventDayOfWeek }}
                            </div>
                            <div style="border-top: 1px solid #D4AF37; border-bottom: 1px solid #D4AF37; padding: 2px 0; margin: 0 10px; font-size: 11px; font-weight: 900; color: #F3E5AB; letter-spacing: 1px;">
                                {{ $eventDayDate }}
                            </div>
                        </td>
                    </tr>

                    <!-- 6. Two Venue Columns -->
                    <tr>
                        <td style="padding: 4px 0; vertical-align: middle;">
                            <table style="width: 100%; border-collapse: collapse; text-align: center;">
                                <tr>
                                    <td style="width: 50%; vertical-align: top; padding: 0 2px;">
                                        <div style="font-size: 6.5px; text-transform: uppercase; letter-spacing: 1px; color: #cbd5e1;">Service At</div>
                                        <div style="font-size: 7.5px; font-weight: bold; font-style: italic; color: #ffffff; margin-top: 1px;">{{ $churchVenue }}</div>
                                        <div style="font-size: 6.5px; color: #D4AF37; margin-top: 1px;">{{ $churchTime }}</div>
                                    </td>
                                    <td style="width: 50%; vertical-align: top; padding: 0 2px;">
                                        <div style="font-size: 6.5px; text-transform: uppercase; letter-spacing: 1px; color: #cbd5e1;">Reception</div>
                                        <div style="font-size: 7.5px; font-weight: bold; font-style: italic; color: #ffffff; margin-top: 1px;">{{ $receptionVenue }}</div>
                                        <div style="font-size: 6.5px; color: #D4AF37; margin-top: 1px;">{{ $receptionTime }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- 7. RSVP -->
                    <tr>
                        <td style="padding: 2px 0; vertical-align: middle;">
                            <div style="font-size: 7.5px; font-weight: bold; font-style: italic; color: #F3E5AB;">Rsvp to</div>
                            <div style="font-size: 6.5px; color: #e2e8f0; margin-top: 1px;">{{ $rsvpContacts }}</div>
                        </td>
                    </tr>

                    <!-- 8. Colour Palette -->
                    <tr>
                        <td style="padding: 2px 0; vertical-align: middle;">
                            <span style="font-size: 6.5px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8;">Colors: </span>
                            <span style="display: inline-block; width: 8px; height: 8px; background: #000000; border: 1px solid #ffffff; vertical-align: middle;"></span>
                            <span style="display: inline-block; width: 8px; height: 8px; background: #D4AF37; border: 1px solid #000000; vertical-align: middle;"></span>
                            <span style="display: inline-block; width: 8px; height: 8px; background: #800020; border: 1px solid #ffffff; vertical-align: middle;"></span>
                            <span style="font-size: 6.5px; font-style: italic; color: #94a3b8;">(Black, Gold, Maroon)</span>
                        </td>
                    </tr>

                    <!-- 9 & 10. QR Pass & Branding -->
                    <tr>
                        <td style="padding-top: 4px; vertical-align: bottom;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="width: 50%; vertical-align: bottom; text-align: left; padding-left: 4px;">
                                        <div style="font-size: 7.5px; font-weight: bold; color: #e2e8f0;">Tukio 360</div>
                                        <div style="font-size: 5.5px; color: #94a3b8;">smart way to invite</div>
                                    </td>
                                    <td style="width: 50%; vertical-align: bottom; text-align: right; padding-right: 4px;">
                                        <div style="display: inline-block; text-align: center;">
                                            <div style="display: inline-block; background: #7A1C1C; color: #F9D976; font-size: 6px; font-weight: 900; letter-spacing: 1px; padding: 1px 4px; border-radius: 3px; margin-bottom: 2px;">
                                                {{ $packageLabel }}
                                            </div>
                                            <div style="width: 52px; height: 52px; background: #ffffff; padding: 3px; border: 1px solid #D4AF37; border-radius: 6px;">
                                                @if($qrBase64)
                                                    <img src="{{ $qrBase64 }}" style="width: 100%; height: 100%;" alt="QR Code">
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        @else
        @foreach($fields as $key => $field)
            @php
                $left = ($field['x'] ?? 0) * 100;
                $top = ($field['y'] ?? 0) * 100;
                $w = ($field['width'] ?? 0.2) * 100;
                $h = ($field['height'] ?? 0.1) * 100;
                $type = $field['type'] ?? 'text';
            @endphp

            @if($type === 'qr' || $key === 'qr_code')
                @php
                    $qrBase64 = $d['qr_code_base64'] ?? null;
                    if (!$qrBase64) {
                        try {
                            $qrVal = $d['qr_code_value'] ?? ($d['verification_url'] ?? 'TUKIO360-ENTRY-PASS');
                            $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(240)->margin(0)->generate($qrVal);
                            $qrBase64 = 'data:image/png;base64,' . base64_encode($qrSvg);
                        } catch (\Throwable $e) {
                            $qrBase64 = null;
                        }
                    }
                @endphp
                <div class="zone" style="left: {{ $left }}%; top: {{ max(0, $top - 2.8) }}%; width: {{ $w }}%; text-align: center; color: #E5C158; font-size: 8px; font-weight: 800; letter-spacing: 2px;">
                    QR PASS
                </div>
                <div class="zone qr-box" style="left: {{ $left }}%; top: {{ $top }}%; width: {{ $w }}%; height: {{ $h }}%; border: 2px solid #D4AF37; border-radius: 10px; background: #ffffff; padding: 4px;">
                    @if($qrBase64)
                        <img src="{{ $qrBase64 }}" style="width: 100%; height: 100%;" alt="QR Code">
                    @endif
                </div>
            @elseif($type === 'image' || str_contains($key, 'photo'))
                @php
                    $photoPath = $field['photo_path'] ?? null;
                    if (!$photoPath && !empty($field['photo_url'])) {
                        $rel = preg_replace('#^https?://[^/]+/#i', '', $field['photo_url']);
                        $rel = preg_replace('#^/?storage/#i', '', ltrim($rel, '/'));
                        $photoPath = $rel;
                    }
                    $photoFull = $photoPath ? storage_path('app/public/' . $photoPath) : null;
                    $photoBase64 = null;
                    if ($photoFull && file_exists($photoFull)) {
                        $mime = mime_content_type($photoFull) ?: 'image/jpeg';
                        $photoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($photoFull));
                    }
                    $radius = $field['border_radius'] ?? '8px';
                    $bWidth = (int) ($field['border_width'] ?? 0);
                    $bColor = $field['border_color'] ?? '#c59b27';
                @endphp
                @if($photoBase64)
                    <div class="zone photo-box" style="left: {{ $left }}%; top: {{ $top }}%; width: {{ $w }}%; height: {{ $h }}%; border-radius: {{ $radius }}; {{ $bWidth > 0 ? "border: {$bWidth}px solid {$bColor};" : '' }}">
                        <img src="{{ $photoBase64 }}" style="width: 100%; height: 100%;" alt="Couple Photo">
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
                    $size = max(11, (int) ($field['font_size'] ?? 18));
                    $color = $field['color'] ?? '#ffffff';
                    $align = $field['alignment'] ?? 'center';
                    $isHeading = ($key === 'guest_name');
                    $isTitle = ($key === 'event_title' || strtoupper(trim($text)) === 'HARUSI');
                @endphp
                <div class="zone" style="left: {{ $left }}%; top: {{ $top }}%; width: {{ $w }}%; height: {{ $h }}%; text-align: {{ $align }}; color: {{ $color }};">
                    <div class="zone-text">
                        <div class="zone-text-cell" style="font-size: {{ $size }}px; text-align: {{ $align }}; font-weight: {{ ($isHeading || $isTitle) ? '900' : '600' }}; letter-spacing: {{ $isTitle ? '4px' : ($isHeading ? '1px' : '0px') }};">
                            {{ $isHeading || $isTitle ? strtoupper($text) : $text }}
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
        @endif
    </div>
</body>
</html>

