<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Wedding Invitation - {{ $d['guest_name'] ?? 'Guest' }}</title>
    <style>
        @page {
            margin: 0;
            size: a5 landscape;
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
            background-color: #0c0e14;
            color: #ffffff;
            width: 100%;
            height: 100%;
        }
        .card-table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }
        .left-col {
            width: 50%;
            height: 100%;
            vertical-align: top;
            background-color: #111827;
            position: relative;
            text-align: center;
        }
        .right-col {
            width: 50%;
            height: 100%;
            vertical-align: top;
            background-color: #0d0f14;
            padding: 20px 25px;
            text-align: center;
        }
        .gold-text {
            color: #F3E5AB;
        }
        .gold-bright {
            color: #F9D976;
        }
        .gold-border {
            border: 1px solid #D4AF37;
        }
    </style>
</head>
<body>
    @php
        $coupleNames = $d['couple_names'] ?? ($d['event_title'] ?? 'Dr. Sadiki & Vivian');
        $guestName = $d['guest_name'] ?? 'DORIS';
        $parentsText = $d['groom_parents'] ?? ($d['parents_names'] ?? 'MR & MRS SAIDI NAFTAL SAIDI');
        $churchName = $d['church_venue'] ?? 'Kana Central SDA Church';
        $venue = $d['venue_name'] ?? 'Regal Naiveral Hall';
        $dateFormatted = '06.07.2025';
        if (!empty($d['countdown_date'])) {
            $dateFormatted = \Carbon\Carbon::parse($d['countdown_date'])->format('d.m.Y');
        }

        $photoFull = null;
        if (!empty($d['couple_photo'])) {
            $rel = preg_replace('#^https?://[^/]+/#i', '', $d['couple_photo']);
            $rel = preg_replace('#^/?storage/#i', '', ltrim($rel, '/'));
            $c = storage_path('app/public/' . $rel);
            if (file_exists($c)) $photoFull = $c;
        }
        $photoBase64 = null;
        if ($photoFull && file_exists($photoFull)) {
            $mime = mime_content_type($photoFull) ?: 'image/jpeg';
            $photoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($photoFull));
        }

        $qrBase64 = $d['qr_code_base64'] ?? null;
        if (!$qrBase64) {
            try {
                $qrVal = $d['qr_code_value'] ?? ($d['verification_url'] ?? 'TUKIO360-ENTRY-PASS');
                $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(220)->margin(0)->generate($qrVal);
                $qrBase64 = 'data:image/png;base64,' . base64_encode($qrSvg);
            } catch (\Throwable $e) {
                $qrBase64 = null;
            }
        }
    @endphp

    <table class="card-table">
        <tr>
            <!-- LEFT PANEL: COUPLE PHOTO -->
            <td class="left-col" style="background: #0f172a; padding: 0;">
                @if($photoBase64)
                    <img src="{{ $photoBase64 }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Couple Photo">
                @else
                    <div style="padding-top: 150px; color: #d4af37; font-size: 16px; font-weight: bold;">
                        {{ $coupleNames }}
                    </div>
                @endif
            </td>

            <!-- RIGHT PANEL: DETAILS & QR -->
            <td class="right-col">
                <div style="font-size: 8px; letter-spacing: 2px; color: #cbd5e1; text-transform: uppercase; margin-bottom: 2px;">
                    THE FAMILY OF
                </div>
                <div style="font-size: 10px; font-weight: bold; letter-spacing: 1.5px; color: #F3E5AB; text-transform: uppercase; margin-bottom: 3px;">
                    {{ $parentsText }}
                </div>
                <div style="font-size: 7.5px; letter-spacing: 1.5px; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px;">
                    REQUEST THE PLEASURE OF THE COMPANY OF
                </div>

                <!-- GUEST NAME -->
                <div style="margin: 6px auto 10px auto;">
                    <span style="font-size: 16px; font-weight: 900; color: #ffffff; letter-spacing: 3px; text-transform: uppercase; border-bottom: 2px solid #D4AF37; padding-bottom: 3px;">
                        {{ $guestName }}
                    </span>
                </div>

                <div style="font-size: 8px; letter-spacing: 2px; color: #cbd5e1; text-transform: uppercase; margin-bottom: 8px;">
                    TO THE WEDDING CEREMONY OF THEIR BELOVED SON
                </div>

                <!-- HERO COUPLE NAMES -->
                <div style="font-size: 26px; color: #F9D976; font-style: italic; font-weight: bold; margin: 4px 0 10px 0;">
                    {{ $coupleNames }}
                </div>

                <!-- DATE -->
                <div style="margin-bottom: 12px;">
                    <span style="display: inline-block; padding: 3px 12px; border: 1.5px solid #D4AF37; border-radius: 6px; font-size: 12px; font-weight: 900; color: #F3E5AB; letter-spacing: 2px;">
                        📅 {{ $dateFormatted }}
                    </span>
                </div>

                <!-- 2-COLUMN SCHEDULE -->
                <table style="width: 100%; margin-bottom: 10px;">
                    <tr>
                        <td style="width: 50%; text-align: center; vertical-align: top;">
                            <div style="font-size: 8px; color: #D4AF37; text-transform: uppercase; font-weight: bold;">⛪ Service At</div>
                            <div style="font-size: 9px; font-weight: bold; color: #ffffff;">{{ $churchName }}</div>
                            <div style="font-size: 8px; color: #D4AF37;">at 13:00hrs</div>
                        </td>
                        <td style="width: 50%; text-align: center; vertical-align: top;">
                            <div style="font-size: 8px; color: #D4AF37; text-transform: uppercase; font-weight: bold;">📍 Reception</div>
                            <div style="font-size: 9px; font-weight: bold; color: #ffffff;">{{ $venue }}</div>
                            <div style="font-size: 8px; color: #D4AF37;">18:00hrs</div>
                        </td>
                    </tr>
                </table>

                <!-- RSVP & COLORS -->
                <div style="font-size: 8px; color: #F3E5AB; font-style: italic; margin-bottom: 2px;">
                    Rsvp: Willy Kea - 0715 388 975 &bull; Saumu - 0716 736 557
                </div>
                <div style="font-size: 7.5px; color: #94a3b8; margin-bottom: 8px;">
                    Colors: (Black, Gold, Maroon)
                </div>

                <!-- QR PASS -->
                <div style="margin-top: 5px;">
                    <div style="font-size: 8px; font-weight: bold; color: #800020; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 2px;">
                        DOUBLE PASS
                    </div>
                    @if($qrBase64)
                        <img src="{{ $qrBase64 }}" style="width: 65px; height: 65px; background: white; padding: 3px; border: 1.5px solid #D4AF37; border-radius: 6px;" alt="QR Pass">
                    @endif
                </div>
            </td>
        </tr>
    </table>
</body>
</html>

