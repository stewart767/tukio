@extends('layouts.landing')

@section('title', 'Smart Events & Digital Invitations Management Platform')

@section('content')
@php
    $brandSettings = $systemSettings ?? [];
    $socialLinks = $brandSettings['social'] ?? [];
    $waFloatingUrl = $socialLinks['whatsapp'] ?? $brandSettings['whatsapp_url'] ?? ('https://wa.me/' . ($brandSettings['whatsapp_clean'] ?? '255662199071'));
@endphp
<!-- ============================================
     HERO PHOTO SLIDER / CAROUSEL (CLEAR, VIBRANT IMAGES)
     ============================================ -->
<section class="relative w-full overflow-hidden bg-slate-950" id="hero-slider-section">
    <!-- Slider Container -->
    <div class="relative w-full h-[500px] sm:h-[580px] lg:h-[640px] overflow-hidden" id="hero-carousel">
        
        <!-- Slide 1: Royal Wedding -->
        <div class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-100 z-10" data-slide-index="0">
            <!-- Background Image (Shown Clearly) -->
            <img src="{{ asset('media/categories/wedding.jpg') }}" alt="Royal Wedding Celebration" class="w-full h-full object-cover object-center transform scale-100 transition-transform duration-10000 ease-out">
            
            <!-- Soft Directional Scrim (Leaves photo clear and vibrant while ensuring readable text) -->
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/85 via-slate-950/45 to-transparent sm:w-3/4 lg:w-3/5"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent"></div>

            <!-- Slide Content -->
            <div class="absolute inset-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center z-20">
                <div class="max-w-2xl text-left space-y-4 sm:space-y-6">
                    <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-bold border border-white/30 shadow-lg">
                        <span class="w-2 h-2 rounded-full bg-pink-400 animate-pulse"></span>
                        <span>✨ Royal Weddings &amp; Receptions</span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black font-heading text-white tracking-tight leading-[1.1] drop-shadow-md">
                        Celebrate with <span class="gradient-text">Excellence &amp; Innovation</span>
                    </h1>

                    <p class="text-sm sm:text-lg text-slate-200 leading-relaxed font-normal max-w-xl drop-shadow-sm">
                        Create breathtaking digital wedding invitations, automate WhatsApp RSVPs, and verify guests at the entrance with instant QR gate passes in Tanzania.
                    </p>

                    <div class="flex flex-wrap items-center gap-3.5 pt-2">
                        <a href="{{ route('host.register') }}" class="btn-primary shadow-xl shadow-indigo-600/30">
                            Host Your Event Free &rarr;
                        </a>
                        <button type="button" onclick="openTrackModal()" class="px-5 py-3 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-sm backdrop-blur-md border border-white/30 transition-all flex items-center space-x-2 shadow-lg">
                            <svg class="w-4 h-4 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Track Application / RSVP</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2: Luxury VIP Fleet & Chauffeur Convoy -->
        <div class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0 pointer-events-none z-0" data-slide-index="1">
            <img src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=1920&q=85" alt="Luxury VIP Fleet Convoy" class="w-full h-full object-cover object-center transform scale-100 transition-transform duration-10000 ease-out">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/85 via-slate-950/45 to-transparent sm:w-3/4 lg:w-3/5"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent"></div>

            <div class="absolute inset-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center z-20">
                <div class="max-w-2xl text-left space-y-4 sm:space-y-6">
                    <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-bold border border-white/30 shadow-lg">
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                        <span>🚗 VIP Chauffeur Fleet &amp; Shuttles</span>
                    </div>

                    <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black font-heading text-white tracking-tight leading-[1.1] drop-shadow-md">
                        Arrive in Pure <span class="gradient-text-gold">Luxury &amp; Grandeur</span>
                    </h2>

                    <p class="text-sm sm:text-lg text-slate-200 leading-relaxed font-normal max-w-xl drop-shadow-sm">
                        Mercedes Maybach, Range Rover VIP, and 30-Seater Coasters with uniformed chauffeurs, fuel, and custom ribbon decorations for weddings &amp; VIP guests.
                    </p>

                    <div class="flex flex-wrap items-center gap-3.5 pt-2">
                        <a href="{{ route('car-hire.index') }}" class="btn-primary shadow-xl">
                            Explore VIP Fleet &rarr;
                        </a>
                        <a href="{{ $waFloatingUrl }}" target="_blank" class="px-5 py-3 rounded-xl bg-emerald-600/90 hover:bg-emerald-600 text-white font-bold text-sm backdrop-blur-md border border-white/30 transition-all flex items-center space-x-2 shadow-lg">
                            <span>Chat on WhatsApp</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3: Corporate Summits & Galas -->
        <div class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0 pointer-events-none z-0" data-slide-index="2">
            <img src="{{ asset('media/categories/corporate.jpg') }}" alt="Corporate Summits & Galas" class="w-full h-full object-cover object-center transform scale-100 transition-transform duration-10000 ease-out">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/85 via-slate-950/45 to-transparent sm:w-3/4 lg:w-3/5"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent"></div>

            <div class="absolute inset-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center z-20">
                <div class="max-w-2xl text-left space-y-4 sm:space-y-6">
                    <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-bold border border-white/30 shadow-lg">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                        <span>💼 Summits, Galas &amp; Conferences</span>
                    </div>

                    <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black font-heading text-white tracking-tight leading-[1.1] drop-shadow-md">
                        Flawless Gate Security &amp; <span class="gradient-text">Instant Passes</span>
                    </h2>

                    <p class="text-sm sm:text-lg text-slate-200 leading-relaxed font-normal max-w-xl drop-shadow-sm">
                        Admit 500+ guests without long reception lines. Tamper-proof QR gate passes scanned in 2 seconds from any smartphone with real-time arrival counts.
                    </p>

                    <div class="flex flex-wrap items-center gap-3.5 pt-2">
                        <a href="{{ route('host.register') }}" class="btn-primary shadow-xl">
                            Create Corporate Event &rarr;
                        </a>
                        <a href="{{ route('events') }}" class="px-5 py-3 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-sm backdrop-blur-md border border-white/30 transition-all shadow-lg">
                            View All Events
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 4: Designer Digital Invitations -->
        <div class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0 pointer-events-none z-0" data-slide-index="3">
            <img src="{{ asset('media/slider.jpg') }}" alt="Designer Digital Invitation Cards" class="w-full h-full object-cover object-center transform scale-100 transition-transform duration-10000 ease-out">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/85 via-slate-950/45 to-transparent sm:w-3/4 lg:w-3/5"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent"></div>

            <div class="absolute inset-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center z-20">
                <div class="max-w-2xl text-left space-y-4 sm:space-y-6">
                    <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-bold border border-white/30 shadow-lg">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>💌 Automated WhatsApp Invitations</span>
                    </div>

                    <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black font-heading text-white tracking-tight leading-[1.1] drop-shadow-md">
                        Personalized Invites &amp; <span class="gradient-text">Live Responses</span>
                    </h2>

                    <p class="text-sm sm:text-lg text-slate-200 leading-relaxed font-normal max-w-xl drop-shadow-sm">
                        Zero paper waste, zero printing delays. Send personalized interactive invitations directly to WhatsApp with music, countdowns, and live Google Maps directions.
                    </p>

                    <div class="flex flex-wrap items-center gap-3.5 pt-2">
                        <a href="{{ route('templates.index') }}" class="btn-primary shadow-xl">
                            Browse Templates &rarr;
                        </a>
                        <button type="button" onclick="openTrackModal()" class="px-5 py-3 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-sm backdrop-blur-md border border-white/30 transition-all shadow-lg">
                            Track Your Pass
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 5: Birthdays & Milestones -->
        <div class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0 pointer-events-none z-0" data-slide-index="4">
            <img src="{{ asset('media/categories/birthday.jpg') }}" alt="Birthdays & Milestone Celebrations" class="w-full h-full object-cover object-center transform scale-100 transition-transform duration-10000 ease-out">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/85 via-slate-950/45 to-transparent sm:w-3/4 lg:w-3/5"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent"></div>

            <div class="absolute inset-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center z-20">
                <div class="max-w-2xl text-left space-y-4 sm:space-y-6">
                    <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-bold border border-white/30 shadow-lg">
                        <span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>
                        <span>🎂 Birthdays &amp; Anniversaries</span>
                    </div>

                    <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black font-heading text-white tracking-tight leading-[1.1] drop-shadow-md">
                        Every Occasion <span class="gradient-text">Tailored Perfectly</span>
                    </h2>

                    <p class="text-sm sm:text-lg text-slate-200 leading-relaxed font-normal max-w-xl drop-shadow-sm">
                        From 1st birthdays to golden anniversaries and intimate kitchen showers, plan seamless celebrations with loved ones.
                    </p>

                    <div class="flex flex-wrap items-center gap-3.5 pt-2">
                        <a href="{{ route('host.register') }}" class="btn-primary shadow-xl">
                            Start Free &rarr;
                        </a>
                        <a href="{{ route('categories') }}" class="px-5 py-3 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-sm backdrop-blur-md border border-white/30 transition-all shadow-lg">
                            View All Occasions
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Navigation Arrows (Frosted Glass Styling) -->
    <button type="button" id="slider-prev-btn" class="absolute left-4 sm:left-8 top-1/2 -translate-y-1/2 z-30 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-white/20 hover:bg-white/35 backdrop-blur-md border border-white/30 text-white flex items-center justify-center transition-all duration-200 shadow-xl hover:scale-105 active:scale-95 focus:outline-none" aria-label="Previous Slide">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button type="button" id="slider-next-btn" class="absolute right-4 sm:right-8 top-1/2 -translate-y-1/2 z-30 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-white/20 hover:bg-white/35 backdrop-blur-md border border-white/30 text-white flex items-center justify-center transition-all duration-200 shadow-xl hover:scale-105 active:scale-95 focus:outline-none" aria-label="Next Slide">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
    </button>

    <!-- Slide Indicators / Dots -->
    <div class="absolute bottom-6 inset-x-0 z-30 flex items-center justify-center space-x-2.5" id="slider-dots">
        <button type="button" class="slider-dot w-8 h-2.5 rounded-full bg-white transition-all duration-300" data-dot-index="0" aria-label="Go to Slide 1"></button>
        <button type="button" class="slider-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white/70 transition-all duration-300" data-dot-index="1" aria-label="Go to Slide 2"></button>
        <button type="button" class="slider-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white/70 transition-all duration-300" data-dot-index="2" aria-label="Go to Slide 3"></button>
        <button type="button" class="slider-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white/70 transition-all duration-300" data-dot-index="3" aria-label="Go to Slide 4"></button>
        <button type="button" class="slider-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white/70 transition-all duration-300" data-dot-index="4" aria-label="Go to Slide 5"></button>
    </div>
