@extends('layouts.host')

@section('title', 'Wedding & Event Fleet Hire')
@section('page-title', 'Car Hire & Fleet Transport')

@section('content')
<div class="space-y-8">
    
    <!-- Top Banner Card (Ambient Translucent Glass) -->
    <div class="rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-8 shadow-xl border border-indigo-500/20 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-pink-500/20 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="space-y-2 text-center md:text-left relative z-10">
            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-pink-500 text-white">Event Transportation</span>
            <h2 class="text-2xl sm:text-3xl font-black font-heading text-white">Hire Cars For {{ $event->title ?? 'Your Event' }}</h2>
            <p class="text-slate-300 text-xs sm:text-sm max-w-xl">
                Select luxury bridal cars for bride &amp; groom and 30-seater guest coasters for your entourage. Pre-configured with your event date: <strong>{{ $event?->date?->format('d M Y') ?? 'Upcoming' }}</strong>.
            </p>
        </div>

        <div class="flex items-center space-x-3 relative z-10">
            <a href="{{ route('car-hire.index') }}" target="_blank" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white font-bold text-xs rounded-xl border border-white/20 transition-colors flex items-center space-x-2">
                <span>View Full Showroom</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
    </div>

    <!-- Host Active Bookings List (if any) -->
    @if($myBookings->count() > 0)
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold font-heading text-slate-900">Your Reserved Vehicles ({{ $myBookings->count() }})</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($myBookings as $bk)
                    @php
                        $st = $bk->status_badge;
                    @endphp
                    <div class="glass-card rounded-2xl p-5 shadow-sm border border-slate-200/80 flex flex-col justify-between space-y-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                @if($bk->vehicle)
                                    <img src="{{ $bk->vehicle->image_url_formatted }}" alt="{{ $bk->vehicle->name }}" class="w-16 h-12 rounded-xl object-cover border border-slate-100 shadow-sm">
                                @endif
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">{{ $bk->vehicle->name ?? 'Wedding Vehicle' }}</h4>
                                    <span class="text-xs text-slate-500">Ref: {{ $bk->booking_reference }} &bull; {{ $bk->start_date?->format('d M Y') }}</span>
                                </div>
                            </div>
                            <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold {{ $st['bg'] }} border shadow-xs">
                                <span class="w-1.5 h-1.5 rounded-full {{ $st['dot'] }}"></span>
                                <span>{{ $st['label'] }}</span>
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-100">
                            <div>
                                <span class="text-slate-400">Total:</span>
                                <span class="font-bold text-slate-900">{{ $bk->formatted_price }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('host.car-hire.show', $bk->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition-colors">
                                    View Voucher
                                </a>
                                <a href="{{ $bk->whatsapp_url }}" target="_blank" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-lg flex items-center space-x-1 transition-colors">
                                    <span>WhatsApp</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Category Filter Tabs (Translucent Glass Tabs) -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-2 scrollbar-none">
        <a href="{{ route('host.car-hire.index', ['category' => 'all']) }}" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $category == 'all' ? 'bg-primary-600 text-white shadow-md' : 'bg-white/80 backdrop-blur-md text-slate-600 hover:bg-white border border-slate-200' }}">
            All Fleet
        </a>
        <a href="{{ route('host.car-hire.index', ['category' => 'luxury_bride_groom']) }}" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $category == 'luxury_bride_groom' ? 'bg-pink-600 text-white shadow-md' : 'bg-white/80 backdrop-blur-md text-slate-600 hover:bg-pink-50 hover:text-pink-600 border border-slate-200' }}">
            👰 Bride &amp; Groom Luxury
        </a>
        <a href="{{ route('host.car-hire.index', ['category' => 'guest_coaster_bus']) }}" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $category == 'guest_coaster_bus' ? 'bg-amber-600 text-white shadow-md' : 'bg-white/80 backdrop-blur-md text-slate-600 hover:bg-amber-50 hover:text-amber-600 border border-slate-200' }}">
            🚌 Guest Coasters &amp; Buses
        </a>
        <a href="{{ route('host.car-hire.index', ['category' => 'vip_suv_convoy']) }}" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $category == 'vip_suv_convoy' ? 'bg-purple-600 text-white shadow-md' : 'bg-white/80 backdrop-blur-md text-slate-600 hover:bg-purple-50 hover:text-purple-600 border border-slate-200' }}">
            👑 VIP Convoys &amp; SUVs
        </a>
    </div>

    <!-- Vehicles Fleet Cards Grid (Transparent / Glassmorphic Cards Containing Images) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($vehicles as $car)
            @php
                $badge = $car->category_badge;
            @endphp
            <div class="glass-card rounded-3xl overflow-hidden shadow-sm hover:shadow-xl border border-slate-200/80 hover:border-primary-400 transition-all duration-300 flex flex-col justify-between group bg-white/90 backdrop-blur-lg">
                
                <!-- Image Box with Translucent Overlays -->
                <div class="relative h-48 bg-slate-900 overflow-hidden">
                    <img src="{{ $car->image_url_formatted }}" alt="{{ $car->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                    
                    <div class="absolute top-3 left-3">
                        <span class="badge-pill {{ $badge['pill'] }} text-[10px] shadow-sm backdrop-blur-md">
                            {{ $badge['icon'] }} {{ $badge['label'] }}
                        </span>
                    </div>

                    <div class="absolute bottom-3 left-3 right-3 flex items-end justify-between text-white">
                        <span class="text-xl font-black font-heading">{{ $car->formatted_price }}<span class="text-xs text-slate-300 font-normal">/day</span></span>
                        <span class="text-xs bg-black/40 px-2.5 py-1 rounded-lg backdrop-blur-md border border-white/10">👥 {{ $car->seating_capacity }} seats</span>
                    </div>
                </div>

                <!-- Body Details -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <h4 class="font-bold font-heading text-slate-900 text-base group-hover:text-primary-600 transition-colors">{{ $car->name }}</h4>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $car->description }}</p>
                    </div>

                    <div class="bg-slate-50/80 backdrop-blur-xs p-2.5 rounded-xl border border-slate-100 grid grid-cols-2 gap-1.5 text-[11px] text-slate-600">
                        <div>✓ Chauffeur Included</div>
                        <div>✓ Fuel Included</div>
                        <div>✓ White Satin Bows</div>
                        <div>✓ {{ $car->brand }}</div>
                    </div>

                    <!-- Hire Button Triggering Modal -->
                    <button type="button" onclick="openHostBookingModal({{ json_encode($car) }})" class="w-full py-2.5 bg-gradient-to-r from-primary-600 to-indigo-600 hover:from-primary-500 hover:to-indigo-500 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                        Book for My Event
                    </button>
                </div>

            </div>
        @endforeach
    </div>

