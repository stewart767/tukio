<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Door Check-In Roster - {{ $website->title }}</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #0f172a;
            margin: 20px;
            background: #fff;
        }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; padding: 10px; }
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }
        h1 { margin: 0 0 6px 0; font-size: 24px; }
        p { margin: 0; color: #475569; font-size: 13px; }
        .btn-print {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            padding: 10px 8px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }
        th {
            background: #f8fafc;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.05em;
            color: #334155;
        }
        .checkbox-box {
            width: 20px;
            height: 20px;
            border: 2px solid #94a3b8;
            border-radius: 4px;
            display: inline-block;
        }
        .checked {
            background-color: #22c55e;
            border-color: #16a34a;
            color: white;
            text-align: center;
            line-height: 20px;
            font-size: 14px;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-attending { background: #dcfce7; color: #166534; }
        .badge-tentative { background: #fef3c7; color: #92400e; }
        .badge-declined { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <h1>{{ $website->title }} — Event Door Check-In Roster</h1>
            <p><strong>Date & Time:</strong> {{ $website->event_date ? $website->event_date->format('l, F j, Y') : 'Date TBD' }} at {{ $website->event_time ?: '18:00' }} &bull; <strong>Venue:</strong> {{ $website->venue_name }}, {{ $website->venue_address }}</p>
            <p style="margin-top: 6px; font-weight: 600; color: #4338ca;">
                Confirmed Guests: {{ $attending->count() }} (Total Expected Heads: {{ $totalHeads }})
            </p>
        </div>
        <div class="no-print">
            <button class="btn-print" onclick="window.print()">Print Roster</button>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">Check</th>
                <th style="width: 100px;">Pass Code</th>
                <th>Guest Name</th>
                <th>Phone Number</th>
                <th>Status</th>
                <th style="width: 60px; text-align: center;">+1s</th>
                <th>Dietary / Special Needs</th>
                <th style="width: 120px;">Signature</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rsvps as $rsvp)
                <tr>
                    <td style="text-align: center;">
                        <div class="checkbox-box {{ $rsvp->is_checked_in ? 'checked' : '' }}">
                            {!! $rsvp->is_checked_in ? '&#10003;' : '' !!}
                        </div>
                    </td>
                    <td><strong>{{ $rsvp->confirmation_code }}</strong></td>
                    <td>
                        <span style="font-size: 13px; font-weight: bold;">{{ $rsvp->full_name }}</span>
                    </td>
                    <td>{{ $rsvp->phone }}</td>
                    <td>
                        @if($rsvp->attendance_status === 'attending')
                            <span class="badge badge-attending">Attending</span>
                        @elseif($rsvp->attendance_status === 'declined')
                            <span class="badge badge-declined">Declined</span>
                        @else
                            <span class="badge badge-tentative">Tentative</span>
                        @endif
                    </td>
                    <td style="text-align: center; font-weight: bold;">{{ $rsvp->plus_one_count }}</td>
                    <td>
                        @if($rsvp->dietary_requirements)
                            <strong>Diet:</strong> {{ $rsvp->dietary_requirements }}
                        @else
                            <span style="color: #94a3b8;">None</span>
                        @endif
                    </td>
                    <td style="border-bottom: 1px dotted #94a3b8;">&nbsp;</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