</section>


<!-- ============================================
     3. SUPA-STYLE QUICK SERVICES HUB (4 CARDS LIKE SUPA DEGREES & DIPLOMAS)
     ============================================ -->
<section class="py-12 bg-white border-b border-slate-200/80 relative z-20 -mt-6 rounded-t-3xl shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Quick Hub 1: Digital Invitations -->
            <a href="{{ route('templates.index') }}" class="group p-5 rounded-2xl border border-slate-200/90 hover:border-indigo-500 bg-slate-50/50 hover:bg-indigo-50/30 transition-all duration-300 hover:-translate-y-1 shadow-xs hover:shadow-lg flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xl font-bold flex-shrink-0 group-hover:scale-110 transition-transform shadow-md shadow-indigo-500/20">
                    💌
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">Smart Cards</span>
                    <h3 class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">Digital Invitations</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Interactive cards with countdowns, music &amp; automated WhatsApp RSVPs.</p>
                </div>
            </a>

            <!-- Quick Hub 2: Gate Passes & Verification -->
            <div onclick="openTrackModal()" class="cursor-pointer group p-5 rounded-2xl border border-slate-200/90 hover:border-purple-500 bg-slate-50/50 hover:bg-purple-50/30 transition-all duration-300 hover:-translate-y-1 shadow-xs hover:shadow-lg flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-600 text-white flex items-center justify-center text-xl font-bold flex-shrink-0 group-hover:scale-110 transition-transform shadow-md shadow-purple-500/20">
                    📱
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-purple-600">Instant Check-in</span>
                    <h3 class="text-base font-bold text-slate-900 group-hover:text-purple-600 transition-colors">QR Gate Passes</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Tamper-proof gate passes scanned in 2 seconds with phone camera.</p>
                </div>
            </div>

            <!-- Quick Hub 3: VIP Car Hire Fleet -->
            <a href="{{ route('car-hire.index') }}" class="group p-5 rounded-2xl border border-slate-200/90 hover:border-pink-500 bg-slate-50/50 hover:bg-pink-50/30 transition-all duration-300 hover:-translate-y-1 shadow-xs hover:shadow-lg flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-pink-600 text-white flex items-center justify-center text-xl font-bold flex-shrink-0 group-hover:scale-110 transition-transform shadow-md shadow-pink-500/20">
                    🚗
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-pink-600">VIP Transport</span>
                    <h3 class="text-base font-bold text-slate-900 group-hover:text-pink-600 transition-colors">Luxury Car Hire</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Maybach, Range Rover &amp; 30-Seater Coasters with uniformed chauffeurs.</p>
                </div>
            </a>

            <!-- Quick Hub 4: Curated Venues & Decor -->
            <a href="{{ route('venues') }}" class="group p-5 rounded-2xl border border-slate-200/90 hover:border-emerald-500 bg-slate-50/50 hover:bg-emerald-50/30 transition-all duration-300 hover:-translate-y-1 shadow-xs hover:shadow-lg flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl font-bold flex-shrink-0 group-hover:scale-110 transition-transform shadow-md shadow-emerald-500/20">
                    📍
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Venues Hub</span>
                    <h3 class="text-base font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">Curated Venues</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Verified ballrooms, lush gardens &amp; beach resorts in Tanzania.</p>
                </div>
            </a>

        </div>
    </div>
