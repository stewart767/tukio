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
    <div class="relative w-full h-[520px] sm:h-[600px] lg:h-[660px] overflow-hidden" id="hero-carousel">
        
        <!-- Slide 0: Royal Wedding -->
        <div class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-100 z-10" data-slide-index="0">
            <img src="{{ asset('media/categories/wedding.jpg') }}" alt="Royal Wedding Celebration" class="w-full h-full object-cover object-center transform scale-100 transition-transform duration-10000 ease-out">
            <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/70 to-black/40"></div>
            <div class="absolute inset-0 bg-black/25"></div>
        </div>

        <!-- Slide 1: Luxury VIP Fleet & Chauffeur Convoy -->
        <div class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0 pointer-events-none z-0" data-slide-index="1">
            <img src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=1920&q=85" alt="Luxury VIP Fleet Convoy" class="w-full h-full object-cover object-center transform scale-100 transition-transform duration-10000 ease-out">
            <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/70 to-black/40"></div>
            <div class="absolute inset-0 bg-black/25"></div>
        </div>

        <!-- Slide 2: Royal Send-Off & Reception -->
        <div class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0 pointer-events-none z-0" data-slide-index="2">
            <img src="{{ asset('media/categories/sendoff.jpg') }}" alt="Royal Send-Off & Reception" class="w-full h-full object-cover object-center transform scale-100 transition-transform duration-10000 ease-out">
            <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/70 to-black/40"></div>
            <div class="absolute inset-0 bg-black/25"></div>
        </div>

        <!-- Slide 3: Designer Digital Invitations -->
        <div class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0 pointer-events-none z-0" data-slide-index="3">
            <img src="{{ asset('media/slider.jpg') }}" alt="Designer Digital Invitation Cards" class="w-full h-full object-cover object-center transform scale-100 transition-transform duration-10000 ease-out">
            <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/70 to-black/40"></div>
            <div class="absolute inset-0 bg-black/25"></div>
        </div>

        <!-- Slide 4: Anniversaries & Celebrations -->
        <div class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0 pointer-events-none z-0" data-slide-index="4">
            <img src="{{ asset('media/categories/birthday.jpg') }}" alt="Milestone Celebrations" class="w-full h-full object-cover object-center transform scale-100 transition-transform duration-10000 ease-out">
            <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/70 to-black/40"></div>
            <div class="absolute inset-0 bg-black/25"></div>
        </div>

        <!-- Single Unified Hero Content Layer with Dynamic Typewriter (z-20) -->
        <div class="absolute inset-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center z-20 pointer-events-none">
            <div class="max-w-2xl text-left space-y-4 sm:space-y-6 pointer-events-auto">

                <!-- Dynamic Typewriter Headline -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black font-heading text-white tracking-tight leading-[1.15] drop-shadow-[0_4px_16px_rgba(0,0,0,0.8)] min-h-[110px] sm:min-h-[140px] flex flex-col justify-center">
                    <span class="text-white text-lg sm:text-2xl font-bold block mb-1 drop-shadow-[0_2px_8px_rgba(0,0,0,0.8)]">Sherehekea na</span>
                    <span class="flex items-center flex-wrap">
                        <span id="hero-typewriter-text" class="text-amber-300 font-black drop-shadow-[0_2px_12px_rgba(0,0,0,0.9)]"></span>
                        <span id="hero-typewriter-cursor" class="inline-block w-1.5 sm:w-2 h-8 sm:h-12 bg-amber-400 ml-1.5 animate-pulse shadow-md"></span>
                    </span>
                </h1>

                <!-- Dynamic Descriptive Copy -->
                <p id="hero-dynamic-desc" class="text-sm sm:text-lg text-white leading-relaxed font-semibold max-w-xl drop-shadow-[0_2px_8px_rgba(0,0,0,0.9)] min-h-[48px] transition-opacity duration-300">
                    Kadi za kisasa za kidijitali za harusi, usimamizi wa wageni kwa WhatsApp (RSVP), na msafara wa kifahari wa magari ya maharusi Tanzania.
                </p>

                <!-- Action CTA Buttons -->
                <div class="flex flex-wrap items-center gap-3.5 pt-2">
                    <a href="{{ route('host.register') }}" class="btn-primary shadow-2xl">
                        Sajili Sherehe Yako &rarr;
                    </a>
                    <button type="button" onclick="openTrackModal()" class="px-5 py-3 rounded-xl bg-white/20 hover:bg-white/30 text-white font-bold text-sm backdrop-blur-md border border-white/30 transition-all flex items-center space-x-2 shadow-xl hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Fuatilia Kadi &bull; Track RSVP</span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Slide Indicators / Dots (No Next/Prev Buttons) -->
    <div class="absolute bottom-6 inset-x-0 z-30 flex items-center justify-center space-x-2.5" id="slider-dots">
        <button type="button" class="slider-dot w-8 h-2.5 rounded-full bg-white transition-all duration-300" data-dot-index="0" aria-label="Go to Slide 1"></button>
        <button type="button" class="slider-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white/70 transition-all duration-300" data-dot-index="1" aria-label="Go to Slide 2"></button>
        <button type="button" class="slider-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white/70 transition-all duration-300" data-dot-index="2" aria-label="Go to Slide 3"></button>
        <button type="button" class="slider-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white/70 transition-all duration-300" data-dot-index="3" aria-label="Go to Slide 4"></button>
        <button type="button" class="slider-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white/70 transition-all duration-300" data-dot-index="4" aria-label="Go to Slide 5"></button>
    </div>
