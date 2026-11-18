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
    </div>
</body>
</html>

