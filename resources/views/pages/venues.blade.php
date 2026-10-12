@extends('layouts.landing')

@section('title', __('common.premium_venues') . ' - Tukio 360')

@section('content')
<div class="min-h-screen bg-slate-50 pt-10 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->role === 'super_admin' || auth()->user()->isEventAdmin()))
        <!-- Superadmin In-Place Management Toolbar -->
        <div class="rounded-2xl border border-indigo-200/80 bg-gradient-to-r from-indigo-950 via-purple-950 to-slate-900 p-4 text-white shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-600/40 border border-indigo-400/40 text-indigo-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A2.25 2.25 0 0018.25 8.25H5.75A2.25 2.25 0 003.5 10.333V21M3 21h18" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-200">Superadmin Control Active &bull; Venues Directory</h4>
                    </div>
                    <p class="text-[11px] text-slate-300">Manage partner locations, upload new venue photos, set capacities, and control public visibility.</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.venues.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 px-3.5 py-2 text-xs font-bold text-white shadow-md shadow-indigo-600/30 transition-all">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span>Manage All Venues (Admin)</span>
                </a>
                <a href="{{ route('admin.venues.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-pink-600 hover:bg-pink-500 px-3.5 py-2 text-xs font-bold text-white shadow-md shadow-pink-600/30 transition-all">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add New Venue</span>
                </a>
            </div>
        </div>
        @endif

        <!-- Page Header -->
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <div class="flex items-center justify-center gap-2">
                <span class="badge-pill badge-indigo">Exclusive Locations</span>
                @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->role === 'super_admin' || auth()->user()->isEventAdmin()))
                    <a href="{{ route('admin.venues.index') }}" class="badge-pill bg-purple-100 text-purple-700 hover:bg-purple-200 transition-colors text-xs font-bold inline-flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                        <span>Admin Console</span>
                    </a>
                @endif
            </div>
            <h1 class="text-4xl sm:text-5xl font-black font-heading text-slate-900 tracking-tight">
                {{ __('common.premium_venues') }}
            </h1>
            <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                {{ __('common.venues_description') }}
            </p>
        </div>

        <!-- Venue Types Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $activeVenues = isset($venues) && $venues->count() > 0 
                    ? $venues 
                    : \App\Models\Venue::where('is_active', true)->orderBy('is_featured', 'desc')->orderBy('sort_order', 'asc')->get();
            @endphp

            @forelse($activeVenues as $venue)
                @php
                    $imgSrc = $venue->image_url_formatted;
                    $subject = $venue->inquiry_subject ?: ('Venue Inquiry - ' . $venue->name);
                    $inquiryUrl = route('contact') . '?subject=' . urlencode($subject);
                    $waVenueText = urlencode("Habari Tukio 360! 🏛️ Ninapenda kufahamu zaidi na kuweka nafasi ya ukumbi wa: *{$venue->name}* ({$venue->location}). Tafadhali naomba maelezo ya bei na upatikanaji wa tarehe yangu.");
                    $waVenueUrl = "https://wa.me/" . ($brandSettings['whatsapp_clean'] ?? '255662199071') . "?text=" . $waVenueText;
                @endphp
                <div class="glass-card rounded-3xl overflow-hidden group flex flex-col justify-between hover:shadow-2xl transition-all duration-300 relative border border-slate-200/80 bg-white shadow-md">
                    <div>
                        <div class="relative h-60 overflow-hidden bg-slate-900">
                            <img src="{{ $imgSrc }}" alt="{{ $venue->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/20"></div>
                            
                            <!-- Category Badge (Top Left) -->
                            <div class="absolute top-4 left-4">
                                <span class="badge-pill bg-purple-600/90 backdrop-blur-md text-white text-xs shadow-md">
                                    {{ $venue->badge ?: $venue->category }}
                                </span>
                            </div>

                            <!-- Top Right Action: Superadmin Edit or Featured Badge -->
                            @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->role === 'super_admin' || auth()->user()->isEventAdmin()))
                                <div class="absolute top-4 right-4 z-20 flex items-center gap-1.5 bg-slate-950/90 backdrop-blur-md px-2.5 py-1 rounded-xl border border-indigo-400/40 shadow-lg text-white">
                                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <a href="{{ route('admin.venues.edit', $venue->id) }}" class="text-[11px] font-bold text-indigo-300 hover:text-white flex items-center gap-1 transition-colors" title="Edit Venue in Admin">
                                        <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        <span>Edit</span>
                                    </a>
                                    @if($venue->is_featured)
                                        <span class="text-white/30 text-[10px]">|</span>
                                        <span class="text-amber-300 text-[10px] font-bold">★ Featured</span>
                                    @endif
                                </div>
                            @elseif($venue->is_featured)
                                <div class="absolute top-4 right-4">
                                    <span class="badge-pill bg-amber-400 text-slate-900 text-xs font-bold shadow-md">
                                        ★ Featured
                                    </span>
                                </div>
                            @endif

                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <h3 class="text-xl font-bold font-heading">{{ $venue->name }}</h3>
                                <p class="text-xs text-slate-300">{{ $venue->capacity ?: 'Flexible Capacity' }} &bull; {{ $venue->location }}</p>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <p class="text-slate-600 text-xs leading-relaxed">
                                {{ $venue->description }}
                            </p>
                            
                            @if(!empty($venue->amenities) && is_array($venue->amenities))
                            <div class="flex flex-wrap gap-1.5 pt-2">
                                @foreach($venue->amenities as $amenity)
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[11px] font-medium rounded-lg">
                                        {{ $amenity }}
                                    </span>
                                @endforeach
                            </div>
                            @endif

                            @if($venue->price_range)
                            <div class="text-xs font-semibold text-slate-500 pt-1">
                                Estimate: <span class="text-emerald-600 font-bold">{{ $venue->price_range }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-6 pt-0 flex items-center space-x-2.5">
                        <a href="{{ $inquiryUrl }}" class="btn-primary flex-1 text-center text-xs py-2.5 shadow-sm">
                            {{ $venue->inquiry_button_text ?: 'Inquire Online' }}
                        </a>
                        @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->role === 'super_admin' || auth()->user()->isEventAdmin()))
                            <a href="{{ route('admin.venues.edit', $venue->id) }}" class="p-2.5 rounded-xl border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 transition-all shadow-xs shrink-0" title="Edit in Admin">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                        @endif
                        <a href="{{ $waVenueUrl }}" target="_blank" class="w-10 h-10 rounded-xl bg-emerald-500/20 hover:bg-emerald-500 text-emerald-600 hover:text-white border border-emerald-500/30 flex items-center justify-center transition-all duration-200 shadow-xs group-hover:scale-105" title="Chat on WhatsApp">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.966.527 1.76.812 2.795.812 3.179 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.999-5.767-5.999zm3.385 8.163c-.144.405-.837.774-1.17.822-.312.043-.699.077-2.029-.472-1.696-.703-2.779-2.457-2.863-2.569-.084-.112-.686-.914-.686-1.743 0-.829.434-1.237.588-1.408.155-.171.339-.214.452-.214.113 0 .226.002.324.007.104.005.243-.039.38.291.144.344.492 1.2.535 1.288.043.088.071.191.014.305-.056.113-.085.184-.17.283-.085.1-.179.223-.255.3-.099.099-.202.207-.087.404.115.197.51 1.042 1.093 1.562.752.669 1.386.877 1.583.974.197.098.312.085.428-.049.115-.133.493-.574.625-.771.132-.197.265-.164.444-.099.18.066 1.139.537 1.336.635.197.099.329.148.378.23.049.083.049.48-.095.885z"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 text-slate-400 glass-card rounded-3xl border border-slate-200">
                    <p class="text-base font-semibold text-slate-600">No partner venues listed yet.</p>
                    @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->role === 'super_admin' || auth()->user()->isEventAdmin()))
                        <a href="{{ route('admin.venues.create') }}" class="btn-primary text-xs mt-3 inline-block">
                            + Add First Venue
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Venue Benefits Feature Grid -->
        <div class="glass-card rounded-3xl p-8 sm:p-12 border border-slate-200">
            <div class="text-center max-w-2xl mx-auto mb-10 space-y-2">
                <span class="badge-pill badge-indigo">{{ __('common.what_we_offer') }}</span>
                <h2 class="text-2xl sm:text-3xl font-black font-heading text-slate-900">
                    Why Book Venues Through Tukio 360?
                </h2>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-center sm:text-left">
                <div class="space-y-2">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold mx-auto sm:mx-0">
                        ✓
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm">{{ __('common.verified_venues') }}</h4>
                    <p class="text-slate-500 text-xs leading-relaxed">{{ __('common.verified_venues_desc') }}</p>
                </div>
                <div class="space-y-2">
                    <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center font-bold mx-auto sm:mx-0">
                        📅
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm">{{ __('common.flexible_booking') }}</h4>
                    <p class="text-slate-500 text-xs leading-relaxed">{{ __('common.flexible_booking_desc') }}</p>
                </div>
                <div class="space-y-2">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold mx-auto sm:mx-0">
                        📞
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm">{{ __('common.support_24_7') }}</h4>
                    <p class="text-slate-500 text-xs leading-relaxed">{{ __('common.support_24_7_desc') }}</p>
                </div>
                <div class="space-y-2">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold mx-auto sm:mx-0">
                        💎
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm">{{ __('common.best_prices') }}</h4>
                    <p class="text-slate-500 text-xs leading-relaxed">{{ __('common.best_prices_desc') }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
