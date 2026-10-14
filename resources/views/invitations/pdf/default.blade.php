<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invitation Pass - {{ $d['guest_name'] }}</title>
    <style>
        @page {
            margin: 0;
            size: A5 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #0d1b1e;
            color: #ffffff;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
        }
        .container {
            border: 2px solid #d4af37;
            border-radius: 15px;
            padding: 25px 20px;
            background-color: #122327;
            height: 90%;
        }
        .host-header {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #d4af37;
            margin-bottom: 5px;
        }
        .event-type {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #a0aec0;
            margin-bottom: 12px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 8px;
        }
        .guest-box {
            margin: 15px auto;
            padding: 12px;
            background-color: rgba(212, 175, 55, 0.1);
            border: 1px dashed #d4af37;
            border-radius: 8px;
            max-width: 85%;
        }
        .guest-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #d4af37;
        }
        .guest-name {
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            margin-top: 3px;
        }
        .table-badge {
            font-size: 10px;
            color: #d4af37;
            margin-top: 4px;
        }
        .details-table {
            width: 100%;
            margin: 15px auto;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 8px 5px;
            font-size: 11px;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.1);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .details-label {
            color: #d4af37;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            display: block;
            margin-bottom: 2px;
        }
        .qr-section {
            margin-top: 15px;
        }
        .qr-img {
            width: 120px;
            height: 120px;
            background-color: #ffffff;
            padding: 6px;
            border-radius: 8px;
        }
        .code-text {
            font-family: monospace;
            font-size: 12px;
            font-weight: bold;
            color: #d4af37;
            margin-top: 5px;
            letter-spacing: 1px;
        }
        .footer-note {
            font-size: 9px;
            color: #718096;
            margin-top: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="host-header">{{ $d['host_name'] }}</div>
        <div class="event-type">KADI YA MWALIKO</div>

        <div class="title">{{ $d['couple_names'] }}</div>

        <div class="guest-box">
            <div class="guest-label">HONORED GUEST</div>
            <div class="guest-name">{{ $d['guest_name'] }}</div>
            @if($d['guest_table'])
                <div class="table-badge">Table: {{ $d['guest_table'] }}</div>
            @endif
        </div>

        <table class="details-table">
            <tr>
                <td width="50%">
                    <span class="details-label">DATE &amp; TIME</span>
                    {{ $d['event_date_formatted'] }}<br>
                    <strong>{{ $d['event_time'] }}</strong>
                </td>
                <td width="50%">
                    <span class="details-label">VENUE LOCATION</span>
                    <strong>{{ $d['venue_name'] }}</strong><br>
                    {{ $d['venue_address'] }}
                </td>
            </tr>
        </table>

        <div class="qr-section">
            @if(!empty($d['qr_code_base64']))
                <img src="{{ $d['qr_code_base64'] }}" class="qr-img" alt="QR Pass">
            @elseif(!empty($d['qr_code_url']))
                <img src="{{ $d['qr_code_url'] }}" class="qr-img" alt="QR Pass">
            @endif
            <div class="code-text">{{ $d['invitation_code'] }}</div>
        </div>

        <div class="footer-note">
            Please present this QR Pass at the reception entrance.<br>
            Powered by <strong>Tukio 360</strong>
        </div>
    </div>
</body>
</html>
