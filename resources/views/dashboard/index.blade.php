@extends('layouts.dashboard')

@section('title', 'Admin Command Center')

@section('content')
<div class="space-y-8 pb-8">
    
    <!-- Executive Welcome Hero Hub -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-8 lg:p-10 shadow-xl border border-indigo-900/40">
        <!-- Ambient decorative lights -->
        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-72 h-72 rounded-full bg-indigo-500/20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/3 -mb-12 w-64 h-64 rounded-full bg-purple-500/15 blur-3xl pointer-events-none"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff05_1px,transparent_1px),linear-gradient(to_bottom,#ffffff05_1px,transparent_1px)] bg-[size:24px_24px] pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-semibold text-indigo-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Executive Command Center</span>
                    <span class="text-white/40">•</span>
                    <span class="text-emerald-300">Live Synced</span>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-500 to-slate-900 p-0.5 shadow-lg shadow-indigo-500/30 flex items-center justify-center shrink-0">
                        <div class="w-full h-full bg-slate-900/40 rounded-[14px] flex items-center justify-center backdrop-blur-xs text-indigo-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white font-heading">
                            Welcome back, <span class="bg-gradient-to-r from-white via-indigo-100 to-indigo-300 bg-clip-text text-transparent">{{ auth()->user()->name }}</span>
                        </h1>
                        <p class="text-slate-300 text-sm sm:text-base mt-1">
                            Here's what is happening across your events, hosts, and fleet mobility today.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Action Hub in Hero -->
            <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.hosts.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/15 text-xs sm:text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:scale-[1.02]">
                        <svg class="w-4 h-4 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        <span>Add Host</span>
                    </a>
                    <a href="{{ route('admin.vehicles.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/15 text-xs sm:text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:scale-[1.02]">
                        <svg class="w-4 h-4 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.175V3.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75"></path>
                        </svg>
                        <span>Fleet Center</span>
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-xs sm:text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>View Analytics</span>
                    </a>
                @elseif(auth()->user()->isEventAdmin())
                    <a href="{{ route('host.events.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-xs sm:text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Manage Events</span>
                    </a>
                @elseif(auth()->user()->isStaff())
                    <a href="{{ route('staff.scanner') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 text-xs sm:text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 transition-all duration-200 hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        <span>Launch QR Scanner</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if(auth()->user()->isSuperAdmin())
        
        <!-- 5-Grid Radiant Executive KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-5">
            
            <!-- 1. Total Hosts Card -->
            <div class="admin-stat-card p-5 group bg-gradient-to-br from-white via-indigo-50/30 to-blue-50/40 border-indigo-100/80">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Total Hosts</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight font-heading">{{ $data['total_hosts'] }}</div>
                    <span class="inline-flex items-center text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                        &uarr; 100% Active
                    </span>
                </div>
                <div class="mt-3 flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-indigo-50">
                    <span>Registered organizers</span>
                    <a href="{{ route('admin.hosts.index') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                        Manage &rarr;
                    </a>
                </div>
            </div>

            <!-- 2. Total Events Card -->
            <div class="admin-stat-card p-5 group bg-gradient-to-br from-white via-emerald-50/30 to-teal-50/40 border-emerald-100/80">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Total Events</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight font-heading">{{ $data['total_events'] }}</div>
                    <span class="inline-flex items-center text-[11px] font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full border border-teal-100">
                        {{ $data['active_events'] }} Live Now
                    </span>
                </div>
                <div class="mt-3 flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-emerald-50">
                    <span>{{ $data['draft_events'] }} drafts in progress</span>
                    <span class="text-emerald-600 font-semibold">● Realtime</span>
                </div>
            </div>

            <!-- 3. Total Guests Card -->
            <div class="admin-stat-card p-5 group bg-gradient-to-br from-white via-purple-50/30 to-violet-50/40 border-purple-100/80">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-purple-600">Total Guests</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300 shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight font-heading">{{ number_format($data['total_guests']) }}</div>
                    <span class="inline-flex items-center text-[11px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-100">
                        {{ $data['attendance_rate'] }}% Check-in
                    </span>
                </div>
                <div class="mt-3 flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-purple-50">
                    <span>{{ $data['attended_guests'] }} scanned attendees</span>
                    <span class="text-purple-600 font-semibold">Synced</span>
                </div>
            </div>

            <!-- 4. Revenue Collected Card -->
            <div class="admin-stat-card p-5 group bg-gradient-to-br from-white via-amber-50/30 to-orange-50/40 border-amber-100/80">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Total Revenue</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300 shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl sm:text-[26px] font-extrabold text-slate-900 tracking-tight font-heading truncate">
                        <span class="text-xs font-bold text-amber-600 mr-1">TZS</span>{{ number_format($data['total_amount'], 0) }}
                    </div>
                </div>
                <div class="mt-3 flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-amber-50">
                    <span>Host invoice totals</span>
                    <span class="text-amber-600 font-semibold">Paid &amp; Active</span>
                </div>
            </div>

            <!-- 5. Fleet & Bookings Card -->
            <div class="admin-stat-card p-5 group bg-gradient-to-br from-white via-sky-50/30 to-blue-50/40 border-sky-100/80">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-sky-600">Fleet Mobility</span>
                    <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-sky-600 group-hover:text-white transition-all duration-300 shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.175V3.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight font-heading">{{ $data['total_vehicles'] }} <span class="text-sm font-semibold text-slate-500">Cars</span></div>
                    <span class="inline-flex items-center text-[11px] font-bold text-sky-700 bg-sky-50 px-2 py-0.5 rounded-full border border-sky-100">
                        {{ $data['total_bookings'] }} Bookings
                    </span>
                </div>
                <div class="mt-3 flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-sky-50">
                    <span>{{ $data['available_vehicles'] }} available for hire</span>
                    <a href="{{ route('admin.car-bookings.index') }}" class="font-semibold text-sky-600 hover:text-sky-700">
                        Bookings &rarr;
                    </a>
                </div>
            </div>

        </div>

        <!-- Interactive Analytics & Performance Charts Suite (Chart.js) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Monthly Events & Revenue Curve (2 Columns) -->
            <div class="lg:col-span-2 glass-surface rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-bold text-slate-900 font-heading">Event &amp; Revenue Trends</h3>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">Interactive</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Platform growth trajectory over the past 6 months</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600">
                            <span class="w-3 h-3 rounded-full bg-indigo-600"></span> Events
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-500 ml-2">
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span> Revenue (TZS)
                        </span>
                    </div>
                </div>
                
                <div class="mt-4 relative h-64 sm:h-72 w-full">
                    <canvas id="eventsTrendChart"></canvas>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 grid grid-cols-3 gap-2 text-center">
                    <div class="p-2 rounded-xl bg-slate-50">
                        <span class="text-[11px] text-slate-400 font-semibold block uppercase">Total Events</span>
                        <span class="text-sm font-bold text-slate-800">{{ $data['total_events'] }}</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50">
                        <span class="text-[11px] text-slate-400 font-semibold block uppercase">Active Hosts</span>
                        <span class="text-sm font-bold text-indigo-600">{{ $data['total_hosts'] }}</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50">
                        <span class="text-[11px] text-slate-400 font-semibold block uppercase">Attendance Rate</span>
                        <span class="text-sm font-bold text-emerald-600">{{ $data['attendance_rate'] }}%</span>
                    </div>
                </div>
            </div>

            <!-- Operations Status Donut Breakdown (1 Column) -->
            <div class="glass-surface rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div class="pb-4 border-b border-slate-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Status Distribution</h3>
                        <span class="text-xs font-semibold text-slate-400">All Modules</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Live status of events and fleet requests</p>
                </div>

                <div class="mt-4 relative h-52 sm:h-56 w-full flex items-center justify-center">
                    <canvas id="statusDonutChart"></canvas>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-2 text-slate-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Active Events
                        </span>
                        <span class="font-bold text-slate-900">{{ $data['active_events'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-2 text-slate-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> Draft Events
                        </span>
                        <span class="font-bold text-slate-900">{{ $data['draft_events'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-2 text-slate-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span> Fleet Bookings
                        </span>
                        <span class="font-bold text-slate-900">{{ $data['total_bookings'] }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Dynamic Management Center: Recent Events & Team Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- 1. Recent Events Interactive List -->
            <div class="glass-surface rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm">
                <div class="p-5 sm:p-6 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-indigo-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white font-heading">Recent Events</h3>
                            <p class="text-xs text-indigo-200">Latest created event workspaces</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-white/10 text-white border border-white/10">
                        {{ count($data['recent_events']) }} Shown
                    </span>
                </div>

                <div class="p-5 sm:p-6 space-y-3 max-h-96 overflow-y-auto custom-scrollbar">
                    @forelse($data['recent_events'] as $event)
                    <div class="group p-3.5 rounded-2xl bg-white hover:bg-slate-50/80 border border-slate-200/60 hover:border-indigo-200 transition-all duration-200 shadow-xs flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-600 text-white flex flex-col items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                                <span class="text-[10px] font-bold uppercase leading-none">{{ $event->date ? $event->date->format('M') : 'N/A' }}</span>
                                <span class="text-sm font-black leading-tight">{{ $event->date ? $event->date->format('d') : '--' }}</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-slate-900 truncate group-hover:text-indigo-600 transition-colors">{{ $event->title }}</h4>
                                <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5">
                                    <span class="truncate">{{ $event->user ? $event->user->name : ($event->host ? $event->host->full_name : 'System Host') }}</span>
                                    <span>•</span>
                                    <span>{{ $event->date ? $event->date->format('M d, Y') : 'Pending Date' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold
                                {{ $event->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 
                                   ($event->status === 'draft' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-700 border border-slate-200') }}">
                                {{ ucfirst($event->status ?? 'Active') }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">No events found</p>
                        <p class="text-xs text-slate-400 mt-1">Events will appear here once created</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- 2. Recent Users & Team Access Hub -->
            <div class="glass-surface rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm">
                <div class="p-5 sm:p-6 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-emerald-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white font-heading">Recent Users</h3>
                            <p class="text-xs text-emerald-200">System accounts and roles</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="px-2.5 py-1 rounded-full text-xs font-semibold bg-white/10 text-white hover:bg-white/20 transition-colors border border-white/10">
                        View All Users &rarr;
                    </a>
                </div>

                <div class="p-5 sm:p-6 space-y-3 max-h-96 overflow-y-auto custom-scrollbar">
                    @forelse($data['recent_users'] as $user)
                    <div class="group p-3.5 rounded-2xl bg-white hover:bg-slate-50/80 border border-slate-200/60 hover:border-emerald-200 transition-all duration-200 shadow-xs flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="relative">
                                <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-600 text-white font-bold text-base flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-slate-900 truncate group-hover:text-emerald-600 transition-colors">{{ $user->name }}</h4>
                                <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold
                                {{ $user->role === 'super_admin' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 
                                   ($user->role === 'event_admin' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200') }}">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">No users found</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

    @elseif(auth()->user()->isEventAdmin())
        
        <!-- Event Admin View -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="admin-stat-card p-6 bg-gradient-to-br from-white to-indigo-50/40 border-indigo-100">
                <span class="text-xs font-bold uppercase text-indigo-600">My Events</span>
                <div class="mt-2 text-3xl font-extrabold text-slate-900 font-heading">{{ $data['total_events'] }}</div>
                <p class="mt-2 text-xs text-slate-500">Created by your account</p>
            </div>
            <div class="admin-stat-card p-6 bg-gradient-to-br from-white to-purple-50/40 border-purple-100">
                <span class="text-xs font-bold uppercase text-purple-600">Total Guests</span>
                <div class="mt-2 text-3xl font-extrabold text-slate-900 font-heading">{{ $data['total_guests'] }}</div>
                <p class="mt-2 text-xs text-slate-500">{{ $data['attended_guests'] }} checked in</p>
            </div>
            <div class="admin-stat-card p-6 bg-gradient-to-br from-white to-emerald-50/40 border-emerald-100">
                <span class="text-xs font-bold uppercase text-emerald-600">Active Events</span>
                <div class="mt-2 text-3xl font-extrabold text-slate-900 font-heading">{{ $data['active_events'] }}</div>
                <p class="mt-2 text-xs text-slate-500">Live for guest attendance</p>
            </div>
            <div class="admin-stat-card p-6 bg-gradient-to-br from-white to-indigo-50/40 border-indigo-100 flex flex-col justify-center items-center text-center">
                <a href="{{ route('host.events.index') }}" class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-sm shadow-md shadow-indigo-500/25 hover:from-indigo-500 hover:to-purple-500 transition-all">
                    Manage Events &rarr;
                </a>
            </div>
        </div>

        @if(isset($data['upcoming_events']) && $data['upcoming_events']->count() > 0)
        <div class="glass-surface rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 font-heading mb-4">Upcoming Events</h3>
            <div class="space-y-3">
                @foreach($data['upcoming_events'] as $event)
                <div class="p-4 rounded-2xl bg-white border border-slate-100 shadow-xs flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-slate-900">{{ $event->title }}</h4>
                        <p class="text-xs text-slate-500">{{ $event->venue }} • {{ $event->date ? $event->date->format('M d, Y') : '' }}</p>
                    </div>
                    <a href="{{ route('host.events.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">View Events &rarr;</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    @elseif(auth()->user()->isStaff())
        
        <!-- Staff Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="admin-stat-card p-6 bg-gradient-to-br from-white to-emerald-50/40 border-emerald-100">
                <span class="text-xs font-bold uppercase text-emerald-600">Scans Today</span>
                <div class="mt-2 text-3xl font-extrabold text-slate-900 font-heading">{{ $data['scans_today'] }}</div>
                <p class="mt-2 text-xs text-slate-500">Live verified attendees</p>
            </div>
            <div class="admin-stat-card p-6 bg-gradient-to-br from-white to-indigo-50/40 border-indigo-100">
                <span class="text-xs font-bold uppercase text-indigo-600">Total Scans</span>
                <div class="mt-2 text-3xl font-extrabold text-slate-900 font-heading">{{ $data['total_scans'] }}</div>
                <p class="mt-2 text-xs text-slate-500">All-time check-ins</p>
            </div>
            <div class="admin-stat-card p-6 bg-gradient-to-br from-white to-teal-50/40 border-teal-100 flex flex-col justify-center items-center text-center">
                <a href="{{ route('staff.scanner') }}" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold text-sm shadow-md shadow-emerald-500/25 hover:scale-[1.02] transition-all">
                    Launch QR Scanner
                </a>
            </div>
        </div>

    @endif

</div>

@push('scripts')
@if(auth()->user()->isSuperAdmin())
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Events & Revenue Trend Chart
    const trendCtx = document.getElementById('eventsTrendChart');
    if (trendCtx) {
        const months = {!! json_encode($data['chart_months'] ?? ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep']) !!};
        const eventsData = {!! json_encode($data['chart_events'] ?? [0, 0, 0, 0, 0, 0]) !!};
        const revenueData = {!! json_encode($data['chart_revenue'] ?? [0, 0, 0, 0, 0, 0]) !!};

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Events Created',
                        data: eventsData,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.12)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y',
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#6366f1',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Revenue (TZS)',
                        data: revenueData,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.08)',
                        borderWidth: 2.5,
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0.35,
                        yAxisID: 'y1',
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#f59e0b',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: 'bold' },
                        bodyFont: { family: 'Inter', size: 12 },
                        padding: 12,
                        cornerRadius: 12,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Inter', size: 11 }, color: '#64748b' }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: { color: 'rgba(226, 232, 240, 0.6)' },
                        ticks: { stepSize: 1, font: { family: 'Inter', size: 11 }, color: '#64748b' }
                    },
                    y1: {
                        type: 'linear',
                        display: false,
                        position: 'right',
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });
    }

    // 2. Status Distribution Donut Chart
    const statusCtx = document.getElementById('statusDonutChart');
    if (statusCtx) {
        const activeEvents = {{ $data['active_events'] ?? 1 }};
        const draftEvents = {{ $data['draft_events'] ?? 0 }};
        const bookings = {{ $data['total_bookings'] ?? 0 }};
        const completed = {{ $data['completed_events'] ?? 0 }};

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Active Events', 'Draft Events', 'Fleet Bookings', 'Completed'],
                datasets: [{
                    data: [activeEvents || 1, draftEvents, bookings, completed],
                    backgroundColor: [
                        '#10b981', // emerald
                        '#6366f1', // indigo
                        '#0284c7', // sky
                        '#94a3b8'  // slate
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 10,
                        cornerRadius: 10,
                    }
                }
            }
        });
    }
});
</script>
@endif
@endpush
@endsection