</div>

<!-- Host Quick Booking Modal (Frosted Glass Modal) -->
<div id="host-booking-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white/95 backdrop-blur-xl rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-white/60 relative max-h-[90vh] overflow-y-auto space-y-5">
        
        <!-- Close button -->
        <button type="button" onclick="closeHostBookingModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="space-y-1">
            <span class="badge-pill badge-pink text-[10px]">Event Linked Reservation</span>
            <h3 class="text-xl font-bold font-heading text-slate-900" id="modal-car-name">Reserve Vehicle</h3>
            <p class="text-xs text-slate-500" id="modal-car-price">TZS 0 / day</p>
        </div>

        <form action="{{ route('host.car-hire.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="vehicle_id" id="modal-vehicle-id">

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Start Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="start_date" required value="{{ $event?->date?->format('Y-m-d') ?? date('Y-m-d', strtotime('+7 days')) }}" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Duration (Days)</label>
                    <input type="number" name="days_count" value="1" min="1" max="30" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pickup Address <span class="text-rose-500">*</span></label>
                    <input type="text" name="pickup_location" required placeholder="Masaki / Salon / Home" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Drop-off / Venue</label>
                    <input type="text" name="dropoff_location" value="{{ $event?->venue ?? '' }}" placeholder="Venue name" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500">
                </div>
            </div>

            <!-- Add Coasters Checkbox -->
            <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 space-y-2">
                <label class="flex items-center space-x-2 text-xs font-bold text-amber-900 cursor-pointer">
                    <input type="checkbox" name="need_guest_coasters" value="1" class="rounded text-amber-600 focus:ring-amber-500">
                    <span>Also include 30-Seater Guest Coaster (+TZS 400,000/day)</span>
                </label>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Special Notes / Schedule</label>
                <textarea name="special_requests" rows="2" placeholder="e.g. Need driver at 7:00 AM for church departure" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500"></textarea>
            </div>

            <button type="submit" class="w-full py-3 bg-primary-600 hover:bg-primary-500 text-white font-bold text-xs rounded-xl shadow-md transition-colors">
                Confirm Booking Request
            </button>
        </form>

    </div>
</div>

<script>
    function openHostBookingModal(car) {
        document.getElementById('modal-vehicle-id').value = car.id;
        document.getElementById('modal-car-name').textContent = car.name;
        document.getElementById('modal-car-price').textContent = car.formatted_price + ' / day (Chauffeur Included)';
        const modal = document.getElementById('host-booking-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeHostBookingModal() {
        const modal = document.getElementById('host-booking-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection
