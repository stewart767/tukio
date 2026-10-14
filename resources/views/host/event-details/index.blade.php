@extends('layouts.host')

@section('title', 'Event Details')
@section('page-title', 'Event Configuration Details')

@section('content')
<div class="space-y-8 animate-fade-in max-w-5xl mx-auto">
    
    <!-- Hero Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 p-8 sm:p-10 text-white shadow-xl shadow-indigo-500/20">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-xs font-semibold uppercase tracking-wider text-indigo-100">
                    <span>Configured Event</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black font-heading tracking-tight text-white capitalize">
                    {{ ucfirst(str_replace('_', ' ', $host->event_type)) }} Overview
                </h1>
                <p class="text-indigo-100/90 text-sm sm:text-base max-w-xl">
                    Your event details, custom fields, and uploaded photos used for digital invitation card generation.
                </p>
            </div>

            <a href="{{ route('host.event-config') }}" class="modern-button bg-white text-indigo-700 hover:bg-indigo-50 font-bold shadow-lg shadow-black/10 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Edit Configuration</span>
            </a>
        </div>
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Basic Information Grid -->
    <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-xl shadow-slate-200/30 space-y-6">
        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold font-heading text-slate-900">Registration &amp; Booking Details</h2>
                <p class="text-xs text-slate-500">Overview of your account and package quotas.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Event Category</span>
                <span class="text-base font-bold text-slate-900 mt-1 block capitalize">{{ ucfirst(str_replace('_', ' ', $host->event_type)) }}</span>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Guest Allocation</span>
                <span class="text-base font-bold text-slate-900 mt-1 block">{{ number_format($host->number_of_guests) }} Guests</span>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Total Package Amount</span>
                <span class="text-base font-bold text-indigo-600 mt-1 block">TZS {{ number_format($host->total_cost > 0 ? $host->total_cost : $host->number_of_guests * 2000) }}</span>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Host Phone</span>
                <span class="text-base font-bold text-slate-900 mt-1 block font-mono">{{ $host->phone }}</span>
            </div>
        </div>
    </div>

    <!-- Custom Event Configuration Data -->
    @if($host->event_config_data && count($host->event_config_data) > 0)
        <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-xl shadow-slate-200/30 space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold font-heading text-slate-900">Custom Event Fields &amp; Media</h2>
                        <p class="text-xs text-slate-500">Live fields displayed on your digital cards.</p>
                    </div>
                </div>
                <a href="{{ route('host.event-config') }}" class="modern-button modern-button-sm modern-button-secondary">
                    Edit Fields
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($host->event_config_data as $key => $value)
                    @if($value)
                        <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-200/80 space-y-2">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ ucfirst(str_replace('_', ' ', $key)) }}</p>
                            @if(str_contains($value, '.jpg') || str_contains($value, '.jpeg') || str_contains($value, '.png') || str_contains($value, '.gif') || str_contains($value, '.webp'))
                                <div class="mt-2">
                                    <img src="{{ storage_url('event_uploads/' . $value) }}" alt="{{ ucfirst(str_replace('_', ' ', $key)) }}" class="w-full h-48 object-cover rounded-xl border border-slate-200 shadow-sm">
                                </div>
                            @else
                                <p class="text-base font-bold text-slate-900">{{ $value }}</p>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