</section>


<!-- ============================================
     3. QUICK SERVICES HUB
     ============================================ -->
<section class="py-12 bg-[#f3ffe3] border-b border-slate-200/80 relative z-20 -mt-6 rounded-t-3xl shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Quick Hub 1: Digital Invitations -->
            <a href="{{ route('templates.index') }}" class="group p-5 rounded-2xl border border-slate-200/90 hover:border-emerald-600 bg-white/80 hover:bg-white transition-all duration-300 hover:-translate-y-1 shadow-xs hover:shadow-lg flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-700 text-white flex items-center justify-center font-bold flex-shrink-0 group-hover:scale-110 transition-transform shadow-md shadow-emerald-700/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">Kadi za Kidijitali</h3>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">Kadi za kisasa zenye countdown, muziki na uthibitisho wa WhatsApp (RSVP).</p>
                </div>
            </a>

            <!-- Quick Hub 2: Gate Passes & Verification -->
            <div onclick="openTrackModal()" class="cursor-pointer group p-5 rounded-2xl border border-slate-200/90 hover:border-emerald-600 bg-white/80 hover:bg-white transition-all duration-300 hover:-translate-y-1 shadow-xs hover:shadow-lg flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-teal-700 text-white flex items-center justify-center font-bold flex-shrink-0 group-hover:scale-110 transition-transform shadow-md shadow-teal-700/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">Uhakiki wa Wageni (QR Pass)</h3>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">Kila mgeni anapata kadi yenye QR code inayohakikiwa haraka mlangoni.</p>
                </div>
            </div>

            <!-- Quick Hub 3: VIP Car Hire Fleet -->
            <a href="{{ route('car-hire.index') }}" class="group p-5 rounded-2xl border border-slate-200/90 hover:border-emerald-600 bg-white/80 hover:bg-white transition-all duration-300 hover:-translate-y-1 shadow-xs hover:shadow-lg flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-bold flex-shrink-0 group-hover:scale-110 transition-transform shadow-md shadow-slate-900/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">Magari ya Mahurusi &amp; Wageni</h3>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">Maybach, Range Rover na Coasters zenye madereva na mapambo ya maua.</p>
                </div>
            </a>

            <!-- Quick Hub 4: Curated Venues & Decor -->
            <a href="{{ route('venues') }}" class="group p-5 rounded-2xl border border-slate-200/90 hover:border-emerald-600 bg-white/80 hover:bg-white transition-all duration-300 hover:-translate-y-1 shadow-xs hover:shadow-lg flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-bold flex-shrink-0 group-hover:scale-110 transition-transform shadow-md shadow-emerald-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">Kumbi za Sherehe</h3>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">Kumbi za kisasa, bustani (gardens), na maeneo ya ufukweni kote Tanzania.</p>
                </div>
            </a>

        </div>
    </div>
