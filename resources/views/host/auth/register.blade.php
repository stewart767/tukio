@extends('layouts.landing')

@php
    $brandSettings = $systemSettings ?? [];
    $brandName = $brandSettings['name'] ?? 'Tukio 360';
@endphp

@section('title', 'Create Host Account - ' . $brandName)
@section('description', 'Register as an event host on Tukio 360 to create digital invitations, automate WhatsApp RSVPs, and manage guest QR gate check-ins.')
@section('nav_dark', true)

@section('content')
<div class="min-h-screen bg-ambient-mesh-dark text-white relative overflow-hidden pt-8 pb-20 font-sans selection:bg-pink-500 selection:text-white">
    <!-- Ambient Multidimensional Radiant Light Glows -->
    <div class="absolute top-10 left-1/4 -translate-x-1/2 w-[600px] h-[600px] bg-indigo-600/20 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/3 right-10 w-[550px] h-[550px] bg-pink-600/15 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 left-10 w-[500px] h-[500px] bg-purple-600/15 rounded-full blur-[130px] pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-start pt-4 sm:pt-6">
            
            <!-- Left Column (5 Cols) - Visual Brand & Host Privileges -->
            <div class="lg:col-span-5 space-y-6 text-center lg:text-left">
                
                <!-- Luminous Badge -->
                <div class="inline-flex items-center space-x-2.5 px-4 py-2 rounded-full border border-pink-500/40 text-pink-300 text-xs font-semibold tracking-wide shadow-xl shadow-pink-500/20" style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(16px);">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-pink-500"></span>
                    </span>
                    <span>✨ Official Event Host Portal</span>
                </div>

                <!-- Page Headline -->
                <div class="space-y-3">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black font-heading text-white tracking-tight leading-tight">
                        Create Your <br class="hidden sm:inline">
                        <span class="bg-gradient-to-r from-pink-400 via-purple-300 to-indigo-300 bg-clip-text text-transparent">
                            Host Account
                        </span>
                    </h1>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                        Setup your wedding, birthday, kitchen party, or corporate gala in minutes with automated WhatsApp cards &amp; QR gate passes.
                    </p>
                </div>

                <!-- 4 Host Benefits Showcase Cards -->
                <div class="space-y-3 pt-2 text-left">
                    <div class="p-4 rounded-2xl glass-card-transparent border border-white/15 flex items-start space-x-3.5 hover:border-pink-500/40 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-pink-500/20 text-pink-400 flex items-center justify-center shrink-0 text-lg border border-pink-500/30">
                            💬
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider">Automated WhatsApp Cards</h4>
                            <p class="text-xs text-slate-300 mt-0.5 leading-relaxed">Personalized invitation messages and instant interactive RSVP buttons delivered straight to guests.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl glass-card-transparent border border-white/15 flex items-start space-x-3.5 hover:border-indigo-500/40 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0 text-lg border border-indigo-500/30">
                            🎫
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider">Tamper-Proof QR Gate Passes</h4>
                            <p class="text-xs text-slate-300 mt-0.5 leading-relaxed">Unique gate passes scanned in under 1 second by your event staff to prevent gatecrashers.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl glass-card-transparent border border-white/15 flex items-start space-x-3.5 hover:border-purple-500/40 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center shrink-0 text-lg border border-purple-500/30">
                            📊
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider">Real-Time Attendance Portal</h4>
                            <p class="text-xs text-slate-300 mt-0.5 leading-relaxed">Live dashboards tracking confirmed RSVPs, meal choices, and check-in times on event day.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl glass-card-transparent border border-white/15 flex items-start space-x-3.5 hover:border-amber-500/40 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0 text-lg border border-amber-500/30">
                            🚗
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider">VIP Fleet &amp; Convoy Discounts</h4>
                            <p class="text-xs text-slate-300 mt-0.5 leading-relaxed">Special package discounts on luxury Maybach bridal cars and 30-seater guest coasters.</p>
                        </div>
                    </div>
                </div>

                <!-- Already Registered Prompt -->
                <div class="p-4 rounded-2xl bg-white/[0.04] border border-white/10 flex items-center justify-between text-xs">
                    <span class="text-slate-300">Already registered as a host?</span>
                    <a href="{{ route('host.login') }}" class="font-bold text-pink-400 hover:text-pink-300 flex items-center space-x-1 transition-colors">
                        <span>Sign in here</span>
                        <span>&rarr;</span>
                    </a>
                </div>

            </div>

            <!-- Right Column (7 Cols) - Registration Card -->
            <div class="lg:col-span-7">
                <div class="glass-card-transparent rounded-3xl p-6 sm:p-8 md:p-10 border border-white/20 shadow-2xl space-y-6 backdrop-blur-2xl">
                    
                    <div class="border-b border-white/10 pb-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl sm:text-2xl font-black font-heading text-white">Event Host Details</h2>
                                <p class="text-xs text-slate-300 mt-0.5">Please provide your contact information to setup your event workspace.</p>
                            </div>
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-500 flex items-center justify-center text-white text-xl shadow-lg shadow-indigo-500/30 shrink-0">
                                ✨
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="p-4 rounded-2xl bg-rose-950/60 border border-rose-500/50 text-rose-200 text-xs space-y-1.5 shadow-lg">
                            <div class="font-bold flex items-center space-x-2 text-rose-300">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span>Please resolve the following:</span>
                            </div>
                            <ul class="list-disc list-inside space-y-0.5 pl-2 text-rose-200/90">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('host.register') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <!-- Full Name -->
                            <div class="space-y-1.5 md:col-span-2">
                                <label for="full_name" class="block text-xs font-bold text-slate-200">
                                    Full Name <span class="text-pink-400">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7 7z"/></svg>
                                    </div>
                                    <input id="full_name" name="full_name" type="text" required value="{{ old('full_name') }}"
                                           class="w-full pl-10 pr-4 py-3 bg-white/10 hover:bg-white/15 focus:bg-white/20 text-white placeholder-slate-400 text-xs sm:text-sm rounded-xl border border-white/20 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500/40 transition-all" 
                                           placeholder="e.g. David &amp; Sarah Minja">
                                </div>
                                @error('full_name')
                                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-1.5">
                                <label for="email" class="block text-xs font-bold text-slate-200">
                                    Email Address <span class="text-pink-400">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <input id="email" name="email" type="email" required value="{{ old('email') }}"
                                           class="w-full pl-10 pr-4 py-3 bg-white/10 hover:bg-white/15 focus:bg-white/20 text-white placeholder-slate-400 text-xs sm:text-sm rounded-xl border border-white/20 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500/40 transition-all" 
                                           placeholder="you@example.com">
                                </div>
                                @error('email')
                                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone / WhatsApp -->
                            <div class="space-y-1.5">
                                <label for="phone" class="block text-xs font-bold text-slate-200">
                                    Phone / WhatsApp <span class="text-pink-400">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.966.527 1.76.812 2.795.812 3.179 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.999-5.767-5.999zm3.385 8.163c-.144.405-.837.774-1.17.822-.312.043-.699.077-2.029-.472-1.696-.703-2.779-2.457-2.863-2.569-.084-.112-.686-.914-.686-1.743 0-.829.434-1.237.588-1.408.155-.171.339-.214.452-.214.113 0 .226.002.324.007.104.005.243-.039.38.291.144.344.492 1.2.535 1.288.043.088.071.191.014.305-.056.113-.085.184-.17.283-.085.1-.179.223-.255.3-.099.099-.202.207-.087.404.115.197.51 1.042 1.093 1.562.752.669 1.386.877 1.583.974.197.098.312.085.428-.049.115-.133.493-.574.625-.771.132-.197.265-.164.444-.099.18.066 1.139.537 1.336.635.197.099.329.148.378.23.049.083.049.48-.095.885z"/></svg>
                                    </div>
                                    <input id="phone" name="phone" type="tel" required value="{{ old('phone') }}"
                                           class="w-full pl-10 pr-4 py-3 bg-white/10 hover:bg-white/15 focus:bg-white/20 text-white placeholder-slate-400 text-xs sm:text-sm rounded-xl border border-white/20 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500/40 transition-all" 
                                           placeholder="0712345678 or +255...">
                                </div>
                                @error('phone')
                                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Event Type -->
                            <div class="space-y-1.5">
                                <label for="event_type" class="block text-xs font-bold text-slate-200">
                                    Event Type <span class="text-pink-400">*</span>
                                </label>
                                <div class="relative">
                                    <select id="event_type" name="event_type" required 
                                            class="w-full px-4 py-3 bg-slate-900/90 text-white text-xs sm:text-sm rounded-xl border border-white/20 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500/40 transition-all cursor-pointer">
                                        <option value="" class="bg-slate-900 text-slate-400">Select event occasion</option>
                                        @if(isset($eventTypes))
                                            @foreach($eventTypes as $eventType)
                                                <option value="{{ $eventType->event_type }}" {{ old('event_type') === $eventType->event_type ? 'selected' : '' }} class="bg-slate-900 text-white">
                                                    @if(str_contains(strtolower($eventType->event_type), 'wed'))
                                                        💍 Wedding
                                                    @elseif(str_contains(strtolower($eventType->event_type), 'birth'))
                                                        🎂 Birthday
                                                    @elseif(str_contains(strtolower($eventType->event_type), 'corp'))
                                                        💼 Corporate Gala
                                                    @elseif(str_contains(strtolower($eventType->event_type), 'send'))
                                                        👗 Send-Off Party
                                                    @elseif(str_contains(strtolower($eventType->event_type), 'kitch'))
                                                        🌸 Kitchen Party
                                                    @else
                                                        ✨ {{ ucfirst(str_replace('_', ' ', $eventType->event_type)) }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="wedding" class="bg-slate-900 text-white">💍 Wedding Celebration</option>
                                            <option value="birthday" class="bg-slate-900 text-white">🎂 Birthday Party</option>
                                            <option value="corporate" class="bg-slate-900 text-white">💼 Corporate Event</option>
                                            <option value="send_off" class="bg-slate-900 text-white">👗 Send-Off Party</option>
                                            <option value="kitchen_party" class="bg-slate-900 text-white">🌸 Kitchen Party</option>
                                        @endif
                                    </select>
                                </div>
                                @error('event_type')
                                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Number of Guests -->
                            <div class="space-y-1.5">
                                <label for="number_of_guests" class="block text-xs font-bold text-slate-200">
                                    Estimated Guests <span class="text-pink-400">*</span>
                                </label>
                                <div class="relative">
                                    <input id="number_of_guests" name="number_of_guests" type="number" min="1" max="1000" required
                                           value="{{ old('number_of_guests', 100) }}"
                                           class="w-full px-4 py-3 bg-white/10 hover:bg-white/15 focus:bg-white/20 text-white placeholder-slate-400 text-xs sm:text-sm rounded-xl border border-white/20 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500/40 transition-all font-bold" 
                                           placeholder="100">
                                </div>
                                @error('number_of_guests')
                                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quick Guest Count Buttons -->
                            <div class="md:col-span-2 flex items-center gap-2 overflow-x-auto pb-1 text-xs">
                                <span class="text-[11px] text-slate-400 shrink-0">Quick presets:</span>
                                <button type="button" onclick="setGuestCount(50)" class="guest-preset-btn px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/15 text-slate-300 border border-white/10 font-semibold transition-all">50</button>
                                <button type="button" onclick="setGuestCount(100)" class="guest-preset-btn px-2.5 py-1 rounded-lg bg-pink-500/20 text-pink-300 border border-pink-500/30 font-semibold transition-all">100</button>
                                <button type="button" onclick="setGuestCount(200)" class="guest-preset-btn px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/15 text-slate-300 border border-white/10 font-semibold transition-all">200</button>
                                <button type="button" onclick="setGuestCount(300)" class="guest-preset-btn px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/15 text-slate-300 border border-white/10 font-semibold transition-all">300</button>
                                <button type="button" onclick="setGuestCount(500)" class="guest-preset-btn px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/15 text-slate-300 border border-white/10 font-semibold transition-all">500</button>
                            </div>

                            <!-- Dynamic Live Total Calculation Capsule -->
                            <div id="total-amount-display" class="md:col-span-2 p-5 rounded-2xl bg-gradient-to-r from-indigo-950/80 via-purple-950/80 to-pink-950/80 border border-indigo-400/40 shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-pink-500 text-white flex items-center justify-center text-xl font-bold shadow-md shadow-indigo-500/30 shrink-0">
                                        💳
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase font-extrabold text-pink-300 tracking-wider">Total Event Package</p>
                                        <p class="text-[11px] text-slate-300 mt-0.5">TZS 2,000 / guest &bull; Includes WhatsApp + QR Pass + Scanner</p>
                                    </div>
                                </div>
                                <div class="text-left sm:text-right border-t sm:border-t-0 border-white/10 pt-2 sm:pt-0">
                                    <p class="text-2xl font-black text-amber-300 font-heading" id="total-amount">TZS 200,000</p>
                                    <span class="text-[10px] text-emerald-400 font-bold bg-emerald-500/20 px-2 py-0.5 rounded-full border border-emerald-500/30">
                                        ✓ Full Portal Features
                                    </span>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="space-y-1.5">
                                <label for="password" class="block text-xs font-bold text-slate-200">
                                    Password <span class="text-pink-400">*</span>
                                </label>
                                <div class="relative">
                                    <input id="password" name="password" type="password" required
                                           class="w-full pl-4 pr-10 py-3 bg-white/10 hover:bg-white/15 focus:bg-white/20 text-white placeholder-slate-400 text-xs sm:text-sm rounded-xl border border-white/20 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500/40 transition-all" 
                                           placeholder="••••••••">
                                    <button type="button" onclick="togglePasswordVisibility('password', 'pwd-icon-1')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-white transition-colors" tabindex="-1">
                                        <svg id="pwd-icon-1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="space-y-1.5">
                                <label for="password_confirmation" class="block text-xs font-bold text-slate-200">
                                    Confirm Password <span class="text-pink-400">*</span>
                                </label>
                                <div class="relative">
                                    <input id="password_confirmation" name="password_confirmation" type="password" required
                                           class="w-full pl-4 pr-10 py-3 bg-white/10 hover:bg-white/15 focus:bg-white/20 text-white placeholder-slate-400 text-xs sm:text-sm rounded-xl border border-white/20 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500/40 transition-all" 
                                           placeholder="••••••••">
                                    <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'pwd-icon-2')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-white transition-colors" tabindex="-1">
                                        <svg id="pwd-icon-2" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            </div>

                        </div>

                        <!-- Submit CTA Button -->
                        <div class="pt-3">
                            <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-pink-500 via-purple-600 to-indigo-600 hover:from-pink-400 hover:to-indigo-500 text-white font-black text-sm rounded-xl shadow-xl shadow-pink-500/30 hover:shadow-pink-500/50 transition-all duration-300 flex items-center justify-center space-x-2 transform hover:-translate-y-0.5 cursor-pointer">
                                <span>Complete Registration &amp; Setup Event</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>

                        <p class="text-center text-[11px] text-slate-400 pt-1">
                            By continuing, you agree to {{ $brandName }}'s 
                            <a href="{{ route('page.show', 'terms-of-use') }}" class="underline text-pink-300 hover:text-white">Terms of Service</a> &amp; 
                            <a href="{{ route('page.show', 'privacy-policy') }}" class="underline text-pink-300 hover:text-white">Privacy Policy</a>.
                        </p>
                    </form>

                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const guestsInput = document.getElementById('number_of_guests');
    const amountDisplay = document.getElementById('total-amount-display');
    const totalAmountElement = document.getElementById('total-amount');
    
    window.updateCalc = function() {
        const numberOfGuests = parseInt(guestsInput.value) || 0;
        const costPerGuest = 2000;
        const totalAmount = numberOfGuests * costPerGuest;
        
        if (numberOfGuests > 0) {
            totalAmountElement.textContent = 'TZS ' + totalAmount.toLocaleString('en-US');
            amountDisplay.style.display = 'flex';
        } else {
            totalAmountElement.textContent = 'TZS 0';
        }
    };
    
    window.setGuestCount = function(count) {
        if (guestsInput) {
            guestsInput.value = count;
            window.updateCalc();
            
            // Highlight active button
            document.querySelectorAll('.guest-preset-btn').forEach(btn => {
                if (btn.textContent.trim() === count.toString()) {
                    btn.classList.add('bg-pink-500/20', 'text-pink-300', 'border-pink-500/30');
                    btn.classList.remove('bg-white/5', 'text-slate-300', 'border-white/10');
                } else {
                    btn.classList.remove('bg-pink-500/20', 'text-pink-300', 'border-pink-500/30');
                    btn.classList.add('bg-white/5', 'text-slate-300', 'border-white/10');
                }
            });
        }
    };

    window.togglePasswordVisibility = function(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input) return;
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        }
    };
    
    if (guestsInput) {
        guestsInput.addEventListener('input', window.updateCalc);
        window.updateCalc();
    }
});
</script>
@endpush
