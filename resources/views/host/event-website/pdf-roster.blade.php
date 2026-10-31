<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RSVP Guest Roster - {{ $website->title }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11px;
            margin: 0;
            padding: 15px;
        }
        .header {
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #1e1b4b;
            margin: 0 0 4px 0;
        }
        .subtitle {
            font-size: 11px;
            color: #64748b;
            margin: 0;
        }
        .meta-grid {
            margin-top: 10px;
            font-size: 10px;
            color: #475569;
        }
        .stats-table {
            width: 100%;
            margin-bottom: 15px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        .stats-table td {
            padding: 8px 12px;
            text-align: center;
        }
        .stats-num {
            font-size: 16px;
            font-weight: bold;
            color: #4f46e5;
        }
        .stats-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
        }
        table.roster {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.roster th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            text-align: left;
            padding: 7px 6px;
            font-size: 9px;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
        }
        table.roster td {
            padding: 6px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
            vertical-align: top;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-attending { background-color: #dcfce7; color: #15803d; }
        .badge-declined { background-color: #fee2e2; color: #b91c1c; }
        .badge-tentative { background-color: #fef3c7; color: #b45309; }
        .badge-checked { background-color: #e0e7ff; color: #4338ca; }
        .footer {
            margin-top: 25px;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            font-size: 9px;
            color: #94a3b8;
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">{{ $website->title }}</h1>
        <p class="subtitle">{{ $website->tagline }}</p>
        <div class="meta-grid">
            <strong>Date:</strong> {{ $website->event_date ? $website->event_date->format('l, F j, Y') : 'TBD' }} &bull; 
            <strong>Time:</strong> {{ $website->event_time ?: '18:00' }} &bull; 
            <strong>Venue:</strong> {{ $website->venue_name }}, {{ $website->venue_address }}
        </div>
    </div>

    <table class="stats-table">
        <tr>
            <td>
                <div class="stats-num">{{ $rsvps->count() }}</div>
                <div class="stats-label">Total Responses</div>
            </td>
            <td>
                <div class="stats-num" style="color: #15803d;">{{ $attending->count() }}</div>
                <div class="stats-label">Attending</div>
            </td>
            <td>
                <div class="stats-num" style="color: #4f46e5;">{{ $totalHeads }}</div>
                <div class="stats-label">Total Expected Guests (+1s)</div>
            </td>
            <td>
                <div class="stats-num" style="color: #b91c1c;">{{ $declined->count() }}</div>
                <div class="stats-label">Declined</div>
            </td>
            <td>
                <div class="stats-num" style="color: #b45309;">{{ $tentative->count() }}</div>
                <div class="stats-label">Tentative</div>
            </td>
        </tr>
    </table>

    <table class="roster">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 14%;">Code</th>
                <th style="width: 25%;">Guest Name</th>
                <th style="width: 16%;">Phone / Email</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 8%;">+1s</th>
                <th style="width: 20%;">Dietary / Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rsvps as $idx => $rsvp)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td><strong>{{ $rsvp->confirmation_code }}</strong></td>
                    <td>
                        <strong>{{ $rsvp->full_name }}</strong>
                        @if($rsvp->is_checked_in)
                            <span class="badge badge-checked" style="margin-left: 4px;">Checked-In</span>
                        @endif
                    </td>
                    <td>
                        {{ $rsvp->phone }}<br>
                        <span style="color: #64748b; font-size: 9px;">{{ $rsvp->email ?: 'N/A' }}</span>
                    </td>
                    <td>
                        @if($rsvp->attendance_status === 'attending')
                            <span class="badge badge-attending">Attending</span>
                        @elseif($rsvp->attendance_status === 'declined')
                            <span class="badge badge-declined">Declined</span>
                        @else
                            <span class="badge badge-tentative">Tentative</span>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $rsvp->plus_one_count }}</td>
                    <td>
                        @if($rsvp->dietary_requirements)
                            <div style="font-weight: 600; color: #b45309;">Diet: {{ $rsvp->dietary_requirements }}</div>
                        @endif
                        @if($rsvp->special_requests)
                            <div style="font-style: italic; color: #64748b; font-size: 9px;">"{{ Str::limit($rsvp->special_requests, 50) }}"</div>
                        @endif
                        @if(!$rsvp->dietary_requirements && !$rsvp->special_requests)
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #94a3b8;">
                        No RSVPs recorded yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated via Tukio360 Digital Event Websites &bull; {{ date('Y-m-d H:i') }} &bull; tukio360.co.tz
    </div>

</body>
</html>

