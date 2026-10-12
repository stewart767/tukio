@extends('layouts.host')

@section('title', 'Guest Management')
@section('page-title', 'Guest Management')

@section('content')
<div class="space-y-8 animate-fade-in">
    
    <!-- Page Header & Guest Allocation Bar -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xl shadow-slate-200/30 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            <h1 class="text-2xl sm:text-3xl font-black font-heading text-slate-900 tracking-tight">Guest List &amp; RSVPs</h1>
            <p class="text-xs sm:text-sm text-slate-500">Manage your event invitees, assign invitation codes, and track check-in status.</p>
            
            <!-- Progress Bar -->
            <div class="pt-2 max-w-md">
                <div class="flex items-center justify-between text-xs font-semibold mb-1.5">
                    <span class="text-slate-600">
                        <strong class="text-indigo-600">{{ $stats['total'] }}</strong> of <strong class="text-slate-800">{{ $stats['allowed'] }}</strong> guest slots used
                    </span>
                    @if($stats['remaining'] > 0)
                        <span class="text-emerald-600 font-bold">&check; {{ $stats['remaining'] }} slots free</span>
                    @else
                        <span class="text-rose-600 font-bold">&excl; Limit reached</span>
                    @endif
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                    @php
                        $percentage = min(100, $stats['allowed'] > 0 ? round(($stats['total'] / $stats['allowed']) * 100) : 0);
                    @endphp
                    <div class="h-2.5 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 transition-all duration-500" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <a href="{{ route('host.guests.create') }}" class="modern-button modern-button-primary {{ $stats['remaining'] <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}" 
               @if($stats['remaining'] <= 0) onclick="return false;" title="Guest limit reached" @endif>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add New Guest</span>
            </a>
            <a href="{{ route('host.invitations.create') }}" class="modern-button modern-button-secondary">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                <span>Dispatch Cards</span>
            </a>
        </div>
    </div>

    <!-- Stats Overview Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        <!-- Total -->
        <div class="host-stat-card p-5">
            <div class="flex items-center justify-between">
                <div class="w-11 h-11 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-slate-400 uppercase">Total</span>
            </div>
            <p class="text-2xl sm:text-3xl font-black font-heading text-slate-900 mt-3">{{ $stats['total'] }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Invited Guests</p>
        </div>

        <!-- Confirmed / Attended -->
        <div class="host-stat-card p-5">
            <div class="flex items-center justify-between">
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-emerald-600 uppercase bg-emerald-50 px-2 py-0.5 rounded-full">Confirmed</span>
            </div>
            <p class="text-2xl sm:text-3xl font-black font-heading text-slate-900 mt-3">{{ $stats['confirmed'] }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Attending / Verified</p>
        </div>

        <!-- Pending -->
        <div class="host-stat-card p-5">
            <div class="flex items-center justify-between">
                <div class="w-11 h-11 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-amber-600 uppercase bg-amber-50 px-2 py-0.5 rounded-full">Pending</span>
            </div>
            <p class="text-2xl sm:text-3xl font-black font-heading text-slate-900 mt-3">{{ $stats['pending'] }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Awaiting RSVP</p>
        </div>

        <!-- Declined -->
        <div class="host-stat-card p-5">
            <div class="flex items-center justify-between">
                <div class="w-11 h-11 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-rose-600 uppercase bg-rose-50 px-2 py-0.5 rounded-full">Declined</span>
            </div>
            <p class="text-2xl sm:text-3xl font-black font-heading text-slate-900 mt-3">{{ $stats['declined'] }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Cannot attend</p>
        </div>

    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-xl shadow-slate-200/30">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <div class="flex flex-1 flex-col sm:flex-row items-center gap-3">
                <div class="relative w-full sm:w-80">
                    <input type="text" id="guestSearchInput" placeholder="Search by name, phone, code..." 
                           class="modern-input pl-10" onkeyup="filterGuestTable()">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <select id="guestStatusFilter" class="modern-select w-full sm:w-44" onchange="filterGuestTable()">
                    <option value="">All Statuses</option>
                    <option value="confirmed">Confirmed / Attended</option>
                    <option value="pending">Pending</option>
                    <option value="declined">Declined</option>
                </select>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('host.guests.create') }}" class="modern-button modern-button-secondary">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    <span>Import Excel</span>
                </a>
            </div>

        </div>
    </div>

    <!-- Guests Table -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl shadow-slate-200/30 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-bold font-heading text-slate-900">All Registered Guests</h3>
            <span class="text-xs font-semibold text-slate-400">{{ $guests->count() }} Total</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100" id="guestsTable">
                <thead class="bg-slate-50/75">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Guest</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Invitation Code</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Pass Type</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Phone / WhatsApp</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($guests as $guest)
                        <tr class="hover:bg-slate-50/60 transition-colors guest-row" data-status="{{ strtolower($guest->status ?? 'pending') }}">
                            
                            <!-- Guest Name + Avatar -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-50 to-purple-50 border border-indigo-100/80 flex items-center justify-center text-indigo-700 font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($guest->full_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900 guest-name">{{ $guest->full_name }}</div>
                                        <div class="text-[11px] text-slate-400">Added {{ $guest->created_at ? $guest->created_at->format('M d') : 'Recently' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Invitation Code -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 border border-indigo-100/80 font-mono text-xs font-bold text-indigo-700">
                                    <span>{{ $guest->invitation_code ?? 'N/A' }}</span>
                                </div>
                            </td>

                            <!-- Pass Type -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    @if(($guest->guest_type ?? 'single') === 'double') bg-purple-50 text-purple-700 border border-purple-200/60
                                    @else bg-slate-100 text-slate-700 border border-slate-200
                                    @endif">
                                    {{ ucfirst($guest->guest_type ?? 'single') }}
                                </span>
                            </td>

                            <!-- Phone -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs font-semibold text-slate-700 font-mono guest-phone">{{ $guest->phone }}</span>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold
                                    @if($guest->status === 'attended' || $guest->status === 'confirmed') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($guest->status === 'pending') bg-amber-50 text-amber-700 border border-amber-200
                                    @elseif($guest->status === 'declined') bg-rose-50 text-rose-700 border border-rose-200
                                    @else bg-slate-100 text-slate-700
                                    @endif">
                                    {{ ucfirst($guest->status ?? 'Pending') }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('host.guests.edit', $guest) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit Guest">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('host.guests.destroy', $guest) }}" method="POST" class="inline" onsubmit="return confirm('Remove {{ $guest->full_name }} from guest list?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Delete Guest">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-500 flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 18.75c0-.621-.503-1.125-1.125-1.125H5.625c-.621 0-1.125.503-1.125 1.125v2.25c0 .621.503 1.125 1.125 1.125h11.25c.621 0 1.125-.503 1.125-1.125v-2.25zM15.75 10.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-900 mb-1">No Guests Added Yet</h3>
                                    <p class="text-xs text-slate-500 mb-6">Start by adding your invitees or importing from an Excel spreadsheet.</p>
                                    <a href="{{ route('host.guests.create') }}" class="modern-button modern-button-primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        <span>Add First Guest</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Filter JavaScript -->
<script>
function filterGuestTable() {
    const searchVal = (document.getElementById('guestSearchInput')?.value || '').toLowerCase();
    const statusVal = (document.getElementById('guestStatusFilter')?.value || '').toLowerCase();
    const rows = document.querySelectorAll('.guest-row');

    rows.forEach(row => {
        const name = (row.querySelector('.guest-name')?.textContent || '').toLowerCase();
        const phone = (row.querySelector('.guest-phone')?.textContent || '').toLowerCase();
        const status = (row.getAttribute('data-status') || '').toLowerCase();

        const matchesSearch = !searchVal || name.includes(searchVal) || phone.includes(searchVal);
        const matchesStatus = !statusVal || status === statusVal || (statusVal === 'confirmed' && (status === 'attended' || status === 'confirmed'));

        if (matchesSearch && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endsection
