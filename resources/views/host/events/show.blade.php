@extends('layouts.host')

@section('title', $event->title . ' - Event Details')
@section('page-title', 'Event Overview')

@section('content')
<div class="space-y-8 animate-fade-in max-w-7xl mx-auto">

    <!-- Hero Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary-900 via-primary-800 to-indigo-950 p-6 md:p-8 text-white shadow-xl">
        <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-primary-500/20 blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 backdrop-blur-md border border-white/15 text-primary-200 uppercase tracking-wider">
                        {{ ucfirst(str_replace('_', ' ', $event->category)) }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                        {{ $event->status === 'active' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 
                           ($event->status === 'draft' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 
                           ($event->status === 'completed' ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30')) }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $event->status === 'active' ? 'bg-emerald-400' : 'bg-amber-400' }}"></span>
                        {{ ucfirst($event->status) }}
                    </span>
                </div>
                <h1 class="text-2xl md:text-4xl font-bold font-heading">{{ $event->title }}</h1>
                <p class="text-sm text-slate-300 max-w-2xl">
                    Scheduled on <strong class="text-white">{{ $event->date?->format('F d, Y') }}</strong> at <strong class="text-white">{{ $event->time }}</strong> &bull; {{ $event->venue }}
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0 flex-wrap">
                <a href="{{ route('host.events.edit', $event) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-medium text-xs backdrop-blur-md transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Details
                </a>
                <a href="{{ route('host.events.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-medium text-xs backdrop-blur-md transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    All Events
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stat Strip -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="host-stat-card">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Guests</div>
            <div class="text-2xl font-black font-heading text-slate-900 mt-1">{{ $event->guest_count ?: ($event->guests?->count() ?? 0) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">Invited / Registered</div>
        </div>

        <div class="host-stat-card">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Event Date</div>
            <div class="text-xl font-bold font-heading text-slate-900 mt-1">{{ $event->date?->format('M d, Y') }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">{{ $event->time }}</div>
        </div>

        <div class="host-stat-card">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Venue</div>
            <div class="text-lg font-bold font-heading text-slate-900 mt-1 truncate">{{ $event->venue }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5 truncate">{{ $event->venue_address ?: 'Dar es Salaam' }}</div>
        </div>

        <div class="host-stat-card">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Estimated Cost</div>
            <div class="text-xl font-black font-heading text-primary-600 mt-1">TZS {{ number_format($event->total_cost ?: ($event->guest_count * 2000)) }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">Automated billing</div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Main Details (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Description / Overview Card -->
            <div class="modern-card p-6 md:p-8">
                <h3 class="text-base font-bold font-heading text-slate-900 mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-primary-600"></span>
                    Event Information &amp; Schedule
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-medium text-slate-400 block">Date &amp; Time</span>
                        <span class="text-sm font-bold text-slate-800 mt-0.5 block">{{ $event->date?->format('l, F d, Y') }} at {{ $event->time }}</span>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-medium text-slate-400 block">Location / Venue</span>
                        <span class="text-sm font-bold text-slate-800 mt-0.5 block">{{ $event->venue }}</span>
                        @if($event->venue_address)
                            <span class="text-xs text-slate-500 mt-0.5 block">{{ $event->venue_address }}</span>
                        @endif
                    </div>
                </div>

                @if($event->description)
                    <div class="pt-4 border-t border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-2">Description / Notes</span>
                        <p class="text-sm text-slate-600 leading-relaxed bg-slate-50/70 p-4 rounded-2xl border border-slate-100/80">{{ $event->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Custom Event Data (Photos / Names / Dynamic Fields) -->
            @if($event->custom_data && count($event->custom_data) > 0)
                <div class="modern-card p-6 md:p-8">
                    <h3 class="text-base font-bold font-heading text-slate-900 mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                        {{ ucfirst(str_replace('_', ' ', $event->category)) }} Custom Details
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($event->custom_data as $key => $value)
                            @if($value)
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                    <span class="text-xs font-medium text-slate-400 block mb-1">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                    @if(str_contains($key, 'photo') || str_contains($key, 'banner') || str_contains($key, 'image'))
                                        <div class="mt-2">
                                            <img src="{{ storage_url('event_uploads/' . $value) }}" alt="{{ $key }}" class="w-32 h-32 object-cover rounded-xl shadow-md border border-slate-200">
                                        </div>
                                    @else
                                        <span class="text-sm font-bold text-slate-800">{{ $value }}</span>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Template Information -->
            @if($event->template)
                <div class="modern-card p-6 md:p-8">
                    <h3 class="text-base font-bold font-heading text-slate-900 mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        Attached Invitation Template
                    </h3>
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-primary-50/60 border border-primary-100">
                        <div class="w-12 h-12 rounded-xl bg-primary-600 text-white flex items-center justify-center shrink-0 shadow-md">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">{{ $event->template->name }}</h4>
                            <p class="text-xs text-slate-600 mt-0.5">{{ $event->template->description ?: 'Standard premium animated invitation layout.' }}</p>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <!-- Right Side Actions & Stats (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Quick Actions Card -->
            <div class="modern-card p-6">
                <h3 class="text-sm font-bold font-heading text-slate-900 mb-4 uppercase tracking-wider text-slate-400">Event Launchpad</h3>
                <div class="space-y-3">
                    <a href="{{ route('host.guests.index', ['event' => $event->id]) }}" class="modern-button modern-button-primary w-full justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Manage Guests &amp; RSVP</span>
                    </a>
                    
                    <a href="{{ route('host.invitations.index', ['event' => $event->id]) }}" class="modern-button modern-button-secondary w-full justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>Dispatch WhatsApp Cards</span>
                    </a>

                    <a href="{{ route('host.reports.index') }}" class="modern-button modern-button-ghost w-full justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Attendance Reports</span>
                    </a>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="modern-card p-6">
                <h3 class="text-sm font-bold font-heading text-slate-900 mb-4 uppercase tracking-wider text-slate-400">Milestone Timeline</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 mt-1.5 shrink-0 ring-4 ring-emerald-100"></div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Event Created</p>
                            <p class="text-[11px] text-slate-400">{{ $event->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    @if($event->status === 'active')
                    <div class="flex items-start gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-primary-500 mt-1.5 shrink-0 ring-4 ring-primary-100"></div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Event Active &amp; Ready</p>
                            <p class="text-[11px] text-slate-400">Invitations and check-ins enabled</p>
                        </div>
                    </div>
                    @endif

                    @if($event->date && $event->date->isFuture())
                    <div class="flex items-start gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 mt-1.5 shrink-0 ring-4 ring-indigo-100"></div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Celebration Day</p>
                            <p class="text-[11px] text-slate-400">{{ $event->date->format('M d, Y') }} ({{ $event->date->diffForHumans() }})</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