</section>


<!-- ============================================
     5. FEATURED EVENT OCCASIONS & MILESTONES (SUPA CATALOG)
     ============================================ -->
<section class="py-24 bg-[#f3ffe3] relative overflow-hidden" id="milestones">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-14 gap-4">
            <div>
                <h2 class="text-3xl sm:text-4xl font-black font-heading text-slate-900">
                    Sherehe na Matukio Yote
                </h2>
                <p class="text-slate-700 text-sm mt-1 font-semibold">Kuanzia harusi za kifalme, send-off, kitchen party, hadi sherehe za kuzaliwa — chagua huduma inayofaa tukio lako.</p>
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
                        'dot' => 'bg-indigo-400',
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
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            <span>Edit</span>
                                        </span>
                                    @endif
                                @endauth
                                <div class="w-10 h-10 rounded-xl bg-white/95 backdrop-blur-md flex items-center justify-center text-indigo-600 shadow-md border border-white/60 group-hover:rotate-6 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
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
<section class="py-24 bg-[#f3ffe3] relative overflow-hidden text-slate-800" id="templates-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->role === 'super_admin' || auth()->user()->isEventAdmin()))
        <!-- Superadmin In-Place Management Toolbar -->
        <div class="mb-8 rounded-2xl border border-purple-200 bg-white p-4 shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-purple-100 border border-purple-200 text-purple-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-purple-900">Superadmin Control Active</h4>
                    </div>
                    <p class="text-[11px] text-slate-500">You can customize, add, or edit these invitation templates directly in the admin console.</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.templates.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-purple-600 hover:bg-purple-700 px-3.5 py-2 text-xs font-bold text-white shadow-sm transition-all">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span>Manage All Templates</span>
                </a>
                <a href="{{ route('admin.templates.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 hover:bg-black px-3.5 py-2 text-xs font-bold text-white shadow-sm transition-all">
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
                <h2 class="text-3xl sm:text-4xl font-black font-heading text-slate-900">
                    Sampuli za Kadi za Mwaliko
                </h2>
                <p class="text-slate-700 text-sm mt-1 font-semibold">Chagua muundo wa kadi unaoupenda kisha uweke majina na picha za sherehe yako kwa urahisi.</p>
            </div>
            <div class="flex items-center gap-3">
                @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->role === 'super_admin' || auth()->user()->isEventAdmin()))
                    <a href="{{ route('admin.templates.index') }}" class="bg-white hover:bg-slate-50 text-purple-700 font-bold text-xs px-4 py-2.5 rounded-xl border border-purple-200 shadow-sm transition-all flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Admin Console</span>
                    </a>
                @endif
                <a href="{{ route('templates.index') }}" class="bg-white hover:bg-slate-50 text-slate-800 font-bold text-xs px-5 py-2.5 rounded-xl border border-slate-200 shadow-sm transition-all">
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
                            'wedding' => 'bg-indigo-500/80 text-white border-indigo-400/50',
                            'corporate' => 'bg-slate-800/80 text-white border-slate-700/50',
                            'birthday' => 'bg-amber-500/80 text-white border-amber-400/50',
                            'kitchen_party' => 'bg-purple-600/80 text-white border-purple-400/50',
                            'send_off' => 'bg-cyan-600/80 text-white border-cyan-400/50',
                            'traditional' => 'bg-orange-600/80 text-white border-orange-400/50',
                        ];
                        $catBadgeClass = $catStyles[$catKey] ?? 'bg-slate-900/80 text-white border-slate-700/50';
                    @endphp
                    <div class="bg-white rounded-3xl overflow-hidden group border border-slate-200/80 hover:border-slate-300 hover:shadow-xl shadow-md transition-all duration-300 flex flex-col justify-between relative">
                        <div class="relative h-60 bg-slate-100 overflow-hidden">
                            <img src="{{ $cardImg }}" alt="{{ $template->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                 onerror="this.onerror=null; this.src='{{ $fallbackImg }}';"
                                 loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                            
                            <!-- Superadmin Direct Edit Badge on Card -->
                            @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->role === 'super_admin' || auth()->user()->isEventAdmin()))
                                <div class="absolute top-3 left-3 z-20 flex items-center gap-1.5 bg-slate-900/90 backdrop-blur-md px-2.5 py-1 rounded-xl border border-slate-700 shadow-lg">
                                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                                    <a href="{{ route('admin.templates.edit', $template->id) }}" class="text-[11px] font-bold text-slate-200 hover:text-white flex items-center gap-1 transition-colors" title="Edit template in Admin">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        <span>Edit</span>
                                    </a>
                                    <span class="text-white/40 text-[10px]">|</span>
                                    <a href="{{ route('admin.templates.preview', $template->id) }}" target="_blank" class="text-[10px] font-medium text-slate-300 hover:text-white transition-colors" title="Live Preview">
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
                                <span class="text-[10px] uppercase font-bold tracking-widest text-emerald-200 block">Kadi ya Mwaliko</span>
                                <span class="text-sm font-bold truncate block">{{ $template->name }}</span>
                            </div>
                        </div>

                        <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2">
                                    <h3 class="font-bold font-heading text-slate-900 text-base group-hover:text-emerald-700 transition-colors">{{ $template->name }}</h3>
                                    @if($template->price > 0 || $template->template_type === 'premium')
                                        <span class="text-[10px] font-black px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                            {{ number_format($template->price) }} {{ $template->currency ?: 'TZS' }}
                                        </span>
                                    @else
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Included
                                        </span>
                                    @endif
                                </div>
                                <p class="text-slate-600 text-xs line-clamp-2 mt-1">{{ $template->description ?? 'Kadi nzuri ya kisasa yenye uthibitisho wa RSVP na QR pass ya mlangoni.' }}</p>
                            </div>

                            <div class="space-y-3 pt-2">
                                <div class="flex items-center space-x-2 text-[11px] text-emerald-800 font-bold bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-lg">
                                    <span>Mwaliko wa WhatsApp</span>
                                    <span>&bull;</span>
                                    <span>QR Gate Pass</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('templates.show', $template->id) }}" class="btn-primary flex-1 text-center text-xs py-2.5 shadow-sm block">
                                        Use This Template
                                    </a>
                                    @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->role === 'super_admin' || auth()->user()->isEventAdmin()))
                                        <a href="{{ route('admin.templates.edit', $template->id) }}" class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 transition-all shadow-xs shrink-0" title="Edit Template in Superadmin">
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
                <div class="bg-white rounded-3xl overflow-hidden group border border-slate-200 p-5 space-y-3 shadow-md">
                    <h3 class="font-bold text-slate-900 text-base">Elegance Royale</h3>
                    <p class="text-slate-500 text-xs">Golden foil accents, countdown timer, RSVP button.</p>
                    <a href="{{ route('templates.index') }}" class="btn-primary w-full text-center text-xs py-2 block">Preview Template</a>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- ============================================
     7. FEATURED CAR HIRE FLEET (VIP TRANSPORT)
     ============================================ -->