</section>


<!-- ============================================
     5. FEATURED EVENT OCCASIONS & MILESTONES (SUPA CATALOG)
     ============================================ -->
<section class="py-24 bg-white relative overflow-hidden" id="milestones">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-14 gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 mb-2">
                    Occasion Catalog &bull; Sherehe na Matukio
                </span>
                <h2 class="text-3xl sm:text-4xl font-black font-heading text-slate-900">
                    Tailored for Every Milestone
                </h2>
                <p class="text-slate-600 text-sm mt-1">From royal weddings to executive summits, choose your occasion package.</p>
            </div>
            <div class="flex items-center space-x-3">
                @auth
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isEventAdmin() || in_array(auth()->user()->role ?? '', ['admin', 'super_admin']))
                        <a href="{{ route('admin.milestones.index') }}" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all shadow-md flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Manage in Admin</span>
                        </a>
                    @endif
                @endauth
                <a href="{{ route('categories') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition-all shadow-xs">
                    View All Occasions &rarr;
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $activeMilestones = isset($milestones) && $milestones->count() > 0 
                    ? $milestones 
                    : \App\Models\Milestone::where('is_active', true)->orderBy('sort_order', 'asc')->get();
            @endphp

            @forelse($activeMilestones as $m)
                @php
                    $theme = $m->theme_classes ?? [
                        'badge_bg' => 'bg-white/90 text-purple-800',
                        'border_hover' => 'hover:border-indigo-400/80',
                        'tag_bg' => 'bg-indigo-50 text-indigo-700',
                        'dot' => 'bg-pink-400',
                        'primary' => 'text-indigo-600',
                    ];
                    $btnUrl = $m->button_url 
                        ? (str_starts_with($m->button_url, 'http') || str_starts_with($m->button_url, '/') ? $m->button_url : url('/' . $m->button_url)) 
                        : route('categories', ['cat' => $m->slug]);
                    $imgSrc = $m->image_url_formatted;
                @endphp
                <a href="{{ $btnUrl }}" class="group relative bg-white rounded-3xl border border-slate-200 {{ $theme['border_hover'] }} shadow-md hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 overflow-hidden flex flex-col justify-between">
                    <!-- Image Banner (Visible & Crisp) -->
                    <div class="relative h-48 sm:h-52 w-full overflow-hidden bg-slate-900">
                        <img src="{{ $imgSrc }}" alt="{{ $m->title }}" class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700" onerror="this.onerror=null; this.src='{{ asset('media/categories/wedding.jpg') }}';">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-slate-950/20 to-transparent"></div>
                        
                        <!-- Floating Badges & Admin Edit Shortcut -->
                        <div class="absolute top-3.5 left-3.5 right-3.5 flex items-center justify-between z-10">
                            @if($m->badge)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-wide backdrop-blur-md {{ $theme['badge_bg'] }} shadow-sm">
                                    {{ $m->badge }}
                                </span>
                            @else
                                <span></span>
                            @endif

                            <div class="flex items-center space-x-2">
                                @auth
                                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isEventAdmin() || in_array(auth()->user()->role ?? '', ['admin', 'super_admin']))
                                        <span onclick="event.preventDefault(); window.location.href='{{ route('admin.milestones.edit', $m->id) }}';" class="px-2.5 py-1 rounded-lg bg-slate-900/85 hover:bg-indigo-600 text-white text-[11px] font-bold shadow-md transition-all backdrop-blur-md flex items-center space-x-1 cursor-pointer" title="Edit in Admin Panel">
                                            <span>✏️ Edit</span>
                                        </span>
                                    @endif
                                @endauth
                                <div class="w-10 h-10 rounded-xl bg-white/95 backdrop-blur-md flex items-center justify-center text-xl shadow-md border border-white/60 group-hover:rotate-6 transition-transform">
                                    {{ $m->icon ?: '✨' }}
                                </div>
                            </div>
                        </div>

                        @if($m->tagline)
                        <div class="absolute bottom-3 left-4 right-4 z-10">
                            <span class="text-[11px] font-semibold text-white/95 uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full {{ $theme['dot'] }}"></span> {{ $m->tagline }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="text-xl font-bold font-heading text-slate-900 group-hover:text-indigo-600 transition-colors">
                                {{ $m->title }}
                            </h3>
                            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed mt-2">
                                {{ $m->description }}
                            </p>
                            
                            @if(!empty($m->tags) && is_array($m->tags))
                            <div class="flex flex-wrap gap-1.5 mt-4 pt-3.5 border-t border-slate-100">
                                @foreach($m->tags as $t)
                                    <span class="text-[11px] font-medium {{ $theme['tag_bg'] }} px-2.5 py-0.5 rounded-md">{{ $t }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <div class="pt-2 flex items-center justify-between text-xs sm:text-sm font-semibold text-indigo-600 group-hover:text-indigo-700">
                            <span>{{ $m->button_text ?: 'Explore Features' }}</span>
                            <span class="w-8 h-8 rounded-full bg-indigo-50 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center transition-all duration-300 group-hover:translate-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-400">
                    No occasion categories configured yet.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ============================================
     6. POPULAR TEMPLATES GALLERY (SUPA SHOWCASE)
     ============================================ -->
<section class="py-24 bg-ambient-mesh-dark relative overflow-hidden text-white" id="templates-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->role === 'super_admin' || auth()->user()->isEventAdmin()))
        <!-- Superadmin In-Place Management Toolbar -->
        <div class="mb-8 rounded-2xl border border-purple-500/40 bg-gradient-to-r from-purple-950/80 via-indigo-950/80 to-slate-900/80 p-4 backdrop-blur-xl shadow-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-purple-600/30 border border-purple-400/40 text-purple-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-purple-200">Superadmin Control Active</h4>
                    </div>
                    <p class="text-[11px] text-slate-300">You can customize, add, or edit these invitation templates directly in the admin console.</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.templates.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-purple-600 hover:bg-purple-500 px-3.5 py-2 text-xs font-bold text-white shadow-md shadow-purple-600/30 transition-all">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span>Manage All Templates</span>
                </a>
                <a href="{{ route('admin.templates.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-pink-600 hover:bg-pink-500 px-3.5 py-2 text-xs font-bold text-white shadow-md shadow-pink-600/30 transition-all">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add New Template</span>
                </a>
            </div>
        </div>
        @endif

        <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-12 gap-4">
            <div>
                <span class="glass-tag-transparent px-3 py-1 rounded-full text-xs font-semibold text-pink-300 border border-pink-500/30 mb-2 inline-block">Designer Collection</span>
                <h2 class="text-3xl sm:text-4xl font-black font-heading text-white">
                    Popular Invitation Templates
                </h2>
                <p class="text-slate-300 text-sm mt-1">Ready-made layouts for instant online customization &amp; WhatsApp dispatch</p>
            </div>
            <div class="flex items-center gap-3">
                @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->role === 'super_admin' || auth()->user()->isEventAdmin()))
                    <a href="{{ route('admin.templates.index') }}" class="glass-tag-transparent hover:bg-purple-600/30 text-purple-200 font-bold text-xs px-4 py-2.5 rounded-xl border border-purple-400/30 shadow-md transition-all flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Admin Console</span>
                    </a>
                @endif
                <a href="{{ route('templates.index') }}" class="glass-tag-transparent hover:bg-white/20 text-white font-bold text-xs px-5 py-2.5 rounded-xl border border-white/20 shadow-md transition-all">
                    View All Templates &rarr;
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @if(isset($templates) && $templates->count() > 0)
                @foreach($templates as $template)
                    @php
                        $catKey = strtolower(str_replace([' ', '-'], '_', $template->category ?? 'wedding'));
                        $categoryFallbacks = [
                            'wedding' => asset('media/categories/wedding.jpg'),
                            'corporate' => asset('media/categories/corporate.jpg'),
                            'birthday' => asset('media/categories/birthday.jpg'),
                            'kitchen_party' => asset('media/categories/kitchen_party.jpg'),
                            'send_off' => asset('media/categories/sendoff.jpg'),
                            'sendoff' => asset('media/categories/sendoff.jpg'),
                            'traditional' => asset('media/categories/wedding.jpg'),
                            'concert' => asset('media/categories/concert.jpg'),
                        ];
                        $fallbackImg = $categoryFallbacks[$catKey] ?? asset('media/categories/wedding.jpg');

                        $cardImg = !empty($template->preview_image) 
                            ? storage_url($template->preview_image) 
                            : $fallbackImg;

                        // Category styling badges
                        $catStyles = [
                            'wedding' => 'bg-pink-500/20 text-pink-300 border-pink-400/40',
                            'corporate' => 'bg-indigo-500/20 text-indigo-300 border-indigo-400/40',
                            'birthday' => 'bg-amber-500/20 text-amber-300 border-amber-400/40',
                            'kitchen_party' => 'bg-purple-500/20 text-purple-300 border-purple-400/40',
                            'send_off' => 'bg-cyan-500/20 text-cyan-300 border-cyan-400/40',
                            'traditional' => 'bg-orange-500/20 text-orange-300 border-orange-400/40',
                        ];
                        $catBadgeClass = $catStyles[$catKey] ?? 'bg-white/10 text-white border-white/20';
                    @endphp
                    <div class="glass-card-transparent rounded-3xl overflow-hidden group border border-white/15 hover:border-pink-500/60 backdrop-blur-xl bg-white/5 hover:bg-white/10 shadow-2xl flex flex-col justify-between transition-all duration-300 relative">
                        <div class="relative h-60 bg-slate-950 overflow-hidden">
                            <img src="{{ $cardImg }}" alt="{{ $template->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-500"
                                 onerror="this.onerror=null; this.src='{{ $fallbackImg }}';"
                                 loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/25 to-transparent"></div>
                            
                            <!-- Superadmin Direct Edit Badge on Card -->
                            @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->role === 'super_admin' || auth()->user()->isEventAdmin()))
                                <div class="absolute top-3 left-3 z-20 flex items-center gap-1.5 bg-slate-950/90 backdrop-blur-md px-2.5 py-1 rounded-xl border border-purple-500/40 shadow-lg">
                                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <a href="{{ route('admin.templates.edit', $template->id) }}" class="text-[11px] font-bold text-purple-300 hover:text-white flex items-center gap-1 transition-colors" title="Edit template in Admin">
                                        <svg class="w-3 h-3 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        <span>Edit</span>
                                    </a>
                                    <span class="text-white/30 text-[10px]">|</span>
                                    <a href="{{ route('admin.templates.preview', $template->id) }}" target="_blank" class="text-[10px] font-medium text-slate-300 hover:text-pink-300 transition-colors" title="Live Preview">
                                        Preview
                                    </a>
                                </div>
                            @endif

                            <div class="absolute top-3 right-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold shadow-md border backdrop-blur-md {{ $catBadgeClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $template->category ?? 'Event')) }}
                                </span>
                            </div>

                            <div class="absolute bottom-3 left-4 right-4 text-white">
                                <span class="text-[10px] uppercase font-bold tracking-widest text-pink-300 block">Smart Digital Card</span>
                                <span class="text-sm font-bold truncate block">{{ $template->name }}</span>
                            </div>
                        </div>

                        <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2">
                                    <h3 class="font-bold font-heading text-white text-base group-hover:text-pink-400 transition-colors">{{ $template->name }}</h3>
                                    @if($template->price > 0 || $template->template_type === 'premium')
                                        <span class="text-[10px] font-black px-2 py-0.5 rounded-full bg-amber-400/20 text-amber-300 border border-amber-400/30">
                                            {{ number_format($template->price) }} {{ $template->currency ?: 'TZS' }}
                                        </span>
                                    @else
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                                            Included
                                        </span>
                                    @endif
                                </div>
                                <p class="text-slate-300 text-xs line-clamp-2 mt-1">{{ $template->description ?? 'Elegant digital card with interactive RSVP and QR gate pass.' }}</p>
                            </div>

                            <div class="space-y-3 pt-2">
                                <div class="flex items-center space-x-2 text-[11px] text-emerald-400 font-bold glass-tag-transparent px-2.5 py-1 rounded-lg">
                                    <span>✓ WhatsApp Ready</span>
                                    <span>&bull;</span>
                                    <span>Instant QR</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('templates.show', $template->id) }}" class="btn-primary flex-1 text-center text-xs py-2.5 shadow-md block">
                                        Use This Template
                                    </a>
                                    @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->role === 'super_admin' || auth()->user()->isEventAdmin()))
                                        <a href="{{ route('admin.templates.edit', $template->id) }}" class="p-2.5 rounded-xl border border-purple-500/40 bg-purple-600/20 hover:bg-purple-600/40 text-purple-300 hover:text-white transition-all shadow-md shrink-0" title="Edit Template in Superadmin">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Fallback Curated Card Mockups -->
                <div class="glass-card-transparent rounded-3xl overflow-hidden group border border-white/15 hover:border-pink-500/60 p-5 space-y-3">
                    <h3 class="font-bold text-white text-base">Elegance Royale</h3>
                    <p class="text-slate-300 text-xs">Golden foil accents, countdown timer, RSVP button.</p>
                    <a href="{{ route('templates.index') }}" class="btn-primary w-full text-center text-xs py-2 block">Preview Template</a>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- ============================================
     7. FEATURED CAR HIRE FLEET (VIP TRANSPORT)
     ============================================ -->
