@extends('layouts.dashboard')

@section('title', 'Digital Event Websites & RSVP Control')
@section('page-title', 'Digital Event Websites & RSVP')

@section('content')
<div class="space-y-6">

    <!-- Top Metrics Overview -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Event Websites</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-black font-heading text-slate-900">{{ $stats['total_websites'] }}</span>
                <span class="text-xs font-semibold text-slate-500">sites</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-emerald-100 shadow-xs bg-gradient-to-br from-emerald-50/50 to-white">
            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Published Live</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-black font-heading text-emerald-600">{{ $stats['published_websites'] }}</span>
                <span class="text-xs font-semibold text-emerald-700">public</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-indigo-100 shadow-xs bg-gradient-to-br from-indigo-50/50 to-white">
            <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-700">Total RSVPs</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-black font-heading text-indigo-600">{{ $stats['total_rsvps'] }}</span>
                <span class="text-xs font-semibold text-indigo-700">responses</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-amber-100 shadow-xs bg-gradient-to-br from-amber-50/50 to-white">
            <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Confirmed (Yes)</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-black font-heading text-amber-600">{{ $stats['total_attending'] }}</span>
                <span class="text-xs font-semibold text-amber-700">attending</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-purple-100 shadow-xs bg-gradient-to-br from-purple-50/50 to-white">
            <span class="text-[11px] font-bold uppercase tracking-wider text-purple-700">Checked-In</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-black font-heading text-purple-600">{{ $stats['total_checked_in'] }}</span>
                <span class="text-xs font-semibold text-purple-700">scanned</span>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">

        <!-- Toolbar & Search -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <h3 class="font-heading font-black text-xl text-slate-900">All Digital Event Websites</h3>
                <p class="text-xs text-slate-500 mt-0.5">Monitor client event websites, countdown status, and RSVP traffic.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.rsvps.index') }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>View Global RSVP Master Roster</span>
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('admin.event-websites.index') }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 overflow-x-auto w-full sm:w-auto pb-1">
                <a href="{{ route('admin.event-websites.index', ['status' => null, 'search' => $search]) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ empty($status) ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    All Sites ({{ $stats['total_websites'] }})
                </a>
                <a href="{{ route('admin.event-websites.index', ['status' => 'published', 'search' => $search]) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $status === 'published' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Live Published ({{ $stats['published_websites'] }})
                </a>
                <a href="{{ route('admin.event-websites.index', ['status' => 'draft', 'search' => $search]) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $status === 'draft' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Drafts
                </a>
            </div>

            <div class="w-full sm:w-72">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search title, host, venue, slug..." class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </form>

        <!-- Websites Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="p-3">Website &amp; Event</th>
                        <th class="p-3">Host / Organizer</th>
                        <th class="p-3">Date &amp; Venue</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-center">RSVP Responses</th>
                        <th class="p-3 text-center">Check-In</th>
                        <th class="p-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($websites as $site)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-3">
                                <div class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                    <span>{{ $site->title }}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase bg-slate-100 text-slate-600">
                                        {{ str_replace('_', ' ', $site->category) }}
                                    </span>
                                </div>
                                <div class="text-[11px] text-slate-500 font-mono mt-0.5 flex items-center gap-2">
                                    <span class="text-indigo-600">/e/{{ $site->slug }}</span>
                                    <button type="button" onclick="navigator.clipboard.writeText('{{ $site->public_url }}'); alert('Copied: {{ $site->public_url }}');" class="text-slate-400 hover:text-indigo-600 font-sans" title="Copy public link">
                                        📋 Copy
                                    </button>
                                </div>
                            </td>

                            <td class="p-3">
                                @if($site->event && $site->event->host)
                                    <div class="font-bold text-slate-800">{{ $site->event->host->full_name }}</div>
                                    <div class="text-[11px] text-slate-500 font-mono">{{ $site->event->host->phone }}</div>
                                @else
                                    <span class="text-slate-400">Independent Event</span>
                                @endif
                            </td>

                            <td class="p-3">
                                <div class="font-semibold text-slate-800">{{ $site->event_date ? $site->event_date->format('M j, Y') : 'Date TBD' }}</div>
                                <div class="text-[11px] text-slate-500 truncate max-w-[180px]">{{ $site->venue_name ?: 'Venue TBD' }}</div>
                            </td>

                            <td class="p-3">
                                @if($site->is_published)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        ● Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        ○ Draft
                                    </span>
                                @endif
                            </td>

                            <td class="p-3 text-center">
                                <a href="{{ route('admin.rsvps.index', ['website_id' => $site->id]) }}" class="inline-flex flex-col items-center group">
                                    <span class="font-black text-indigo-600 text-sm group-hover:underline">{{ $site->total_rsvps }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $site->confirmed_count }} attending</span>
                                </a>
                            </td>

                            <td class="p-3 text-center">
                                <span class="font-bold text-purple-700 text-xs">{{ $site->checked_in_count }}</span>
                            </td>

                            <td class="p-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ $site->public_url }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition-colors" title="Open public event site">
                                        View Site &rarr;
                                    </a>

                                    <a href="{{ route('admin.rsvps.index', ['website_id' => $site->id]) }}" class="px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-[11px] transition-colors">
                                        RSVP Roster
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">
                                No event websites found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $websites->links() }}
        </div>

    </div>

</div>
@endsection

