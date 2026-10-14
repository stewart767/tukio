@extends('layouts.host')

@section('title', 'Booking Voucher - ' . $booking->booking_reference)
@section('page-title', 'Car Hire Voucher')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('host.car-hire.index') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center space-x-1">
            <span>&larr; Back to Car Hire</span>
        </a>
        <button type="button" onclick="window.print()" class="px-4 py-2 bg-white text-slate-700 text-xs font-bold rounded-xl border border-slate-200 shadow-xs flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Print Voucher</span>
        </button>
    </div>

    @php
        $st = $booking->status_badge;
        $vehicle = $booking->vehicle;
    @endphp

    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs text-indigo-300 font-bold uppercase tracking-wider block">Booking Reference</span>
                <h2 class="text-2xl sm:text-3xl font-black font-heading text-indigo-400 mt-1">{{ $booking->booking_reference }}</h2>
            </div>
            <span class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full text-xs font-bold {{ $st['bg'] }} border shadow-sm">
                <span class="w-2 h-2 rounded-full {{ $st['dot'] }}"></span>
                <span>{{ $st['label'] }}</span>
            </span>
        </div>

        <!-- Body -->
        <div class="p-6 sm:p-8 space-y-8">
            
            <!-- Vehicle Info -->
            <div class="flex items-center space-x-5 pb-6 border-b border-slate-100">
                @if($vehicle)
                    <img src="{{ $vehicle->image_url_formatted }}" alt="{{ $vehicle->name }}" class="w-28 h-20 rounded-2xl object-cover border border-slate-200 shadow-sm">
                @endif
                <div class="space-y-1">
                    <h3 class="text-xl font-bold font-heading text-slate-900">{{ $vehicle->name ?? 'Wedding Vehicle' }}</h3>
                    <p class="text-xs text-slate-500">{{ $vehicle->category_label ?? 'Fleet' }} &bull; {{ $vehicle->seating_capacity ?? 4 }} Seats &bull; Chauffeur Included</p>
                    <span class="text-base font-black text-indigo-600 font-heading block">{{ $booking->formatted_price }} Total</span>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-1">
                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Start Date</span>
                    <span class="font-bold text-slate-900 text-sm block">{{ $booking->start_date?->format('d M Y') }}</span>
                    <span class="text-slate-500">{{ $booking->days_count }} Day(s) Duration</span>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-1">
                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Pickup Address</span>
                    <span class="font-bold text-slate-900 text-sm block truncate">{{ $booking->pickup_location }}</span>
                    <span class="text-slate-500">Time: {{ $booking->pickup_time ?? '08:00 AM' }}</span>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-1">
                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Destination Venue</span>
                    <span class="font-bold text-slate-900 text-sm block truncate">{{ $booking->dropoff_location ?? 'Reception Venue' }}</span>
                </div>
            </div>

            @if($booking->driver_name)
                <!-- Driver Card -->
                <div class="p-5 rounded-2xl bg-indigo-50 border border-indigo-200 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase text-indigo-500 block">Assigned Chauffeur</span>
                        <h4 class="text-base font-black text-indigo-950">{{ $booking->driver_name }}</h4>
                        <p class="text-xs text-indigo-700">{{ $booking->driver_phone }}</p>
                    </div>
                    <a href="tel:{{ $booking->driver_phone }}" class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl shadow-sm">
                        Call Driver
                    </a>
                </div>
            @endif

            <!-- WhatsApp Action -->
            <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="space-y-0.5 text-center sm:text-left">
                    <h4 class="font-bold text-emerald-950 text-sm">Need to update your schedule or ribbon color?</h4>
                    <p class="text-xs text-emerald-700">Chat with the Tukio 360 fleet manager directly on WhatsApp with your reference number.</p>
                </div>
                <a href="{{ $booking->whatsapp_url }}" target="_blank" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-sm whitespace-nowrap flex items-center space-x-2">
                    <span>Chat on WhatsApp</span>
                </a>
            </div>

        </div>

    </div>

</div>
@endsection
