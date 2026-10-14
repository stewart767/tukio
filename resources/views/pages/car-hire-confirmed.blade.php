@extends('layouts.landing')

@section('title', 'Booking Received - ' . $booking->booking_reference . ' - Tukio 360')

@section('content')
<div class="min-h-screen bg-[#f3ffe3] text-slate-800 relative overflow-hidden pt-10 pb-24" style="background-color: #f3ffe3;">
    <!-- Ambient Floating Highlights -->
    <div class="absolute top-20 left-1/4 -translate-x-1/2 w-[500px] h-[500px] bg-emerald-200/30 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-10 w-[450px] h-[450px] bg-amber-200/30 rounded-full blur-[130px] pointer-events-none"></div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 relative z-10">
        
        <!-- Success Alert Header -->
        <div class="text-center space-y-3">
            <div class="w-16 h-16 rounded-3xl bg-emerald-600 text-white flex items-center justify-center mx-auto shadow-xl shadow-emerald-600/20 text-2xl animate-bounce">
                ✓
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-emerald-800 bg-emerald-100 border border-emerald-200 text-xs font-semibold">Reservation Request Received</span>
            <h1 class="text-3xl sm:text-4xl font-black font-heading text-slate-900">
                Car Hire Booking Confirmed!
            </h1>
            <p class="text-slate-600 text-sm max-w-md mx-auto">
                Thank you, <strong>{{ $booking->customer_name }}</strong>. Our fleet management team has received your booking and is preparing your vehicle.
            </p>
        </div>

        @php
            $statusBadge = $booking->status_badge;
            $vehicle = $booking->vehicle;
        @endphp

        <!-- Printable Voucher Card (Clean Luxury White Pass) -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-200/80 overflow-hidden" id="voucher-printable">
            
            <!-- Voucher Top Banner -->
            <div class="bg-slate-900 text-white p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800">
                <div>
                    <span class="text-xs text-indigo-300 font-bold uppercase tracking-widest block">Booking Voucher Reference</span>
                    <h2 class="text-2xl sm:text-3xl font-black font-heading text-amber-300 tracking-wider mt-1">{{ $booking->booking_reference }}</h2>
                </div>
                <div>
                    <span class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full text-xs font-bold {{ $statusBadge['bg'] }} border shadow-sm backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full {{ $statusBadge['dot'] }}"></span>
                        <span>{{ $statusBadge['label'] }}</span>
                    </span>
                </div>
            </div>

            <!-- Voucher Content -->
            <div class="p-6 sm:p-8 space-y-8">
                
                <!-- Vehicle & Customer Quick Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-slate-200">
                    <!-- Left: Vehicle Summary (Image Frame) -->
                    <div class="space-y-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Reserved Vehicle</h3>
                        <div class="flex items-center space-x-4">
                            @if($vehicle)
                                <div class="bg-slate-100 p-1 rounded-xl overflow-hidden border border-slate-200 flex-shrink-0">
                                    <img src="{{ $vehicle->image_url_formatted }}" alt="{{ $vehicle->name }}" class="w-20 h-14 rounded-lg object-cover">
                                </div>
                            @endif
                            <div>
                                <h4 class="font-black font-heading text-slate-900 text-base">{{ $vehicle->name ?? 'Wedding Vehicle' }}</h4>
                                <p class="text-xs text-slate-500">{{ $vehicle->category_label ?? 'Fleet' }} &bull; {{ $vehicle->seating_capacity ?? 4 }} Seats</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Customer Info -->
                    <div class="space-y-1.5 text-xs text-slate-700 bg-slate-50 border border-slate-200/80 p-4 rounded-2xl">
                        <h3 class="font-bold uppercase tracking-wider text-slate-400 text-[10px] mb-1">Customer &amp; Event</h3>
                        <div><strong class="text-slate-900">Name:</strong> {{ $booking->customer_name }}</div>
                        <div><strong class="text-slate-900">Phone:</strong> {{ $booking->customer_phone }}</div>
                        @if($booking->customer_email)
                            <div><strong class="text-slate-900">Email:</strong> {{ $booking->customer_email }}</div>
                        @endif
                        <div><strong class="text-slate-900">Event:</strong> {{ $booking->event_type }}</div>
                    </div>
                </div>

                <!-- Schedule & Logistics -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Schedule &amp; Route Logistics</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                        <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl">
                            <span class="text-slate-400 block text-[10px]">Start Date</span>
                            <span class="font-bold text-slate-900 text-sm mt-0.5 block">{{ $booking->start_date?->format('d M Y') }}</span>
                            <span class="text-slate-500 text-[11px]">{{ $booking->days_count }} Day(s)</span>
                        </div>
                        <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl">
                            <span class="text-slate-400 block text-[10px]">Pickup Time</span>
                            <span class="font-bold text-slate-900 text-sm mt-0.5 block">{{ $booking->pickup_time ?? '08:00 AM' }}</span>
                        </div>
                        <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl">
                            <span class="text-slate-400 block text-[10px]">Pickup Address</span>
                            <span class="font-bold text-slate-900 text-sm mt-0.5 block truncate">{{ $booking->pickup_location }}</span>
                        </div>
                    </div>
                </div>

                <!-- Inclusions & Addons Breakdown -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Order &amp; Services Breakdown</h3>
                    <div class="rounded-2xl border border-slate-200 overflow-hidden divide-y divide-slate-200 text-xs">
                        
                        <div class="p-3.5 flex items-center justify-between bg-white">
                            <div>
                                <span class="font-bold text-slate-900">{{ $vehicle->name }}</span>
                                <span class="text-slate-500 block text-[11px]">{{ $booking->days_count }} Day Rental &bull; Uniformed Chauffeur &bull; Fuel</span>
                            </div>
                            <span class="font-bold text-slate-900">
                                TZS {{ number_format(($vehicle->price_per_day ?? 0) * $booking->days_count, 0, '.', ',') }}
                            </span>
                        </div>

                        @if($booking->need_guest_coasters)
                            <div class="p-3.5 flex items-center justify-between bg-amber-50 border-t border-amber-200">
                                 <div>
                                     <span class="font-bold text-amber-800">Guest Coaster Transport</span>
                                     <span class="text-amber-700/80 block text-[11px]">{{ $booking->guest_coasters_count }} Coaster(s) &bull; {{ $booking->days_count }} Day(s)</span>
                                 </div>
                                 <span class="font-bold text-amber-800">
                                     TZS {{ number_format($booking->guest_coasters_count * 400000 * $booking->days_count, 0, '.', ',') }}
                                 </span>
                            </div>
                        @endif

                        <div class="p-3.5 flex items-center justify-between bg-emerald-50">
                            <div>
                                <span class="font-semibold text-emerald-900">Bridal Floral Ribbons &amp; Bows</span>
                            </div>
                            <span class="font-bold text-emerald-700">FREE INCLUDED</span>
                        </div>

                        <div class="p-4 flex items-center justify-between bg-slate-900 text-white">
                            <div>
                                <span class="text-xs text-slate-300 uppercase tracking-wider font-bold">Total Amount Payable</span>
                                <p class="text-[11px] text-slate-400">Payable via M-Pesa, TigoPesa, Airtel Money, or Bank Transfer</p>
                            </div>
                            <span class="text-2xl font-black font-heading text-amber-400">{{ $booking->formatted_price }}</span>
                        </div>

                    </div>
                </div>

                @if($booking->driver_name)
                    <!-- Assigned Driver Card -->
                    <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-200 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-indigo-700 block">Assigned Chauffeur</span>
                            <h4 class="font-black text-slate-900 text-sm">{{ $booking->driver_name }}</h4>
                            <p class="text-xs text-slate-600">{{ $booking->driver_phone }}</p>
                        </div>
                        <a href="tel:{{ $booking->driver_phone }}" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs">
                            Call Driver
                        </a>
                    </div>
                @endif

            </div>

            <!-- Voucher Footer Actions -->
            <div class="bg-slate-50 p-6 sm:p-8 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <button type="button" onclick="window.print()" class="w-full sm:w-auto px-5 py-2.5 bg-white hover:bg-slate-100 text-slate-800 text-xs font-bold rounded-xl border border-slate-300 shadow-xs flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print / Save Voucher</span>
                </button>

                <!-- Direct WhatsApp Verification -->
                <a href="{{ $booking->whatsapp_url }}" target="_blank" class="w-full sm:w-auto px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-md flex items-center justify-center space-x-2 transition-all">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.966.527 1.76.812 2.795.812 3.179 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.999-5.767-5.999zm3.385 8.163c-.144.405-.837.774-1.17.822-.312.043-.699.077-2.029-.472-1.696-.703-2.779-2.457-2.863-2.569-.084-.112-.686-.914-.686-1.743 0-.829.434-1.237.588-1.408.155-.171.339-.214.452-.214.113 0 .226.002.324.007.104.005.243-.039.38.291.144.344.492 1.2.535 1.288.043.088.071.191.014.305-.056.113-.085.184-.17.283-.085.1-.179.223-.255.3-.099.099-.202.207-.087.404.115.197.51 1.042 1.093 1.562.752.669 1.386.877 1.583.974.197.098.312.085.428-.049.115-.133.493-.574.625-.771.132-.197.265-.164.444-.099.18.066 1.139.537 1.336.635.197.099.329.148.378.23.049.083.049.48-.095.885z"/></svg>
                    <span>Instant WhatsApp Verification</span>
                </a>
            </div>

        </div>

        <!-- Back Link -->
        <div class="text-center">
            <a href="{{ route('car-hire.index') }}" class="text-xs font-bold text-slate-700 hover:text-slate-900">
                &larr; Back to Car Hire Showroom
            </a>
        </div>

    </div>
</div>
@endsection
