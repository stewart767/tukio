<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $brandSettings = $systemSettings ?? [];
        $brandFavicon = $brandSettings['favicon_url'] ?? null;
        $brandName = $brandSettings['name'] ?? 'Tukio 360';
        $brandLogo = $brandSettings['logo_url'] ?? null;
        $contactPhone = $brandSettings['phone'] ?? '+255 662 199 071';
        $contactEmail = $brandSettings['email'] ?? 'info@tukio360.co.tz';
        $socialLinks = $brandSettings['social'] ?? [];
        $waFloatingUrl = $socialLinks['whatsapp'] ?? $brandSettings['whatsapp_url'] ?? ('https://wa.me/' . ($brandSettings['whatsapp_clean'] ?? '255662199071'));
        $isDarkNav = false;
    @endphp

    <!-- Favicon -->
    @if($brandFavicon)
        <link rel="icon" type="image/png" href="{{ $brandFavicon }}">
        <link rel="alternate icon" href="{{ $brandFavicon }}">
    @else
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=2">
        <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
        <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <meta name="theme-color" content="#4f46e5">
    @endif

    <title>{{ $brandName }} - @yield('title', 'Smart Events & Digital Invitations Management System')</title>
    <meta name="description" content="@yield('description', 'Mfumo wa kisasa wa kadi za mwaliko kwa njia ya WhatsApp, uthibitisho wa RSVP, magari ya sherehe na ukaguzi wa wageni mlangoni kwa QR code Tanzania.')">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/css/modern-animations.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="min-h-full flex flex-col bg-[#f3ffe3] text-slate-800 font-sans antialiased selection:bg-slate-900 selection:text-white" style="background-color: #f3ffe3 !important;">
    <div id="app" class="flex-1 flex flex-col bg-[#f3ffe3]" style="background-color: #f3ffe3 !important;">

        <!-- ============================================
             2. MAIN INSTITUTIONAL NAVIGATION HEADER
             ============================================ -->
        <header class="sticky top-0 z-40 transition-all duration-300 w-full" id="main-header">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <nav class="{{ $isDarkNav ? 'rounded-2xl px-4 sm:px-6 py-2.5 transition-all duration-300 flex items-center justify-between shadow-2xl border border-white/15 backdrop-blur-2xl bg-slate-950/85' : 'bg-white/95 backdrop-blur-xl rounded-2xl px-4 sm:px-6 py-2 transition-all duration-300 flex items-center justify-between shadow-md shadow-slate-900/5 hover:shadow-lg hover:shadow-slate-900/10 border border-slate-200/90' }}">
                    
                    <!-- Clean Graphical Brand Logo (No Cluttered Raw Text) -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center group py-0.5" title="{{ $brandName }}">
                            <img src="{{ asset('images/logo-tukio-cropped.png') }}" alt="{{ $brandName }}" class="h-14 sm:h-16 w-auto object-contain transition-transform duration-300 group-hover:scale-105" onerror="this.src='{{ asset('images/logo-tukio.png') }}'">
                        </a>
                    </div>

                    <!-- Desktop Center Menu -->
                    <div class="hidden lg:flex items-center space-x-0.5 xl:space-x-1 font-semibold text-xs xl:text-sm {{ $isDarkNav ? 'text-slate-300' : 'text-slate-600' }}">
                        <a href="{{ route('home') }}" class="whitespace-nowrap shrink-0 px-3 xl:px-3.5 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('home*') ? ($isDarkNav ? 'text-white bg-white/10 font-bold' : 'text-slate-900 bg-slate-100 font-bold shadow-2xs') : ($isDarkNav ? 'hover:text-white hover:bg-white/5' : 'hover:text-slate-900 hover:bg-slate-100/70') }}">
                            {{ __('common.home') }}
                        </a>

                        <!-- Occasions Dropdown -->
                        <div class="relative group">
                            <button type="button" class="whitespace-nowrap shrink-0 px-3 xl:px-3.5 py-2 rounded-xl transition-all duration-200 flex items-center space-x-1.5 {{ request()->routeIs('categories*') || request()->routeIs('events*') ? ($isDarkNav ? 'text-white bg-white/10 font-bold' : 'text-slate-900 bg-slate-100 font-bold') : ($isDarkNav ? 'hover:text-white hover:bg-white/5' : 'hover:text-slate-900 hover:bg-slate-100/70') }}">
                                <span>Explore Occasions</span>
                                <svg class="w-3.5 h-3.5 text-slate-400 group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <!-- Dropdown Menu -->
                            <div class="absolute left-0 top-full pt-2 w-64 opacity-0 translate-y-2 pointer-events-none group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-200 z-50">
                                <div class="{{ $isDarkNav ? 'bg-slate-900/95 backdrop-blur-2xl border border-white/15' : 'bg-white/95 backdrop-blur-xl border border-slate-200' }} rounded-2xl p-2.5 shadow-2xl space-y-1">
                                    <a href="{{ route('categories') }}?cat=wedding" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs {{ $isDarkNav ? 'hover:bg-white/10 text-white' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-800' }} transition-colors">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-500/10 text-indigo-600 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        </div>
                                        <div>
                                            <div class="font-bold {{ $isDarkNav ? 'text-white' : 'text-slate-900' }}">Weddings &amp; Send-Offs</div>
                                            <div class="text-[10px] {{ $isDarkNav ? 'text-slate-400' : 'text-slate-500' }}">Royal invites, RSVP, convoy</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('categories') }}?cat=corporate" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs {{ $isDarkNav ? 'hover:bg-white/10 text-white' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-800' }} transition-colors">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-500/10 text-indigo-600 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div>
                                            <div class="font-bold {{ $isDarkNav ? 'text-white' : 'text-slate-900' }}">Corporate &amp; Summits</div>
                                            <div class="text-[10px] {{ $isDarkNav ? 'text-slate-400' : 'text-slate-500' }}">QR badges, speaker passes</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('categories') }}?cat=birthday" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs {{ $isDarkNav ? 'hover:bg-white/10 text-white' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-800' }} transition-colors">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-500/10 text-indigo-600 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V6a2 2 0 10-2 2h2zm-8 8h16a2 2 0 002-2v-3a2 2 0 00-2-2H4a2 2 0 00-2 2v3a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div>
                                            <div class="font-bold {{ $isDarkNav ? 'text-white' : 'text-slate-900' }}">Birthdays &amp; Anniversaries</div>
                                            <div class="text-[10px] {{ $isDarkNav ? 'text-slate-400' : 'text-slate-500' }}">Countdowns, live maps</div>
                                        </div>
                                    </a>
                                    <div class="border-t {{ $isDarkNav ? 'border-white/10' : 'border-slate-100' }} pt-1 mt-1">
                                        <a href="{{ route('categories') }}" class="block text-center px-3 py-1.5 text-[11px] font-bold text-slate-700 hover:text-slate-900 {{ $isDarkNav ? 'hover:bg-white/10 text-slate-300' : 'hover:bg-slate-100' }} rounded-lg">
                                            View All Occasions &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Templates -->
                        <a href="{{ route('templates.index') }}" class="whitespace-nowrap shrink-0 px-3 xl:px-3.5 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('templates*') ? ($isDarkNav ? 'text-white bg-white/10 font-bold' : 'text-slate-900 bg-slate-100 font-bold shadow-2xs') : ($isDarkNav ? 'hover:text-white hover:bg-white/5' : 'hover:text-slate-900 hover:bg-slate-100/70') }}">
                            Templates
                        </a>

                        <!-- Car Hire -->
                        <a href="{{ route('car-hire.index') }}" class="whitespace-nowrap shrink-0 px-3 xl:px-3.5 py-2 rounded-xl transition-all duration-200 flex items-center space-x-1.5 {{ request()->routeIs('car-hire*') ? ($isDarkNav ? 'text-white bg-white/10 font-bold' : 'text-slate-900 bg-slate-100 font-bold shadow-2xs') : ($isDarkNav ? 'hover:text-white hover:bg-white/5' : 'hover:text-slate-900 hover:bg-slate-100/70') }}">
                            <span>VIP Car Hire</span>
                        </a>

                        <!-- Venues -->
                        <a href="{{ route('venues') }}" class="whitespace-nowrap shrink-0 px-3 xl:px-3.5 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('venues*') ? ($isDarkNav ? 'text-white bg-white/10 font-bold' : 'text-slate-900 bg-slate-100 font-bold shadow-2xs') : ($isDarkNav ? 'hover:text-white hover:bg-white/5' : 'hover:text-slate-900 hover:bg-slate-100/70') }}">
                            {{ __('common.venues') }}
                        </a>

                        <!-- Contact -->
                        <a href="{{ route('contact') }}" class="whitespace-nowrap shrink-0 px-3 xl:px-3.5 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('contact*') ? ($isDarkNav ? 'text-white bg-white/10 font-bold' : 'text-slate-900 bg-slate-100 font-bold shadow-2xs') : ($isDarkNav ? 'hover:text-white hover:bg-white/5' : 'hover:text-slate-900 hover:bg-slate-100/70') }}">
                            {{ __('common.contact') }}
                        </a>
                    </div>

                    <!-- Right Side Actions: Modern Executive Cluster -->
                    <div class="flex items-center gap-2 sm:gap-2.5 shrink-0">
                        <!-- Direct Track RSVP Interactive Trigger -->
                        <button type="button" onclick="openTrackModal()" class="hidden md:inline-flex items-center gap-2 h-10 px-3.5 text-xs font-semibold rounded-xl whitespace-nowrap transition-all duration-200 {{ $isDarkNav ? 'text-slate-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10' : 'text-slate-700 hover:text-slate-950 bg-slate-100/80 hover:bg-slate-200/70 border border-slate-200/80 shadow-2xs' }}" title="Lookup RSVP or Gate Pass">
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Track RSVP</span>
                        </button>

                        @include('components.language-switcher')

                        <div class="hidden sm:block h-4 w-px {{ $isDarkNav ? 'bg-white/15' : 'bg-slate-200' }} shrink-0"></div>

                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 h-10 px-4.5 sm:px-5 rounded-xl font-bold text-xs whitespace-nowrap transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 shrink-0 {{ $isDarkNav ? 'bg-white hover:bg-slate-100 text-slate-900' : 'bg-emerald-700 hover:bg-emerald-800 text-white shadow-md shadow-emerald-800/20' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                                <span>{{ __('common.dashboard') }}</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center justify-center h-10 px-3.5 text-xs font-semibold rounded-xl whitespace-nowrap transition-all duration-200 shrink-0 {{ $isDarkNav ? 'text-slate-300 hover:text-white hover:bg-white/10' : 'text-slate-700 hover:text-slate-950 hover:bg-slate-100/80' }}">
                                {{ __('common.login') }}
                            </a>
                            <a href="{{ route('host.register') }}" class="group relative inline-flex items-center justify-center gap-2 h-10 px-4 sm:px-5 rounded-xl font-bold text-xs whitespace-nowrap transition-all duration-200 shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 shrink-0 {{ $isDarkNav ? 'bg-white hover:bg-slate-100 text-slate-900 shadow-white/10' : 'bg-emerald-700 hover:bg-emerald-800 text-white shadow-md shadow-emerald-800/20' }}">
                                <span class="w-4 h-4 rounded-md flex items-center justify-center text-[10px] font-black {{ $isDarkNav ? 'bg-slate-900 text-white' : 'bg-white/20 text-white' }} transition-transform duration-200 group-hover:rotate-90">+</span>
                                <span>Host Your Event</span>
                            </a>
                        @endauth

                        <!-- Mobile Menu Toggle Button -->
                        <button id="mobile-menu-toggle" type="button" class="lg:hidden p-2 rounded-xl {{ $isDarkNav ? 'text-slate-200 hover:text-white hover:bg-white/10' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} transition-colors focus:outline-none shrink-0" aria-label="Toggle menu">
                            <svg id="hamburger-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </nav>
            </div>

            <!-- Mobile Slide Drawer Menu -->
            <div id="mobile-drawer" class="lg:hidden fixed inset-x-4 top-20 {{ $isDarkNav ? 'bg-slate-950/95 text-white border-white/20' : 'bg-white/95 text-slate-700 border-slate-200/80' }} backdrop-blur-2xl border rounded-3xl p-6 shadow-2xl transition-all duration-300 transform -translate-y-4 opacity-0 pointer-events-none z-50">
                <div class="flex flex-col space-y-2 text-base font-semibold">
                    <a href="{{ route('home') }}" class="px-4 py-3 rounded-2xl transition-colors {{ request()->routeIs('home*') ? ($isDarkNav ? 'bg-white/10 text-white font-bold' : 'bg-indigo-50 text-indigo-600') : ($isDarkNav ? 'hover:bg-white/5 text-slate-200' : 'hover:bg-slate-50') }}">
                        {{ __('common.home') }}
                    </a>
                    <button type="button" onclick="openTrackModal()" class="w-full text-left px-4 py-3 rounded-2xl {{ $isDarkNav ? 'bg-indigo-950/60 text-indigo-200 border border-indigo-500/30' : 'bg-emerald-50 text-emerald-800' }} font-bold flex items-center justify-between">
                        <span>Fuatilia Kadi / Track RSVP</span>
                    </button>
                    <a href="{{ route('events') }}" class="px-4 py-3 rounded-2xl transition-colors {{ request()->routeIs('events*') ? ($isDarkNav ? 'bg-white/10 text-white font-bold' : 'bg-indigo-50 text-indigo-600') : ($isDarkNav ? 'hover:bg-white/5 text-slate-200' : 'hover:bg-slate-50') }}">
                        {{ __('common.events') }}
                    </a>
                    <a href="{{ route('car-hire.index') }}" class="px-4 py-3 rounded-2xl transition-colors {{ request()->routeIs('car-hire*') ? ($isDarkNav ? 'bg-indigo-500/20 text-indigo-300 font-bold border border-indigo-500/30' : 'bg-indigo-50 text-indigo-600 font-bold') : ($isDarkNav ? 'hover:bg-white/5 text-slate-200' : 'hover:bg-slate-50') }}">
                        VIP Car Hire
                    </a>
                    <a href="{{ route('categories') }}" class="px-4 py-3 rounded-2xl transition-colors {{ request()->routeIs('categories*') ? ($isDarkNav ? 'bg-white/10 text-white font-bold' : 'bg-indigo-50 text-indigo-600') : ($isDarkNav ? 'hover:bg-white/5 text-slate-200' : 'hover:bg-slate-50') }}">
                        {{ __('common.categories') }}
                    </a>
                    <a href="{{ route('venues') }}" class="px-4 py-3 rounded-2xl transition-colors {{ request()->routeIs('venues*') ? ($isDarkNav ? 'bg-white/10 text-white font-bold' : 'bg-indigo-50 text-indigo-600') : ($isDarkNav ? 'hover:bg-white/5 text-slate-200' : 'hover:bg-slate-50') }}">
                        {{ __('common.venues') }}
                    </a>
                    <a href="{{ route('templates.index') }}" class="px-4 py-3 rounded-2xl transition-colors {{ request()->routeIs('templates*') ? ($isDarkNav ? 'bg-white/10 text-white font-bold' : 'bg-indigo-50 text-indigo-600') : ($isDarkNav ? 'hover:bg-white/5 text-slate-200' : 'hover:bg-slate-50') }}">
                        Invitation Templates
                    </a>
                    <a href="{{ route('contact') }}" class="px-4 py-3 rounded-2xl transition-colors {{ request()->routeIs('contact*') ? ($isDarkNav ? 'bg-white/10 text-white font-bold' : 'bg-indigo-50 text-indigo-600') : ($isDarkNav ? 'hover:bg-white/5 text-slate-200' : 'hover:bg-slate-50') }}">
                        {{ __('common.contact') }}
                    </a>
                </div>
                <div class="mt-6 pt-5 border-t {{ $isDarkNav ? 'border-white/10' : 'border-slate-100' }} flex flex-col space-y-3">
                    @guest
                        <a href="{{ route('login') }}" class="w-full text-center py-3 font-semibold {{ $isDarkNav ? 'text-slate-200 bg-white/10 hover:bg-white/20' : 'text-slate-700 bg-slate-100 hover:bg-slate-200' }} rounded-2xl transition-colors">
                            {{ __('common.login') }}
                        </a>
                        <a href="{{ route('host.register') }}" class="w-full text-center py-3 font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-2xl shadow-md shadow-emerald-800/20">
                            Host Your Event Free
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="w-full text-center py-3 font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-2xl shadow-md shadow-emerald-800/20">
                            {{ __('common.dashboard') }}
                        </a>
                    @endguest
                </div>
            </div>
        </header>

        <!-- ============================================
             3. MAIN PAGE CONTENT
             ============================================ -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- ============================================
             4. MODERN LUXURY FOOTER (UNCLUTTERED & ELEGANT)
             ============================================ -->
        <footer class="relative bg-white/90 backdrop-blur-xl border-t border-emerald-950/10 text-slate-700 overflow-hidden mt-auto">
            <!-- Subtle Ambient Light Orbs -->
            <div class="absolute -top-32 -left-32 w-80 h-80 bg-emerald-300/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-80 h-80 bg-teal-300/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-10 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12 mb-12">
                    
                    <!-- Column 1: Brand & Purpose -->
                    <div class="space-y-4">
                        <a href="{{ route('home') }}" class="inline-block" title="{{ $brandName }}">
                            <img src="{{ asset('images/logo-tukio-cropped.png') }}" alt="{{ $brandName }}" class="h-24 w-auto object-contain" loading="lazy" onerror="this.src='{{ asset('images/logo-tukio.png') }}'">
                        </a>

                        <!-- Verified Trust Badge -->
                        <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200/70 text-xs font-semibold text-emerald-800 shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>QR Gate Passes &amp; Verified RSVPs</span>
                        </div>
                        
                        <!-- Social Media Icon Pills -->
                        <div class="flex items-center space-x-2 pt-1">
                            <a href="{{ $socialLinks['facebook'] ?? '#' }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-600 flex items-center justify-center transition-all duration-200 hover:-translate-y-0.5 shadow-2xs" aria-label="Facebook">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                            </a>
                            <a href="{{ $socialLinks['instagram'] ?? '#' }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-pink-600 hover:text-white text-slate-600 flex items-center justify-center transition-all duration-200 hover:-translate-y-0.5 shadow-2xs" aria-label="Instagram">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.919C2.163 15.6 2.151 15.22 2.151 12c0-3.204.012-3.583.069-4.849.149-3.228 1.664-4.771 4.919-4.919 1.265-.058 1.645-.07 4.849-.07zm0-2.163C8.741 0 8.332.014 7.052.072 2.694.272.272 2.694.072 7.052.014 8.332 0 8.741 0 12s.014 3.668.072 4.948c.2 4.358 2.622 6.78 6.98 6.98 1.28.058 1.689.072 4.948.072s3.668-.014 4.948-.072c4.354-.2 6.782-2.618 6.979-6.98.059-1.281.073-1.689.073-4.948s-.014-3.668-.072-4.948C23.728 2.694 21.306.272 16.948.072 15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998zm6.406-11.845a1.44 1.44 0 11-2.881 0 1.44 1.44 0 012.881 0z"/></svg>
                            </a>
                            <a href="{{ $waFloatingUrl }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-emerald-600 hover:text-white text-slate-600 flex items-center justify-center transition-all duration-200 hover:-translate-y-0.5 shadow-2xs" aria-label="WhatsApp">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.966.527 1.76.812 2.795.812 3.179 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.999-5.767-5.999zm3.385 8.163c-.144.405-.837.774-1.17.822-.312.043-.699.077-2.029-.472-1.696-.703-2.779-2.457-2.863-2.569-.084-.112-.686-.914-.686-1.743 0-.829.434-1.237.588-1.408.155-.171.339-.214.452-.214.113 0 .226.002.324.007.104.005.243-.039.38.291.144.344.492 1.2.535 1.288.043.088.071.191.014.305-.056.113-.085.184-.17.283-.085.1-.179.223-.255.3-.099.099-.202.207-.087.404.115.197.51 1.042 1.093 1.562.752.669 1.386.877 1.583.974.197.098.312.085.428-.049.115-.133.493-.574.625-.771.132-.197.265-.164.444-.099.18.066 1.139.537 1.336.635.197.099.329.148.378.23.049.083.049.48-.095.885z"/></svg>
                            </a>
                            <a href="https://twitter.com" target="_blank" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-900 hover:text-white text-slate-600 flex items-center justify-center transition-all duration-200 hover:-translate-y-0.5 shadow-2xs" aria-label="Twitter">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Column 2: Huduma Zetu (Services) -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-4 flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                            <span>Huduma Zetu</span>
                        </h4>
                        <ul class="space-y-2.5 text-xs sm:text-sm">
                            <li>
                                <a href="{{ route('templates.index') }}" class="text-slate-600 hover:text-emerald-700 font-medium transition-colors inline-flex items-center gap-2 group">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300 group-hover:bg-emerald-600 transition-colors"></span>
                                    <span>Kadi za Kidijitali za Harusi</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('car-hire.index') }}" class="text-emerald-700 hover:text-emerald-800 font-semibold transition-colors inline-flex items-center gap-2 group">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 group-hover:bg-emerald-800 transition-colors"></span>
                                    <span>Msafara wa Magari ya Kifahari</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('venues') }}" class="text-slate-600 hover:text-emerald-700 font-medium transition-colors inline-flex items-center gap-2 group">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300 group-hover:bg-emerald-600 transition-colors"></span>
                                    <span>Kumbi Teule za Sherehe</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('categories') }}" class="text-slate-600 hover:text-emerald-700 font-medium transition-colors inline-flex items-center gap-2 group">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300 group-hover:bg-emerald-600 transition-colors"></span>
                                    <span>Aina Zote za Matukio</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Column 3: Mwenyeji & Wageni (Guests & Self-Service) -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-4 flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-teal-600"></span>
                            <span>Mwenyeji &amp; Wageni</span>
                        </h4>
                        <ul class="space-y-2.5 text-xs sm:text-sm">
                            <li>
                                <button type="button" onclick="openTrackModal()" class="text-emerald-800 hover:text-emerald-950 font-bold transition-colors inline-flex items-center gap-2 group text-left">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 group-hover:bg-emerald-800 transition-colors"></span>
                                    <span>Fuatilia Kadi &amp; RSVP</span>
                                </button>
                            </li>
                            <li>
                                <a href="{{ route('host.register') }}" class="text-slate-700 hover:text-emerald-800 font-semibold transition-colors inline-flex items-center gap-2 group">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 group-hover:bg-emerald-600 transition-colors"></span>
                                    <span>Sajili Sherehe Yako</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('contact') }}" class="text-slate-700 hover:text-emerald-800 font-semibold transition-colors inline-flex items-center gap-2 group">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 group-hover:bg-emerald-600 transition-colors"></span>
                                    <span>Maswali Yanayoulizwa (FAQs)</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('contact') }}" class="text-slate-700 hover:text-emerald-800 font-semibold transition-colors inline-flex items-center gap-2 group">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 group-hover:bg-emerald-600 transition-colors"></span>
                                    <span>Msaada &amp; Ushauri</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Column 4: Mawasiliano & Ofisi -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-4 flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                            <span>Mawasiliano</span>
                        </h4>
                        <ul class="space-y-3 text-xs sm:text-sm">
                            <li class="flex items-start space-x-2.5 text-slate-700 font-medium">
                                <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <span class="leading-relaxed">Kinondoni, Dar es Salaam, Tanzania</span>
                            </li>
                            <li class="flex items-center space-x-2.5 text-slate-700 font-medium">
                                <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone) }}" class="font-bold text-slate-900 hover:text-emerald-700 transition-colors">{{ $contactPhone }}</a>
                            </li>
                            <li class="flex items-center space-x-2.5 text-slate-700 font-medium">
                                <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <a href="mailto:{{ $contactEmail }}" class="text-slate-900 font-semibold hover:text-emerald-700 transition-colors truncate">{{ $contactEmail }}</a>
                            </li>
                        </ul>
                        
                        <!-- Quick WhatsApp Action -->
                        <div class="pt-3">
                            <a href="{{ $waFloatingUrl }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs hover:shadow-md transition-all hover:scale-[1.02]">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.966.527 1.76.812 2.795.812 3.179 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.999-5.767-5.999zm3.385 8.163c-.144.405-.837.774-1.17.822-.312.043-.699.077-2.029-.472-1.696-.703-2.779-2.457-2.863-2.569-.084-.112-.686-.914-.686-1.743 0-.829.434-1.237.588-1.408.155-.171.339-.214.452-.214.113 0 .226.002.324.007.104.005.243-.039.38.291.144.344.492 1.2.535 1.288.043.088.071.191.014.305-.056.113-.085.184-.17.283-.085.1-.179.223-.255.3-.099.099-.202.207-.087.404.115.197.51 1.042 1.093 1.562.752.669 1.386.877 1.583.974.197.098.312.085.428-.049.115-.133.493-.574.625-.771.132-.197.265-.164.444-.099.18.066 1.139.537 1.336.635.197.099.329.148.378.23.049.083.049.48-.095.885z"/></svg>
                                <span>Ongea Nasi WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Bottom Copyright & Developer Attribution -->
                <div class="pt-6 border-t border-slate-200/80 flex flex-col md:flex-row items-center justify-between text-xs text-slate-700 gap-4">
                    <p>&copy; {{ now()->year }} <span class="text-slate-900 font-bold">{{ $brandName }}</span>. Haki zote zimehifadhiwa.</p>
                    <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                        <span class="text-slate-700">Imetengenezwa na <a href="http://www.reliancesolutions.co.tz" target="_blank" class="text-emerald-700 hover:text-emerald-900 font-bold hover:underline">Reliance Solutions &amp; Technology</a></span>
                        <a href="{{ route('page.show', 'privacy-policy') }}" class="text-slate-700 hover:text-slate-950 font-medium transition-colors">Sera ya Faragha</a>
                        <a href="{{ route('page.show', 'terms-of-use') }}" class="text-slate-700 hover:text-slate-950 font-medium transition-colors">Vigezo na Masharti</a>
                        <a href="{{ route('contact') }}" class="text-slate-700 hover:text-slate-950 font-medium transition-colors">Msaada</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- ============================================
         5. INTERACTIVE SUPA-STYLE TRACK MODAL
         ============================================ -->
    <div id="supa-track-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/75 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
        <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden transform scale-95 transition-all duration-300" id="supa-track-modal-box">
            
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-emerald-900 via-teal-950 to-emerald-900 p-6 text-white relative">
                <button type="button" onclick="closeTrackModal()" class="absolute top-5 right-5 text-emerald-200 hover:text-white transition-colors" aria-label="Close modal">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <h3 class="text-xl sm:text-2xl font-black font-heading text-white">Fuatilia Mwaliko au RSVP</h3>
                <p class="text-emerald-100/90 text-xs mt-1">Weka namba ya simu au namba ya kadi (Invitation Code) ili kuthibitisha mahudhurio au kupata QR Pass.</p>
            </div>

            <!-- Modal Form Body -->
            <div class="p-6 sm:p-7 space-y-5">
                <form action="{{ route('track') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="modal_track_query" class="block text-xs font-bold uppercase tracking-wider text-slate-800 mb-1.5">
                            Namba ya Simu / Invitation Code
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" id="modal_track_query" name="query" placeholder="0712345678 au INV-XXXX" required class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600 transition-all font-semibold">
                        </div>
                        <span class="text-[11px] text-slate-600 font-medium mt-1 block">
                            (Enter your registered phone number or control code to check RSVP &amp; QR gate pass.)
                        </span>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm shadow-md shadow-emerald-800/20 transition-all flex items-center justify-center space-x-2">
                        <span>Fuatilia Sasa &bull; Check Status</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>

                <!-- Shortcut links -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <a href="{{ route('host.register') }}" class="text-indigo-600 font-bold hover:underline">
                        + Anza Kujaza Maombi (Host Event)
                    </a>
                    <a href="{{ route('contact') }}" class="text-slate-500 hover:text-slate-700">
                        Msaada wa Haraka
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts: Header Scroll, Drawer & Track Modal -->
    <script>
        function openTrackModal() {
            const modal = document.getElementById('supa-track-modal');
            const box = document.getElementById('supa-track-modal-box');
            if (modal && box) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100');
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
                setTimeout(() => {
                    const input = document.getElementById('modal_track_query');
                    if (input) input.focus();
                }, 100);
            }
        }

        function closeTrackModal() {
            const modal = document.getElementById('supa-track-modal');
            const box = document.getElementById('supa-track-modal-box');
            if (modal && box) {
                modal.classList.add('opacity-0', 'pointer-events-none');
                modal.classList.remove('opacity-100');
                box.classList.add('scale-95');
                box.classList.remove('scale-100');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Close modal on click outside
            const modal = document.getElementById('supa-track-modal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeTrackModal();
                    }
                });
            }

            // Close on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeTrackModal();
                }
            });

            // Mobile Drawer toggle
            const toggleBtn = document.getElementById('mobile-menu-toggle');
            const drawer = document.getElementById('mobile-drawer');
            const hamburgerIcon = document.getElementById('hamburger-icon');
            const closeIcon = document.getElementById('close-icon');
            let isDrawerOpen = false;

            if (toggleBtn && drawer) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    isDrawerOpen = !isDrawerOpen;
                    if (isDrawerOpen) {
                        drawer.classList.remove('opacity-0', '-translate-y-4', 'pointer-events-none');
                        drawer.classList.add('opacity-100', 'translate-y-0');
                        hamburgerIcon.classList.add('hidden');
                        closeIcon.classList.remove('hidden');
                    } else {
                        drawer.classList.add('opacity-0', '-translate-y-4', 'pointer-events-none');
                        drawer.classList.remove('opacity-100', 'translate-y-0');
                        hamburgerIcon.classList.remove('hidden');
                        closeIcon.classList.add('hidden');
                    }
                });

                document.addEventListener('click', function(e) {
                    if (isDrawerOpen && !drawer.contains(e.target) && !toggleBtn.contains(e.target)) {
                        isDrawerOpen = false;
                        drawer.classList.add('opacity-0', '-translate-y-4', 'pointer-events-none');
                        drawer.classList.remove('opacity-100', 'translate-y-0');
                        hamburgerIcon.classList.remove('hidden');
                        closeIcon.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    <!-- Floating WhatsApp Widget -->
    <div class="fixed bottom-6 right-6 z-50 flex items-center group">
        <a href="{{ $waFloatingUrl }}" target="_blank" rel="noopener noreferrer"
           class="mr-3 hidden sm:flex items-center gap-2 bg-emerald-900/95 text-white text-xs font-semibold px-4 py-2 rounded-2xl shadow-xl backdrop-blur-md border border-emerald-500/20 opacity-0 translate-x-3 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300 pointer-events-none group-hover:pointer-events-auto">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Chat on WhatsApp</span>
        </a>

        <a href="{{ $waFloatingUrl }}" target="_blank" rel="noopener noreferrer" 
           class="relative flex items-center justify-center w-14 h-14 bg-gradient-to-tr from-emerald-600 via-emerald-500 to-green-500 hover:from-emerald-500 hover:to-green-400 text-white rounded-full shadow-2xl shadow-emerald-500/40 hover:shadow-emerald-500/60 transition-all duration-300 transform hover:scale-110 active:scale-95 focus:outline-none"
           aria-label="Chat on WhatsApp">
            <span class="absolute -inset-1 rounded-full bg-emerald-500/30 animate-ping opacity-75 pointer-events-none"></span>
            <span class="absolute top-0 right-0 w-3.5 h-3.5 bg-emerald-400 border-2 border-white rounded-full shadow-xs"></span>
            <svg class="w-7 h-7 relative z-10 fill-current" viewBox="0 0 24 24">
                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.966.527 1.76.812 2.795.812 3.179 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.999-5.767-5.999zm3.385 8.163c-.144.405-.837.774-1.17.822-.312.043-.699.077-2.029-.472-1.696-.703-2.779-2.457-2.863-2.569-.084-.112-.686-.914-.686-1.743 0-.829.434-1.237.588-1.408.155-.171.339-.214.452-.214.113 0 .226.002.324.007.104.005.243-.039.38.291.144.344.492 1.2.535 1.288.043.088.071.191.014.305-.056.113-.085.184-.17.283-.085.1-.179.223-.255.3-.099.099-.202.207-.087.404.115.197.51 1.042 1.093 1.562.752.669 1.386.877 1.583.974.197.098.312.085.428-.049.115-.133.493-.574.625-.771.132-.197.265-.164.444-.099.18.066 1.139.537 1.336.635.197.099.329.148.378.23.049.083.049.48-.095.885z"/>
            </svg>
        </a>
    </div>

    @stack('scripts')
</body>
</html>
