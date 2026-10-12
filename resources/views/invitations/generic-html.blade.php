<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $d['event_title'] ?? 'Event Invitation' }} — {{ $d['guest_name'] ?? 'Guest' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @if(!empty($css))
        <style>
            {!! $css !!}
        </style>
    @endif
</head>
<body class="bg-slate-950 text-white min-h-screen">
    @if(!empty($d['watermark']))
        <div class="sticky top-0 z-50 bg-rose-600 text-white py-2 text-center text-xs font-black tracking-widest uppercase">
            ⚠️ {{ $d['watermark'] }}
        </div>
    @endif

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="custom-template-render">
            {!! $content !!}
        </div>

        <div class="mt-8 text-center space-y-4">
            <x-invitation.qr-code 
                :qr-url="$d['qr_code_url']" 
                :code="$d['invitation_code']" 
                :guest-name="$d['guest_name']" 
                :table="$d['guest_table']" />

            <x-invitation.rsvp 
                :submit-url="$d['rsvp_submit_url']" 
                :current-status="$d['rsvp_status']" 
                :rsvp-at="$d['rsvp_at']" 
                :guest-name="$d['guest_name']" />
        </div>
    </div>
</body>
</html>
