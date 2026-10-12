@extends('layouts.dashboard')

@section('title', 'Car Hire Bookings & Inquiries')
@section('page-title', 'Charter & Bookings Desk')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Executive Mobility Header -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-indigo-50/40 to-slate-50 p-8 sm:p-10 shadow-xl backdrop-blur-md">
        <div class="absolute -top-12 -right-12 h-44 w-44 rounded-full bg-gradient-to-br from-purple-400/20 to-indigo-400/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 h-48 w-48 rounded-full bg-gradient-to-tr from-pink-400/15 to-amber-400/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full border border-purple-200/60 bg-purple-50/80 px-3.5 py-1 text-xs font-semibold text-purple-700 shadow-xs">
                    <span class="inline-flex h-2 w-2 rounded-full bg-purple-500 animate-pulse"></span>
                    Mobility Logistics &amp; Reservations
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-purple-600 via-indigo-600 to-pink-600 text-white shadow-lg shadow-purple-500/25">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Car Hire Bookings &amp; Inquiries</h1>
                        <p class="mt-1 text-sm sm:text-base text-slate-600 max-w-xl">Review inbound wedding charter requests, assign chauffeurs, and track customer reservations.</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.vehicles.index') }}" class="btn-secondary text-sm">
                    <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.175V3.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75" />
                    </svg>
                    <span>Manage Fleet Items</span>
                </a>
            </div>
        </div>

        <!-- Metric KPI Cards -->
        <div class="relative z-10 mt-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div class="rounded-2xl border border-slate-200/60 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-500">
                    <span>Total Bookings</span>
                    <span class="text-purple-600">📋</span>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['total'] }}</div>
                <div class="mt-1 text-[11px] text-slate-400">All recorded charter orders</div>
            </div>

            <div class="rounded-2xl border border-amber-100 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-amber-600">
                    <span>Pending Review</span>
                    <span>⏳</span>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['pending'] }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Awaiting confirmation</div>
            </div>

            <div class="rounded-2xl border border-blue-100 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-blue-600">
                    <span>Confirmed</span>
                    <span>✓</span>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['confirmed'] }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Scheduled for transport</div>
            </div>

            <div class="rounded-2xl border border-emerald-100 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-emerald-600">
                    <span>Completed</span>
                    <span>🎉</span>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['completed'] }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Successfully delivered trips</div>
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
        <form action="{{ route('admin.car-bookings.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-72">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ref, customer, phone..." 
                       class="form-input-modern pl-10 text-xs sm:text-sm py-2">
            </div>
            
            <select name="status" class="form-input-modern text-xs sm:text-sm py-2 w-full sm:w-auto">
                <option value="all">All Booking Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending Review</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>✓ Confirmed</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>🚗 In Progress</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>🎉 Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
            </select>

            <button type="submit" class="btn-primary text-xs px-4 py-2">
                Filter
            </button>

            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.car-bookings.index') }}" class="text-xs text-slate-500 hover:text-indigo-600 font-semibold underline">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Bookings Table Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md overflow-hidden">
        <div class="border-b border-slate-100 bg-slate-50/70 px-6 sm:px-8 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900 font-heading">Reservation Inquiries &amp; Charters</h2>
                <p class="text-xs text-slate-500 mt-0.5">Track wedding convoy arrangements, pickup dates, and client invoices</p>
            </div>
            <div class="text-xs font-semibold text-slate-500">
                Active Queue: <span class="font-bold text-amber-600">{{ $stats['pending'] }}</span> pending review
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-left text-xs sm:text-sm">
                <thead class="bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 sm:px-8 py-4">Booking Ref</th>
                        <th class="px-6 py-4">Charter Vehicle</th>
                        <th class="px-6 py-4">Customer Contact</th>
                        <th class="px-6 py-4">Event Date</th>
                        <th class="px-6 py-4">Total Amount</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 sm:px-8 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-normal">
                    @forelse($bookings as $bk)
                    @php
                        $st = $bk->status_badge;
                    @endphp
                    <tr class="group hover:bg-purple-50/30 transition-colors duration-200">
                        <td class="px-6 sm:px-8 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.car-bookings.show', $bk->id) }}" class="font-mono font-bold text-indigo-600 hover:text-indigo-800 text-sm">
                                {{ $bk->booking_reference }}
                            </a>
                            <span class="text-[10px] text-slate-400 block">{{ $bk->created_at->diffForHumans() }}</span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-slate-900">{{ $bk->vehicle->name ?? 'Vehicle Item' }}</div>
                            @if($bk->need_guest_coasters)
                                <span class="text-[11px] text-amber-700 font-semibold block">🚌 +{{ $bk->guest_coasters_count }} Guest Coaster(s)</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-slate-900">{{ $bk->customer_name }}</div>
                            <div class="text-xs text-slate-500 flex items-center gap-1.5 mt-0.5">
                                <span class="font-mono">{{ $bk->customer_phone }}</span>
                                @if($bk->customer_phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $bk->customer_phone) }}" target="_blank" class="text-emerald-600 hover:text-emerald-700" title="WhatsApp Customer">
                                        💬
                                    </a>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-slate-900">{{ $bk->start_date?->format('d M Y') }}</div>
                            <div class="text-xs text-slate-500">{{ $bk->days_count }} Day(s) &bull; {{ ucfirst(str_replace('_', ' ', $bk->event_type ?? 'Wedding')) }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap font-mono text-xs font-bold text-slate-900">
                            {{ $bk->formatted_price }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $st['bg'] }} border shadow-2xs">
                                <span class="h-1.5 w-1.5 rounded-full {{ $st['dot'] }}"></span>
                                <span>{{ $st['label'] }}</span>
                            </span>
                        </td>

                        <td class="px-6 sm:px-8 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.car-bookings.show', $bk->id) }}" class="btn-secondary text-xs px-3 py-1.5">
                                    Manage
                                </a>

                                <form action="{{ route('admin.car-bookings.destroy', $bk->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this booking record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Delete Booking">
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
                        <td colspan="7" class="px-8 py-16 text-center">
                            <div class="max-w-sm mx-auto text-center space-y-4">
                                <div class="w-16 h-16 bg-purple-50 rounded-2xl mx-auto flex items-center justify-center text-purple-600 text-2xl shadow-inner">
                                    📋
                                </div>
                                <h3 class="text-base font-bold text-slate-900">No bookings recorded</h3>
                                <p class="text-xs text-slate-500">Inbound charter requests submitted from the car hire catalog will automatically appear here for review and confirmation.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
        <div class="px-6 sm:px-8 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
