@extends('layouts.landing')

@section('title', $vehicle->name . ' - Wedding & Event Car Hire - Tukio 360')
@section('description', 'Hire ' . $vehicle->name . ' for your wedding or event in Tanzania. Includes professional chauffeur, floral ribbon decoration, and luxury comfort.')
@section('nav_dark', true)

@section('content')
<div class="min-h-screen bg-ambient-mesh-dark text-white relative overflow-hidden pt-8 pb-20">
    <!-- Ambient Floating Orbs for Glass Transparency -->
    <div class="absolute top-20 left-1/4 -translate-x-1/2 w-[550px] h-[550px] bg-indigo-600/20 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/3 right-10 w-[500px] h-[500px] bg-pink-600/15 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 left-10 w-[500px] h-[500px] bg-purple-600/15 rounded-full blur-[130px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 relative z-10">
        
        <!-- Breadcrumbs (Translucent Glass Style) -->
        <nav class="flex items-center space-x-2 text-xs font-semibold text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-pink-400 transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('car-hire.index') }}" class="hover:text-pink-400 transition-colors">Car Hire</a>
            <span>/</span>
            <a href="{{ route('car-hire.index', ['category' => $vehicle->category]) }}" class="hover:text-pink-400 transition-colors">{{ $vehicle->category_label }}</a>
            <span>/</span>
            <span class="text-white font-bold truncate">{{ $vehicle->name }}</span>
        </nav>

        @php
            $badge = $vehicle->category_badge;
        @endphp

        <!-- Main Product Layout Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            
            <!-- Left Column: Vehicle Visuals & Specifications (7 Cols) -->
            <div class="lg:col-span-7 space-y-8">
                
                <!-- Main Image Stage (Transparent Glass Frame) -->
                <div class="glass-card-transparent rounded-3xl p-2 border border-white/20 shadow-2xl relative group">
                    <div class="relative rounded-2xl overflow-hidden bg-slate-950">
                        <img id="main-car-image" src="{{ $vehicle->image_url_formatted }}" alt="{{ $vehicle->name }}" class="w-full h-80 sm:h-[420px] object-cover transition-all duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent pointer-events-none"></div>

                        <!-- Category Badge -->
                        <div class="absolute top-5 left-5">
                            <span class="glass-tag-transparent px-3 py-1.5 rounded-full text-xs font-semibold shadow-lg">
                                {{ $badge['icon'] }} {{ $badge['label'] }}
                            </span>
                        </div>

                        <!-- Availability Pill -->
                        <div class="absolute top-5 right-5">
                            <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/90 text-white shadow-lg backdrop-blur-md">
                                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                <span>Available For Booking</span>
                            </span>
                        </div>

                        <!-- Price overlay on image bottom -->
                        <div class="absolute bottom-5 left-5 right-5 flex items-end justify-between text-white">
                            <div>
                                <span class="text-xs text-slate-300 font-semibold uppercase tracking-wider block">Standard Day Rate</span>
                                <span class="text-3xl sm:text-4xl font-black font-heading text-white">{{ $vehicle->formatted_price }}</span>
                                <span class="text-xs text-slate-300">/ day (8-12 hrs)</span>
                            </div>
                            <div>
                                <span class="inline-block px-3 py-1 glass-tag-transparent rounded-xl text-xs font-semibold text-white">
                                    👥 {{ $vehicle->seating_capacity }} Passengers
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery Thumbnails (Transparent Frames) -->
                @if(!empty($vehicle->gallery) && count($vehicle->gallery) > 1)
                    <div class="flex items-center space-x-3 overflow-x-auto pb-2 scrollbar-none">
                        @foreach($vehicle->gallery as $index => $img)
                            <button type="button" onclick="document.getElementById('main-car-image').src = '{{ $img }}'" class="relative flex-shrink-0 w-24 h-16 rounded-2xl overflow-hidden glass-card-transparent p-1 border border-white/20 hover:border-pink-500 focus:border-pink-500 focus:outline-none transition-all">
                                <img src="{{ $img }}" alt="" class="w-full h-full object-cover rounded-xl">
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Overview & Description (Transparent Glass Card) -->
                <div class="glass-card-transparent rounded-3xl p-6 sm:p-8 space-y-5 border border-white/15">
                    <h2 class="text-2xl font-bold font-heading text-white">About This Vehicle</h2>
                    <p class="text-slate-300 text-sm leading-relaxed whitespace-pre-line">
                        {{ $vehicle->description }}
                    </p>

                    <!-- Key Service Inclusions -->
                    <div class="pt-4 border-t border-white/10">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Complimentary Inclusions</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <div class="flex items-center space-x-2.5 p-2.5 rounded-xl glass-spec-box">
                                <span class="w-6 h-6 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold">✓</span>
                                <span class="font-semibold text-slate-200">Professional Chauffeur in Uniform</span>
                            </div>
                            <div class="flex items-center space-x-2.5 p-2.5 rounded-xl glass-spec-box">
                                <span class="w-6 h-6 rounded-lg bg-pink-500/20 text-pink-400 flex items-center justify-center font-bold">✓</span>
                                <span class="font-semibold text-slate-200">Bridal White Satin Ribbons &amp; Bows</span>
                            </div>
                            <div class="flex items-center space-x-2.5 p-2.5 rounded-xl glass-spec-box">
                                <span class="w-6 h-6 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold">✓</span>
                                <span class="font-semibold text-slate-200">Fuel Included for City Limits</span>
                            </div>
                            <div class="flex items-center space-x-2.5 p-2.5 rounded-xl glass-spec-box">
                                <span class="w-6 h-6 rounded-lg bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold">✓</span>
                                <span class="font-semibold text-slate-200">Vehicle Washed &amp; Sanitized</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technical Specs & Amenities (Transparent Glass Card) -->
                <div class="glass-card-transparent rounded-3xl p-6 sm:p-8 space-y-6 border border-white/15">
                    <h3 class="text-xl font-bold font-heading text-white">Vehicle Specifications</h3>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs">
                        <div class="glass-spec-box p-3.5 rounded-2xl">
                            <span class="text-slate-400 block text-[11px]">Brand &amp; Make</span>
                            <span class="font-bold text-white text-sm mt-0.5 block">{{ $vehicle->brand ?? 'Luxury' }}</span>
                        </div>
                        <div class="glass-spec-box p-3.5 rounded-2xl">
                            <span class="text-slate-400 block text-[11px]">Model Year</span>
                            <span class="font-bold text-white text-sm mt-0.5 block">{{ $vehicle->model_year ?? '2023' }}</span>
                        </div>
                        <div class="glass-spec-box p-3.5 rounded-2xl">
                            <span class="text-slate-400 block text-[11px]">Seating Capacity</span>
                            <span class="font-bold text-white text-sm mt-0.5 block">👥 {{ $vehicle->seating_capacity }} Passengers</span>
                        </div>
                        <div class="glass-spec-box p-3.5 rounded-2xl">
                            <span class="text-slate-400 block text-[11px]">Transmission</span>
                            <span class="font-bold text-white text-sm mt-0.5 block">{{ $vehicle->transmission ?? 'Automatic' }}</span>
                        </div>
                        <div class="glass-spec-box p-3.5 rounded-2xl">
                            <span class="text-slate-400 block text-[11px]">Fuel Type</span>
                            <span class="font-bold text-white text-sm mt-0.5 block">{{ $vehicle->fuel_type ?? 'Petrol' }}</span>
                        </div>
                        <div class="glass-spec-box p-3.5 rounded-2xl">
                            <span class="text-slate-400 block text-[11px]">Color</span>
                            <span class="font-bold text-white text-sm mt-0.5 block">{{ $vehicle->color ?? 'White' }}</span>
                        </div>
                    </div>

                    <!-- Luxury Features List -->
                    @if(!empty($vehicle->features))
                        <div class="pt-4 border-t border-white/10 space-y-3">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Highlighted Luxury Features</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-200">
                                @foreach($vehicle->features as $feat)
                                    <div class="flex items-center space-x-2">
                                        <span class="text-pink-400 font-bold">✨</span>
                                        <span>{{ $feat }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Right Column: Interactive Booking Card & Quotation (Transparent Glass Card) -->
            <div class="lg:col-span-5 sticky top-24 space-y-6">
                
                <div class="glass-card-transparent rounded-3xl p-6 sm:p-8 shadow-2xl border border-white/20 space-y-6 relative overflow-hidden backdrop-blur-2xl">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-pink-500/20 rounded-full blur-2xl pointer-events-none"></div>

                    <!-- Header -->
                    <div class="border-b border-white/10 pb-4">
                        <span class="glass-tag-transparent px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-pink-300">Book Online or Reserve via WhatsApp</span>
                        <h3 class="text-2xl font-black font-heading text-white mt-2">{{ $vehicle->name }}</h3>
                        <div class="flex items-center justify-between mt-3">
                            <div>
                                <span class="text-2xl font-extrabold text-pink-400 font-heading" id="dynamic-display-price">{{ $vehicle->formatted_price }}</span>
                                <span class="text-xs text-slate-300" id="dynamic-display-days">/ 1 day</span>
                            </div>
                            <span class="text-xs font-semibold text-emerald-400 bg-emerald-500/20 px-2.5 py-1 rounded-full border border-emerald-500/30">
                                Best Price Guarantee
                            </span>
                        </div>
                    </div>

                    <!-- Booking Form -->
                    <form action="{{ route('car-hire.book', $vehicle->slug) }}" method="POST" id="booking-form" class="space-y-4">
                        @csrf

                        <!-- Customer Name -->
                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-1">Your Full Name <span class="text-pink-400">*</span></label>
                            <input type="text" name="customer_name" required value="{{ auth('host')->check() ? auth('host')->user()->full_name : old('customer_name') }}" placeholder="e.g. David Mwangi / Sarah John" class="w-full px-3.5 py-2.5 text-sm bg-white/10 text-white placeholder-slate-400 border border-white/15 rounded-xl focus:bg-white/15 focus:border-pink-400 focus:ring-2 focus:ring-pink-400 focus:outline-none transition-all">
                        </div>

                        <!-- Phone & Email Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-300 mb-1">WhatsApp / Phone <span class="text-pink-400">*</span></label>
                                <input type="tel" name="customer_phone" required value="{{ auth('host')->check() ? auth('host')->user()->phone : old('customer_phone') }}" placeholder="+255 712 345 678" class="w-full px-3.5 py-2.5 text-sm bg-white/10 text-white placeholder-slate-400 border border-white/15 rounded-xl focus:bg-white/15 focus:border-pink-400 focus:ring-2 focus:ring-pink-400 focus:outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-300 mb-1">Email Address</label>
                                <input type="email" name="customer_email" value="{{ auth('host')->check() ? auth('host')->user()->email : old('customer_email') }}" placeholder="name@email.com" class="w-full px-3.5 py-2.5 text-sm bg-white/10 text-white placeholder-slate-400 border border-white/15 rounded-xl focus:bg-white/15 focus:border-pink-400 focus:ring-2 focus:ring-pink-400 focus:outline-none transition-all">
                            </div>
                        </div>

                        <!-- Event Type & Date Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-300 mb-1">Event Type <span class="text-pink-400">*</span></label>
                                <select name="event_type" required class="w-full px-3.5 py-2.5 text-sm bg-slate-900 text-white border border-white/20 rounded-xl focus:ring-2 focus:ring-pink-400 focus:outline-none transition-all">
                                    <option value="Wedding">💒 Wedding Reception</option>
                                    <option value="Send Off">✈️ Send-Off Party</option>
                                    <option value="Kitchen Party">🍽️ Kitchen Party</option>
                                    <option value="Corporate">💼 Corporate Gala / VIP</option>
                                    <option value="Airport Transfer">✈️ VIP Airport Escort</option>
                                    <option value="Birthday">🎂 Birthday Celebration</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-300 mb-1">Event Date <span class="text-pink-400">*</span></label>
                                <input type="date" name="start_date" id="event-start-date" required min="{{ date('Y-m-d') }}" value="{{ old('start_date', date('Y-m-d', strtotime('+7 days'))) }}" class="w-full px-3.5 py-2.5 text-sm bg-white/10 text-white placeholder-slate-400 border border-white/15 rounded-xl focus:bg-white/15 focus:border-pink-400 focus:ring-2 focus:ring-pink-400 focus:outline-none transition-all">
                            </div>
                        </div>

                        <!-- Duration (Days) Counter -->
                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-1">Rental Duration (Days)</label>
                            <div class="flex items-center space-x-3">
                                <button type="button" onclick="adjustDays(-1)" class="w-10 h-10 rounded-xl glass-tag-transparent hover:bg-white/20 text-white font-bold text-lg flex items-center justify-center transition-colors">-</button>
                                <input type="number" name="days_count" id="days-count-input" value="1" min="1" max="30" readonly class="w-20 text-center font-black text-white text-lg bg-white/10 border border-white/15 rounded-xl py-2">
                                <button type="button" onclick="adjustDays(1)" class="w-10 h-10 rounded-xl glass-tag-transparent hover:bg-white/20 text-white font-bold text-lg flex items-center justify-center transition-colors">+</button>
                                <span class="text-xs text-slate-300">Day(s)</span>
                            </div>
                        </div>

                        <!-- Pickup Location & Time -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-300 mb-1">Pickup Location <span class="text-pink-400">*</span></label>
                                <input type="text" name="pickup_location" required placeholder="e.g. Masaki / Mikocheni / Hotel" class="w-full px-3.5 py-2.5 text-sm bg-white/10 text-white placeholder-slate-400 border border-white/15 rounded-xl focus:bg-white/15 focus:border-pink-400 focus:ring-2 focus:ring-pink-400 focus:outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-300 mb-1">Pickup Time</label>
                                <input type="time" name="pickup_time" value="08:00" class="w-full px-3.5 py-2.5 text-sm bg-white/10 text-white border border-white/15 rounded-xl focus:bg-white/15 focus:border-pink-400 focus:ring-2 focus:ring-pink-400 focus:outline-none transition-all">
                            </div>
                        </div>

                        <!-- Add-On Option: Guest Coasters / Bus -->
                        <div class="p-3.5 rounded-2xl bg-amber-500/10 border border-amber-400/30 space-y-2 backdrop-blur-md">
                            <label class="flex items-start space-x-2.5 cursor-pointer">
                                <input type="checkbox" name="need_guest_coasters" id="need-coasters-toggle" value="1" onchange="toggleCoasters(this.checked)" class="mt-0.5 rounded text-amber-500 focus:ring-amber-400 w-4 h-4 bg-slate-900 border-white/20">
                                <div class="text-xs">
                                    <span class="font-bold text-amber-300">Add Guest Coaster (30-Seater) to convoy</span>
                                    <p class="text-amber-200/80 text-[11px] mt-0.5">Transport your entourage &amp; guests in matching fleet (+TZS 400,000/coaster/day)</p>
                                </div>
                            </label>
                            
                            <div id="coasters-count-box" class="hidden pl-6 pt-1 flex items-center space-x-2">
                                <span class="text-xs text-amber-200 font-semibold">Number of Coasters:</span>
                                <select name="guest_coasters_count" id="guest-coasters-count" onchange="calculateQuotation()" class="text-xs px-2.5 py-1 rounded-lg border border-amber-400/40 bg-slate-900 text-white">
                                    <option value="1">1 Coaster (30 Seats)</option>
                                    <option value="2">2 Coasters (60 Seats)</option>
                                    <option value="3">3 Coasters (90 Seats)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Special Requests -->
                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-1">Special Requests / Route Schedule</label>
                            <textarea name="special_requests" rows="2" placeholder="e.g. Church at St. Peter's Oysterbay -> Photo shoot at Coco Beach -> Serena Hotel reception" class="w-full px-3.5 py-2 text-xs bg-white/10 text-white placeholder-slate-400 border border-white/15 rounded-xl focus:bg-white/15 focus:border-pink-400 focus:ring-2 focus:ring-pink-400 focus:outline-none transition-all"></textarea>
                        </div>

                        <!-- Total Quote Breakdown Summary Box (Translucent Glass) -->
                        <div class="glass-spec-box p-4 rounded-2xl space-y-2 border border-white/15">
                            <div class="flex items-center justify-between text-xs text-slate-300">
                                <span>{{ $vehicle->name }} (<span id="calc-days-label">1</span> day):</span>
                                <span class="font-bold text-white" id="calc-base-total">{{ $vehicle->formatted_price }}</span>
                            </div>
                            <div id="calc-coaster-row" class="hidden flex items-center justify-between text-xs text-amber-300">
                                <span>Guest Coaster(s):</span>
                                <span class="font-bold text-amber-300" id="calc-coaster-total">TZS 0</span>
                            </div>
                            <div class="pt-2 border-t border-white/10 flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-300">Estimated Total:</span>
                                <span class="text-xl font-black font-heading text-pink-400" id="calc-grand-total">{{ $vehicle->formatted_price }}</span>
                            </div>
                        </div>

                        <!-- Primary Submit Button -->
                        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-pink-600 via-purple-600 to-indigo-600 hover:from-pink-500 hover:to-indigo-500 text-white font-black text-sm rounded-2xl shadow-xl shadow-pink-500/25 transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                            <span>Confirm &amp; Reserve This Vehicle</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>

                        <!-- WhatsApp Instant Chat Button -->
                        @php
                            $instantWaText = urlencode("Habari Tukio 360! 🚗 Ninapenda kuweka nafasi ya gari: *{$vehicle->name}* kwa ajili ya tukio langu. Tafadhali naomba maelezo ya malipo na upatikanaji.");
                        @endphp
                        <a href="https://wa.me/{{ $brandSettings['whatsapp_clean'] ?? '255662199071' }}?text={{ $instantWaText }}" target="_blank" class="w-full py-3 bg-emerald-600/90 hover:bg-emerald-500 text-white font-bold text-xs rounded-2xl shadow-md transition-colors flex items-center justify-center space-x-2 backdrop-blur-md">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.966.527 1.76.812 2.795.812 3.179 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.999-5.767-5.999zm3.385 8.163c-.144.405-.837.774-1.17.822-.312.043-.699.077-2.029-.472-1.696-.703-2.779-2.457-2.863-2.569-.084-.112-.686-.914-.686-1.743 0-.829.434-1.237.588-1.408.155-.171.339-.214.452-.214.113 0 .226.002.324.007.104.005.243-.039.38.291.144.344.492 1.2.535 1.288.043.088.071.191.014.305-.056.113-.085.184-.17.283-.085.1-.179.223-.255.3-.099.099-.202.207-.087.404.115.197.51 1.042 1.093 1.562.752.669 1.386.877 1.583.974.197.098.312.085.428-.049.115-.133.493-.574.625-.771.132-.197.265-.164.444-.099.18.066 1.139.537 1.336.635.197.099.329.148.378.23.049.083.049.48-.095.885z"/></svg>
                            <span>Chat Directly with Fleet Manager</span>
                        </a>

                    </form>

                    <!-- Trust indicators -->
                    <div class="pt-3 border-t border-white/10 flex items-center justify-around text-[11px] text-slate-400 text-center">
                        <div>🔒 Secure Booking</div>
                        <div>⚡ Instant Confirmation</div>
                        <div>📞 24/7 Driver Support</div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Related Vehicles Section (Transparent Cards) -->
        @if($relatedVehicles->count() > 0)
            <div class="pt-12 border-t border-white/10 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="glass-tag-transparent px-3 py-1 rounded-full text-xs font-semibold text-indigo-300">Alternative Options</span>
                        <h3 class="text-2xl font-bold font-heading text-white mt-1">Other {{ $vehicle->category_label }} Vehicles</h3>
                    </div>
                    <a href="{{ route('car-hire.index', ['category' => $vehicle->category]) }}" class="text-xs font-bold text-pink-400 hover:text-pink-300">
                        View Category &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedVehicles as $rel)
                        <div class="glass-card-transparent rounded-3xl overflow-hidden group border border-white/15 hover:border-pink-500/60 transition-all duration-300">
                            <div class="relative h-48 bg-slate-950">
                                <img src="{{ $rel->image_url_formatted }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/20"></div>
                                <div class="absolute bottom-3 left-3 right-3 text-white flex items-center justify-between">
                                    <span class="text-sm font-black font-heading">{{ $rel->formatted_price }}</span>
                                    <span class="text-[11px] glass-tag-transparent px-2 py-0.5 rounded-md">👥 {{ $rel->seating_capacity }} seats</span>
                                </div>
                            </div>
                            <div class="p-5 space-y-3">
                                <h4 class="font-bold font-heading text-white group-hover:text-pink-400 transition-colors truncate">
                                    <a href="{{ route('car-hire.show', $rel->slug) }}">{{ $rel->name }}</a>
                                </h4>
                                <a href="{{ route('car-hire.show', $rel->slug) }}" class="btn-primary w-full text-center text-xs py-2">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>

