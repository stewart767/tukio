@extends('layouts.dashboard')

@section('title', 'Venues & Spaces Management')
@section('page-title', 'Venues & Event Locations')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-indigo-50/40 to-slate-50 p-8 sm:p-10 shadow-xl backdrop-blur-md">
        <div class="absolute -top-12 -right-12 h-44 w-44 rounded-full bg-gradient-to-br from-indigo-400/20 to-blue-400/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 h-48 w-48 rounded-full bg-gradient-to-tr from-emerald-400/15 to-teal-400/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full border border-indigo-200/60 bg-indigo-50/80 px-3.5 py-1 text-xs font-semibold text-indigo-700 shadow-xs">
                    <span class="inline-flex h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    Venue &amp; Location Directory
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 via-blue-600 to-teal-600 text-white shadow-lg shadow-indigo-500/25">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A2.25 2.25 0 0018.25 8.25H5.75A2.25 2.25 0 003.5 10.333V21M3 21h18" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Venues &amp; Event Spaces</h1>
                        <p class="mt-1 text-sm sm:text-base text-slate-600 max-w-2xl">Manage premium partner venues, banquet halls, conference centers, beach lawns, capacities, amenities, and inquiries.</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('venues') }}" target="_blank" class="btn-secondary text-xs sm:text-sm py-2.5 px-4 shadow-sm">
                    <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    View Public /venues
                </a>
                <a href="{{ route('admin.venues.create') }}" class="btn-primary text-xs sm:text-sm py-2.5 px-5 shadow-lg shadow-indigo-500/20">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Add New Venue
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
    <div class="relative overflow-hidden rounded-2xl border border-emerald-200 bg-gradient-to-r from-emerald-50 via-teal-50 to-green-50 px-6 py-4 text-emerald-900 shadow-md">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-sm font-semibold">{{ session('success') }}</div>
        </div>
    </div>
    @endif

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-sm backdrop-blur-md flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Venues</div>
                <div class="text-3xl font-black text-slate-900 font-heading">{{ $stats['total'] }}</div>
                <div class="text-xs text-slate-500">Listed partner locations</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                📍
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-sm backdrop-blur-md flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-bold uppercase tracking-wider text-emerald-600">Active / Live</div>
                <div class="text-3xl font-black text-emerald-600 font-heading">{{ $stats['active'] }}</div>
                <div class="text-xs text-slate-500">Publicly visible venues</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                🏛️
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-sm backdrop-blur-md flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-bold uppercase tracking-wider text-amber-600">Featured Venues</div>
                <div class="text-3xl font-black text-amber-600 font-heading">{{ $stats['featured'] }}</div>
                <div class="text-xs text-slate-500">Highlighted on top</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                ⭐
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-sm backdrop-blur-md flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-bold uppercase tracking-wider text-teal-600">Outdoor &amp; Beach</div>
                <div class="text-3xl font-black text-teal-600 font-heading">{{ $stats['outdoor'] }}</div>
                <div class="text-xs text-slate-500">Gardens &amp; seaside lawns</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl">
                🏖️
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-md backdrop-blur-md">
        <form action="{{ route('admin.venues.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="relative w-full md:w-96">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by venue name, location, or amenities..." 
                       class="form-input-modern text-sm pl-10">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <select name="category" class="form-input-modern text-xs py-2 w-full sm:w-44" onchange="this.form.submit()">
                    <option value="all">All Categories</option>
                    <option value="Indoor Ballrooms" {{ request('category') === 'Indoor Ballrooms' ? 'selected' : '' }}>Indoor Ballrooms</option>
                    <option value="Beach & Garden" {{ request('category') === 'Beach & Garden' ? 'selected' : '' }}>Beach &amp; Garden</option>
                    <option value="Conference Centers" {{ request('category') === 'Conference Centers' ? 'selected' : '' }}>Conference Centers</option>
                </select>

                <select name="status" class="form-input-modern text-xs py-2 w-full sm:w-40" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="featured" {{ request('status') === 'featured' ? 'selected' : '' }}>Featured Only</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Hidden Only</option>
                </select>

                @if(request()->hasAny(['search', 'category', 'status']))
                    <a href="{{ route('admin.venues.index') }}" class="btn-secondary text-xs py-2 px-3">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Venues Grid -->
    @if($venues->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
        @foreach($venues as $venue)
        <div class="group relative rounded-3xl border border-slate-200/80 bg-white/95 backdrop-blur-md shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col justify-between">
            
            <div>
                <!-- Image Banner -->
                <div class="relative h-56 w-full overflow-hidden bg-slate-900">
                    <img src="{{ $venue->image_url_formatted }}" alt="{{ $venue->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent"></div>

                    <!-- Top Badge & Featured Star -->
                    <div class="absolute top-3.5 left-3.5 right-3.5 flex items-center justify-between z-10">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-wide backdrop-blur-md bg-white/90 text-slate-800 shadow-sm border border-white/50">
                            {{ $venue->badge ?: $venue->category }}
                        </span>

                        @if($venue->is_featured)
                            <span class="w-8 h-8 rounded-xl bg-amber-400 text-slate-900 flex items-center justify-center font-bold text-sm shadow-md" title="Featured Venue">
                                ★
                            </span>
                        @endif
                    </div>

                    <!-- Bottom Info over Image -->
                    <div class="absolute bottom-3 left-4 right-4 text-white z-10">
                        <h3 class="text-lg font-bold font-heading line-clamp-1 text-white">
                            {{ $venue->name }}
                        </h3>
                        <p class="text-xs text-slate-300 flex items-center gap-1.5 mt-0.5">
                            <span>👥 {{ $venue->capacity ?: 'Flexible Capacity' }}</span>
                            <span>&bull;</span>
                            <span>📍 {{ $venue->location }}</span>
                        </p>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-6 space-y-4">
                    <p class="text-slate-600 text-xs leading-relaxed line-clamp-3">
                        {{ $venue->description ?: 'No detailed description entered.' }}
                    </p>

                    <!-- Amenities / Features -->
                    @if(!empty($venue->amenities) && is_array($venue->amenities))
                    <div class="flex flex-wrap gap-1.5 pt-2 border-t border-slate-100">
                        @foreach($venue->amenities as $amenity)
                            <span class="text-[11px] font-medium bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded-md">
                                {{ $amenity }}
                            </span>
                        @endforeach
                    </div>
                    @endif

                    @if($venue->price_range)
                    <div class="pt-2 flex items-center justify-between text-xs font-semibold text-slate-700">
                        <span class="text-slate-400 font-normal">Pricing:</span>
                        <span class="text-emerald-600 font-bold">{{ $venue->price_range }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions Footer -->
            <div class="p-6 pt-0 border-t border-slate-100 mt-2 space-y-3">
                
                <!-- Toggle Buttons Row -->
                <div class="flex items-center justify-between text-xs pt-3">
                    <form action="{{ route('admin.venues.toggle-active', $venue) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-lg {{ $venue->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $venue->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                            {{ $venue->is_active ? 'Live' : 'Hidden' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.venues.toggle-featured', $venue) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-lg {{ $venue->is_featured ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                            {{ $venue->is_featured ? '★ Featured' : '☆ Standard' }}
                        </button>
                    </form>
                </div>

                <!-- Primary Action Buttons -->
                <div class="flex items-center gap-2 pt-1">
                    <a href="{{ route('admin.venues.edit', $venue) }}" class="btn-primary text-xs py-2 px-3 flex-1 justify-center shadow-xs">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Venue
                    </a>

                    <form action="{{ route('admin.venues.destroy', $venue) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete venue \'{{ $venue->name }}\'?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors" title="Delete Venue">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $venues->links() }}
    </div>

    @else
    <!-- Empty State -->
    <div class="rounded-3xl border border-dashed border-slate-300 bg-white/70 p-12 text-center max-w-lg mx-auto space-y-4">
        <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl mx-auto shadow-sm">
            🏛️
        </div>
        <h3 class="text-lg font-bold text-slate-900 font-heading">No Venues Found</h3>
        <p class="text-xs text-slate-500 leading-relaxed">No venues match your current search or category filter query.</p>
        <a href="{{ route('admin.venues.create') }}" class="btn-primary text-xs py-2.5 px-4 inline-flex">
            Add First Venue
        </a>
    </div>
    @endif
</div>
@endsection
