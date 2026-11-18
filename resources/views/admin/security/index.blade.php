@extends('layouts.dashboard')

@section('title', 'Advanced Security Operations Center (SOC) & Threat Intelligence')
@section('page-title', 'Security Operations Center (SOC)')

@section('content')
<div class="space-y-8 pb-16" x-data="{ 
    activeTab: '{{ request('tab', 'threats') }}', 
    inspectedPayload: null,
    investigatingIncident: null,
    editingVuln: null 
}">

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="rounded-2xl bg-emerald-50 border border-emerald-200/80 p-4 text-sm font-semibold text-emerald-800 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-2xl bg-rose-50 border border-rose-200/80 p-4 text-sm font-semibold text-rose-800 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- SOC Executive Telemetry Header -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-900 bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 p-8 sm:p-10 shadow-2xl text-white">
        <div class="absolute -top-12 -right-12 h-64 w-64 rounded-full bg-rose-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 h-64 w-64 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-500/15 px-3.5 py-1 text-xs font-semibold text-emerald-300 backdrop-blur-md">
                    <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>Tukio360 Sentinel Active</span>
                    <span class="text-slate-400">|</span>
                    <span class="text-[11px] text-slate-300 font-mono">PHP-IDS v2.0 + Wazuh SIEM Hub</span>
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 text-white shadow-xl shadow-rose-950/60 border border-white/20">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight font-heading">Security Operations Center (SOC)</h1>
                        <p class="mt-1 text-sm sm:text-base text-slate-300 max-w-xl">
                            Autonomous intrusion detection, incident lifecycle triage, Wazuh SIEM telemetry, and continuous vulnerability surveillance.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Telemetry Badges -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="px-5 py-3 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 text-right">
                    <div class="text-2xl font-black text-rose-400 font-mono">{{ $stats['critical_threats'] }}</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Critical Threats</div>
                </div>
                <div class="px-5 py-3 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 text-right">
                    <div class="text-2xl font-black text-amber-400 font-mono">{{ $stats['blocked_ips_count'] }}</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Bans</div>
                </div>
                <div class="px-5 py-3 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 text-right">
                    <div class="text-2xl font-black {{ $wazuhStatus['connected'] ? 'text-emerald-400' : 'text-slate-400' }} font-mono">
                        {{ $wazuhStatus['connected'] ? 'ACTIVE' : 'STANDBY' }}
                    </div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Wazuh SIEM</div>
                </div>
            </div>
        </div>

        <!-- 4 Metric Cards -->
        <div class="relative z-10 mt-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 sm:p-5 backdrop-blur-md">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <span>Total Incidents</span>
                    <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-white font-mono">{{ number_format($stats['total_incidents']) }}</div>
                <p class="mt-1 text-[11px] text-slate-400">Intercepted across platform</p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 sm:p-5 backdrop-blur-md">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <span>Attacks Today</span>
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-amber-300 font-mono">{{ number_format($stats['attacks_today']) }}</div>
                <p class="mt-1 text-[11px] text-slate-400">Past 24 hours ingress</p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 sm:p-5 backdrop-blur-md">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <span>Active In Triage</span>
                    <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-rose-400 font-mono">{{ number_format($stats['active_threats']) }}</div>
                <p class="mt-1 text-[11px] text-slate-400">Pending analyst containment</p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 sm:p-5 backdrop-blur-md">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <span>Open CVEs</span>
                    <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-extrabold text-purple-300 font-mono">{{ number_format($stats['open_vulnerabilities']) }}</div>
                <p class="mt-1 text-[11px] text-slate-400">Package advisories</p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex border-b border-slate-200 gap-2 sm:gap-6 overflow-x-auto text-xs font-bold whitespace-nowrap">
        <button type="button" @click="activeTab = 'threats'" :class="activeTab === 'threats' ? 'border-rose-600 text-rose-600' : 'border-transparent text-slate-500 hover:text-slate-800'" class="pb-3 border-b-2 flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <span>Live Threat Stream</span>
            <span class="px-2 py-0.5 text-[10px] rounded-full bg-slate-100 text-slate-700 font-mono">{{ $incidents->total() }}</span>
        </button>

        <button type="button" @click="activeTab = 'incidents'" :class="activeTab === 'incidents' ? 'border-rose-600 text-rose-600' : 'border-transparent text-slate-500 hover:text-slate-800'" class="pb-3 border-b-2 flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <span>Incident Response Center</span>
        </button>

        <button type="button" @click="activeTab = 'wazuh'" :class="activeTab === 'wazuh' ? 'border-rose-600 text-rose-600' : 'border-transparent text-slate-500 hover:text-slate-800'" class="pb-3 border-b-2 flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span>Wazuh SIEM &amp; FIM</span>
            <span class="w-2 h-2 rounded-full {{ $wazuhStatus['connected'] ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
        </button>

        <button type="button" @click="activeTab = 'firewall'" :class="activeTab === 'firewall' ? 'border-rose-600 text-rose-600' : 'border-transparent text-slate-500 hover:text-slate-800'" class="pb-3 border-b-2 flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            <span>Firewall &amp; Blocked IPs</span>
            <span class="px-2 py-0.5 text-[10px] rounded-full bg-slate-100 text-slate-700 font-mono">{{ $blockedIps->count() }}</span>
        </button>

        <button type="button" @click="activeTab = 'vulns'" :class="activeTab === 'vulns' ? 'border-rose-600 text-rose-600' : 'border-transparent text-slate-500 hover:text-slate-800'" class="pb-3 border-b-2 flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Vulnerabilities (CVEs)</span>
            <span class="px-2 py-0.5 text-[10px] rounded-full bg-slate-100 text-slate-700 font-mono">{{ $vulnerabilities->total() }}</span>
        </button>

        <button type="button" @click="activeTab = 'health'" :class="activeTab === 'health' ? 'border-rose-600 text-rose-600' : 'border-transparent text-slate-500 hover:text-slate-800'" class="pb-3 border-b-2 flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
            <span>Server Health</span>
        </button>

        <button type="button" @click="activeTab = 'audit'" :class="activeTab === 'audit' ? 'border-rose-600 text-rose-600' : 'border-transparent text-slate-500 hover:text-slate-800'" class="pb-3 border-b-2 flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Audit Trail</span>
        </button>

        <button type="button" @click="activeTab = 'settings'" :class="activeTab === 'settings' ? 'border-rose-600 text-rose-600' : 'border-transparent text-slate-500 hover:text-slate-800'" class="pb-3 border-b-2 flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span>Defense Settings</span>
        </button>
    </div>

    <!-- TAB 1: LIVE THREAT STREAM -->
    <div x-show="activeTab === 'threats'" class="space-y-6">
        <!-- Filter Bar & Export -->
        <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-xs flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <form method="GET" action="{{ route('admin.security.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 flex-1">
                <input type="hidden" name="tab" value="threats">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Severity</label>
                    <select name="severity" class="w-full text-xs rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500">
                        <option value="">All Severities</option>
                        <option value="critical" {{ request('severity') === 'critical' ? 'selected' : '' }}>Critical</option>
                        <option value="high" {{ request('severity') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('severity') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('severity') === 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Threat Type</label>
                    <select name="threat_type" class="w-full text-xs rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500">
                        <option value="">All Attack Types</option>
                        <option value="sqli" {{ request('threat_type') === 'sqli' ? 'selected' : '' }}>SQL Injection (SQLi)</option>
                        <option value="xss" {{ request('threat_type') === 'xss' ? 'selected' : '' }}>Cross-Site Scripting (XSS)</option>
                        <option value="path_traversal" {{ request('threat_type') === 'path_traversal' ? 'selected' : '' }}>Path Traversal / LFI</option>
                        <option value="scanner_probe" {{ request('threat_type') === 'scanner_probe' ? 'selected' : '' }}>Scanner Probe</option>
                        <option value="brute_force" {{ request('threat_type') === 'brute_force' ? 'selected' : '' }}>Auth Brute-Force</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                    <select name="status" class="w-full text-xs rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500">
                        <option value="">All Statuses</option>
                        <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                        <option value="investigating" {{ request('status') === 'investigating' ? 'selected' : '' }}>Investigating</option>
                        <option value="contained" {{ request('status') === 'contained' ? 'selected' : '' }}>Contained</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="false_positive" {{ request('status') === 'false_positive' ? 'selected' : '' }}>False Positive</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <input type="text" name="ip" value="{{ request('ip') }}" placeholder="Source IP..." class="w-full text-xs rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500">
                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-semibold hover:bg-slate-800 transition-colors">
                        Filter
                    </button>
                </div>
            </form>

            <div class="flex items-center gap-2 shrink-0 border-t sm:border-t-0 pt-3 sm:pt-0">
                <a href="{{ route('admin.security.export.incidents') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold flex items-center gap-1.5 transition-colors">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Export CSV</span>
                </a>
            </div>
        </div>

        <!-- Incidents Table -->
        <div class="rounded-2xl bg-white border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                    <thead class="bg-slate-50 font-semibold text-slate-600 uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-4 py-3">Timestamp</th>
                            <th class="px-4 py-3">Severity &amp; Type</th>
                            <th class="px-4 py-3">Attacker IP</th>
                            <th class="px-4 py-3">Target Endpoint</th>
                            <th class="px-4 py-3">Detection Vector</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($incidents as $incident)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-slate-500 font-mono text-[11px]">
                                    {{ $incident->created_at->format('Y-m-d H:i:s') }}
                                    <div class="text-[10px] text-slate-400">{{ $incident->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        @if($incident->severity === 'critical')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 font-mono uppercase">Critical</span>
                                        @elseif($incident->severity === 'high')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 font-mono uppercase">High</span>
                                        @elseif($incident->severity === 'medium')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-800 font-mono uppercase">Medium</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 font-mono uppercase">Low</span>
                                        @endif
                                        <span class="font-bold text-slate-800 uppercase text-[11px]">{{ $incident->threat_type }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap font-mono text-[11px]">
                                    <span class="font-bold text-slate-900">{{ $incident->source_ip }}</span>
                                </td>
                                <td class="px-4 py-3 max-w-[200px] truncate font-mono text-[11px] text-slate-600">
                                    <span class="px-1.5 py-0.5 rounded bg-slate-100 font-semibold">{{ $incident->request_method }}</span>
                                    <span>{{ $incident->request_url }}</span>
                                </td>
                                <td class="px-4 py-3 max-w-[250px]">
                                    <div class="truncate text-slate-800 font-medium" title="{{ $incident->detected_rule }}">
                                        {{ $incident->detected_rule }}
                                    </div>
                                    @if(!empty($incident->payload))
                                        <button type="button" @click="inspectedPayload = {{ json_encode($incident->payload) }}" class="text-[10px] text-indigo-600 hover:underline">
                                            View Intercepted Payload
                                        </button>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if(in_array($incident->status, ['resolved', 'closed']))
                                        <span class="inline-flex items-center gap-1 text-emerald-700 font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> {{ ucfirst($incident->status) }}
                                        </span>
                                    @elseif($incident->status === 'false_positive')
                                        <span class="inline-flex items-center gap-1 text-slate-500 font-semibold">False Positive</span>
                                    @elseif($incident->status === 'contained')
                                        <span class="inline-flex items-center gap-1 text-rose-700 font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span> Contained
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-amber-700 font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> {{ ucfirst($incident->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right space-x-1">
                                    <button type="button" @click="investigatingIncident = {{ json_encode($incident) }}" class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-semibold text-[11px]">
                                        Triage &amp; Investigate
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                                    No security incidents match the criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($incidents->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $incidents->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- TAB 2: INCIDENT RESPONSE CENTER -->
    <div x-show="activeTab === 'incidents'" class="space-y-6" style="display: none;">
        <div class="rounded-2xl bg-white border border-slate-200/80 shadow-xs p-6">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-2">Security Incident Investigation &amp; Case Management</h3>
            <p class="text-xs text-slate-500 mb-6">Assign incidents to SOC security analysts, document evidence, transition containment states, and maintain audit-compliant remediation history.</p>

            <div class="space-y-4">
                @forelse($incidents as $incident)
                    <div class="p-5 rounded-2xl border border-slate-200/90 hover:border-slate-300 transition-colors space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono {{ $incident->severity === 'critical' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $incident->severity }}
                                </span>
                                <span class="text-xs font-bold text-slate-900 font-mono">{{ $incident->incident_uuid }}</span>
                                <span class="text-xs text-slate-400">|</span>
                                <span class="text-xs font-semibold text-slate-700 uppercase">{{ $incident->threat_type }}</span>
                            </div>

                            <!-- Assignment & Status Form -->
                            <div class="flex flex-wrap items-center gap-2">
                                <form method="POST" action="{{ route('admin.security.incident.assign', $incident) }}" class="inline-flex items-center gap-1.5">
                                    @csrf
                                    <select name="assigned_to" onchange="this.form.submit()" class="text-[11px] py-1 px-2.5 rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Unassigned</option>
                                        @foreach($administrators as $admin)
                                            <option value="{{ $admin->id }}" {{ $incident->assigned_to == $admin->id ? 'selected' : '' }}>
                                                Assign: {{ $admin->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>

                                <form method="POST" action="{{ route('admin.security.incident.status', $incident) }}" class="inline-flex items-center gap-1.5">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="text-[11px] py-1 px-2.5 rounded-lg border-slate-300 font-semibold focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="new" {{ $incident->status === 'new' ? 'selected' : '' }}>New</option>
                                        <option value="investigating" {{ $incident->status === 'investigating' ? 'selected' : '' }}>Investigating</option>
                                        <option value="contained" {{ $incident->status === 'contained' ? 'selected' : '' }}>Contained</option>
                                        <option value="fix_in_progress" {{ $incident->status === 'fix_in_progress' ? 'selected' : '' }}>Fix in Progress</option>
                                        <option value="resolved" {{ $incident->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="closed" {{ $incident->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                        <option value="false_positive" {{ $incident->status === 'false_positive' ? 'selected' : '' }}>False Positive</option>
                                    </select>
                                </form>
                            </div>
                        </div>

                        <!-- Incident Details -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs bg-slate-50 p-4 rounded-xl">
                            <div>
                                <span class="text-slate-500 block text-[10px] uppercase font-bold">Attacker Source IP</span>
                                <span class="font-mono font-bold text-slate-800">{{ $incident->source_ip }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block text-[10px] uppercase font-bold">Target Resource</span>
                                <span class="font-mono text-slate-800 truncate block">{{ $incident->request_method }} {{ $incident->request_url }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block text-[10px] uppercase font-bold">Matched Detection Heuristic</span>
                                <span class="text-slate-800 truncate block font-medium">{{ $incident->detected_rule }}</span>
                            </div>
                        </div>

                        <!-- Investigation Notes Timeline -->
                        @if($incident->notes->isNotEmpty())
                            <div class="space-y-2 border-t border-slate-100 pt-3">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Investigation History:</span>
                                @foreach($incident->notes as $note)
                                    <div class="text-xs text-slate-700 bg-white p-3 rounded-lg border border-slate-200/60 flex items-start justify-between">
                                        <div>
                                            <span class="font-bold text-slate-900">{{ $note->user ? $note->user->name : 'Analyst' }}:</span>
                                            <span>{{ $note->note }}</span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 shrink-0 font-mono">{{ $note->created_at->diffForHumans() }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Add Note Quick Form -->
                        <form method="POST" action="{{ route('admin.security.incident.note', $incident) }}" class="flex items-center gap-2 pt-2">
                            @csrf
                            <input type="text" name="note" required placeholder="Add investigation log note or remediation action..." class="flex-1 text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-colors">
                                Add Note
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-500">
                        No active security cases requiring containment.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- TAB 3: WAZUH SIEM & FIM -->
    <div x-show="activeTab === 'wazuh'" class="space-y-6" style="display: none;">
        <!-- Wazuh Connection Telemetry Banner -->
        <div class="rounded-2xl p-6 border {{ $wazuhStatus['connected'] ? 'bg-emerald-50/60 border-emerald-200' : 'bg-slate-50 border-slate-200' }}">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl {{ $wazuhStatus['connected'] ? 'bg-emerald-500' : 'bg-slate-400' }} text-white flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-bold text-slate-900">Wazuh SIEM / XDR Manager Integration</h3>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono {{ $wazuhStatus['connected'] ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}">
                                {{ $wazuhStatus['status'] }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-600 mt-1">{{ $wazuhStatus['message'] }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 text-xs font-mono">
                    <div class="px-3 py-2 bg-white rounded-xl border border-slate-200 text-right">
                        <div class="font-bold text-slate-900">{{ $wazuhStatus['agents_active'] }} / {{ $wazuhStatus['agents_total'] }}</div>
                        <div class="text-[10px] text-slate-500 uppercase">Active Agents</div>
                    </div>
                    @if($wazuhStatus['manager_version'])
                        <div class="px-3 py-2 bg-white rounded-xl border border-slate-200 text-right">
                            <div class="font-bold text-slate-900">{{ $wazuhStatus['manager_version'] }}</div>
                            <div class="text-[10px] text-slate-500 uppercase">Version</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Connected Agents Grid -->
        <div class="rounded-2xl bg-white border border-slate-200/80 shadow-xs p-6 space-y-4">
            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Monitored Endpoint Agents</h4>
            @if(!empty($wazuhAgents))
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-xs">
                        <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-[10px]">
                            <tr>
                                <th class="px-4 py-2.5">Agent ID</th>
                                <th class="px-4 py-2.5">Host Name</th>
                                <th class="px-4 py-2.5">IP Address</th>
                                <th class="px-4 py-2.5">OS Platform</th>
                                <th class="px-4 py-2.5">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($wazuhAgents as $agent)
                                <tr>
                                    <td class="px-4 py-3 font-mono font-bold">{{ $agent['id'] }}</td>
                                    <td class="px-4 py-3 font-semibold text-slate-900">{{ $agent['name'] }}</td>
                                    <td class="px-4 py-3 font-mono">{{ $agent['ip'] }}</td>
                                    <td class="px-4 py-3">{{ $agent['os']['name'] ?? 'Linux' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ ($agent['status'] ?? '') === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $agent['status'] ?? 'unknown' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 bg-slate-50 rounded-xl text-center text-xs text-slate-500">
                    No remote Wazuh agents currently linked. When deployed on a dedicated Linux VPS or multi-server cluster, Wazuh agents continuously stream File Integrity Monitoring (FIM) and system anomalies here.
                </div>
            @endif
        </div>
    </div>

    <!-- TAB 4: FIREWALL & BLOCKED IPS -->
    <div x-show="activeTab === 'firewall'" class="space-y-6" style="display: none;">
        <!-- Manual Ban Form -->
        <div class="rounded-2xl bg-white p-6 border border-slate-200/80 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>Manual Attacker Quarantine &amp; IP Blacklist</span>
            </h3>
            <form method="POST" action="{{ route('admin.security.block-ip') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">IP Address to Block *</label>
                    <input type="text" name="ip_address" required placeholder="e.g. 198.51.100.4" class="w-full text-xs rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Reason for Ban *</label>
                    <input type="text" name="reason" required placeholder="Targeted SQLi probe" class="w-full text-xs rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Ban Duration</label>
                    <select name="duration_minutes" class="w-full text-xs rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500">
                        <option value="30">30 Minutes</option>
                        <option value="60">1 Hour</option>
                        <option value="1440">24 Hours</option>
                        <option value="10080">7 Days</option>
                        <option value="0">Permanent Blacklist</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full py-2.5 px-4 bg-rose-600 text-white rounded-xl text-xs font-bold hover:bg-rose-700 transition-colors shadow-xs">
                        Execute IP Ban
                    </button>
                </div>
            </form>
        </div>

        <!-- Blocked IPs List -->
        <div class="rounded-2xl bg-white border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Active Blacklisted IP Addresses</h4>
                    <span class="text-xs text-slate-500 font-mono">{{ $blockedIps->count() }} active restrictions</span>
                </div>
                <a href="{{ route('admin.security.export.blocked-ips') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                    Export CSV
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                    <thead class="bg-slate-50 font-semibold text-slate-600 uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-4 py-3">IP Address</th>
                            <th class="px-4 py-3">Ban Reason</th>
                            <th class="px-4 py-3">Strike Frequency</th>
                            <th class="px-4 py-3">Blocked At</th>
                            <th class="px-4 py-3">Expires At</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($blockedIps as $blocked)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap font-mono font-bold text-slate-900">
                                    {{ $blocked->ip_address }}
                                </td>
                                <td class="px-4 py-3 text-slate-800 font-medium">
                                    {{ $blocked->reason }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap font-mono">
                                    <span class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 font-bold">
                                        {{ $blocked->threat_count }} {{ Str::plural('strike', $blocked->threat_count) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-500 font-mono text-[11px]">
                                    {{ $blocked->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap font-mono text-[11px]">
                                    @if($blocked->isPermanent())
                                        <span class="px-2 py-0.5 rounded-full bg-slate-900 text-white font-bold text-[10px] uppercase">Permanent</span>
                                    @else
                                        <span class="text-slate-600">{{ $blocked->expires_at ? $blocked->expires_at->format('Y-m-d H:i') : 'Active' }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <form method="POST" action="{{ route('admin.security.unblock-ip', $blocked->ip_address) }}" class="inline" onsubmit="return confirm('Lift ban for {{ $blocked->ip_address }}?');">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg text-xs font-semibold transition-colors">
                                            Lift Ban / Whitelist
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                                    No IPs are currently banned. The application firewall is clear.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 5: VULNERABILITY MANAGEMENT (CVES) -->
    <div x-show="activeTab === 'vulns'" class="space-y-6" style="display: none;">
        <div class="rounded-2xl bg-white border border-slate-200/80 shadow-xs p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Vulnerability Management &amp; Package Security (CVEs)</h3>
                    <p class="text-xs text-slate-500 mt-1">Audit dependency lockfiles and correlate CVE advisories across packages and runtime libraries.</p>
                </div>

                <form method="POST" action="{{ route('admin.security.vulnerabilities.scan') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold flex items-center gap-2 transition-colors shadow-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Execute Dependency Vulnerability Scan</span>
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-xs">
                    <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-[10px]">
                        <tr>
                            <th class="px-4 py-3">CVE ID</th>
                            <th class="px-4 py-3">Advisory Title</th>
                            <th class="px-4 py-3">Package</th>
                            <th class="px-4 py-3">Severity</th>
                            <th class="px-4 py-3">CVSS</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($vulnerabilities as $vuln)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $vuln->cve_id ?: 'GHSA-Advisory' }}</td>
                                <td class="px-4 py-3 font-medium text-slate-900 max-w-xs truncate" title="{{ $vuln->title }}">{{ $vuln->title }}</td>
                                <td class="px-4 py-3 font-mono text-slate-600">{{ $vuln->package_name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono {{ $vuln->severity === 'critical' ? 'bg-rose-100 text-rose-700' : ($vuln->severity === 'high' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                                        {{ $vuln->severity }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-mono font-bold">{{ $vuln->cvss_score ?: '—' }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $vuln->status === 'open' ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700' }}">
                                        {{ $vuln->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <form method="POST" action="{{ route('admin.security.vulnerabilities.status', $vuln) }}" class="inline-flex items-center gap-1.5">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()" class="text-[11px] py-1 px-2 rounded-lg border-slate-300">
                                            <option value="open" {{ $vuln->status === 'open' ? 'selected' : '' }}>Open</option>
                                            <option value="investigating" {{ $vuln->status === 'investigating' ? 'selected' : '' }}>Investigating</option>
                                            <option value="mitigated" {{ $vuln->status === 'mitigated' ? 'selected' : '' }}>Mitigated</option>
                                            <option value="patched" {{ $vuln->status === 'patched' ? 'selected' : '' }}>Patched</option>
                                            <option value="false_positive" {{ $vuln->status === 'false_positive' ? 'selected' : '' }}>False Positive</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                                    No open package vulnerabilities detected. Dependencies are compliant.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($vulnerabilities->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $vulnerabilities->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- TAB 6: SERVER HEALTH & INFRASTRUCTURE TELEMETRY -->
    <div x-show="activeTab === 'health'" class="space-y-6" style="display: none;">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- CPU Utilization -->
            <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-500">
                    <span>CPU Utilization</span>
                    <span class="font-mono text-slate-900">{{ $serverHealth['cpu']['percentage'] }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-indigo-600 h-3 rounded-full transition-all" style="width: {{ $serverHealth['cpu']['percentage'] }}%"></div>
                </div>
                <div class="text-[11px] text-slate-500 flex items-center justify-between">
                    <span>Status: <strong class="uppercase text-emerald-600">{{ $serverHealth['cpu']['status'] }}</strong></span>
                    <span>System Load</span>
                </div>
            </div>

            <!-- RAM Utilization -->
            <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-500">
                    <span>Memory (RAM)</span>
                    <span class="font-mono text-slate-900">{{ $serverHealth['memory']['percentage'] }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-purple-600 h-3 rounded-full transition-all" style="width: {{ $serverHealth['memory']['percentage'] }}%"></div>
                </div>
                <div class="text-[11px] text-slate-500 flex items-center justify-between">
                    <span>{{ $serverHealth['memory']['used_formatted'] }} used</span>
                    <span>{{ $serverHealth['memory']['total_formatted'] }} total</span>
                </div>
            </div>

            <!-- Disk Storage -->
            <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-500">
                    <span>Disk Storage</span>
                    <span class="font-mono text-slate-900">{{ $serverHealth['disk']['percentage'] }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-emerald-600 h-3 rounded-full transition-all" style="width: {{ $serverHealth['disk']['percentage'] }}%"></div>
                </div>
                <div class="text-[11px] text-slate-500 flex items-center justify-between">
                    <span>{{ $serverHealth['disk']['used_formatted'] }} used</span>
                    <span>{{ $serverHealth['disk']['free_formatted'] }} free</span>
                </div>
            </div>
        </div>

        <!-- Infrastructure Status Table -->
        <div class="rounded-2xl bg-white border border-slate-200/80 shadow-xs p-6">
            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4">Core Infrastructure Probes</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs font-mono">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60">
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Database Connectivity</span>
                    <div class="font-bold text-slate-900 mt-1 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ $serverHealth['database']['connected'] ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                        <span>{{ $serverHealth['database']['connected'] ? 'ONLINE' : 'OFFLINE' }}</span>
                    </div>
                    <span class="text-[10px] text-slate-500 mt-1 block">Latency: {{ $serverHealth['database']['latency_ms'] }}ms</span>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60">
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">PHP &amp; Environment</span>
                    <div class="font-bold text-slate-900 mt-1">PHP {{ $serverHealth['runtime']['php_version'] }}</div>
                    <span class="text-[10px] text-slate-500 mt-1 block">{{ $serverHealth['runtime']['os'] }}</span>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60">
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">SSL Certificate Health</span>
                    <div class="font-bold text-slate-900 mt-1 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>{{ strtoupper($serverHealth['ssl']['status']) }}</span>
                    </div>
                    <span class="text-[10px] text-slate-500 mt-1 block">Valid until: {{ $serverHealth['ssl']['valid_until'] }}</span>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60">
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Latest System Snapshot</span>
                    <div class="font-bold text-slate-900 mt-1">
                        {{ $serverHealth['backup']['has_backup'] ? $serverHealth['backup']['latest']['size_formatted'] : 'No snapshot' }}
                    </div>
                    <span class="text-[10px] text-slate-500 mt-1 block">{{ $serverHealth['backup']['has_backup'] ? $serverHealth['backup']['latest']['created_at'] : 'Create backup in Settings' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 7: SECURITY AUDIT LEDGER -->
    <div x-show="activeTab === 'audit'" class="space-y-6" style="display: none;">
        <div class="rounded-2xl bg-white border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Tamper-Evident Security Operations Audit Ledger</h4>
                    <span class="text-xs text-slate-500 font-mono">Immutable audit trail of administrator and automated actions</span>
                </div>
                <a href="{{ route('admin.security.export.audit-logs') }}" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                    Export CSV
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                    <thead class="bg-slate-50 font-semibold text-slate-600 uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-4 py-3">Timestamp</th>
                            <th class="px-4 py-3">Event Action</th>
                            <th class="px-4 py-3">Actor</th>
                            <th class="px-4 py-3">IP Address</th>
                            <th class="px-4 py-3">Context / Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($auditLogs as $log)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap font-mono text-[11px] text-slate-500">
                                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap font-bold text-slate-900 font-mono uppercase text-[11px]">
                                    {{ $log->action }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-700 font-medium">
                                    {{ $log->user ? $log->user->name : 'System Sentinel' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap font-mono text-[11px]">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="px-4 py-3 max-w-[300px] truncate text-slate-600 font-mono text-[11px]">
                                    {{ json_encode($log->details) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                    No audit entries recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 8: DEFENSE SETTINGS -->
    <div x-show="activeTab === 'settings'" class="space-y-6" style="display: none;">
        <div class="rounded-2xl bg-white p-6 border border-slate-200/80 shadow-xs max-w-3xl">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-2">Automated Threat Mitigation &amp; Alert Channels</h3>
            <p class="text-xs text-slate-500 mb-6">Fine-tune the heuristics engine, strike sensitivity, and incident notification endpoints.</p>

            <form method="POST" action="{{ route('admin.security.settings') }}" class="space-y-6">
                @csrf
                <div class="space-y-4">
                    <!-- Threat Detection Engine Toggle -->
                    <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-200/60">
                        <div>
                            <div class="text-xs font-bold text-slate-900">Real-Time Threat Detection Engine</div>
                            <div class="text-[11px] text-slate-500">Inspect all incoming web requests for SQLi, XSS, Scanner signatures, and probes.</div>
                        </div>
                        <input type="checkbox" name="threat_detection_enabled" value="1" {{ $settings['threat_detection_enabled'] ? 'checked' : '' }} class="rounded text-rose-600 focus:ring-rose-500 h-4 w-4">
                    </div>

                    <!-- Auto-Block Toggle -->
                    <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-200/60">
                        <div>
                            <div class="text-xs font-bold text-slate-900">Automated Threat Mitigation (IPS Auto-Ban)</div>
                            <div class="text-[11px] text-slate-500">Automatically isolate and block attacker IP addresses when strike threshold is reached.</div>
                        </div>
                        <input type="checkbox" name="auto_block_enabled" value="1" {{ $settings['auto_block_enabled'] ? 'checked' : '' }} class="rounded text-rose-600 focus:ring-rose-500 h-4 w-4">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Auto-Ban Duration (Minutes)</label>
                            <input type="number" name="auto_block_duration_minutes" value="{{ $settings['auto_block_duration_minutes'] }}" min="1" max="525600" class="w-full text-xs rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Strike Threshold Before Quarantine</label>
                            <input type="number" name="auto_block_threshold_strikes" value="{{ $settings['auto_block_threshold_strikes'] }}" min="1" max="20" class="w-full text-xs rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Whitelisted IP Addresses (Comma-separated)</label>
                        <input type="text" name="whitelisted_ips" value="{{ $settings['whitelisted_ips'] }}" class="w-full text-xs rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500 font-mono">
                        <p class="text-[10px] text-slate-400 mt-1">Whitelisted IPs will never be automatically banned or blocked by the intrusion prevention system.</p>
                    </div>

                    <hr class="border-slate-100 my-4">

                    <!-- Alert Channels -->
                    <div class="text-xs font-bold text-slate-900 uppercase tracking-wider">Multi-Channel Alert Configuration</div>

                    <!-- WhatsApp Alerts -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span>WhatsApp Cloud API Instant Security Alerts</span>
                                </div>
                                <div class="text-[11px] text-slate-500">Dispatches high/critical threat notifications to WhatsApp via Meta Cloud API.</div>
                            </div>
                            <input type="checkbox" name="alert_whatsapp_enabled" value="1" {{ $settings['alert_whatsapp_enabled'] ? 'checked' : '' }} class="rounded text-rose-600 focus:ring-rose-500 h-4 w-4">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Admin WhatsApp Phone (E.164, e.g. 255712345678)</label>
                            <input type="text" name="alert_whatsapp_recipient" value="{{ $settings['alert_whatsapp_recipient'] }}" class="w-full text-xs rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500 font-mono" placeholder="255...">
                        </div>
                    </div>

                    <!-- Email Alerts -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                    <span>SOC Alert Notification Email</span>
                                </div>
                                <div class="text-[11px] text-slate-500">Dispatches comprehensive incident details to designated security inbox.</div>
                            </div>
                            <input type="checkbox" name="alert_email_enabled" value="1" {{ $settings['alert_email_enabled'] ? 'checked' : '' }} class="rounded text-rose-600 focus:ring-rose-500 h-4 w-4">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Alert Destination Email</label>
                            <input type="email" name="alert_email_recipient" value="{{ $settings['alert_email_recipient'] }}" class="w-full text-xs rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500" placeholder="security@tukio360.co.tz">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-6 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-colors shadow-sm">
                        Save Security Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payload Inspection Modal -->
    <div x-show="inspectedPayload" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;" @keydown.escape.window="inspectedPayload = null">
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-slate-200 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h4 class="text-sm font-bold text-slate-900">Intercepted Request Payload</h4>
                <button type="button" @click="inspectedPayload = null" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <pre class="bg-slate-900 text-emerald-400 p-4 rounded-xl text-xs font-mono overflow-x-auto max-h-96" x-text="JSON.stringify(inspectedPayload, null, 2)"></pre>
            <div class="flex justify-end">
                <button type="button" @click="inspectedPayload = null" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
