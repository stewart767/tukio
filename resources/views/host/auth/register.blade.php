@extends('layouts.landing')

@php
    $brandSettings = $systemSettings ?? [];
    $brandName = $brandSettings['name'] ?? 'Tukio 360';
@endphp

@section('title', 'Create Host Account - ' . $brandName)
@section('description', 'Register as an event host on Tukio 360 to create digital invitations, automate WhatsApp RSVPs, and manage guest QR gate check-ins.')

@section('content')
<div class="min-h-screen bg-[#f3ffe3] text-slate-800 relative overflow-hidden pt-8 pb-20 font-sans selection:bg-slate-900 selection:text-white" style="background-color: #f3ffe3;">
    <!-- Ambient Multidimensional Warm Light Highlights -->
    <div class="absolute top-10 left-1/4 -translate-x-1/2 w-[600px] h-[600px] bg-amber-200/30 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/3 right-10 w-[550px] h-[550px] bg-emerald-100/40 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 left-10 w-[500px] h-[500px] bg-lime-200/25 rounded-full blur-[130px] pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-start pt-4 sm:pt-6">
            
            <!-- Left Column (5 Cols) - Visual Brand & Host Privileges -->
            <div class="lg:col-span-5 space-y-6 text-center lg:text-left">
                
                <!-- Page Headline -->
                <div class="space-y-3">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black font-heading text-slate-900 tracking-tight leading-tight">
                        Sajili Sherehe Yako <br class="hidden sm:inline">
                        <span class="text-emerald-950">
                            Tukio 360
                        </span>
                    </h1>
                    <p class="text-slate-700 text-sm sm:text-base leading-relaxed font-semibold">
                        Pata kadi za kisasa za kidijitali za WhatsApp, usimamizi rahisi wa wageni (RSVP), na msafara wa magari ya maharusi.
                    </p>
                </div>

                <!-- 4 Host Benefits Showcase Cards -->
                <div class="space-y-3 pt-2 text-left">
                    <div class="p-4 rounded-2xl bg-white/95 border border-slate-200 flex items-start space-x-3.5 shadow-xs">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Kadi &amp; Mialiko ya WhatsApp</h4>
                            <p class="text-xs text-slate-700 font-medium mt-0.5 leading-relaxed">Tuma kadi rasmi ya mwaliko yenye jina la mgeni na kiungo cha kuthibitisha mahudhurio (RSVP) moja kwa moja WhatsApp.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/95 border border-slate-200 flex items-start space-x-3.5 shadow-xs">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Uhakiki wa Wageni Mlangoni (QR Pass)</h4>
                            <p class="text-xs text-slate-700 font-medium mt-0.5 leading-relaxed">Kila mgeni anapata kadi yenye QR Code maalum inayohakikiwa haraka mlangoni na wasimamizi wako.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/95 border border-slate-200 flex items-start space-x-3.5 shadow-xs">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0 border border-teal-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Orodha ya Wageni &amp; Mahudhurio</h4>
                            <p class="text-xs text-slate-700 font-medium mt-0.5 leading-relaxed">Fuatilia wageni waliothibitisha kuja, idadi ya viti vilivyobaki, na ripoti ya waliokwishaingia ukumbini.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/95 border border-slate-200 flex items-start space-x-3.5 shadow-xs">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 border border-amber-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-9 4h10M5 16h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v7a2 2 0 002 2zm2 2h.01M17 18h.01"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Magari ya Mahurusi &amp; Coasters za Wageni</h4>
                            <p class="text-xs text-slate-700 font-medium mt-0.5 leading-relaxed">Magari safi ya msafara yakiwa na madereva nadhifu na mapambo ya maua kulingana na rangi ya sherehe yako.</p>
                        </div>
                    </div>
                </div>

                <!-- Already Registered Prompt -->
                <div class="p-4 rounded-2xl bg-white border border-slate-200 flex items-center justify-between text-xs shadow-xs">
                    <span class="text-slate-800 font-medium">Umekwisha sajili sherehe yako?</span>
                    <a href="{{ route('host.login') }}" class="font-bold text-emerald-700 hover:text-emerald-900 flex items-center space-x-1 transition-colors">
                        <span>Ingia hapa (Login)</span>
                        <span>&rarr;</span>
                    </a>
                </div>

            </div>

            <!-- Right Column (7 Cols) - Registration Card -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl p-6 sm:p-8 md:p-10 border border-slate-200 shadow-xl space-y-6">
                    
                    <div class="border-b border-slate-200 pb-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl sm:text-2xl font-black font-heading text-slate-900">Taarifa za Sherehe &amp; Mwenyeji</h2>
                                <p class="text-xs text-slate-600 font-medium mt-0.5">Jaza mawasiliano yako ili kuanza kutengeneza kadi na kusimamia wageni wako.</p>
                            </div>
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-800 text-white flex items-center justify-center shadow-md shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1.5 shadow-xs">
                            <div class="font-bold flex items-center space-x-2 text-rose-800">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span>Please resolve the following:</span>
                            </div>
                            <ul class="list-disc list-inside space-y-0.5 pl-2 text-rose-700">
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
                                <label for="full_name" class="block text-xs font-bold text-slate-800">
                                    Full Name <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7 7z"/></svg>
                                    </div>
                                    <input id="full_name" name="full_name" type="text" required value="{{ old('full_name') }}"
                                           class="w-full pl-10 pr-4 py-3 bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 placeholder-slate-400 text-xs sm:text-sm rounded-xl border border-slate-300 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20 transition-all font-semibold" 
                                           placeholder="e.g. David &amp; Sarah Minja">
                                </div>
                                @error('full_name')
                                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-1.5">
                                <label for="email" class="block text-xs font-bold text-slate-800">
                                    Email Address <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <input id="email" name="email" type="email" required value="{{ old('email') }}"
                                           class="w-full pl-10 pr-4 py-3 bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 placeholder-slate-400 text-xs sm:text-sm rounded-xl border border-slate-300 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20 transition-all font-semibold" 
                                           placeholder="you@example.com">
                                </div>
                                @error('email')
                                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone / WhatsApp -->
                            <div class="space-y-1.5">
                                <label for="phone" class="block text-xs font-bold text-slate-800">
                                    Phone / WhatsApp <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.966.527 1.76.812 2.795.812 3.179 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.999-5.767-5.999zm3.385 8.163c-.144.405-.837.774-1.17.822-.312.043-.699.077-2.029-.472-1.696-.703-2.779-2.457-2.863-2.569-.084-.112-.686-.914-.686-1.743 0-.829.434-1.237.588-1.408.155-.171.339-.214.452-.214.113 0 .226.002.324.007.104.005.243-.039.38.291.144.344.492 1.2.535 1.288.043.088.071.191.014.305-.056.113-.085.184-.17.283-.085.1-.179.223-.255.3-.099.099-.202.207-.087.404.115.197.51 1.042 1.093 1.562.752.669 1.386.877 1.583.974.197.098.312.085.428-.049.115-.133.493-.574.625-.771.132-.197.265-.164.444-.099.18.066 1.139.537 1.336.635.197.099.329.148.378.23.049.083.049.48-.095.885z"/></svg>
                                    </div>
                                    <input id="phone" name="phone" type="tel" required value="{{ old('phone') }}"
                                           class="w-full pl-10 pr-4 py-3 bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 placeholder-slate-400 text-xs sm:text-sm rounded-xl border border-slate-300 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20 transition-all font-semibold" 
                                           placeholder="0712345678 or +255...">
                                </div>
                                @error('phone')
                                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Event Type -->
                            <div class="space-y-1.5">
                                <label for="event_type" class="block text-xs font-bold text-slate-800">
                                    Event Type <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <select id="event_type" name="event_type" required 
                                            class="w-full px-4 py-3 bg-slate-50 text-slate-900 text-xs sm:text-sm rounded-xl border border-slate-300 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20 transition-all cursor-pointer font-semibold">
                                        <option value="" class="text-slate-400">Select event occasion</option>
                                        @if(isset($eventTypes))
                                            @foreach($eventTypes as $eventType)
                                                <option value="{{ $eventType->event_type }}" {{ old('event_type') === $eventType->event_type ? 'selected' : '' }}>
                                                    @if(str_contains(strtolower($eventType->event_type), 'wed'))
                                                        Wedding
                                                    @elseif(str_contains(strtolower($eventType->event_type), 'birth'))
                                                        Birthday
                                                    @elseif(str_contains(strtolower($eventType->event_type), 'corp'))
                                                        Corporate Gala
                                                    @elseif(str_contains(strtolower($eventType->event_type), 'send'))
                                                        Send-Off Party
                                                    @elseif(str_contains(strtolower($eventType->event_type), 'kitch'))
                                                        Kitchen Party
                                                    @else
                                                        {{ ucfirst(str_replace('_', ' ', $eventType->event_type)) }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="wedding">Wedding Celebration</option>
                                            <option value="birthday">Birthday Party</option>
                                            <option value="corporate">Corporate Event</option>
                                            <option value="send_off">Send-Off Party</option>
                                            <option value="kitchen_party">Kitchen Party</option>
                                        @endif
                                    </select>
                                </div>
                                @error('event_type')
                                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Number of Guests -->
                            <div class="space-y-1.5">
                                <label for="number_of_guests" class="block text-xs font-bold text-slate-800">
                                    Estimated Guests <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input id="number_of_guests" name="number_of_guests" type="number" min="1" max="1000" required
                                           value="{{ old('number_of_guests', 100) }}"
                                           class="w-full px-4 py-3 bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 placeholder-slate-400 text-xs sm:text-sm rounded-xl border border-slate-300 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20 transition-all font-bold" 
                                           placeholder="100">
                                </div>
                                @error('number_of_guests')
                                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quick Guest Count Buttons -->
                            <div class="md:col-span-2 flex items-center gap-2 overflow-x-auto pb-1 text-xs">
                                <span class="text-[11px] text-slate-700 font-bold shrink-0">Quick presets:</span>
                                <button type="button" onclick="setGuestCount(50)" class="guest-preset-btn px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-300 font-bold transition-all">50</button>
                                <button type="button" onclick="setGuestCount(100)" class="guest-preset-btn px-2.5 py-1 rounded-lg bg-emerald-700 text-white font-bold border border-emerald-700 shadow-2xs transition-all">100</button>
                                <button type="button" onclick="setGuestCount(200)" class="guest-preset-btn px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-300 font-bold transition-all">200</button>
                                <button type="button" onclick="setGuestCount(300)" class="guest-preset-btn px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-300 font-bold transition-all">300</button>
                                <button type="button" onclick="setGuestCount(500)" class="guest-preset-btn px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-300 font-bold transition-all">500</button>
                            </div>

                            <!-- Dynamic Live Total Calculation Capsule -->
                            <div id="total-amount-display" class="md:col-span-2 p-5 rounded-2xl bg-gradient-to-r from-emerald-800 via-teal-900 to-emerald-950 text-white shadow-lg border border-emerald-700/40 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-12 h-12 rounded-xl bg-white/10 text-emerald-300 flex items-center justify-center text-xl font-bold border border-white/15 shrink-0">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase font-extrabold text-emerald-200 tracking-wider">Gharama ya Mfumo &amp; Kadi</p>
                                        <p class="text-[11px] text-emerald-100/90 mt-0.5">TZS 2,000 kwa kila mgeni &bull; Kadi za WhatsApp, QR Pass &amp; Scanner</p>
                                    </div>
                                </div>
                                <div class="text-left sm:text-right border-t sm:border-t-0 border-white/15 pt-2 sm:pt-0">
                                    <p class="text-2xl font-black text-amber-300 font-heading" id="total-amount">TZS 200,000</p>
                                    <span class="text-[10px] text-emerald-300 font-bold bg-emerald-500/25 px-2.5 py-0.5 rounded-full border border-emerald-400/30">
                                        ✓ Huduma Kamili ya Sherehe
                                    </span>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="space-y-1.5">
                                <label for="password" class="block text-xs font-bold text-slate-800">
                                    Password <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input id="password" name="password" type="password" required
                                           class="w-full pl-4 pr-10 py-3 bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 placeholder-slate-400 text-xs sm:text-sm rounded-xl border border-slate-300 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20 transition-all font-semibold" 
                                           placeholder="••••••••">
                                    <button type="button" onclick="togglePasswordVisibility('password', 'pwd-icon-1')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-700 transition-colors" tabindex="-1">
                                        <svg id="pwd-icon-1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="space-y-1.5">
                                <label for="password_confirmation" class="block text-xs font-bold text-slate-800">
                                    Confirm Password <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input id="password_confirmation" name="password_confirmation" type="password" required
                                           class="w-full pl-4 pr-10 py-3 bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 placeholder-slate-400 text-xs sm:text-sm rounded-xl border border-slate-300 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20 transition-all font-semibold" 
                                           placeholder="••••••••">
                                    <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'pwd-icon-2')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-700 transition-colors" tabindex="-1">
                                        <svg id="pwd-icon-2" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            </div>

                        </div>

                        <!-- Submit CTA Button -->
                        <div class="pt-3">
                            <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-emerald-600 via-emerald-700 to-teal-800 hover:from-emerald-700 hover:to-teal-900 text-white font-black text-sm rounded-xl shadow-xl shadow-emerald-700/25 border border-emerald-500/30 transition-all duration-300 flex items-center justify-center space-x-2 transform hover:-translate-y-0.5 cursor-pointer">
                                <span>Complete Registration &amp; Setup Event</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>

                        <p class="text-center text-[11px] text-slate-600 font-medium pt-1">
                            By continuing, you agree to {{ $brandName }}'s 
                            <a href="{{ route('page.show', 'terms-of-use') }}" class="underline text-emerald-700 hover:text-emerald-900 font-semibold">Terms of Service</a> &amp; 
                            <a href="{{ route('page.show', 'privacy-policy') }}" class="underline text-emerald-700 hover:text-emerald-900 font-semibold">Privacy Policy</a>.
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
                    btn.className = 'guest-preset-btn px-2.5 py-1 rounded-lg bg-emerald-700 text-white font-semibold border border-emerald-700 shadow-2xs transition-all';
                } else {
                    btn.className = 'guest-preset-btn px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-semibold transition-all';
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