<section class="py-24 bg-[#f3ffe3] relative overflow-hidden text-slate-800 border-t border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-12 gap-4">
            <div>
                <h2 class="text-3xl sm:text-4xl font-black font-heading text-slate-900">
                    Magari ya Kifahari ya Mahurusi na Wageni
                </h2>
                <p class="text-slate-700 text-sm mt-1 font-semibold">Mercedes Maybach, Range Rover, na Coasters zenye madereva nadhifu na mapambo ya maua ya sherehe.</p>
            </div>
            <a href="{{ route('car-hire.index') }}" class="bg-white hover:bg-slate-50 text-slate-800 font-bold text-xs px-5 py-2.5 rounded-xl border border-slate-200 shadow-sm transition-all">
                Explore All Fleet &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @if(isset($vehicles) && $vehicles->count() > 0)
                @foreach($vehicles as $car)
                    @php
                        $badge = $car->category_badge;
                    @endphp
                    <div class="bg-white rounded-3xl overflow-hidden group border border-slate-200/80 hover:border-slate-300 hover:shadow-xl shadow-md transition-all duration-300 flex flex-col justify-between">
                        <div class="relative h-60 overflow-hidden bg-slate-100">
                            <img src="{{ $car->image_url_formatted }}" alt="{{ $car->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80';">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent"></div>

                            <div class="absolute top-4 left-4 flex items-center space-x-2">
                                <span class="bg-slate-900/80 backdrop-blur-md text-white border border-slate-700/50 px-2.5 py-1 rounded-full text-xs font-semibold shadow-md">
                                    {{ $badge['label'] }}
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
                                    <span class="text-xs text-slate-200 uppercase tracking-wider font-semibold block">Rental Rate</span>
                                    <span class="text-2xl font-black font-heading text-white">{{ $car->formatted_price }}</span>
                                    <span class="text-xs text-slate-200">/ day</span>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-2.5 py-1 bg-white/20 backdrop-blur-md rounded-xl text-xs font-semibold text-white border border-white/30">
                                        {{ $car->seating_capacity }} Passengers
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col justify-between space-y-5">
                            <div>
                                <h3 class="text-xl font-bold font-heading text-slate-900 group-hover:text-indigo-600 transition-colors">
                                    <a href="{{ route('car-hire.show', $car->slug) }}">
                                        {{ $car->name }}
                                    </a>
                                </h3>
                                <p class="text-slate-500 text-xs mt-1.5 line-clamp-2 leading-relaxed">
                                    {{ $car->description }}
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs text-slate-600 bg-slate-50 border border-slate-200/80 p-3 rounded-2xl">
                                <div class="flex items-center space-x-2">
                                    <span class="text-emerald-600">✓</span>
                                    <span>Chauffeur: <strong class="text-slate-800">Included</strong></span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-emerald-600">✓</span>
                                    <span>Fuel: <strong class="text-slate-800">Included</strong></span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-emerald-600">✓</span>
                                    <span>Deco: <strong class="text-slate-800">Ribbons/Bows</strong></span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-indigo-600">
                                        <svg class="w-3.5 h-3.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    </span>
                                    <span class="truncate text-slate-700">{{ $car->location }}</span>
                                </div>
                            </div>

                            <div class="pt-2 flex items-center space-x-2.5">
                                <a href="{{ route('car-hire.show', $car->slug) }}" class="btn-primary flex-1 text-center text-xs py-2.5 shadow-sm">
                                    Book This Car
                                </a>
                                
                                @php
                                    $waText = urlencode("Habari Tukio 360! Ninapenda kufahamu zaidi kuhusu: *{$car->name}* (Bei: {$car->formatted_price}/siku). Je, lipo wazi?");
                                @endphp
                                <a href="https://wa.me/{{ $brandSettings['whatsapp_clean'] ?? '255662199071' }}?text={{ $waText }}" target="_blank" class="w-10 h-10 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 hover:text-emerald-800 border border-emerald-200 flex items-center justify-center transition-all duration-200 shadow-xs" title="Chat on WhatsApp">
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
     9. HIGH-CONVERTING BOTTOM CTA SECTION
     ============================================ -->
