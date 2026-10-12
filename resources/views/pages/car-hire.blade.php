@extends('layouts.landing')

@section('title', 'Luxury Wedding & Event Car Hire - Mercedes Maybach, Range Rover, Coasters & VIP Buses in Tanzania')
@section('description', 'Book luxury bridal cars (Mercedes-Benz S-Class Maybach, Range Rover, Alphard) and guest transport (30-Seater Toyota Coasters, 50-Seater VIP Buses) with suited chauffeurs and floral ribbon decorations in Tanzania.')
@section('nav_dark', true)

@section('content')
<div class="min-h-screen bg-ambient-mesh-dark text-white relative overflow-hidden font-sans selection:bg-pink-500 selection:text-white" style="background-color: #070a13 !important; background-image: radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.45) 0px, transparent 55%), radial-gradient(at 100% 10%, rgba(236, 72, 153, 0.4) 0px, transparent 50%), radial-gradient(at 50% 45%, rgba(139, 92, 246, 0.3) 0px, transparent 50%), radial-gradient(at 10% 85%, rgba(6, 182, 212, 0.3) 0px, transparent 50%), radial-gradient(at 95% 90%, rgba(245, 158, 11, 0.28) 0px, transparent 50%) !important; color: #ffffff !important;">
    
    <!-- Ambient Multidimensional Radiant Light Glows -->
    <div class="absolute top-10 left-1/4 -translate-x-1/2 w-[650px] h-[650px] bg-indigo-600/25 rounded-full blur-[150px] pointer-events-none"></div>
    <div class="absolute top-1/4 right-5 w-[600px] h-[600px] bg-pink-600/20 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-2/3 left-10 w-[550px] h-[550px] bg-purple-600/20 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute bottom-20 right-1/4 w-[500px] h-[500px] bg-amber-500/15 rounded-full blur-[130px] pointer-events-none"></div>
    
    <!-- Subtle Grid Mesh Overlay -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_10%,#000_70%,transparent_100%)] pointer-events-none"></div>

    <!-- ============================================
         1. HERO SECTION - LUXURY SHOWROOM STAGE
         ============================================ -->
    <section class="relative pt-8 pb-16 lg:pt-16 lg:pb-24 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12 lg:gap-10 items-center">
                
                <!-- Left Hero Content (7 Cols) -->
                <div class="lg:col-span-7 text-center lg:text-left space-y-7">
                    
                    <!-- Luminous Luxury Badge -->
                    <div class="inline-flex items-center space-x-2.5 px-4 py-2 rounded-full border border-pink-500/40 text-pink-300 text-xs font-semibold tracking-wide shadow-xl shadow-pink-500/20" style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(16px);">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-pink-500"></span>
                        </span>
                        <span>✨ Tanzania's #1 Wedding &amp; VIP Fleet Showroom</span>
                    </div>

                    <!-- Main Title with Iridescent Gradient -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black font-heading tracking-tight leading-[1.12]" style="color: #ffffff; text-shadow: 0 4px 20px rgba(0,0,0,0.6);">
                        Arrive in Grandeur With 
                        <span class="block mt-2 font-black" style="background: linear-gradient(135deg, #fbcfe8 0%, #ec4899 45%, #a855f7 70%, #818cf8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            Luxury Wedding &amp; Guest Fleet
                        </span>
                    </h1>

                    <!-- Evocative Narrative -->
                    <p class="text-base sm:text-lg max-w-2xl mx-auto lg:mx-0 leading-relaxed font-normal" style="color: #cbd5e1; text-shadow: 0 2px 10px rgba(0,0,0,0.4);">
                        From prestigious <strong style="color: #ffffff;">Bride &amp; Groom flagship sedans &amp; SUVs</strong> to spacious <strong style="color: #ffffff;">30-Seater Coasters &amp; 50-Seater Charter Buses</strong> for your beloved guests. Complete with uniformed chauffeurs, custom white satin ribbons &amp; fresh floral arrangements.
                    </p>

                    <!-- Fast Fleet Search & Booking Capsule -->
                    <div class="p-3 sm:p-3.5 rounded-2xl sm:rounded-3xl max-w-2xl mx-auto lg:mx-0" style="background: rgba(15, 23, 42, 0.88); backdrop-filter: blur(24px); border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: 0 20px 50px rgba(0,0,0,0.6);">
                        <form action="{{ route('car-hire.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2.5">
                            <div class="relative w-full flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Maybach, Range Rover, Coaster, Alphard..." class="w-full pl-10 pr-3 py-3 text-xs sm:text-sm rounded-xl focus:outline-none transition-all placeholder-slate-400" style="background: rgba(2, 6, 23, 0.75); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.16);">
                            </div>

                            <select name="category" class="w-full sm:w-auto px-3.5 py-3 text-xs sm:text-sm rounded-xl focus:outline-none cursor-pointer" style="background: rgba(15, 23, 42, 0.95); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.2);">
                                <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>🚗 All Vehicle Types</option>
                                <option value="luxury_bride_groom" {{ request('category') == 'luxury_bride_groom' ? 'selected' : '' }}>👰 Luxury Bride &amp; Groom</option>
                                <option value="guest_coaster_bus" {{ request('category') == 'guest_coaster_bus' ? 'selected' : '' }}>🚌 Guest Coasters &amp; Buses</option>
                                <option value="vip_suv_convoy" {{ request('category') == 'vip_suv_convoy' ? 'selected' : '' }}>👑 VIP SUVs &amp; Convoys</option>
                            </select>

                            <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-pink-500 via-purple-600 to-indigo-600 hover:from-pink-400 hover:to-indigo-500 text-white text-xs sm:text-sm font-bold rounded-xl shadow-lg shadow-pink-500/30 transition-all duration-300 flex items-center justify-center space-x-2 transform hover:-translate-y-0.5">
                                <span>Find Fleet</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </form>
                    </div>

                    <!-- 4 VIP Service Guarantees (Glass Capsules) -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2 max-w-2xl mx-auto lg:mx-0">
                        <div class="p-3.5 rounded-2xl text-center lg:text-left transition-all hover:scale-102" style="background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(99, 102, 241, 0.35); box-shadow: 0 10px 25px rgba(0,0,0,0.4);">
                            <div class="text-xl">👔</div>
                            <div class="text-xs font-bold text-white mt-1">Suited Chauffeur</div>
                            <div class="text-[11px] text-slate-300">Included on all cars</div>
                        </div>
                        <div class="p-3.5 rounded-2xl text-center lg:text-left transition-all hover:scale-102" style="background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(236, 72, 153, 0.35); box-shadow: 0 10px 25px rgba(0,0,0,0.4);">
                            <div class="text-xl">🎀</div>
                            <div class="text-xs font-bold text-pink-300 mt-1">Floral Deco</div>
                            <div class="text-[11px] text-slate-300">White ribbons &amp; bows</div>
                        </div>
                        <div class="p-3.5 rounded-2xl text-center lg:text-left transition-all hover:scale-102" style="background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(139, 92, 246, 0.35); box-shadow: 0 10px 25px rgba(0,0,0,0.4);">
                            <div class="text-xl">🛡️</div>
                            <div class="text-xs font-bold text-indigo-300 mt-1">Backup Fleet</div>
                            <div class="text-[11px] text-slate-300">Standby replacement</div>
                        </div>
                        <div class="p-3.5 rounded-2xl text-center lg:text-left transition-all hover:scale-102" style="background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(245, 158, 11, 0.35); box-shadow: 0 10px 25px rgba(0,0,0,0.4);">
                            <div class="text-xl">⏰</div>
                            <div class="text-xs font-bold text-amber-300 mt-1">1-Hr Early</div>
                            <div class="text-[11px] text-slate-300">Punctual dispatch</div>
                        </div>
                    </div>

                    <!-- Social Proof Trust Line -->
                    <div class="flex items-center justify-center lg:justify-start space-x-3 text-xs text-slate-300 pt-1">
                        <div class="flex -space-x-2">
                            <img class="inline-block h-7 w-7 rounded-full ring-2 ring-slate-900 object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" alt="Couple">
                            <img class="inline-block h-7 w-7 rounded-full ring-2 ring-slate-900 object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&q=80" alt="Couple">
                            <img class="inline-block h-7 w-7 rounded-full ring-2 ring-slate-900 object-cover" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=100&q=80" alt="Couple">
                        </div>
                        <div class="flex items-center space-x-1">
                            <span class="text-amber-400 font-bold">★★★★★ 4.9/5</span>
                            <span>&bull; Trusted by 480+ Couples &amp; Event Committees across Tanzania</span>
                        </div>
                    </div>

                </div>

                <!-- Right Hero: Interactive Luxury Fleet Showcase Stage (5 Cols) -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        
                        <!-- Interactive Vehicle Stage Container -->
                        <div class="p-4 sm:p-5 rounded-3xl relative group overflow-hidden" style="background: rgba(15, 23, 42, 0.88); backdrop-filter: blur(24px); border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: 0 25px 60px rgba(0,0,0,0.7);">
                            
                            <!-- Ambient Stage Accent Glow -->
                            <div class="absolute -top-20 -right-20 w-56 h-56 bg-pink-500/25 rounded-full blur-3xl pointer-events-none"></div>
                            <div class="absolute -bottom-20 -left-20 w-56 h-56 bg-indigo-500/25 rounded-full blur-3xl pointer-events-none"></div>

                            <!-- Stage Quick Switcher Tabs (Clickable Flagship Selector) -->
                            <div class="relative z-20 mb-3.5 flex items-center justify-between gap-1.5 p-1.5 rounded-2xl text-[11px] font-bold" style="background: rgba(2, 6, 23, 0.8); border: 1px solid rgba(255, 255, 255, 0.15);">
                                <button type="button" onclick="switchHeroShowcase(0)" id="hero-tab-0" class="hero-tab-btn flex-1 py-2 px-2 rounded-xl text-center transition-all" style="background: linear-gradient(135deg, #ec4899, #8b5cf6); color: #ffffff; box-shadow: 0 4px 15px rgba(236,72,153,0.4); font-weight: 700;">
                                    👰 Maybach
                                </button>
                                <button type="button" onclick="switchHeroShowcase(1)" id="hero-tab-1" class="hero-tab-btn flex-1 py-2 px-2 rounded-xl text-center transition-all text-slate-300 hover:text-white" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);">
                                    🚙 Range Rover
                                </button>
                                <button type="button" onclick="switchHeroShowcase(2)" id="hero-tab-2" class="hero-tab-btn flex-1 py-2 px-2 rounded-xl text-center transition-all text-slate-300 hover:text-white" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);">
                                    🚌 30-Coaster
                                </button>
                                <button type="button" onclick="switchHeroShowcase(3)" id="hero-tab-3" class="hero-tab-btn flex-1 py-2 px-2 rounded-xl text-center transition-all text-slate-300 hover:text-white" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);">
                                    👑 LC300 V8
                                </button>
                            </div>

                            <!-- Showcase Display Card -->
                            <div class="relative rounded-2xl overflow-hidden bg-slate-950">
                                
                                <!-- Main Vehicle Image -->
                                <div class="relative h-72 sm:h-84 overflow-hidden">
                                    <img id="hero-stage-img" src="https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Mercedes-Benz S-Class Maybach" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105">
                                    
                                    <!-- High-Contrast Vignette Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                                    
                                    <!-- Top Status Chips Inside Image -->
                                    <div class="absolute top-3.5 inset-x-3.5 flex items-center justify-between z-10">
                                        <span id="hero-stage-badge" class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-pink-500 text-white shadow-lg backdrop-blur-md">
                                            ★ Flagship Bridal Luxury
                                        </span>
                                        <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-black/60 text-emerald-400 border border-emerald-500/30 backdrop-blur-md">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                            <span>Available For Booking</span>
                                        </span>
                                    </div>

                                    <!-- Bottom Inner Details Overlay -->
                                    <div class="absolute bottom-4 inset-x-4 space-y-2 z-10">
                                        <h3 id="hero-stage-title" class="text-xl sm:text-2xl font-black font-heading text-white tracking-tight">
                                            Mercedes-Benz S-Class Maybach
                                        </h3>
                                        <p id="hero-stage-desc" class="text-xs text-slate-300 line-clamp-2 leading-relaxed">
                                            Flagship luxury bridal sedan. Pearl white finish, floral bows, leather massage seating, champagne cooler and suited chauffeur.
                                        </p>
                                        
                                        <!-- Specs Tags Pill -->
                                        <div id="hero-stage-specs" class="flex flex-wrap gap-1.5 pt-1">
                                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-white/15 text-white backdrop-blur-md">👥 4 Seats</span>
                                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-white/15 text-white backdrop-blur-md">👔 Suited Chauffeur</span>
                                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-white/15 text-pink-300 backdrop-blur-md">🎀 Satin Ribbons</span>
                                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-white/15 text-emerald-300 backdrop-blur-md">⛽ Full Tank</span>
                                        </div>

                                        <!-- Pricing & CTA Row -->
                                        <div class="flex items-center justify-between pt-2 border-t border-white/15">
                                            <div>
                                                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Day Rate</span>
                                                <span id="hero-stage-price" class="text-xl font-black text-amber-300 font-heading">
                                                    TZS 750,000
                                                </span>
                                                <span class="text-[11px] text-slate-400">/ Day</span>
                                            </div>

                                            <div class="flex items-center space-x-2">
                                                <a id="hero-stage-wa" href="https://wa.me/{{ $brandSettings['whatsapp_clean'] ?? '255662199071' }}?text=Habari%20Tukio%20360!%20Ninapenda%20kufahamu%20zaidi%20kuhusu%20Mercedes-Benz%20S-Class%20Maybach" target="_blank" class="px-3 py-2 bg-emerald-500/20 hover:bg-emerald-500 text-emerald-300 hover:text-white rounded-xl border border-emerald-500/40 text-xs font-bold transition-all" title="Chat on WhatsApp">
                                                    💬 WhatsApp
                                                </a>
                                                <a id="hero-stage-link" href="{{ route('car-hire.show', 'mercedes-benz-s-class-maybach-edition') }}" class="px-4 py-2 bg-white text-slate-950 hover:bg-slate-100 rounded-xl text-xs font-bold transition-all shadow-lg flex items-center space-x-1.5">
                                                    <span>View Fleet</span>
                                                    <span>&rarr;</span>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Safe In-Stage Feature Micro-Chips -->
                            <div class="mt-3 grid grid-cols-2 gap-2 text-center text-[11px]">
                                <div class="py-1.5 px-2 rounded-xl bg-white/[0.04] border border-white/10 text-slate-300 flex items-center justify-center space-x-1.5">
                                    <span class="text-pink-400">💐</span>
                                    <span>Theme Ribbon Color Match</span>
                                </div>
                                <div class="py-1.5 px-2 rounded-xl bg-white/[0.04] border border-white/10 text-slate-300 flex items-center justify-center space-x-1.5">
                                    <span class="text-indigo-400">📍</span>
                                    <span>Dar, Arusha, Zanzibar &amp; Countrywide</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================
         2. STICKY CATEGORY SWITCHER & FAST FILTERS BAR
         ============================================ -->
    <section class="py-4 sticky top-16 z-30 backdrop-blur-2xl bg-slate-950/85 border-y border-white/15 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                
                <!-- Category Pills -->
                <div class="flex items-center space-x-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 scrollbar-none">
                    <a href="{{ route('car-hire.index', ['category' => 'all']) }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200 {{ $category == 'all' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30 border border-indigo-400/50' : 'backdrop-blur-md bg-white/5 hover:bg-white/15 text-slate-300 border border-white/10' }}">
                        🚗 All Fleet ({{ $categoryCounts['all'] }})
                    </a>
                    <a href="{{ route('car-hire.index', ['category' => 'luxury_bride_groom']) }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200 {{ $category == 'luxury_bride_groom' ? 'bg-gradient-to-r from-pink-600 to-rose-600 text-white shadow-lg shadow-pink-500/30 border border-pink-400/50' : 'backdrop-blur-md bg-white/5 hover:bg-white/15 text-slate-300 border border-white/10' }}">
                        👰🤵 Bride &amp; Groom ({{ $categoryCounts['luxury_bride_groom'] }})
                    </a>
                    <a href="{{ route('car-hire.index', ['category' => 'guest_coaster_bus']) }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200 {{ $category == 'guest_coaster_bus' ? 'bg-gradient-to-r from-amber-600 to-orange-600 text-white shadow-lg shadow-amber-500/30 border border-amber-400/50' : 'backdrop-blur-md bg-white/5 hover:bg-white/15 text-slate-300 border border-white/10' }}">
                        🚌 Guest Coasters &amp; Buses ({{ $categoryCounts['guest_coaster_bus'] }})
                    </a>
                    <a href="{{ route('car-hire.index', ['category' => 'vip_suv_convoy']) }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200 {{ $category == 'vip_suv_convoy' ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-lg shadow-purple-500/30 border border-purple-400/50' : 'backdrop-blur-md bg-white/5 hover:bg-white/15 text-slate-300 border border-white/10' }}">
                        👑 VIP Convoys &amp; V8s ({{ $categoryCounts['vip_suv_convoy'] }})
                    </a>
                </div>

                <!-- Sort By Selector & Clear Filter Link -->
                <div class="flex items-center space-x-2 text-xs font-semibold text-slate-300 w-full md:w-auto justify-end">
                    <span class="text-slate-400 hidden sm:inline">Sort:</span>
                    <a href="{{ route('car-hire.index', array_merge(request()->query(), ['sort' => 'featured'])) }}" class="px-3 py-1.5 rounded-lg transition-all {{ $sortBy == 'featured' ? 'bg-white text-slate-950 font-bold shadow-md' : 'backdrop-blur-md bg-white/5 hover:bg-white/15 text-slate-300 border border-white/10' }}">
                        Featured
                    </a>
                    <a href="{{ route('car-hire.index', array_merge(request()->query(), ['sort' => 'price_low'])) }}" class="px-3 py-1.5 rounded-lg transition-all {{ $sortBy == 'price_low' ? 'bg-white text-slate-950 font-bold shadow-md' : 'backdrop-blur-md bg-white/5 hover:bg-white/15 text-slate-300 border border-white/10' }}">
                        Price: Low &rarr; High
                    </a>
                    <a href="{{ route('car-hire.index', array_merge(request()->query(), ['sort' => 'capacity'])) }}" class="px-3 py-1.5 rounded-lg transition-all {{ $sortBy == 'capacity' ? 'bg-white text-slate-950 font-bold shadow-md' : 'backdrop-blur-md bg-white/5 hover:bg-white/15 text-slate-300 border border-white/10' }}">
                        Capacity
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================
         3. MAIN VEHICLE FLEET GRID SHOWROOM
         ============================================ -->
    <section class="py-16 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <!-- Section Title & Subheading -->
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 border-b border-white/10 pb-6">
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-indigo-300 bg-indigo-500/15 border border-indigo-500/30">
                        Showroom Catalog
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black font-heading text-white mt-2">
                        @if($category == 'luxury_bride_groom')
                            Luxury Bride &amp; Groom Cars
                        @elseif($category == 'guest_coaster_bus')
                            Guest Coasters, Minibuses &amp; Buses
                        @elseif($category == 'vip_suv_convoy')
                            VIP Escort &amp; Convoy SUVs
                        @else
                            Explore Complete Wedding &amp; Event Fleet
                        @endif
                    </h2>
                    <p class="text-slate-400 text-sm mt-1">Showing {{ $vehicles->total() }} luxury vehicles available for dispatch across Dar es Salaam &amp; Tanzania</p>
                </div>

                @if(request('search') || (request('category') && request('category') !== 'all'))
                    <a href="{{ route('car-hire.index') }}" class="text-xs font-semibold text-pink-400 hover:text-pink-300 flex items-center space-x-1.5 bg-pink-500/10 px-3 py-1.5 rounded-xl border border-pink-500/20">
                        <span>Reset filters</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>

            <!-- Vehicles Grid (Translucent Specular Glass Cards) -->
            @if($vehicles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($vehicles as $car)
                        @php
                            $badge = $car->category_badge;
                        @endphp
                        <div class="backdrop-blur-2xl bg-white/[0.04] hover:bg-white/[0.08] rounded-3xl overflow-hidden border border-white/15 hover:border-pink-500/50 shadow-2xl hover:shadow-pink-500/10 transition-all duration-300 flex flex-col group transform hover:-translate-y-1">
                            
                            <!-- Vehicle Image Showcase Container -->
                            <div class="relative h-64 overflow-hidden bg-slate-950">
                                <img src="{{ $car->image_url_formatted }}" alt="{{ $car->name }}" class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>

                                <!-- Top Badges -->
                                <div class="absolute top-4 left-4 flex items-center space-x-2">
                                    <span class="backdrop-blur-md bg-black/60 px-3 py-1 rounded-full text-xs font-semibold border border-white/20 text-white shadow-md">
                                        {{ $badge['icon'] }} {{ $badge['label'] }}
                                    </span>
                                </div>

                                @if($car->is_featured)
                                    <div class="absolute top-4 right-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-400 text-slate-950 shadow-md">
                                            ★ Popular Choice
                                        </span>
                                    </div>
                                @endif

                                <!-- Bottom Price Overlay on Image -->
                                <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between text-white">
                                    <div>
                                        <span class="text-[10px] text-slate-300 uppercase tracking-wider font-bold block">Rental Rate</span>
                                        <span class="text-2xl font-black font-heading text-amber-300">{{ $car->formatted_price }}</span>
                                        <span class="text-xs text-slate-300">/ day</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block px-3 py-1 backdrop-blur-md bg-black/60 border border-white/20 rounded-xl text-xs font-semibold text-white">
                                            👥 {{ $car->seating_capacity }} Passengers
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-6 flex-1 flex flex-col justify-between space-y-5">
                                
                                <div>
                                    <div class="text-[11px] font-semibold text-slate-400 mb-1 flex items-center justify-between">
                                        <span>{{ $car->brand }} &bull; {{ $car->model_year ?? 'Executive' }}</span>
                                        <span class="text-pink-400 font-medium">{{ $car->color ?? 'Bridal Edition' }}</span>
                                    </div>
                                    <h3 class="text-xl font-bold font-heading text-white group-hover:text-pink-300 transition-colors">
                                        <a href="{{ route('car-hire.show', $car->slug) }}">
                                            {{ $car->name }}
                                        </a>
                                    </h3>
                                    <p class="text-slate-300 text-xs mt-2 line-clamp-2 leading-relaxed">
                                        {{ $car->description }}
                                    </p>
                                </div>

                                <!-- 4 Quick Specs Capsule -->
                                <div class="grid grid-cols-2 gap-2 text-xs text-slate-300 bg-black/30 p-3 rounded-2xl border border-white/10">
                                    <div class="flex items-center space-x-1.5">
                                        <span class="text-emerald-400">✓</span>
                                        <span>Driver: <strong class="text-white">Suited</strong></span>
                                    </div>
                                    <div class="flex items-center space-x-1.5">
                                        <span class="text-emerald-400">✓</span>
                                        <span>Fuel: <strong class="text-white">Included</strong></span>
                                    </div>
                                    <div class="flex items-center space-x-1.5">
                                        <span class="text-emerald-400">✓</span>
                                        <span>Deco: <strong class="text-white">Ribbons &amp; Bows</strong></span>
                                    </div>
                                    <div class="flex items-center space-x-1.5">
                                        <span class="text-pink-400">📍</span>
                                        <span class="truncate text-slate-200">{{ $car->location }}</span>
                                    </div>
                                </div>

                                <!-- Feature Chips -->
                                @if(!empty($car->features))
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach(array_slice($car->features, 0, 3) as $feature)
                                            <span class="px-2.5 py-1 rounded-lg text-[11px] font-medium bg-white/10 text-slate-200 border border-white/10">
                                                • {{ $feature }}
                                            </span>
                                        @endforeach
                                        @if(count($car->features) > 3)
                                            <span class="px-2.5 py-1 rounded-lg text-[11px] font-medium bg-white/5 text-pink-300 border border-white/10">
                                                +{{ count($car->features) - 3 }} more
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Action Buttons Row -->
                                <div class="pt-2 flex items-center space-x-2.5">
                                    <a href="{{ route('car-hire.show', $car->slug) }}" class="flex-1 text-center text-xs py-3 px-4 bg-gradient-to-r from-pink-500 to-indigo-600 hover:from-pink-400 hover:to-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-pink-500/20 transition-all transform hover:-translate-y-0.5">
                                        Book This Car
                                    </a>
                                    
                                    @php
                                        $waText = urlencode("Habari Tukio 360! 🚗 Ninapenda kufahamu zaidi na kuweka nafasi ya gari: *{$car->name}* (Bei: {$car->formatted_price}/siku). Je, lipo wazi kwa tarehe yangu?");
                                    @endphp
                                    <a href="https://wa.me/{{ $brandSettings['whatsapp_clean'] ?? '255662199071' }}?text={{ $waText }}" target="_blank" class="w-11 h-11 rounded-xl bg-emerald-500/20 hover:bg-emerald-500 text-emerald-400 hover:text-white border border-emerald-500/40 flex items-center justify-center transition-all duration-200 shadow-sm group-hover:scale-105" title="Chat on WhatsApp">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.966.527 1.76.812 2.795.812 3.179 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.999-5.767-5.999zm3.385 8.163c-.144.405-.837.774-1.17.822-.312.043-.699.077-2.029-.472-1.696-.703-2.779-2.457-2.863-2.569-.084-.112-.686-.914-.686-1.743 0-.829.434-1.237.588-1.408.155-.171.339-.214.452-.214.113 0 .226.002.324.007.104.005.243-.039.38.291.144.344.492 1.2.535 1.288.043.088.071.191.014.305-.056.113-.085.184-.17.283-.085.1-.179.223-.255.3-.099.099-.202.207-.087.404.115.197.51 1.042 1.093 1.562.752.669 1.386.877 1.583.974.197.098.312.085.428-.049.115-.133.493-.574.625-.771.132-.197.265-.164.444-.099.18.066 1.139.537 1.336.635.197.099.329.148.378.23.049.083.049.48-.095.885z"/></svg>
                                    </a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pt-6">
                    {{ $vehicles->links() }}
                </div>
            @else
                <div class="text-center py-20 backdrop-blur-2xl bg-white/[0.04] rounded-3xl border border-white/20 space-y-5 max-w-2xl mx-auto p-8">
                    <div class="w-20 h-20 rounded-2xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center mx-auto text-4xl border border-indigo-500/30">
                        🚗
                    </div>
                    <h3 class="text-2xl font-bold font-heading text-white">No Vehicles Matching Criteria</h3>
                    <p class="text-slate-300 text-sm">We couldn't find any vehicles matching your filter criteria. Try clearing filters or selecting another category.</p>
                    <a href="{{ route('car-hire.index') }}" class="inline-flex px-6 py-3 bg-gradient-to-r from-pink-500 to-indigo-600 text-white font-bold rounded-xl text-xs shadow-lg">
                        View Complete Fleet
                    </a>
                </div>
            @endif

        </div>
    </section>

    <!-- ============================================
         4. RECOMMENDED WEDDING FLEET PACKAGES & BUNDLES
         ============================================ -->
    <section class="py-20 relative z-10 border-t border-white/10 bg-gradient-to-b from-transparent via-slate-950/60 to-transparent">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold text-pink-300 bg-pink-500/15 border border-pink-500/30">
                    Curated Entourage Bundles
                </span>
                <h2 class="text-3xl sm:text-4xl font-black font-heading text-white">
                    Popular Wedding Convoy Packages
                </h2>
                <p class="text-slate-300 text-sm sm:text-base">
                    Save time and budget by bundling the Bride &amp; Groom car with your family transport and VIP escort convoy.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Package 1: Royal Bridal Duo -->
                <div class="backdrop-blur-2xl bg-white/[0.04] p-8 rounded-3xl border border-white/15 flex flex-col justify-between space-y-6 hover:border-pink-500/40 transition-all duration-300">
                    <div class="space-y-4">
                        <div class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-pink-500/20 text-pink-300 border border-pink-500/30">
                            Couple Entrance
                        </div>
                        <h3 class="text-2xl font-bold font-heading text-white">The Royal Couple</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            For couples who want an unforgettable grand entrance with zero transport stress.
                        </p>
                        <div class="pt-2">
                            <span class="text-3xl font-black text-amber-300 font-heading">TZS 750,000</span>
                            <span class="text-xs text-slate-400">/ Day</span>
                        </div>
                        <ul class="space-y-2.5 text-xs text-slate-200 pt-3 border-t border-white/10">
                            <li class="flex items-center space-x-2">
                                <span class="text-emerald-400 font-bold">✓</span>
                                <span>1x Mercedes-Benz S-Class or Range Rover</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-emerald-400 font-bold">✓</span>
                                <span>Uniformed Chauffeur in Dark Suit</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-emerald-400 font-bold">✓</span>
                                <span>Bridal White Satin Ribbon &amp; Floral Bonnet Deco</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-emerald-400 font-bold">✓</span>
                                <span>Chilled Champagne / Beverage Cooler</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-emerald-400 font-bold">✓</span>
                                <span>Full Day City Coverage (Salon, Church, Photos, Hall)</span>
                            </li>
                        </ul>
                    </div>
                    <a href="https://wa.me/{{ $brandSettings['whatsapp_clean'] ?? '255662199071' }}?text=Habari!%20Ninapenda%20kuweka%20nafasi%20ya%20Package%201%20ya%20Harusi:%20*The%20Royal%20Couple*%20(TZS%20750,000)." target="_blank" class="w-full py-3 text-center text-xs font-bold bg-white/10 hover:bg-white/20 text-white rounded-xl border border-white/20 transition-all">
                        Inquire Package &rarr;
                    </a>
                </div>

                <!-- Package 2: The Grand Entourage (Featured Best Value) -->
                <div class="backdrop-blur-2xl bg-gradient-to-b from-purple-900/40 to-slate-950 p-8 rounded-3xl border-2 border-pink-500 shadow-2xl shadow-pink-500/20 flex flex-col justify-between space-y-6 relative transform lg:-translate-y-2">
                    <div class="absolute -top-3.5 right-6">
                        <span class="px-3.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-gradient-to-r from-pink-500 to-purple-600 text-white shadow-md">
                            ★ Most Popular (Save 15%)
                        </span>
                    </div>

                    <div class="space-y-4">
                        <div class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-pink-500/20 text-pink-300 border border-pink-500/30">
                            Couple + Entourage
                        </div>
                        <h3 class="text-2xl font-bold font-heading text-white">The Grand Entourage</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Complete peace of mind for the bridal party, maids of honor, groomsmen, and close family.
                        </p>
                        <div class="pt-2">
                            <span class="text-3xl font-black text-amber-300 font-heading">TZS 1,050,000</span>
                            <span class="text-xs text-slate-400 line-through ml-2">TZS 1,250,000</span>
                        </div>
                        <ul class="space-y-2.5 text-xs text-slate-200 pt-3 border-t border-white/10">
                            <li class="flex items-center space-x-2">
                                <span class="text-emerald-400 font-bold">✓</span>
                                <span><strong>1x Mercedes Maybach / Range Rover</strong> (Bride &amp; Groom)</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-emerald-400 font-bold">✓</span>
                                <span><strong>1x Toyota Coaster (30-Seater)</strong> for Bridal Entourage</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-emerald-400 font-bold">✓</span>
                                <span>Matching Satin Ribbons &amp; Bows on Both Vehicles</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-emerald-400 font-bold">✓</span>
                                <span>2x Uniformed Certified Chauffeurs</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-emerald-400 font-bold">✓</span>
                                <span>PA Sound Microphone in Coaster for Entourage Chants</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-emerald-400 font-bold">✓</span>
                                <span>Guaranteed Backup Vehicle On Standby</span>
                            </li>
                        </ul>
                    </div>

                    <a href="https://wa.me/{{ $brandSettings['whatsapp_clean'] ?? '255662199071' }}?text=Habari!%20Ninapenda%20kuweka%20nafasi%20ya%20Package%20ya%20Harusi:%20*The%20Grand%20Entourage*%20(TZS%201,050,000)." target="_blank" class="w-full py-3.5 text-center text-xs font-bold bg-gradient-to-r from-pink-500 via-purple-600 to-indigo-600 text-white rounded-xl shadow-lg shadow-pink-500/30 hover:opacity-95 transition-all">
                        Book Entourage Package &rarr;
                    </a>
                </div>

                <!-- Package 3: Presidential VIP Convoy -->
                <div class="backdrop-blur-2xl bg-white/[0.04] p-8 rounded-3xl border border-white/15 flex flex-col justify-between space-y-6 hover:border-indigo-500/40 transition-all duration-300">
                    <div class="space-y-4">
                        <div class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                            High Society &amp; Dignitaries
                        </div>
                        <h3 class="text-2xl font-bold font-heading text-white">Presidential VIP Convoy</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Commanding convoy for high-profile weddings, government dignitaries, and grand society events.
                        </p>
                        <div class="pt-2">
                            <span class="text-3xl font-black text-amber-300 font-heading">TZS 2,350,000</span>
                            <span class="text-xs text-slate-400">/ Day</span>
                        </div>
                        <ul class="space-y-2.5 text-xs text-slate-200 pt-3 border-t border-white/10">
                            <li class="flex items-center space-x-2">
                                <span class="text-emerald-400 font-bold">✓</span>
                                <span>1x Mercedes Maybach / Range Rover (Couple)</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-emerald-400 font-bold">✓</span>
                                <span><strong>2x Toyota Land Cruiser 300 V8 ZX</strong> Escort SUVs</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-emerald-400 font-bold">✓</span>
                                <span><strong>1x Executive 50-Seater Coach</strong> for Church &amp; Hall Guests</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-emerald-400 font-bold">✓</span>
                                <span>Radio Convoy Coordination &amp; Security-Trained Drivers</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-emerald-400 font-bold">✓</span>
                                <span>Full Fresh Floral Hood Garland &amp; VIP Red Carpet Mat</span>
                            </li>
                        </ul>
                    </div>
                    <a href="https://wa.me/{{ $brandSettings['whatsapp_clean'] ?? '255662199071' }}?text=Habari!%20Ninapenda%20kuweka%20nafasi%20ya%20Package%20ya%20Harusi:%20*Presidential%20VIP%20Convoy*%20(TZS%202,350,000)." target="_blank" class="w-full py-3 text-center text-xs font-bold bg-white/10 hover:bg-white/20 text-white rounded-xl border border-white/20 transition-all">
                        Inquire VIP Convoy &rarr;
                    </a>
                </div>

            </div>

        </div>
    </section>

    <!-- ============================================
         5. INTERACTIVE WEDDING FLEET COST CALCULATOR
         ============================================ -->
    <section class="py-16 relative z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="backdrop-blur-2xl bg-gradient-to-br from-white/[0.07] to-white/[0.02] p-8 sm:p-12 rounded-3xl border border-white/20 shadow-2xl relative overflow-hidden space-y-8">
                
                <div class="text-center max-w-2xl mx-auto space-y-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                        Interactive Estimator
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-black font-heading text-white">
                        Build Your Custom Wedding Convoy
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-300">
                        Select your preferred vehicles below to instantly calculate the estimated package cost.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- 1. Bridal Car Select -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-300">👰 Bridal Car</label>
                        <select id="calc-bridal" onchange="calculateFleetTotal()" class="w-full p-3 bg-slate-900/90 text-white text-xs rounded-xl border border-white/20 focus:outline-none focus:ring-2 focus:ring-pink-400">
                            <option value="750000">Mercedes S-Class Maybach (750k)</option>
                            <option value="850000">Range Rover Autobiography (850k)</option>
                            <option value="500000">Toyota Alphard Royal Lounge (500k)</option>
                            <option value="900000">Vintage Beauford Convertible (900k)</option>
                            <option value="0">None (I have bridal car)</option>
                        </select>
                    </div>

                    <!-- 2. Guest Transport Select -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-300">🚌 Guest Transport</label>
                        <select id="calc-guest" onchange="calculateFleetTotal()" class="w-full p-3 bg-slate-900/90 text-white text-xs rounded-xl border border-white/20 focus:outline-none focus:ring-2 focus:ring-pink-400">
                            <option value="400000">1x Toyota Coaster 30-Seater (400k)</option>
                            <option value="800000">2x Toyota Coasters 60 Guests (800k)</option>
                            <option value="650000">1x Charter Coach 50-Seater (650k)</option>
                            <option value="250000">1x HiAce Van 15-Seater (250k)</option>
                            <option value="0">None</option>
                        </select>
                    </div>

                    <!-- 3. VIP Escort Convoy Select -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-300">👑 VIP Escort Convoy</label>
                        <select id="calc-escort" onchange="calculateFleetTotal()" class="w-full p-3 bg-slate-900/90 text-white text-xs rounded-xl border border-white/20 focus:outline-none focus:ring-2 focus:ring-pink-400">
                            <option value="0">None</option>
                            <option value="350000">1x Prado TXL Convoy SUV (350k)</option>
                            <option value="600000">1x Land Cruiser LC300 V8 (600k)</option>
                            <option value="1200000">2x Land Cruiser LC300 V8s (1.2M)</option>
                        </select>
                    </div>

                    <!-- 4. Duration Select -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-300">📅 Duration</label>
                        <select id="calc-days" onchange="calculateFleetTotal()" class="w-full p-3 bg-slate-900/90 text-white text-xs rounded-xl border border-white/20 focus:outline-none focus:ring-2 focus:ring-pink-400">
                            <option value="1">1 Day (Wedding Day)</option>
                            <option value="2">2 Days (Send-Off + Wedding)</option>
                            <option value="3">3 Days (Send-Off + Church + Reception)</option>
                        </select>
                    </div>

                </div>

                <!-- Calculated Output Box -->
                <div class="backdrop-blur-xl bg-black/40 p-6 rounded-2xl border border-white/15 flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div>
                        <div class="text-xs text-slate-400 uppercase font-bold tracking-wider">Estimated Total Investment</div>
                        <div class="flex items-baseline space-x-3 mt-1">
                            <span id="calc-total-display" class="text-3xl sm:text-4xl font-black text-amber-300 font-heading">TZS 1,035,000</span>
                            <span id="calc-discount-badge" class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">10% Bundle Discount Applied!</span>
                        </div>
                        <div class="text-[11px] text-slate-400 mt-1">Includes suited drivers, white satin ribbons &amp; standby vehicle guarantee</div>
                    </div>

                    <a id="calc-wa-btn" href="https://wa.me/{{ $brandSettings['whatsapp_clean'] ?? '255662199071' }}?text=Habari!%20Nimetengeneza%20makadirio%20ya%20msafara%20wa%20harusi%20kwa%20TZS%201,035,000.%20Tafadhali%20tusaidie%20kuweka%20nafasi." target="_blank" class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold rounded-xl text-xs sm:text-sm shadow-lg shadow-emerald-500/30 flex items-center justify-center space-x-2 transition-all">
                        <span>💬 Reserve On WhatsApp</span>
                        <span>&rarr;</span>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================
         6. THE TUKIO 360 VIP STANDARDS (PERKS)
         ============================================ -->
    <section class="py-16 relative z-10 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-pink-300 bg-pink-500/15 border border-pink-500/30">
                    Why Choose Tukio 360
                </span>
                <h2 class="text-3xl font-black font-heading text-white">
                    The VIP Wedding Fleet Standard
                </h2>
                <p class="text-xs sm:text-sm text-slate-300">
                    Your wedding day allows zero room for transportation errors. Here is how we guarantee perfection.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="backdrop-blur-xl bg-white/[0.04] p-6 rounded-3xl border border-white/10 space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-pink-500/20 text-pink-400 flex items-center justify-center text-xl font-bold border border-pink-500/30">
                        👔
                    </div>
                    <h3 class="text-lg font-bold font-heading text-white">Suited Chauffeurs</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Experienced chauffeurs dressed in sharp executive black suits, white shirts and polished shoes with white-glove door opening protocol.
                    </p>
                </div>

                <div class="backdrop-blur-xl bg-white/[0.04] p-6 rounded-3xl border border-white/10 space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-xl font-bold border border-purple-500/30">
                        🎀
                    </div>
                    <h3 class="text-lg font-bold font-heading text-white">Custom Ribbons &amp; Bows</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        We color-coordinate fresh satin ribbons and hood bouquets to match your exact wedding color scheme (white, gold, rose, emerald, royal blue).
                    </p>
                </div>

                <div class="backdrop-blur-xl bg-white/[0.04] p-6 rounded-3xl border border-white/10 space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-xl font-bold border border-indigo-500/30">
                        🛡️
                    </div>
                    <h3 class="text-lg font-bold font-heading text-white">Zero-Delay Backup Fleet</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Every booking is backed by an on-call replacement vehicle and certified mechanics stationed in Dar es Salaam, ensuring 0% cancellation risk.
                    </p>
                </div>

                <div class="backdrop-blur-xl bg-white/[0.04] p-6 rounded-3xl border border-white/10 space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl font-bold border border-amber-500/30">
                        ⏰
                    </div>
                    <h3 class="text-lg font-bold font-heading text-white">60-Min Early Arrival</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Vehicles arrive washed, polished, and stationed at the bride's salon or home a full 1 hour ahead of time, ensuring relaxed wedding photo shoots.
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- ============================================
         7. REAL WEDDING REVIEWS (TESTIMONIALS)
         ============================================ -->
    <section class="py-16 relative z-10 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-emerald-300 bg-emerald-500/15 border border-emerald-500/30">
                    Verified Couple Experiences
                </span>
                <h2 class="text-3xl font-black font-heading text-white">
                    Stories From Happy Couples
                </h2>
                <p class="text-xs sm:text-sm text-slate-300">
                    Hear from real couples who trusted Tukio 360 with their special wedding day transport.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="backdrop-blur-xl bg-white/[0.04] p-6 rounded-3xl border border-white/10 space-y-4">
                    <div class="flex text-amber-400 text-sm">★★★★★</div>
                    <p class="text-xs text-slate-300 leading-relaxed italic">
                        "We booked the Mercedes Maybach for our wedding at Serena Hotel and a 30-Seater Coaster for my bridal entourage. Both vehicles arrived 50 minutes early, spotless, and our chauffeur Joseph was wonderfully polite. Highly recommend Tukio 360!"
                    </p>
                    <div class="pt-2 border-t border-white/10 flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-full bg-pink-500/20 text-pink-300 flex items-center justify-center font-bold text-xs">
                            GK
                        </div>
                        <div>
                            <div class="text-xs font-bold text-white">Gloria &amp; Kelvin M.</div>
                            <div class="text-[10px] text-slate-400">Wedding at Serena Hotel, Dar es Salaam</div>
                        </div>
                    </div>
                </div>

                <div class="backdrop-blur-xl bg-white/[0.04] p-6 rounded-3xl border border-white/10 space-y-4">
                    <div class="flex text-amber-400 text-sm">★★★★★</div>
                    <p class="text-xs text-slate-300 leading-relaxed italic">
                        "The Range Rover Autobiography made our church entrance at St. Peter's Oysterbay absolutely breathtaking! The deployable side step was so helpful with my heavy ballgown. Thank you Tukio 360 for making our day unforgettable!"
                    </p>
                    <div class="pt-2 border-t border-white/10 flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-full bg-purple-500/20 text-purple-300 flex items-center justify-center font-bold text-xs">
                            NE
                        </div>
                        <div>
                            <div class="text-xs font-bold text-white">Neema &amp; Emmanuel S.</div>
                            <div class="text-[10px] text-slate-400">Reception at Mlimani City, Dar es Salaam</div>
                        </div>
                    </div>
                </div>

                <div class="backdrop-blur-xl bg-white/[0.04] p-6 rounded-3xl border border-white/10 space-y-4">
                    <div class="flex text-amber-400 text-sm">★★★★★</div>
                    <p class="text-xs text-slate-300 leading-relaxed italic">
                        "Organized transport for 60 guests from Arusha to Kilimanjaro venue using two luxury coasters. The AC was chilling, sound system kept everyone singing gospel songs, and drivers were punctual. Seamless service!"
                    </p>
                    <div class="pt-2 border-t border-white/10 flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-full bg-indigo-500/20 text-indigo-300 flex items-center justify-center font-bold text-xs">
                            BM
                        </div>
                        <div>
                            <div class="text-xs font-bold text-white">Baraka M. (Committee Chair)</div>
                            <div class="text-[10px] text-slate-400">Arusha &amp; Moshi Wedding Convoy</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ============================================
         8. FREQUENTLY ASKED QUESTIONS (FAQ ACCORDION)
         ============================================ -->
    <section class="py-16 relative z-10 border-t border-white/10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            
            <div class="text-center space-y-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-pink-300 bg-pink-500/15 border border-pink-500/30">
                    Got Questions?
                </span>
                <h2 class="text-3xl font-black font-heading text-white">
                    Frequently Asked Questions
                </h2>
                <p class="text-xs sm:text-sm text-slate-300">
                    Everything you need to know about booking and chauffeur dispatch.
                </p>
            </div>

            <div class="space-y-3">
                
                <div class="backdrop-blur-xl bg-white/[0.04] rounded-2xl border border-white/15 overflow-hidden">
                    <button type="button" onclick="toggleFaq(1)" class="w-full p-5 text-left flex items-center justify-between text-sm font-bold text-white focus:outline-none">
                        <span>Are the driver and fuel included in the daily rental rate?</span>
                        <span id="faq-icon-1" class="text-pink-400 text-lg transition-transform">&plus;</span>
                    </button>
                    <div id="faq-ans-1" class="hidden px-5 pb-5 text-xs text-slate-300 leading-relaxed border-t border-white/10 pt-3">
                        Yes! All our wedding car rental packages include a certified, suited executive chauffeur. Standard city itinerary fuel (salon, photo shoot venues, church, reception hall within Dar es Salaam or designated town) is included. Intercity journeys outside the primary city are billed at clear per-kilometer fuel adjustments.
                    </div>
                </div>

                <div class="backdrop-blur-xl bg-white/[0.04] rounded-2xl border border-white/15 overflow-hidden">
                    <button type="button" onclick="toggleFaq(2)" class="w-full p-5 text-left flex items-center justify-between text-sm font-bold text-white focus:outline-none">
                        <span>Can we choose our wedding theme colors for ribbons and bows?</span>
                        <span id="faq-icon-2" class="text-pink-400 text-lg transition-transform">&plus;</span>
                    </button>
                    <div id="faq-ans-2" class="hidden px-5 pb-5 text-xs text-slate-300 leading-relaxed border-t border-white/10 pt-3">
                        Absolutely. We provide customized satin ribbons, side mirror bows, and bonnet floral bouquets to match your exact theme colors (White, Gold, Rose Gold, Emerald Green, Royal Blue, Burgundy, etc.). You can also request natural fresh flowers from our florists at a small surcharge.
                    </div>
                </div>

                <div class="backdrop-blur-xl bg-white/[0.04] rounded-2xl border border-white/15 overflow-hidden">
                    <button type="button" onclick="toggleFaq(3)" class="w-full p-5 text-left flex items-center justify-between text-sm font-bold text-white focus:outline-none">
                        <span>How early before the wedding should we reserve the cars?</span>
                        <span id="faq-icon-3" class="text-pink-400 text-lg transition-transform">&plus;</span>
                    </button>
                    <div id="faq-ans-3" class="hidden px-5 pb-5 text-xs text-slate-300 leading-relaxed border-t border-white/10 pt-3">
                        We recommend reserving your bridal car (especially the Mercedes S-Class Maybach and Range Rover) and guest coasters 2 to 6 weeks in advance. Popular wedding Saturdays book out quickly during peak wedding seasons (June - August, November - December). Last-minute emergency dispatch is also available subject to vehicle availability.
                    </div>
                </div>

                <div class="backdrop-blur-xl bg-white/[0.04] rounded-2xl border border-white/15 overflow-hidden">
                    <button type="button" onclick="toggleFaq(4)" class="w-full p-5 text-left flex items-center justify-between text-sm font-bold text-white focus:outline-none">
                        <span>Do you provide cars for events outside Dar es Salaam?</span>
                        <span id="faq-icon-4" class="text-pink-400 text-lg transition-transform">&plus;</span>
                    </button>
                    <div id="faq-ans-4" class="hidden px-5 pb-5 text-xs text-slate-300 leading-relaxed border-t border-white/10 pt-3">
                        Yes! Tukio 360 operates across Tanzania, including Dar es Salaam, Bagamoyo, Morogoro, Arusha, Moshi, Dodoma, Mwanza, and Zanzibar. Vehicles can be transported or driven to your regional location with advance itinerary scheduling.
                    </div>
                </div>

                <div class="backdrop-blur-xl bg-white/[0.04] rounded-2xl border border-white/15 overflow-hidden">
                    <button type="button" onclick="toggleFaq(5)" class="w-full p-5 text-left flex items-center justify-between text-sm font-bold text-white focus:outline-none">
                        <span>What happens in case of rain or unexpected breakdown?</span>
                        <span id="faq-icon-5" class="text-pink-400 text-lg transition-transform">&plus;</span>
                    </button>
                    <div id="faq-ans-5" class="hidden px-5 pb-5 text-xs text-slate-300 leading-relaxed border-t border-white/10 pt-3">
                        All our fleet undergoes thorough pre-event mechanical inspections 24 hours before dispatch. In the rare event of an unexpected mechanical issue or puncture, we have standby backup vehicles and direct emergency dispatch ready across town to ensure your wedding schedule suffers zero delays.
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ============================================
         9. VIP CALL-TO-ACTION & HOST DISCOUNTS BANNER
         ============================================ -->
    <section class="py-16 relative z-10 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="backdrop-blur-2xl bg-gradient-to-r from-pink-900/40 via-purple-900/40 to-indigo-900/40 p-8 sm:p-12 rounded-3xl border border-white/20 shadow-2xl relative overflow-hidden flex flex-col lg:flex-row items-center justify-between gap-8">
                
                <div class="space-y-3 text-center lg:text-left relative z-10">
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-pink-500 text-white shadow-md">
                        Event Host Special
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-black font-heading text-white">
                        Hosting an Event on Tukio 360?
                    </h3>
                    <p class="text-slate-300 text-xs sm:text-sm max-w-xl">
                        Log in to your Host Panel to bundle your Digital Invitations, WhatsApp RSVPs, and Wedding Fleet at special package discounts!
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto relative z-10">
                    <a href="https://wa.me/{{ $brandSettings['whatsapp_clean'] ?? '255662199071' }}?text=Habari%20Tukio%20360!%20Ninahitaji%20msaada%20wa%20haraka%20wa%20magari%20ya%20harusi." target="_blank" class="w-full sm:w-auto px-6 py-3.5 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-2xl transition-all text-center text-xs sm:text-sm shadow-lg flex items-center justify-center space-x-2">
                        <span>💬 WhatsApp Fleet Hotline</span>
                    </a>
                    <a href="{{ route('host.login') }}" class="w-full sm:w-auto px-6 py-3.5 bg-white text-slate-950 font-bold rounded-2xl hover:bg-slate-100 transition-colors text-center text-xs sm:text-sm shadow-lg">
                        Host Login &rarr;
                    </a>
                </div>

            </div>
        </div>
    </section>

</div>

<!-- Client-side Interactive Scripts -->
<script>
    // Hero Showcase Data
    const heroShowcaseCars = [
        {
            title: 'Mercedes-Benz S-Class Maybach',
            badge: '★ Flagship Bridal Luxury',
            desc: 'Flagship luxury bridal sedan. Pearl white finish, floral bows, leather massage seating, champagne cooler and suited chauffeur.',
            img: 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            price: 'TZS 750,000',
            specs: ['👥 4 Seats', '👔 Suited Chauffeur', '🎀 Satin Ribbons', '⛽ Full Tank'],
            slug: 'mercedes-benz-s-class-maybach-edition'
        },
        {
            title: 'Range Rover Autobiography LWB',
            badge: '👑 Royal SUV Flagship',
            desc: 'Commanding road presence with deployable side steps for the bride\'s gown, executive rear massage seats and whisper-quiet air suspension.',
            img: 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            price: 'TZS 850,000',
            specs: ['👥 4 Seats', '👔 Suited Chauffeur', '🎀 Deployable Steps', '🔊 Meridian 3D'],
            slug: 'range-rover-autobiography-lwb'
        },
        {
            title: 'Toyota Coaster Luxury Edition (30-Seater)',
            badge: '🚌 Entourage & Guest Convoy',
            desc: 'Keep your entourage and beloved family united. Roof AC, high-back reclining velvet seats, PA sound system and luggage boot.',
            img: 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            price: 'TZS 400,000',
            specs: ['👥 30 Seats', '👔 Heavy-Duty Driver', '❄️ Roof AC', '🎤 PA Microphone'],
            slug: 'toyota-coaster-luxury-edition-30-seater'
        },
        {
            title: 'Toyota Land Cruiser 300 V8 ZX VIP Convoy',
            badge: '⚡ VIP Escort Convoy',
            desc: 'The undisputed king of wedding convoys. Unrivalled prestige and security for parents, chief guests and bridal convoy lead cars.',
            img: 'https://images.unsplash.com/photo-1594502184342-2e12f877aa73?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            price: 'TZS 600,000',
            specs: ['👥 7 Seats', '👔 Security Driver', '⚡ Twin-Turbo V8', '🛡️ Escort Ready'],
            slug: 'toyota-land-cruiser-300-v8-zx'
        }
    ];

    function switchHeroShowcase(index) {
        const car = heroShowcaseCars[index];
        if (!car) return;

        // Update tab styles
        document.querySelectorAll('.hero-tab-btn').forEach((btn, idx) => {
            if (idx === index) {
                btn.style.background = 'linear-gradient(135deg, #ec4899, #8b5cf6)';
                btn.style.color = '#ffffff';
                btn.style.boxShadow = '0 4px 15px rgba(236,72,153,0.4)';
                btn.style.border = 'none';
            } else {
                btn.style.background = 'rgba(255,255,255,0.06)';
                btn.style.color = '#cbd5e1';
                btn.style.boxShadow = 'none';
                btn.style.border = '1px solid rgba(255,255,255,0.08)';
            }
        });

        // Smooth transition image
        const img = document.getElementById('hero-stage-img');
        img.style.opacity = '0.4';
        setTimeout(() => {
            img.src = car.img;
            img.style.opacity = '1';
        }, 150);

        document.getElementById('hero-stage-title').textContent = car.title;
        document.getElementById('hero-stage-badge').textContent = car.badge;
        document.getElementById('hero-stage-desc').textContent = car.desc;
        document.getElementById('hero-stage-price').textContent = car.price;
        
        // Specs
        const specsContainer = document.getElementById('hero-stage-specs');
        specsContainer.innerHTML = car.specs.map(spec => `<span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-white/15 text-white backdrop-blur-md">${spec}</span>`).join('');

        // Link
        document.getElementById('hero-stage-link').href = '/car-hire/' + car.slug;
        document.getElementById('hero-stage-wa').href = 'https://wa.me/{{ $brandSettings['whatsapp_clean'] ?? '255662199071' }}?text=' + encodeURIComponent('Habari Tukio 360! Ninapenda kufahamu zaidi kuhusu ' + car.title + ' (' + car.price + '/siku).');
    }

    // Interactive Fleet Cost Estimator Calculator
    function calculateFleetTotal() {
        const bridal = parseInt(document.getElementById('calc-bridal').value) || 0;
        const guest = parseInt(document.getElementById('calc-guest').value) || 0;
        const escort = parseInt(document.getElementById('calc-escort').value) || 0;
        const days = parseInt(document.getElementById('calc-days').value) || 1;

        let subtotal = (bridal + guest + escort) * days;
        let discount = 0;
        
        // Bundle discount if 2 or more categories selected
        let categoriesPicked = (bridal > 0 ? 1 : 0) + (guest > 0 ? 1 : 0) + (escort > 0 ? 1 : 0);
        if (categoriesPicked >= 2) {
            discount = 0.10; // 10% discount
        }

        let total = subtotal * (1 - discount);

        // Format to TZS
        const formatted = 'TZS ' + Math.round(total).toLocaleString('en-US');
        document.getElementById('calc-total-display').textContent = formatted;

        const discountBadge = document.getElementById('calc-discount-badge');
        if (discount > 0) {
            discountBadge.style.display = 'inline-block';
            discountBadge.textContent = '10% Entourage Bundle Discount Applied!';
        } else {
            discountBadge.style.display = 'none';
        }

        // WhatsApp prefill
        const bridalText = document.getElementById('calc-bridal').options[document.getElementById('calc-bridal').selectedIndex].text;
        const guestText = document.getElementById('calc-guest').options[document.getElementById('calc-guest').selectedIndex].text;
        const escortText = document.getElementById('calc-escort').options[document.getElementById('calc-escort').selectedIndex].text;

        const waMsg = `Habari Tukio 360! 🚗 Nimeunda mpango wa msafara wa harusi:\n- Gari la Maharusi: ${bridalText}\n- Usafiri wa Wageni: ${guestText}\n- Msafara wa VIP: ${escortText}\n- Idadi ya Siku: ${days}\n*Jumla ya Makadirio: ${formatted}*.\nJe, magari haya yapo wazi kwa tarehe yetu?`;
        document.getElementById('calc-wa-btn').href = 'https://wa.me/{{ $brandSettings['whatsapp_clean'] ?? '255662199071' }}?text=' + encodeURIComponent(waMsg);
    }

    // Accordion FAQ Toggle
    function toggleFaq(num) {
        const ans = document.getElementById('faq-ans-' + num);
        const icon = document.getElementById('faq-icon-' + num);
        if (ans.classList.contains('hidden')) {
            ans.classList.remove('hidden');
            icon.textContent = '−';
            icon.classList.add('text-indigo-400');
        } else {
            ans.classList.add('hidden');
            icon.textContent = '+';
            icon.classList.remove('text-indigo-400');
        }
    }

    // Auto-calculate on initial load
    document.addEventListener('DOMContentLoaded', () => {
        calculateFleetTotal();
    });
</script>
@endsection
