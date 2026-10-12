@extends('layouts.landing')

@section('title', 'Luxury Wedding & Event Car Hire - Mercedes Maybach, Range Rover, Coasters & VIP Buses in Tanzania')
@section('description', 'Book luxury bridal cars (Mercedes-Benz S-Class Maybach, Range Rover, Alphard) and guest transport (30-Seater Toyota Coasters, 50-Seater VIP Buses) with suited chauffeurs and floral ribbon decorations in Tanzania.')
@section('nav_dark', true)

@section('content')
@php
    $brandSettings = $systemSettings ?? [];
    $brandName = $brandSettings['name'] ?? 'Tukio 360';
@endphp

<div class="min-h-screen bg-ambient-mesh-dark text-white relative overflow-hidden pt-10 pb-20 font-sans selection:bg-pink-500 selection:text-white">
    <!-- Ambient Floating Glow Orbs -->
    <div class="absolute top-20 left-1/4 -translate-x-1/2 w-[550px] h-[550px] bg-indigo-600/20 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/3 right-10 w-[500px] h-[500px] bg-pink-600/15 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 left-10 w-[500px] h-[500px] bg-purple-600/15 rounded-full blur-[130px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 relative z-10">
        
        <!-- Page Header -->
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <span class="glass-tag-transparent px-3.5 py-1.5 rounded-full text-pink-300 border border-pink-500/30 text-xs font-semibold">VIP Fleet Showroom</span>
            <h1 class="text-4xl sm:text-5xl font-black font-heading text-white tracking-tight">
                Luxury Wedding &amp; Guest Vehicles
            </h1>
            <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                Choose from our curated collection of luxury wedding sedans, VIP SUVs, and spacious guest coasters ready for booking with suited chauffeurs and floral ribbons.
            </p>
        </div>

        <!-- Superadmin Bar if logged in -->
        @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->role === 'super_admin' || auth()->user()->isEventAdmin()))
        <div class="glass-card-transparent rounded-2xl p-4 flex items-center justify-between border-indigo-400/30 bg-indigo-950/40">
            <div class="flex items-center space-x-3">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-400 animate-ping"></span>
                <span class="text-xs font-bold text-white">Admin Mode Active: Manage Fleet</span>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.vehicles.create') }}" class="px-3 py-1.5 bg-indigo-600 text-white rounded-xl text-xs font-semibold hover:bg-indigo-500 transition-colors">
                    + Add New Vehicle
                </a>
                <a href="{{ route('admin.vehicles.index') }}" class="px-3 py-1.5 bg-white/10 text-white border border-white/20 rounded-xl text-xs font-semibold hover:bg-white/20 transition-colors">
                    Admin Panel
                </a>
            </div>
        </div>
        @endif

        <!-- Filter & Search Bar (Transparent Glass Panel) -->
        <div class="glass-card-transparent rounded-3xl p-4 sm:p-6 shadow-xl border border-white/20 space-y-4">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Search Input -->
                <form action="{{ route('car-hire.index') }}" method="GET" class="relative w-full md:w-80">
                    @if(request('category') && request('category') !== 'all')
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    <svg class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Maybach, Range Rover, Coaster..." class="w-full pl-10 pr-4 py-2.5 bg-white/10 hover:bg-white/15 focus:bg-white/20 text-white placeholder-slate-400 text-xs rounded-xl border border-white/15 focus:outline-none focus:ring-2 focus:ring-pink-400 transition-all">
                </form>

                <!-- Category Filters (Transparent Glass Buttons) -->
                <div class="flex items-center space-x-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0 scrollbar-none">
                    <a href="{{ route('car-hire.index', array_merge(request()->except('category', 'page'), ['category' => 'all'])) }}" class="tab-chip {{ $category == 'all' ? 'active' : '' }} text-xs whitespace-nowrap">
                        All Fleet ({{ $categoryCounts['all'] }})
                    </a>
                    <a href="{{ route('car-hire.index', array_merge(request()->except('category', 'page'), ['category' => 'luxury_bride_groom'])) }}" class="tab-chip {{ $category == 'luxury_bride_groom' ? 'active' : '' }} text-xs whitespace-nowrap">
                        👰 Bride &amp; Groom ({{ $categoryCounts['luxury_bride_groom'] }})
                    </a>
                    <a href="{{ route('car-hire.index', array_merge(request()->except('category', 'page'), ['category' => 'guest_coaster_bus'])) }}" class="tab-chip {{ $category == 'guest_coaster_bus' ? 'active' : '' }} text-xs whitespace-nowrap">
                        🚌 Guest Coasters ({{ $categoryCounts['guest_coaster_bus'] }})
                    </a>
                    <a href="{{ route('car-hire.index', array_merge(request()->except('category', 'page'), ['category' => 'vip_suv_convoy'])) }}" class="tab-chip {{ $category == 'vip_suv_convoy' ? 'active' : '' }} text-xs whitespace-nowrap">
                        👑 VIP Convoys ({{ $categoryCounts['vip_suv_convoy'] }})
                    </a>
                </div>
            </div>

            <!-- Sub-Bar: Count, Reset & Sort Options -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-white/10 text-xs text-slate-300">
                <div class="flex items-center gap-2">
                    <span class="text-slate-400">Showing <strong class="text-white">{{ $vehicles->total() }}</strong> luxury vehicles</span>
                    @if(request('search') || (request('category') && request('category') !== 'all'))
                        <a href="{{ route('car-hire.index') }}" class="text-[11px] font-semibold text-pink-400 hover:text-pink-300 flex items-center space-x-1 bg-pink-500/10 px-2.5 py-1 rounded-xl border border-pink-500/20">
                            <span>Reset filters</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>

                <div class="flex items-center space-x-1.5">
                    <span class="text-slate-400">Sort:</span>
                    <a href="{{ route('car-hire.index', array_merge(request()->query(), ['sort' => 'featured'])) }}" class="px-2.5 py-1 rounded-lg transition-all {{ $sortBy == 'featured' ? 'bg-white text-slate-950 font-bold shadow-md' : 'bg-white/5 hover:bg-white/15 text-slate-300 border border-white/10' }}">Featured</a>
                    <a href="{{ route('car-hire.index', array_merge(request()->query(), ['sort' => 'price_low'])) }}" class="px-2.5 py-1 rounded-lg transition-all {{ $sortBy == 'price_low' ? 'bg-white text-slate-950 font-bold shadow-md' : 'bg-white/5 hover:bg-white/15 text-slate-300 border border-white/10' }}">Price: Low &rarr; High</a>
                    <a href="{{ route('car-hire.index', array_merge(request()->query(), ['sort' => 'capacity'])) }}" class="px-2.5 py-1 rounded-lg transition-all {{ $sortBy == 'capacity' ? 'bg-white text-slate-950 font-bold shadow-md' : 'bg-white/5 hover:bg-white/15 text-slate-300 border border-white/10' }}">Capacity</a>
                </div>
            </div>
        </div>

        <!-- Fleet Grid (Translucent Specular Glass Cards) -->
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

    // Auto-calculate on initial load
    document.addEventListener('DOMContentLoaded', () => {
        calculateFleetTotal();
    });
</script>
@endsection