<section class="py-20 relative bg-[#f3ffe3] text-slate-900 overflow-hidden border-t border-slate-200/80">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-6">
        <h2 class="text-3xl sm:text-5xl font-black font-heading text-slate-900">
            Tayari Kuanza Maandalizi ya Sherehe Yako?
        </h2>
        <p class="text-slate-800 text-sm sm:text-base max-w-xl mx-auto leading-relaxed font-semibold">
            Pata kadi za kisasa za kidijitali, usimamizi mzuri wa wageni kwa WhatsApp (RSVP), na msafara wa magari ya kifahari ya maharusi kwa urahisi ndani ya dakika chache.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
            <a href="{{ route('host.register') }}" class="btn-primary w-full sm:w-auto shadow-md">
                Sajili Sherehe Yako Sasa &rarr;
            </a>
            <button type="button" onclick="openTrackModal()" class="px-6 py-3.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-800 font-bold text-sm border border-slate-300 shadow-sm transition-all w-full sm:w-auto">
                Fuatilia Kadi &amp; RSVP
            </button>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- 1. HERO SLIDER AUTO-ROTATION (FASTER SPEED, NO BUTTONS) ---
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.slider-dot');
        const carousel = document.getElementById('hero-carousel');
        
        if (slides.length) {
            let currentSlide = 0;
            let slideInterval = null;
            const totalSlides = slides.length;
            const intervalTime = 3200; // Fast 3.2s per slide

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

            dots.forEach((dot, i) => {
                dot.addEventListener('click', function () {
                    showSlide(i);
                    startAutoplay();
                });
            });

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
                    if (startX - endX > 40) {
                        nextSlide();
                        startAutoplay();
                    } else if (endX - startX > 40) {
                        prevSlide();
                        startAutoplay();
                    }
                }, { passive: true });
            }

            showSlide(0);
            startAutoplay();
        }

        // --- 2. DYNAMIC TYPEWRITER EFFECT (WORDS TYPING & CHANGING) ---
        const typewriterEl = document.getElementById('hero-typewriter-text');
        const dynamicDescEl = document.getElementById('hero-dynamic-desc');

        if (typewriterEl) {
            const phrases = [
                {
                    title: "Kadi za Kidijitali za Harusi",
                    desc: "Kadi za kisasa zenye muziki, countdown ya siku, na ramani ya ukumbi inayoelekeza wageni kwa Google Maps."
                },
                {
                    title: "Msafara wa Magari ya Kifahari",
                    desc: "Mercedes Maybach, Range Rover VIP, na Coasters zenye madereva nadhifu na mapambo ya maua ya maharusi."
                },
                {
                    title: "Sherehe za Send-Off & Harusi",
                    desc: "Usimamizi kamili wa Send-Off, Kitchen Party na harusi za kipekee zenye heshima na hadhi ya juu."
                },
                {
                    title: "Usimamizi wa Wageni kwa WhatsApp",
                    desc: "Tuma mialiko na pokea majibu ya wageni (RSVP) papo hapo bila kupoteza muda wala karatasi."
                },
                {
                    title: "Tiketi & QR Gate Passes za Uhakika",
                    desc: "Hakiki wageni mlangoni kwa sekunde 2 kwa kutumia simu yoyote bila foleni wala wageni wasioalikwa."
                }
            ];

            let phraseIndex = 0;
            let charIndex = 0;
            let isDeleting = false;
            const typingSpeed = 55;
            const deletingSpeed = 28;
            const pauseAfterType = 1800;

            function updateTypewriter() {
                const current = phrases[phraseIndex];
                const fullText = current.title;

                if (isDeleting) {
                    typewriterEl.textContent = fullText.substring(0, charIndex - 1);
                    charIndex--;
                } else {
                    typewriterEl.textContent = fullText.substring(0, charIndex + 1);
                    charIndex++;
                }

                let currentDelay = isDeleting ? deletingSpeed : typingSpeed;

                if (!isDeleting && charIndex === fullText.length) {
                    currentDelay = pauseAfterType;
                    isDeleting = true;
                } else if (isDeleting && charIndex === 0) {
                    isDeleting = false;
                    phraseIndex = (phraseIndex + 1) % phrases.length;
                    currentDelay = 300;

                    if (dynamicDescEl) {
                        dynamicDescEl.style.opacity = '0';
                        setTimeout(() => {
                            dynamicDescEl.textContent = phrases[phraseIndex].desc;
                            dynamicDescEl.style.opacity = '1';
                        }, 200);
                    }
                }

                setTimeout(updateTypewriter, currentDelay);
            }

            // Start typing immediately
            setTimeout(updateTypewriter, 200);
        }
    });
</script>
@endpush
@endsection