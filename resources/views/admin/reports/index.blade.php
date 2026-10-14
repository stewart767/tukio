@extends('layouts.dashboard')

@section('title', 'Intelligence & Telemetry Reports')
@section('page-title', 'Operations Intelligence')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Executive Intelligence Header -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 p-8 sm:p-10 shadow-2xl text-white">
        <div class="absolute -top-12 -right-12 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 h-64 w-64 rounded-full bg-purple-500/20 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-500/15 px-3.5 py-1 text-xs font-semibold text-emerald-300 backdrop-blur-md">
                    <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Live Platform Telemetry Engine
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 text-white backdrop-blur-md shadow-lg shadow-indigo-950/50 border border-white/15">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight font-heading">Operations Intelligence &amp; Analytics</h1>
                        <p class="mt-1 text-sm sm:text-base text-slate-300 max-w-xl">Monitor aggregate invitations dispatched, guest attendance check-in velocity, and host tenant momentum.</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="px-5 py-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10 text-right">
                    <div class="text-2xl font-black text-emerald-400">{{ $metrics['active_hosts'] }}</div>
                    <div class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Active Hosts</div>
                </div>
                <div class="px-5 py-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10 text-right">
                    <div class="text-2xl font-black text-indigo-300">{{ $metrics['upcoming_events'] }}</div>
                    <div class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Upcoming Events</div>
                </div>
            </div>
        </div>

        <!-- Metric KPI Cards -->
        <div class="relative z-10 mt-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 sm:p-5 backdrop-blur-md">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-300">
                    <span>Total Events</span>
                    <svg class="w-4 h-4 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-white">{{ $metrics['total_events'] }}</div>
                <div class="mt-1 text-[11px] text-slate-300">Platform-wide celebrations</div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 sm:p-5 backdrop-blur-md">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-300">
                    <span>Invitations Sent</span>
                    <svg class="w-4 h-4 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-white">{{ number_format($metrics['invitations_sent']) }}</div>
                <div class="mt-1 text-[11px] text-slate-300">Dispatched cards</div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 sm:p-5 backdrop-blur-md">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-300">
                    <span>Guests Registered</span>
                    <svg class="w-4 h-4 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-white">{{ number_format($metrics['total_guests']) }}</div>
                <div class="mt-1 text-[11px] text-slate-300">Synced guest database</div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 sm:p-5 backdrop-blur-md">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-300">
                    <span>WhatsApp Delivered</span>
                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-emerald-400">{{ number_format($metrics['whatsapp_delivered']) }}</div>
                <div class="mt-1 text-[11px] text-slate-300">Confirmed deliveries</div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts & Feed Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Main Column (Left 8 cols) -->
        <div class="lg:col-span-8 space-y-8">
            <!-- Event Momentum Chart Card -->
            <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 sm:p-8 shadow-xl backdrop-blur-md">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 font-heading">Event Growth &amp; Trajectory</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Monthly event volume launched over the trailing 6 months</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                        Real-time Data
                    </span>
                </div>

                <div class="mt-6 h-64 w-full">
                    <canvas id="eventGrowthChart"></canvas>
                </div>

                <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 gap-3 pt-6 border-t border-slate-100">
                    @forelse($eventsTrend as $trend)
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">{{ $trend['label'] }}</div>
                            <div class="text-xl font-black text-slate-900 mt-1">{{ $trend['value'] }}</div>
                            <div class="text-[10px] text-slate-500">events hosted</div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-4 text-xs text-slate-400">No historical activity records yet.</div>
                    @endforelse
                </div>
            </div>

            <!-- Top Performing Events Leaderboard -->
            <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 sm:p-8 shadow-xl backdrop-blur-md">
                <div class="flex items-center justify-between border-b border-slate-100 pb-5">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 font-heading">Top Performing Events</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Ranked by overall guest engagement &amp; invitation count</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Leaderboard
                    </span>
                </div>

                <div class="mt-6 space-y-3.5">
                    @forelse($topEvents as $index => $event)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-slate-50 hover:bg-indigo-50/40 border border-slate-100 transition-colors">
                            <div class="flex items-center gap-3.5">
                                <div class="h-9 w-9 rounded-xl {{ $index === 0 ? 'bg-amber-100 text-amber-700 font-black' : 'bg-slate-200 text-slate-700 font-bold' }} flex items-center justify-center text-xs shrink-0">
                                    #{{ $index + 1 }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-sm">{{ $event->title ?? 'Untitled Event' }}</div>
                                    <div class="text-xs text-slate-500">
                                        Host: {{ optional($event->host)->full_name ?? 'Host' }}
                                        @if($event->date)
                                            &bull; {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-6 self-end sm:self-auto">
                                <div class="text-right">
                                    <div class="text-base font-black text-slate-900 font-mono">{{ number_format($event->guests_count) }}</div>
                                    <div class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Guests</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-base font-black text-indigo-600 font-mono">{{ number_format($event->invitations_count) }}</div>
                                    <div class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Invites</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-slate-400">
                            No event telemetry available yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Analytics Column (Right 4 cols) -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Engagement & Delivery Quality Card -->
            <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 sm:p-7 shadow-xl backdrop-blur-md">
                <h3 class="text-base font-bold text-slate-900 font-heading">Delivery &amp; Engagement</h3>
                <p class="text-xs text-slate-500 mt-0.5">WhatsApp delivery &amp; open telemetry</p>

                <div class="mt-6 space-y-4 text-xs">
                    <div>
                        <div class="flex justify-between font-semibold text-slate-700">
                            <span>WhatsApp Delivery Success</span>
                            <span class="font-bold text-emerald-600">{{ $deliverySummary['delivery_rate'] }}%</span>
                        </div>
                        <div class="mt-2 h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-emerald-500 transition-all duration-1000" style="width: {{ $deliverySummary['delivery_rate'] }}%"></div>
                        </div>
                        <div class="flex justify-between text-[11px] text-slate-400 mt-1">
                            <span>{{ number_format($deliverySummary['whatsapp_delivered']) }} delivered</span>
                            <span>{{ number_format($deliverySummary['total']) }} total</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        <div class="flex justify-between font-semibold text-slate-700">
                            <span>Invitation Open Rate</span>
                            <span class="font-bold text-indigo-600">{{ $deliverySummary['open_rate'] }}%</span>
                        </div>
                        <div class="mt-2 h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-indigo-500 transition-all duration-1000" style="width: {{ $deliverySummary['open_rate'] }}%"></div>
                        </div>
                        <div class="flex justify-between text-[11px] text-slate-400 mt-1">
                            <span>{{ number_format($deliverySummary['opened']) }} viewed</span>
                            <span>{{ number_format($deliverySummary['total']) }} total</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Pulse Card -->
            <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 sm:p-7 shadow-xl backdrop-blur-md">
                <h3 class="text-base font-bold text-slate-900 font-heading">Attendance Pulse</h3>
                <p class="text-xs text-slate-500 mt-0.5">Physical QR check-in conversion</p>

                <div class="mt-5 p-4 rounded-2xl bg-indigo-50/60 border border-indigo-100 text-center">
                    <div class="text-3xl font-black text-indigo-700 font-mono">{{ number_format($attendanceSummary['attended']) }}</div>
                    <div class="text-xs font-bold text-indigo-900 mt-1">Checked-In Guests</div>
                    <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-white shadow-inner">
                        <div class="h-full rounded-full bg-indigo-600" style="width: {{ $attendanceSummary['attendance_rate'] }}%"></div>
                    </div>
                    <div class="text-[11px] text-indigo-700 font-bold mt-2">{{ $attendanceSummary['attendance_rate'] }}% Platform Attendance Rate</div>
                </div>
            </div>

            <!-- Live Invitation Stream -->
            <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 sm:p-7 shadow-xl backdrop-blur-md">
                <h3 class="text-base font-bold text-slate-900 font-heading">Recent Invitation Stream</h3>
                <p class="text-xs text-slate-500 mt-0.5">Latest cards dispatched across platform</p>

                <div class="mt-5 space-y-3">
                    @forelse($recentInvitations as $invitation)
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                            <div class="flex items-center justify-between font-bold text-slate-900">
                                <span>{{ optional($invitation->guest)->full_name ?? 'Guest' }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] uppercase font-bold bg-white text-indigo-700 border border-indigo-100">
                                    {{ $invitation->status ?? 'Sent' }}
                                </span>
                            </div>
                            <div class="text-[11px] text-slate-500 mt-1">Event: {{ optional($invitation->event)->title ?? 'Celebration' }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">{{ $invitation->created_at?->diffForHumans() }}</div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic text-center py-4">No recent invitation dispatches.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Initialization -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const trendCtx = document.getElementById('eventGrowthChart');
    if (!trendCtx) return;

    const labels = @json(array_column($eventsTrend, 'label'));
    const dataPoints = @json(array_column($eventsTrend, 'value'));

    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: labels.length > 0 ? labels : ['Month 1', 'Month 2', 'Month 3', 'Month 4', 'Month 5', 'Current'],
            datasets: [{
                label: 'Events Created',
                data: dataPoints.length > 0 ? dataPoints : [2, 5, 8, 12, 18, 24],
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.12)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#4f46e5',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(226, 232, 240, 0.6)' },
                    ticks: { font: { family: 'Inter', size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Inter', size: 11 } }
                }
            }
        }
    });
});
</script>
@endsection