<section class="py-24 bg-ambient-mesh-dark relative overflow-hidden text-white border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-12 gap-4">
            <div>
                <span class="glass-tag-transparent px-3 py-1 rounded-full text-xs font-semibold text-indigo-300 border border-indigo-500/30 mb-2 inline-block">🚗 VIP Fleet Services</span>
                <h2 class="text-3xl sm:text-4xl font-black font-heading text-white">
                    Luxury Wedding &amp; Guest Transport
                </h2>
                <p class="text-slate-300 text-sm mt-1">Mercedes Maybach, Range Rover VIP, and 30-Seater Coasters with Uniformed Chauffeur &amp; Floral Decor</p>
            </div>
            <a href="{{ route('car-hire.index') }}" class="glass-tag-transparent hover:bg-white/20 text-white font-bold text-xs px-5 py-2.5 rounded-xl border border-white/20 shadow-md transition-all">
                Explore All Fleet &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @if(isset($vehicles) && $vehicles->count() > 0)
                @foreach($vehicles as $car)
                    @php
                        $badge = $car->category_badge;
                    @endphp
                    <div class="glass-card-transparent rounded-3xl overflow-hidden group hover:border-pink-500/60 transition-all duration-300 flex flex-col justify-between">
                        <div class="relative h-60 overflow-hidden bg-slate-950">
                            <img src="{{ $car->image_url_formatted }}" alt="{{ $car->name }}" class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent"></div>

                            <div class="absolute top-4 left-4 flex items-center space-x-2">
                                <span class="glass-tag-transparent px-2.5 py-1 rounded-full text-xs font-semibold shadow-md">
                                    {{ $badge['icon'] }} {{ $badge['label'] }}
                                </span>
                            </div>

                            @if($car->is_featured)
                                <div class="absolute top-4 right-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-400 text-slate-950 shadow-md">
                                        ★ Featured
                                    </span>
                                </div>
                            @endif

                            <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between text-white">
                                <div>
                                    <span class="text-xs text-slate-300 uppercase tracking-wider font-semibold block">Rental Rate</span>
                                    <span class="text-2xl font-black font-heading text-white">{{ $car->formatted_price }}</span>
                                    <span class="text-xs text-slate-300">/ day</span>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-2.5 py-1 glass-tag-transparent rounded-xl text-xs font-semibold text-white">
                                        👥 {{ $car->seating_capacity }} Passengers
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col justify-between space-y-5">
                            <div>
                                <h3 class="text-xl font-bold font-heading text-white group-hover:text-pink-400 transition-colors">
                                    <a href="{{ route('car-hire.show', $car->slug) }}">
                                        {{ $car->name }}
                                    </a>
                                </h3>
                                <p class="text-slate-300 text-xs mt-1.5 line-clamp-2 leading-relaxed">
                                    {{ $car->description }}
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs text-slate-300 glass-spec-box p-3 rounded-2xl">
                                <div class="flex items-center space-x-2">
                                    <span class="text-emerald-400">✓</span>
                                    <span>Chauffeur: <strong class="text-white">Included</strong></span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-emerald-400">✓</span>
                                    <span>Fuel: <strong class="text-white">Included</strong></span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-emerald-400">✓</span>
                                    <span>Deco: <strong class="text-white">Ribbons/Bows</strong></span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-pink-400">📍</span>
                                    <span class="truncate text-slate-200">{{ $car->location }}</span>
                                </div>
                            </div>

                            <div class="pt-2 flex items-center space-x-2.5">
                                <a href="{{ route('car-hire.show', $car->slug) }}" class="btn-primary flex-1 text-center text-xs py-2.5 shadow-md">
                                    Book This Car
                                </a>
                                
                                @php
                                    $waText = urlencode("Habari Tukio 360! 🚗 Ninapenda kufahamu zaidi kuhusu: *{$car->name}* (Bei: {$car->formatted_price}/siku). Je, lipo wazi?");
                                @endphp
                                <a href="https://wa.me/{{ $brandSettings['whatsapp_clean'] ?? '255662199071' }}?text={{ $waText }}" target="_blank" class="w-10 h-10 rounded-xl bg-emerald-500/20 hover:bg-emerald-500 text-emerald-400 hover:text-white border border-emerald-500/30 flex items-center justify-center transition-all duration-200 shadow-xs" title="Chat on WhatsApp">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.966.527 1.76.812 2.795.812 3.179 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.999-5.767-5.999zm3.385 8.163c-.144.405-.837.774-1.17.822-.312.043-.699.077-2.029-.472-1.696-.703-2.779-2.457-2.863-2.569-.084-.112-.686-.914-.686-1.743 0-.829.434-1.237.588-1.408.155-.171.339-.214.452-.214.113 0 .226.002.324.007.104.005.243-.039.38.291.144.344.492 1.2.535 1.288.043.088.071.191.014.305-.056.113-.085.184-.17.283-.085.1-.179.223-.255.3-.099.099-.202.207-.087.404.115.197.51 1.042 1.093 1.562.752.669 1.386.877 1.583.974.197.098.312.085.428-.049.115-.133.493-.574.625-.771.132-.197.265-.164.444-.099.18.066 1.139.537 1.336.635.197.099.329.148.378.23.049.083.049.48-.095.885z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>