<!-- Dynamic Price Calculation Script -->
<script>
    const pricePerDay = {{ (float) $vehicle->price_per_day }};
    const coasterRate = 400000;

    function adjustDays(delta) {
        const input = document.getElementById('days-count-input');
        let current = parseInt(input.value) || 1;
        current += delta;
        if (current < 1) current = 1;
        if (current > 30) current = 30;
        input.value = current;
        calculateQuotation();
    }

    function toggleCoasters(checked) {
        const box = document.getElementById('coasters-count-box');
        const coasterRow = document.getElementById('calc-coaster-row');
        if (checked) {
            box.classList.remove('hidden');
            coasterRow.classList.remove('hidden');
        } else {
            box.classList.add('hidden');
            coasterRow.classList.add('hidden');
        }
        calculateQuotation();
    }

    function calculateQuotation() {
        const days = parseInt(document.getElementById('days-count-input').value) || 1;
        const needCoasters = document.getElementById('need-coasters-toggle').checked;
        const coastersCount = needCoasters ? (parseInt(document.getElementById('guest-coasters-count').value) || 1) : 0;

        const baseTotal = pricePerDay * days;
        const coasterTotal = coastersCount * coasterRate * days;
        const grandTotal = baseTotal + coasterTotal;

        // Update displays
        document.getElementById('calc-days-label').textContent = days;
        document.getElementById('calc-base-total').textContent = 'TZS ' + baseTotal.toLocaleString();
        document.getElementById('calc-coaster-total').textContent = 'TZS ' + coasterTotal.toLocaleString();
        document.getElementById('calc-grand-total').textContent = 'TZS ' + grandTotal.toLocaleString();
        document.getElementById('dynamic-display-price').textContent = 'TZS ' + grandTotal.toLocaleString();
        document.getElementById('dynamic-display-days').textContent = '/ ' + days + ' day' + (days > 1 ? 's' : '');
    }
</script>
@endsection
