@extends('layouts.landing')

@section('title', $vehicle->name . ' - Wedding & Event Car Hire - Tukio 360')
@section('description', 'Hire ' . $vehicle->name . ' for your wedding or event in Tanzania. Includes professional chauffeur, floral ribbon decoration, and luxury comfort.')

@section('content')
<div class="min-h-screen bg-[#f3ffe3] text-slate-800 relative overflow-hidden pt-8 pb-20 font-sans selection:bg-slate-900 selection:text-white" style="background-color: #f3ffe3;">
    <!-- Soft Warm Ambient Highlights -->
    <div class="absolute top-10 left-1/4 -translate-x-1/2 w-[550px] h-[550px] bg-amber-200/30 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/3 right-10 w-[500px] h-[500px] bg-emerald-100/40 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 left-10 w-[500px] h-[500px] bg-lime-200/25 rounded-full blur-[130px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 relative z-10">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center space-x-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('car-hire.index') }}" class="hover:text-slate-900 transition-colors">Car Hire</a>
            <span>/</span>
            <a href="{{ route('car-hire.index', ['category' => $vehicle->category]) }}" class="hover:text-slate-900 transition-colors">{{ $vehicle->category_label }}</a>
            <span>/</span>
            <span class="text-slate-900 font-bold truncate">{{ $vehicle->name }}</span>
        </nav>

        @php
            $badge = $vehicle->category_badge;
        @endphp

        <!-- Main Product Layout Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            
            <!-- Left Column: Vehicle Visuals & Specifications (7 Cols) -->
            <div class="lg:col-span-7 space-y-8">
                
                <!-- Main Image Stage (Clean White Frame) -->
                <div class="bg-white rounded-3xl p-2 border border-slate-200/80 shadow-md relative group">
                    <div class="relative rounded-2xl overflow-hidden bg-slate-100">
                        <img id="main-car-image" src="{{ $vehicle->image_url_formatted }}" alt="{{ $vehicle->name }}" class="w-full h-80 sm:h-[420px] object-cover transition-all duration-500 group-hover:scale-105" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80';">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent pointer-events-none"></div>

                        <!-- Category Badge -->
                        <div class="absolute top-5 left-5">
                            <span class="backdrop-blur-md bg-white/95 px-3 py-1.5 rounded-full text-xs font-bold border border-slate-200/80 text-slate-800 shadow-md">
                                {{ $badge['label'] }}
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
                                    {{ $vehicle->seating_capacity }} Passengers
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery Thumbnails (Transparent Frames) -->
                @if(!empty($vehicle->gallery) && count($vehicle->gallery) > 1)
                    <div class="flex items-center space-x-3 overflow-x-auto pb-2 scrollbar-none">
                        @foreach($vehicle->gallery as $index => $img)
                            <button type="button" onclick="document.getElementById('main-car-image').src = '{{ $img }}'" class="relative flex-shrink-0 w-24 h-16 rounded-2xl overflow-hidden glass-card-transparent p-1 border border-white/20 hover:border-indigo-400 focus:border-indigo-400 focus:outline-none transition-all">
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
                                <span class="w-6 h-6 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold">✓</span>
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
                            <span class="font-bold text-white text-sm mt-0.5 block">{{ $vehicle->seating_capacity }} Passengers</span>
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
                                        <svg class="w-3.5 h-3.5 text-indigo-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
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
                
                <div class="bg-white/95 backdrop-blur-xl rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-200/80 space-y-6 relative overflow-hidden text-slate-800">
                    <!-- Header -->
                    <div class="border-b border-slate-100 pb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-slate-700 bg-slate-100 border border-slate-200">Book Online or Reserve via WhatsApp</span>
                        <h3 class="text-2xl font-black font-heading text-slate-900 mt-2">{{ $vehicle->name }}</h3>
                        <div class="flex items-center justify-between mt-3">
                            <div>
                                <span class="text-2xl font-black text-slate-900 font-heading" id="dynamic-display-price">{{ $vehicle->formatted_price }}</span>
                                <span class="text-xs text-slate-500" id="dynamic-display-days">/ 1 day</span>
                            </div>
                            <span class="text-xs font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                                Best Price Guarantee
                            </span>
                        </div>
                    </div>

                    <!-- Booking Form -->
                    <form action="{{ route('car-hire.book', $vehicle->slug) }}" method="POST" id="booking-form" class="space-y-4">
                        @csrf

                        <!-- Customer Name -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Your Full Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="customer_name" required value="{{ auth('host')->check() ? auth('host')->user()->full_name : old('customer_name') }}" placeholder="e.g. David Mwangi / Sarah John" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 text-slate-900 placeholder-slate-400 border border-slate-200/90 rounded-xl focus:bg-white focus:border-slate-900 focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        </div>

                        <!-- Phone & Email Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">WhatsApp / Phone <span class="text-rose-500">*</span></label>
                                <input type="tel" name="customer_phone" required value="{{ auth('host')->check() ? auth('host')->user()->phone : old('customer_phone') }}" placeholder="+255 712 345 678" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 text-slate-900 placeholder-slate-400 border border-slate-200/90 rounded-xl focus:bg-white focus:border-slate-900 focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                                <input type="email" name="customer_email" value="{{ auth('host')->check() ? auth('host')->user()->email : old('customer_email') }}" placeholder="name@email.com" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 text-slate-900 placeholder-slate-400 border border-slate-200/90 rounded-xl focus:bg-white focus:border-slate-900 focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                            </div>
                        </div>

                        <!-- Event Type & Date Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Event Type <span class="text-rose-500">*</span></label>
                                <select name="event_type" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 text-slate-900 border border-slate-200/90 rounded-xl focus:bg-white focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                                    <option value="Wedding">Wedding Reception</option>
                                    <option value="Send Off">Send-Off Party</option>
                                    <option value="Kitchen Party">Kitchen Party</option>
                                    <option value="Corporate">Corporate Gala / VIP</option>
                                    <option value="Airport Transfer">VIP Airport Escort</option>
                                    <option value="Birthday">Birthday Celebration</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Event Date <span class="text-rose-500">*</span></label>
                                <input type="date" name="start_date" id="event-start-date" required min="{{ date('Y-m-d') }}" value="{{ old('start_date', date('Y-m-d', strtotime('+7 days'))) }}" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 text-slate-900 placeholder-slate-400 border border-slate-200/90 rounded-xl focus:bg-white focus:border-slate-900 focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                            </div>
                        </div>

                        <!-- Duration (Days) Counter -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Rental Duration (Days)</label>
                            <div class="flex items-center space-x-3">
                                <button type="button" onclick="adjustDays(-1)" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200 font-bold text-lg flex items-center justify-center transition-colors">-</button>
                                <input type="number" name="days_count" id="days-count-input" value="1" min="1" max="30" readonly class="w-20 text-center font-black text-slate-900 text-lg bg-slate-50 border border-slate-200 rounded-xl py-2">
                                <button type="button" onclick="adjustDays(1)" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200 font-bold text-lg flex items-center justify-center transition-colors">+</button>
                                <span class="text-xs text-slate-600 font-semibold">Day(s)</span>
                            </div>
                        </div>

                        <!-- Pickup Location & Time -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Pickup Location <span class="text-rose-500">*</span></label>
                                <input type="text" name="pickup_location" required placeholder="e.g. Masaki / Mikocheni / Hotel" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 text-slate-900 placeholder-slate-400 border border-slate-200/90 rounded-xl focus:bg-white focus:border-slate-900 focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Pickup Time</label>
                                <input type="time" name="pickup_time" value="08:00" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 text-slate-900 border border-slate-200/90 rounded-xl focus:bg-white focus:border-slate-900 focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                            </div>
                        </div>

                        <!-- Add-On Option: Guest Coasters / Bus -->
                        <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 space-y-2">
                            <label class="flex items-start space-x-2.5 cursor-pointer">
                                <input type="checkbox" name="need_guest_coasters" id="need-coasters-toggle" value="1" onchange="toggleCoasters(this.checked)" class="mt-0.5 rounded text-amber-600 focus:ring-amber-500 w-4 h-4 bg-white border-slate-300">
                                <div class="text-xs">
                                    <span class="font-bold text-amber-900">Add Guest Coaster (30-Seater) to convoy</span>
                                    <p class="text-amber-800 text-[11px] mt-0.5">Transport your entourage &amp; guests in matching fleet (+TZS 400,000/coaster/day)</p>
                                </div>
                            </label>
                            
                            <div id="coasters-count-box" class="hidden pl-6 pt-1 flex items-center space-x-2">
                                <span class="text-xs text-amber-900 font-semibold">Number of Coasters:</span>
                                <select name="guest_coasters_count" id="guest-coasters-count" onchange="calculateQuotation()" class="text-xs px-2.5 py-1 rounded-lg border border-amber-300 bg-white text-slate-900">
                                    <option value="1">1 Coaster (30 Seats)</option>
                                    <option value="2">2 Coasters (60 Seats)</option>
                                    <option value="3">3 Coasters (90 Seats)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Special Requests -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Special Requests / Route Schedule</label>
                            <textarea name="special_requests" rows="2" placeholder="e.g. Church at St. Peter's Oysterbay -> Photo shoot at Coco Beach -> Serena Hotel reception" class="w-full px-3.5 py-2 text-xs bg-slate-50 text-slate-900 placeholder-slate-400 border border-slate-200/90 rounded-xl focus:bg-white focus:border-slate-900 focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all"></textarea>
                        </div>

                        <!-- Total Quote Breakdown Summary Box -->
                        <div class="bg-slate-50 p-4 rounded-2xl space-y-2 border border-slate-200/80">
                            <div class="flex items-center justify-between text-xs text-slate-600">
                                <span>{{ $vehicle->name }} (<span id="calc-days-label">1</span> day):</span>
                                <span class="font-bold text-slate-900" id="calc-base-total">{{ $vehicle->formatted_price }}</span>
                            </div>
                            <div id="calc-coaster-row" class="hidden flex items-center justify-between text-xs text-amber-800">
                                <span>Guest Coaster(s):</span>
                                <span class="font-bold text-amber-800" id="calc-coaster-total">TZS 0</span>
                            </div>
                            <div class="pt-2 border-t border-slate-200 flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Estimated Total:</span>
                                <span class="text-xl font-black font-heading text-slate-900" id="calc-grand-total">{{ $vehicle->formatted_price }}</span>
                            </div>
                        </div>

                        <!-- Primary Submit Button -->
                        <button type="submit" class="w-full py-3.5 bg-slate-900 hover:bg-black text-white font-black text-sm rounded-2xl shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                            <span>Confirm &amp; Reserve This Vehicle</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>

                        <!-- WhatsApp Instant Chat Button -->
                        @php
                            $instantWaText = urlencode("Habari Tukio 360! Ninapenda kuweka nafasi ya gari: *{$vehicle->name}* kwa ajili ya tukio langu. Tafadhali naomba maelezo ya malipo na upatikanaji.");
                        @endphp
                        <a href="https://wa.me/{{ $brandSettings['whatsapp_clean'] ?? '255662199071' }}?text={{ $instantWaText }}" target="_blank" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-2xl shadow-md transition-colors flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.966.527 1.76.812 2.795.812 3.179 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.999-5.767-5.999zm3.385 8.163c-.144.405-.837.774-1.17.822-.312.043-.699.077-2.029-.472-1.696-.703-2.779-2.457-2.863-2.569-.084-.112-.686-.914-.686-1.743 0-.829.434-1.237.588-1.408.155-.171.339-.214.452-.214.113 0 .226.002.324.007.104.005.243-.039.38.291.144.344.492 1.2.535 1.288.043.088.071.191.014.305-.056.113-.085.184-.17.283-.085.1-.179.223-.255.3-.099.099-.202.207-.087.404.115.197.51 1.042 1.093 1.562.752.669 1.386.877 1.583.974.197.098.312.085.428-.049.115-.133.493-.574.625-.771.132-.197.265-.164.444-.099.18.066 1.139.537 1.336.635.197.099.329.148.378.23.049.083.049.48-.095.885z"/></svg>
                            <span>Chat Directly with Fleet Manager</span>
                        </a>

                    </form>

                    <!-- Trust indicators -->
                    <div class="pt-3 border-t border-white/10 flex items-center justify-around text-[11px] text-slate-400 text-center">
                        <div class="flex items-center space-x-1">
                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>Secure Booking</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>Instant Confirmation</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>24/7 Driver Support</span>
                        </div>
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
                    <a href="{{ route('car-hire.index', ['category' => $vehicle->category]) }}" class="text-xs font-bold text-indigo-400 hover:text-indigo-300">
                        View Category &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedVehicles as $rel)
                        <div class="glass-card-transparent rounded-3xl overflow-hidden group border border-white/15 hover:border-indigo-500/60 transition-all duration-300">
                            <div class="relative h-48 bg-slate-950">
                                <img src="{{ $rel->image_url_formatted }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/20"></div>
                                <div class="absolute bottom-3 left-3 right-3 text-white flex items-center justify-between">
                                    <span class="text-sm font-black font-heading">{{ $rel->formatted_price }}</span>
                                    <span class="text-[11px] glass-tag-transparent px-2 py-0.5 rounded-md">{{ $rel->seating_capacity }} seats</span>
                                </div>
                            </div>
                            <div class="p-5 space-y-3">
                                <h4 class="font-bold font-heading text-white group-hover:text-indigo-300 transition-colors truncate">
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