<!-- ============================================
     9. HIGH-CONVERTING BOTTOM CTA (SUPA ADMISSION STRIP)
     ============================================ -->
<section class="py-20 relative gradient-mesh-hero text-white overflow-hidden">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-6">
        <span class="inline-block px-4 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-semibold uppercase tracking-wider border border-indigo-500/30">
            Usajili wa Mtandao Umefunguliwa &bull; Admissions Open
        </span>
        <h2 class="text-3xl sm:text-5xl font-black font-heading text-white">
            Ready to Begin Your Celebration Journey?
        </h2>
        <p class="text-slate-300 text-sm sm:text-base max-w-xl mx-auto leading-relaxed">
            Take the first step towards securing your digital event invitations &amp; luxury VIP fleet today. Submit your application online in less than 3 minutes.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
            <a href="{{ route('host.register') }}" class="btn-primary w-full sm:w-auto">
                Start Application Now &rarr;
            </a>
            <button type="button" onclick="openTrackModal()" class="btn-ghost-dark w-full sm:w-auto">
                Track Existing Status
            </button>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.slider-dot');
        const prevBtn = document.getElementById('slider-prev-btn');
        const nextBtn = document.getElementById('slider-next-btn');
        const carousel = document.getElementById('hero-carousel');
        
        if (!slides.length) return;

        let currentSlide = 0;
        let slideInterval = null;
        const totalSlides = slides.length;
        const intervalTime = 5500; // 5.5 seconds per slide

        function showSlide(index) {
            if (index < 0) {
                currentSlide = totalSlides - 1;
            } else if (index >= totalSlides) {
                currentSlide = 0;
            } else {
                currentSlide = index;
            }

            slides.forEach((slide, i) => {
                if (i === currentSlide) {
                    slide.classList.remove('opacity-0', 'pointer-events-none', 'z-0');
                    slide.classList.add('opacity-100', 'z-10');
                } else {
                    slide.classList.remove('opacity-100', 'z-10');
                    slide.classList.add('opacity-0', 'pointer-events-none', 'z-0');
                }
            });

            dots.forEach((dot, i) => {
                if (i === currentSlide) {
                    dot.classList.remove('w-2.5', 'bg-white/40');
                    dot.classList.add('w-8', 'bg-white');
                } else {
                    dot.classList.remove('w-8', 'bg-white');
                    dot.classList.add('w-2.5', 'bg-white/40');
                }
            });
        }

        function nextSlide() {
            showSlide(currentSlide + 1);
        }

        function prevSlide() {
            showSlide(currentSlide - 1);
        }

        function startAutoplay() {
            if (slideInterval) clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, intervalTime);
        }

        function stopAutoplay() {
            if (slideInterval) {
                clearInterval(slideInterval);
                slideInterval = null;
            }
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                nextSlide();
                startAutoplay();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                prevSlide();
                startAutoplay();
            });
        }

        dots.forEach((dot, i) => {
            dot.addEventListener('click', function () {
                showSlide(i);
                startAutoplay();
            });
        });

        // Pause on hover
        if (carousel) {
            carousel.addEventListener('mouseenter', stopAutoplay);
            carousel.addEventListener('mouseleave', startAutoplay);

            // Touch Swipe Support for mobile
            let startX = 0;
            let endX = 0;
            carousel.addEventListener('touchstart', function (e) {
                startX = e.changedTouches[0].screenX;
            }, { passive: true });

            carousel.addEventListener('touchend', function (e) {
                endX = e.changedTouches[0].screenX;
                if (startX - endX > 50) {
                    nextSlide(); // Swiped left -> next
                    startAutoplay();
                } else if (endX - startX > 50) {
                    prevSlide(); // Swiped right -> prev
                    startAutoplay();
                }
            }, { passive: true });
        }

        // Initialize slider
        showSlide(0);
        startAutoplay();
    });
</script>
@endpush
@endsection