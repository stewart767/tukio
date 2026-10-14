@extends('layouts.dashboard')

@section('title', 'Hosts Management')
@section('page-title', 'Host Workspace & Onboarding')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Radiant Host Header -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-amber-50/40 to-slate-50 p-8 sm:p-10 shadow-xl backdrop-blur-md">
        <div class="absolute -top-12 -right-12 h-44 w-44 rounded-full bg-gradient-to-br from-amber-400/20 to-orange-400/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 h-48 w-48 rounded-full bg-gradient-to-tr from-indigo-400/15 to-purple-400/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full border border-amber-200/60 bg-amber-50/80 px-3.5 py-1 text-xs font-semibold text-amber-700 shadow-xs">
                    <span class="inline-flex h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Tenant &amp; Host Hub
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-amber-500 via-orange-500 to-indigo-600 text-white shadow-lg shadow-orange-500/25">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12.5l3.5-2.625a2.25 2.25 0 012.75 0l3.5 2.625m-9.75 0V15a2.25 2.25 0 002.25 2.25h2.25M2.25 12.5v4.125A2.25 2.25 0 004.5 18.875h2.25M21.75 12.5l-3.5-2.625a2.25 2.25 0 00-2.75 0l-3.5 2.625m9.75 0V15a2.25 2.25 0 01-2.25 2.25H16m5.75-4.75v4.125A2.25 2.25 0 0119.5 18.875H17.25M11.25 18.75h1.5" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Host Accounts &amp; Onboarding</h1>
                        <p class="mt-1 text-sm sm:text-base text-slate-600 max-w-xl">Supervise registered event organizers, assign field staff coordinators, and track live budget configurations.</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.hosts.create') }}" class="group inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-amber-500 via-orange-500 to-indigo-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-orange-500/25 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-orange-500/35">
                    <svg class="h-4 w-4 transition-transform duration-300 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                    </svg>
                    <span>Register New Host</span>
                </a>
            </div>
        </div>

        <!-- Metric KPI Cards -->
        <div class="relative z-10 mt-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div class="rounded-2xl border border-slate-200/60 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-500">
                    <span>Total Hosts</span>
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ $metrics['total_hosts'] }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Total accounts registered</div>
            </div>

            <div class="rounded-2xl border border-emerald-100 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-emerald-600">
                    <span>Active Portals</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ $metrics['active_hosts'] }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Permitted dashboard access</div>
            </div>

            <div class="rounded-2xl border border-indigo-100 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-indigo-600">
                    <span>Configured Events</span>
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ $metrics['configured_hosts'] }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Completed event setup</div>
            </div>

            <div class="rounded-2xl border border-purple-100 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-purple-600">
                    <span>Readiness Rate</span>
                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">
                    {{ $metrics['total_hosts'] > 0 ? round(($metrics['configured_hosts'] / $metrics['total_hosts']) * 100) : 0 }}%
                </div>
                <div class="mt-1 text-[11px] text-slate-400">Setup completion ratio</div>
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

    <!-- Modern Hosts Table Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md overflow-hidden">
        <div class="border-b border-slate-100 bg-slate-50/70 px-6 sm:px-8 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900 font-heading">Registered Host Tenants</h2>
                <p class="text-xs text-slate-500 mt-0.5">Manage event host accounts, guest capacities, and staff deployments</p>
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                <span>Real-time Onboarding Sync</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-left text-xs sm:text-sm">
                <thead class="bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 sm:px-8 py-4">Host Organizer</th>
                        <th class="px-6 py-4">Event Type</th>
                        <th class="px-6 py-4">Guest Capacity</th>
                        <th class="px-6 py-4">Event Budget</th>
                        <th class="px-6 py-4">Status &amp; Setup</th>
                        <th class="px-6 sm:px-8 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-normal">
                    @forelse ($hosts as $host)
                    <tr class="group hover:bg-amber-50/30 transition-colors duration-200">
                        <td class="px-6 sm:px-8 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3.5">
                                <div class="relative flex-shrink-0">
                                    <div class="h-11 w-11 rounded-2xl bg-gradient-to-tr from-amber-500 via-orange-500 to-indigo-600 flex items-center justify-center font-bold text-sm text-white shadow-md group-hover:scale-105 transition-transform duration-200">
                                        {{ strtoupper(substr($host->full_name, 0, 1)) }}
                                    </div>
                                    <span class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full {{ $host->is_active ? 'bg-emerald-500' : 'bg-slate-400' }} border-2 border-white"></span>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 group-hover:text-amber-600 transition-colors flex items-center gap-2">
                                        <span>{{ $host->full_name }}</span>
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $host->email }}</div>
                                    <div class="text-[11px] font-mono text-slate-400 flex items-center gap-2 mt-0.5">
                                        <span>{{ $host->phone }}</span>
                                        @if($host->phone)
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $host->phone) }}" target="_blank" class="text-emerald-600 hover:text-emerald-700" title="Chat on WhatsApp">
                                                <svg class="w-3.5 h-3.5 inline" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.54 1.772.814 2.79.814 3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.586-5.766-5.767-5.766zm0 10.375c-.881 0-1.64-.23-2.348-.684l-.168-.108-1.577.413.421-1.538-.117-.187c-.503-.805-.769-1.52-.769-2.505 0-2.541 2.068-4.608 4.608-4.608 2.54 0 4.608 2.067 4.608 4.608 0 2.541-2.067 4.609-4.608 4.609z"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/80 shadow-2xs">
                                <span>{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $host->event_type)) }}</span>
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-slate-100 font-bold text-slate-700 text-xs">
                                <span>{{ number_format($host->number_of_guests) }} Guests</span>
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap font-mono text-xs font-bold text-slate-800">
                            TZS {{ number_format($host->total_cost, 0) }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $host->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $host->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    {{ $host->is_active ? 'Active Access' : 'Inactive' }}
                                </span>
                                
                                <span class="inline-flex items-center gap-1 text-[11px] font-medium {{ $host->event_configured ? 'text-indigo-600' : 'text-slate-400' }}">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ $host->event_configured ? 'Setup Complete' : 'Awaiting Details' }}
                                </span>

                                @if($host->hasFinishedEvent())
                                    <span class="inline-flex items-center gap-1 text-[10px] text-orange-600 font-semibold">
                                        <span>Finished ({{ $host->event_finished_at?->format('d M Y') }})</span>
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 sm:px-8 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                @if(!$host->hasFinishedEvent())
                                    <a href="{{ route('admin.hosts.assign-staff', $host) }}" class="p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Assign Staff">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                        </svg>
                                    </a>

                                    <form action="{{ route('admin.hosts.finish', $host) }}" method="POST" class="inline" onsubmit="return confirm('Mark this event as finished? The host account will be closed.');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-2 rounded-xl text-slate-500 hover:text-orange-600 hover:bg-orange-50 transition-colors" title="Mark Event Finished">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('admin.hosts.edit', $host) }}" class="p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit Host">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <form action="{{ route('admin.hosts.destroy', $host) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this host account?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Delete Host">
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
                                <div class="w-16 h-16 bg-amber-50 rounded-2xl mx-auto flex items-center justify-center text-amber-600 text-2xl shadow-inner">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <h3 class="text-base font-bold text-slate-900">No hosts registered</h3>
                                <p class="text-xs text-slate-500">No host accounts exist yet. Onboard your first event host to launch their digital invitations and guest management portal.</p>
                                <a href="{{ route('admin.hosts.create') }}" class="btn-primary text-xs">
                                    Register First Host
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($hosts->hasPages())
        <div class="px-6 sm:px-8 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $hosts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
