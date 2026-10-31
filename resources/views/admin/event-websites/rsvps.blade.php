@extends('layouts.dashboard')

@section('title', 'Global RSVP Master Roster')
@section('page-title', 'Global RSVP Master Roster')

@section('content')
<div class="space-y-6">

    <!-- Top Metrics -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total RSVPs</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-black font-heading text-slate-900">{{ $stats['total_rsvps'] }}</span>
                <span class="text-xs font-semibold text-slate-500">guests</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-emerald-100 shadow-xs bg-gradient-to-br from-emerald-50/50 to-white">
            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Attending (Yes)</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-black font-heading text-emerald-600">{{ $stats['attending'] }}</span>
                <span class="text-xs font-semibold text-emerald-700">confirmed</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-rose-100 shadow-xs bg-gradient-to-br from-rose-50/50 to-white">
            <span class="text-[11px] font-bold uppercase tracking-wider text-rose-700">Declined (No)</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-black font-heading text-rose-600">{{ $stats['declined'] }}</span>
                <span class="text-xs font-semibold text-rose-700">declined</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-amber-100 shadow-xs bg-gradient-to-br from-amber-50/50 to-white">
            <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Tentative (Maybe)</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-black font-heading text-amber-600">{{ $stats['tentative'] }}</span>
                <span class="text-xs font-semibold text-amber-700">unsure</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-purple-100 shadow-xs bg-gradient-to-br from-purple-50/50 to-white">
            <span class="text-[11px] font-bold uppercase tracking-wider text-purple-700">Checked In</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-black font-heading text-purple-600">{{ $stats['checked_in'] }}</span>
                <span class="text-xs font-semibold text-purple-700">door checked</span>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                    <a href="{{ route('admin.event-websites.index') }}" class="hover:text-indigo-600 transition-colors">&larr; Back to Event Websites</a>
                </div>
                <h3 class="font-heading font-black text-xl text-slate-900">Platform-Wide RSVP Submissions</h3>
                <p class="text-xs text-slate-500 mt-0.5">Real-time RSVP registrations submitted by guests through digital event websites.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.rsvps.export', ['website_id' => $websiteId]) }}" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Export to CSV</span>
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('admin.rsvps.index') }}" method="GET" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <!-- Filter by Website -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Filter by Event Website</label>
                    <select name="website_id" onchange="this.form.submit()" class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500">
                        <option value="">All Event Websites</option>
                        @foreach($websitesList as $w)
                            <option value="{{ $w->id }}" {{ $websiteId == $w->id ? 'selected' : '' }}>{{ $w->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter by Status -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Attendance Status</label>
                    <select name="status" onchange="this.form.submit()" class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500">
                        <option value="">All Statuses</option>
                        <option value="attending" {{ $status === 'attending' ? 'selected' : '' }}>Attending (Yes)</option>
                        <option value="declined" {{ $status === 'declined' ? 'selected' : '' }}>Declined (No)</option>
                        <option value="tentative" {{ $status === 'tentative' ? 'selected' : '' }}>Tentative (Maybe)</option>
                        <option value="checked_in" {{ $status === 'checked_in' ? 'selected' : '' }}>Checked In</option>
                    </select>
                </div>

                <!-- Search -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Search Guest</label>
                    <div class="flex gap-2">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Name, phone, pass code..." class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500">
                        <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-900 text-white font-bold text-xs shrink-0">Search</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- RSVPs Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="p-3">Pass Code</th>
                        <th class="p-3">Guest Details</th>
                        <th class="p-3">Event Website</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-center">Party (+1s)</th>
                        <th class="p-3">Dietary / Requests</th>
                        <th class="p-3">Door Check-In</th>
                        <th class="p-3 text-right">Registered</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rsvps as $rsvp)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-3 font-mono font-bold text-indigo-600">
                                {{ $rsvp->confirmation_code }}
                            </td>

                            <td class="p-3">
                                <div class="font-bold text-slate-900 text-sm">{{ $rsvp->full_name }}</div>
                                <div class="text-[11px] text-slate-500 font-mono">{{ $rsvp->phone }} &bull; {{ $rsvp->email ?: 'No email' }}</div>
                            </td>

                            <td class="p-3">
                                @if($rsvp->website)
                                    <a href="{{ $rsvp->website->public_url }}" target="_blank" class="font-semibold text-indigo-600 hover:underline">
                                        {{ $rsvp->website->title }}
                                    </a>
                                    @if($rsvp->website->event && $rsvp->website->event->host)
                                        <div class="text-[10px] text-slate-400">Host: {{ $rsvp->website->event->host->full_name }}</div>
                                    @endif
                                @else
                                    <span class="text-slate-400">Deleted Website</span>
                                @endif
                            </td>

                            <td class="p-3">
                                @if($rsvp->attendance_status === 'attending')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Attending
                                    </span>
                                @elseif($rsvp->attendance_status === 'declined')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                        Declined
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        Tentative
                                    </span>
                                @endif
                            </td>

                            <td class="p-3 text-center">
                                <span class="font-bold text-slate-900">{{ $rsvp->total_party_size }}</span>
                                <span class="text-[10px] text-slate-400 block">+{{ $rsvp->plus_one_count }} guest(s)</span>
                            </td>

                            <td class="p-3 max-w-[200px]">
                                @if($rsvp->dietary_requirements)
                                    <span class="inline-block px-1.5 py-0.5 rounded bg-amber-50 text-amber-700 font-semibold text-[10px] border border-amber-200 mb-1">
                                        {{ $rsvp->dietary_requirements }}
                                    </span>
                                @endif
                                @if($rsvp->special_requests)
                                    <p class="text-[11px] text-slate-500 italic truncate" title="{{ $rsvp->special_requests }}">
                                        "{{ $rsvp->special_requests }}"
                                    </p>
                                @endif
                                @if(!$rsvp->dietary_requirements && !$rsvp->special_requests)
                                    <span class="text-slate-400">Standard</span>
                                @endif
                            </td>

                            <td class="p-3">
                                @if($rsvp->is_checked_in)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                        ✓ {{ $rsvp->checked_in_at ? $rsvp->checked_in_at->format('M j, H:i') : 'Checked In' }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[11px]">Not checked in</span>
                                @endif
                            </td>

                            <td class="p-3 text-right font-mono text-[11px] text-slate-500">
                                {{ $rsvp->created_at->format('M j, Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">
                                No guest RSVP submissions recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $rsvps->links() }}
        </div>

    </div>

</div>
@endsection

