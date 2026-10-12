@extends('layouts.dashboard')

@section('title', 'Fleet & Wedding Cars Management')
@section('page-title', 'Fleet & Mobility Hub')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Executive Fleet Header -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-indigo-50/40 to-slate-50 p-8 sm:p-10 shadow-xl backdrop-blur-md">
        <div class="absolute -top-12 -right-12 h-44 w-44 rounded-full bg-gradient-to-br from-pink-400/20 to-purple-400/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 h-48 w-48 rounded-full bg-gradient-to-tr from-indigo-400/15 to-blue-400/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full border border-pink-200/60 bg-pink-50/80 px-3.5 py-1 text-xs font-semibold text-pink-700 shadow-xs">
                    <span class="inline-flex h-2 w-2 rounded-full bg-pink-500 animate-pulse"></span>
                    VIP Logistics &amp; Transport Hub
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-600 text-white shadow-lg shadow-indigo-500/25">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.175V3.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Vehicles &amp; Fleet Management</h1>
                        <p class="mt-1 text-sm sm:text-base text-slate-600 max-w-xl">Curate luxury bride &amp; groom sedans, VIP SUV convoys, guest shuttles, and track daily charter availability.</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.car-bookings.index') }}" class="btn-secondary text-sm">
                    <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>View Bookings</span>
                </a>
                <a href="{{ route('admin.vehicles.create') }}" class="group inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-pink-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-indigo-600/30 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-600/40">
                    <svg class="h-4 w-4 transition-transform duration-300 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                    </svg>
                    <span>Add New Vehicle</span>
                </a>
            </div>
        </div>

        <!-- Metric KPI Cards -->
        <div class="relative z-10 mt-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div class="rounded-2xl border border-slate-200/60 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-500">
                    <span>Total Fleet</span>
                    <span class="text-indigo-600">🚗</span>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['total'] }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Total registered units</div>
            </div>

            <div class="rounded-2xl border border-pink-100 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-pink-600">
                    <span>Bride &amp; Groom</span>
                    <span>👰</span>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['luxury'] }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Premium wedding cars</div>
            </div>

            <div class="rounded-2xl border border-amber-100 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-amber-600">
                    <span>Coasters &amp; Buses</span>
                    <span>🚌</span>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['coasters'] }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Guest group shuttles</div>
            </div>

            <div class="rounded-2xl border border-emerald-100 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-emerald-600">
                    <span>Available Now</span>
                    <span>🟢</span>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['available'] }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Ready for charter booking</div>
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

    <!-- Search & Filter Controls -->
    <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-4 sm:p-5 shadow-sm backdrop-blur-md flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.vehicles.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search vehicle or brand..." 
                       class="form-input-modern pl-10 text-xs sm:text-sm py-2">
            </div>
            
            <select name="category" class="form-input-modern text-xs sm:text-sm py-2 w-full sm:w-auto">
                <option value="all">All Fleet Categories</option>
                <option value="luxury_bride_groom" {{ request('category') == 'luxury_bride_groom' ? 'selected' : '' }}>👰 Bride &amp; Groom Luxury</option>
                <option value="guest_coaster_bus" {{ request('category') == 'guest_coaster_bus' ? 'selected' : '' }}>🚌 Coasters &amp; Buses</option>
                <option value="vip_suv_convoy" {{ request('category') == 'vip_suv_convoy' ? 'selected' : '' }}>👑 VIP SUV Convoys</option>
                <option value="host_logistics" {{ request('category') == 'host_logistics' ? 'selected' : '' }}>📦 Logistics &amp; Utility</option>
            </select>

            <button type="submit" class="btn-primary text-xs px-4 py-2">
                Apply Filters
            </button>

            @if(request()->hasAny(['search', 'category']))
                <a href="{{ route('admin.vehicles.index') }}" class="text-xs text-slate-500 hover:text-indigo-600 font-semibold underline">
                    Clear Filters
                </a>
            @endif
        </form>
    </div>

    <!-- Vehicles Table Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md overflow-hidden">
        <div class="border-b border-slate-100 bg-slate-50/70 px-6 sm:px-8 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900 font-heading">Fleet Inventory</h2>
                <p class="text-xs text-slate-500 mt-0.5">Manage transport items, passenger capacities, pricing, and live availability</p>
            </div>
            <div class="text-xs font-semibold text-slate-500">
                Total Available: <span class="font-bold text-emerald-600">{{ $stats['available'] }}</span> of {{ $stats['total'] }} units
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-left text-xs sm:text-sm">
                <thead class="bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 sm:px-8 py-4">Vehicle Model</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Capacity &amp; Fuel</th>
                        <th class="px-6 py-4">Daily Rate</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 sm:px-8 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-normal">
                    @forelse($vehicles as $car)
                    @php
                        $badge = $car->category_badge;
                    @endphp
                    <tr class="group hover:bg-indigo-50/30 transition-colors duration-200">
                        <td class="px-6 sm:px-8 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3.5">
                                <div class="relative h-14 w-20 rounded-xl overflow-hidden border border-slate-200 shrink-0 bg-slate-100 shadow-xs">
                                    <img src="{{ $car->image_url_formatted }}" alt="{{ $car->name }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors flex items-center gap-2">
                                        <span>{{ $car->name }}</span>
                                        @if($car->is_featured)
                                            <span class="px-1.5 py-0.5 rounded-md bg-amber-100 text-amber-800 text-[10px] font-black uppercase">⭐ Featured</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $car->brand }} &bull; {{ $car->model_year ?? 'Current' }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono mt-0.5">⚙️ {{ ucfirst($car->transmission) }} &bull; 🎨 {{ $car->color ?? 'White' }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $badge['bg'] }} border text-slate-800 shadow-2xs">
                                <span>{{ $badge['icon'] }}</span>
                                <span>{{ $badge['label'] }}</span>
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs font-semibold text-slate-800 flex items-center gap-1">
                                <span>👥</span> {{ $car->seating_capacity }} Passengers
                            </div>
                            <div class="text-[11px] text-slate-400 mt-0.5">
                                ⛽ {{ ucfirst($car->fuel_type) }} &bull; 🧳 {{ $car->luggage_capacity ?? 'Standard' }}
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap font-mono text-xs font-bold text-slate-900">
                            {{ $car->formatted_price }}
                            @if($car->price_per_hour)
                                <div class="text-[10px] text-slate-400 font-normal">TZS {{ number_format($car->price_per_hour) }}/hr</div>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.vehicles.toggle-active', $car->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all duration-200 {{ $car->is_available ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100' }}" title="Click to toggle availability">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $car->is_available ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    <span>{{ $car->is_available ? 'Available' : 'Unavailable' }}</span>
                                </button>
                            </form>
                        </td>

                        <td class="px-6 sm:px-8 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('car-hire.show', $car->slug) }}" target="_blank" class="p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="View Public Page">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>

                                <a href="{{ route('admin.vehicles.edit', $car->id) }}" class="p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit Vehicle">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <form action="{{ route('admin.vehicles.destroy', $car->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove {{ $car->name }} from the fleet?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Delete Vehicle">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-16 text-center">
                            <div class="max-w-sm mx-auto text-center space-y-4">
                                <div class="w-16 h-16 bg-pink-50 rounded-2xl mx-auto flex items-center justify-center text-pink-600 text-2xl shadow-inner">
                                    🚗
                                </div>
                                <h3 class="text-base font-bold text-slate-900">No vehicles found</h3>
                                <p class="text-xs text-slate-500">No vehicles match your active search filters. Add vehicles to allow hosts and guests to charter transport for their celebrations.</p>
                                <a href="{{ route('admin.vehicles.create') }}" class="btn-primary text-xs">
                                    Add First Vehicle
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vehicles->hasPages())
        <div class="px-6 sm:px-8 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $vehicles->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
