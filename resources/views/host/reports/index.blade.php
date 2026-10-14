@extends('layouts.host')

@section('title', 'Reports & Analytics')
@section('page-title', 'Event Intelligence & Reports')

@section('content')
<div class="space-y-8 animate-fade-in max-w-7xl mx-auto">
    
    <!-- Top Header Bar -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xl shadow-slate-200/30 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-semibold text-indigo-700 mb-2">
                <span>Real-time Attendance &amp; Analytics</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black font-heading text-slate-900 tracking-tight">Reports &amp; Analytics</h1>
            <p class="text-xs sm:text-sm text-slate-500">Track delivery rates, guest RSVP responses, and door scanner verification metrics.</p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <select class="modern-select text-xs w-36">
                <option>All-Time</option>
                <option>Last 30 Days</option>
                <option>Last 7 Days</option>
            </select>
            <button class="modern-button modern-button-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Export Report</span>
            </button>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        <div class="host-stat-card p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.902l-5.4 2.7a2.25 2.25 0 01-2.13 0l-5.4-2.7a2.25 2.25 0 01-1.07-1.902V6.75m19.5 0H4.5" />
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-slate-400 uppercase">Invitations</span>
            </div>
            <p class="text-3xl font-black font-heading text-slate-900 mt-4">100%</p>
            <p class="text-xs text-emerald-600 font-semibold mt-1">&check; Automated Dispatch</p>
        </div>

        <div class="host-stat-card p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-emerald-600 uppercase bg-emerald-50 px-2 py-0.5 rounded-full">Delivery</span>
            </div>
            <p class="text-3xl font-black font-heading text-slate-900 mt-4">98.5%</p>
            <p class="text-xs text-slate-500 mt-1">Direct to WhatsApp</p>
        </div>

        <div class="host-stat-card p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-blue-600 uppercase bg-blue-50 px-2 py-0.5 rounded-full">Open Rate</span>
            </div>
            <p class="text-3xl font-black font-heading text-slate-900 mt-4">92.0%</p>
            <p class="text-xs text-slate-500 mt-1">Viewed Digital Card</p>
        </div>

        <div class="host-stat-card p-6">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-purple-600 uppercase bg-purple-50 px-2 py-0.5 rounded-full">Attendance</span>
            </div>
            <p class="text-3xl font-black font-heading text-slate-900 mt-4">Active</p>
            <p class="text-xs text-slate-500 mt-1">QR Door Scans Ready</p>
        </div>

    </div>

    <!-- Performance Insights -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="p-6 rounded-3xl bg-gradient-to-br from-emerald-50/80 to-teal-100/40 border border-emerald-200/80">
            <div class="flex items-center gap-3.5 mb-3">
                <div class="w-11 h-11 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-md shadow-emerald-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Top Delivery Method</h3>
                    <p class="text-xs text-slate-500">WhatsApp Gateway</p>
                </div>
            </div>
            <p class="text-2xl font-black font-heading text-emerald-700">99.2%</p>
            <p class="text-xs text-slate-500 mt-1">Instant delivery with photo card</p>
        </div>

        <div class="p-6 rounded-3xl bg-gradient-to-br from-amber-50/80 to-orange-100/40 border border-amber-200/80">
            <div class="flex items-center gap-3.5 mb-3">
                <div class="w-11 h-11 rounded-xl bg-amber-600 text-white flex items-center justify-center shadow-md shadow-amber-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Optimal Dispatch Time</h3>
                    <p class="text-xs text-slate-500">Peak Open Rate</p>
                </div>
            </div>
            <p class="text-2xl font-black font-heading text-amber-700">10:00 - 18:00</p>
            <p class="text-xs text-slate-500 mt-1">Highest RSVP conversion window</p>
        </div>

        <div class="p-6 rounded-3xl bg-gradient-to-br from-indigo-50/80 to-purple-100/40 border border-indigo-200/80">
            <div class="flex items-center gap-3.5 mb-3">
                <div class="w-11 h-11 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Security &amp; QR Status</h3>
                    <p class="text-xs text-slate-500">Door Pass Integrity</p>
                </div>
            </div>
            <p class="text-2xl font-black font-heading text-indigo-700">Encrypted</p>
            <p class="text-xs text-slate-500 mt-1">One-time valid entrance check-in</p>
        </div>

    </div>

</div>
@endsection
