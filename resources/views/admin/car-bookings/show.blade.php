@extends('layouts.dashboard')

@section('title', 'Manage Booking #' . $booking->booking_reference)
@section('page-title', 'Charter Reservation Voucher')

@section('content')
<div class="space-y-8 pb-12 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 rounded-full border border-purple-200/60 bg-purple-50/80 px-3 py-1 text-xs font-semibold text-purple-700">
                <span class="h-1.5 w-1.5 rounded-full bg-purple-500"></span>
                Reservation Voucher &amp; Dispatch
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Charter: {{ $booking->booking_reference }}</h1>
            <p class="text-sm text-slate-500">Review logistics itinerary, customer contact, and driver dispatch records.</p>
        </div>
        <div class="flex items-center gap-2">
            @if($booking->whatsapp_url)
                <a href="{{ $booking->whatsapp_url }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition-all">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.54 1.772.814 2.79.814 3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.586-5.766-5.767-5.766zm0 10.375c-.881 0-1.64-.23-2.348-.684l-.168-.108-1.577.413.421-1.538-.117-.187c-.503-.805-.769-1.52-.769-2.505 0-2.541 2.068-4.608 4.608-4.608 2.54 0 4.608 2.067 4.608 4.608 0 2.541-2.067 4.609-4.608 4.609z"/></svg>
                    <span>WhatsApp Client</span>
                </a>
            @endif
            <a href="{{ route('admin.car-bookings.index') }}" class="btn-secondary text-xs sm:text-sm">
                Back to Bookings
            </a>
        </div>
    </div>

    @php
        $st = $booking->status_badge;
        $vehicle = $booking->vehicle;
    @endphp

    <!-- Booking Overview Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md overflow-hidden">
        <!-- Top Voucher Hero Header -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-8 text-white relative">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 relative z-10">
                <div class="space-y-1">
                    <span class="text-[11px] uppercase tracking-widest text-indigo-300 font-bold">Booking Reference ID</span>
                    <h2 class="text-2xl sm:text-3xl font-black font-mono tracking-tight">{{ $booking->booking_reference }}</h2>
                    <p class="text-xs text-indigo-200/80">Received on {{ $booking->created_at->format('d M Y, h:i A') }} ({{ $booking->created_at->diffForHumans() }})</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold {{ $st['bg'] }} border shadow-md">
                        <span class="h-2 w-2 rounded-full {{ $st['dot'] }}"></span>
                        <span>{{ $st['label'] }}</span>
                    </span>
                </div>
            </div>
        </div>

        <div class="p-8 space-y-8">
            <!-- Left & Right Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pb-8 border-b border-slate-100">
                <!-- Vehicle Card -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Charter Fleet Item</h3>
                    <div class="flex items-center gap-4">
                        <div class="h-20 w-28 rounded-xl overflow-hidden bg-slate-200 shrink-0 border border-slate-300">
                            @if($vehicle)
                                <img src="{{ $vehicle->image_url_formatted }}" alt="{{ $vehicle->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center text-slate-400">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zm-9-2h10m1 0h2a1 1 0 001-1v-4a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 0014.586 7H9.414a1 1 0 00-.707.293L6.293 9.707A1 1 0 015.586 10H4a2 2 0 00-2 2v4a1 1 0 001 1h2"/></svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-base">{{ $vehicle->name ?? 'Vehicle' }}</h4>
                            <div class="text-xs text-slate-500">{{ $vehicle->category_label ?? 'Fleet' }} &bull; {{ $vehicle->seating_capacity ?? 4 }} Passenger Seats</div>
                            <div class="text-sm font-black text-indigo-600 font-mono mt-1">{{ $booking->formatted_price }} Total Rate</div>
                        </div>
                    </div>

                    @if($booking->need_guest_coasters)
                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-900 font-medium">
                            <strong>Guest Transport Included:</strong> {{ $booking->guest_coasters_count }} Toyota Coaster Bus (30-Seater)
                        </div>
                    @endif
                </div>

                <!-- Customer Details -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3 text-xs text-slate-700">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Client &amp; Event Host</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between border-b border-slate-200/60 pb-1.5">
                            <span class="text-slate-500">Customer Name</span>
                            <span class="font-bold text-slate-900">{{ $booking->customer_name }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200/60 pb-1.5">
                            <span class="text-slate-500">Phone Number</span>
                            <a href="tel:{{ $booking->customer_phone }}" class="font-bold font-mono text-indigo-600 hover:underline">{{ $booking->customer_phone }}</a>
                        </div>
                        @if($booking->customer_email)
                        <div class="flex justify-between border-b border-slate-200/60 pb-1.5">
                            <span class="text-slate-500">Email Address</span>
                            <span class="font-mono text-slate-800">{{ $booking->customer_email }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between border-b border-slate-200/60 pb-1.5">
                            <span class="text-slate-500">Celebration Type</span>
                            <span class="font-bold text-slate-800">{{ ucfirst(str_replace('_', ' ', $booking->event_type)) }}</span>
                        </div>
                        @if($booking->host)
                        <div class="flex justify-between">
                            <span class="text-slate-500">Linked Host Portal</span>
                            <span class="font-semibold text-slate-800">{{ $booking->host->full_name }} (#{{ $booking->host->id }})</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Schedule & Itinerary Grid -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Itinerary &amp; Schedule</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Charter Date &amp; Duration</span>
                        <span class="font-bold text-slate-900 text-sm mt-1 block">{{ $booking->start_date?->format('d M Y') }}</span>
                        <span class="text-slate-500">{{ $booking->days_count }} Day(s) Charter</span>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Pickup Time</span>
                        <span class="font-bold text-slate-900 text-sm mt-1 block">{{ $booking->pickup_time ?? '08:00 AM' }}</span>
                        <span class="text-slate-500">Morning Departure</span>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Route &amp; Destination</span>
                        <span class="font-bold text-slate-900 text-sm mt-1 block">{{ $booking->pickup_location }}</span>
                        <span class="text-slate-500">To: {{ $booking->dropoff_location ?? 'Reception Venue' }}</span>
                    </div>
                </div>
            </div>

            @if($booking->special_requests)
                <div class="p-4 rounded-2xl bg-indigo-50/60 border border-indigo-100 text-xs space-y-1">
                    <span class="font-bold text-indigo-900 block">Customer Special Requests / Route Instructions:</span>
                    <p class="text-indigo-800">{{ $booking->special_requests }}</p>
                </div>
            @endif

            <!-- Dispatch & Status Form -->
            <form action="{{ route('admin.car-bookings.update-status', $booking->id) }}" method="POST" class="pt-6 border-t border-slate-100 space-y-6">
                @csrf
                @method('PATCH')

                <h3 class="text-base font-bold text-slate-900 font-heading">Admin Dispatch &amp; Status Controls</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Booking Status</label>
                        <select name="status" class="form-input-modern text-sm font-bold">
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed &amp; Reserved</option>
                            <option value="in_progress" {{ $booking->status == 'in_progress' ? 'selected' : '' }}>In Progress (Trip Active)</option>
                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Assigned Chauffeur / Driver</label>
                        <input type="text" name="driver_name" value="{{ old('driver_name', $booking->driver_name) }}" placeholder="e.g. Ally Mkwawa"
                               class="form-input-modern text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Driver Phone Number</label>
                        <input type="tel" name="driver_phone" value="{{ old('driver_phone', $booking->driver_phone) }}" placeholder="+255 7XX XXX XXX"
                               class="form-input-modern text-sm font-mono">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Internal Administration Notes</label>
                    <textarea name="admin_notes" rows="2" placeholder="e.g. Deposit received, driver briefed on church arrival time..."
                              class="form-input-modern text-sm">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.car-bookings.index') }}" class="btn-secondary text-sm">Cancel</a>
                    <button type="submit" class="btn-primary text-sm shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Charter Status &amp; Driver
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
