@extends('layouts.host')

@section('title', 'Digital Event Website & RSVP Management')
@section('page-title', 'Event Website & RSVP')

@section('content')
<div class="space-y-8 animate-fade-in" id="event-website-dashboard">

    <!-- Top Alert Notification -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Hero Header Banner with Actions -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 sm:p-8 text-white shadow-xl">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-3">
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span>Premium Feature</span>
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $website->is_published ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                        {{ $website->is_published ? '● Live & Published' : '○ Draft Mode' }}
                    </span>
                    <span class="text-xs text-slate-300 capitalize">{{ ucfirst(str_replace('_', ' ', $website->category)) }}</span>
                </div>

                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black font-heading tracking-tight text-white">
                    {{ $website->title }}
                </h1>
                
                <div class="flex flex-wrap items-center gap-3 text-xs text-slate-300">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>{{ $website->event_date ? $website->event_date->format('l, F j, Y') : 'Date TBD' }}</span>
                    </span>
                    <span>&bull;</span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        <span>{{ $website->venue_name ?: 'Venue TBD' }}</span>
                    </span>
                </div>
            </div>

            <!-- Header Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ $website->public_url }}" target="_blank" class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>View Live Website</span>
                </a>

                <a href="{{ route('event.website.preview', $website->slug) }}" target="_blank" class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs sm:text-sm border border-white/20 backdrop-blur-md transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>Preview</span>
                </a>

                <a href="{{ route('host.website.distribution') }}" class="px-4 py-2.5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs sm:text-sm shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span>Broadcast</span>
                </a>
            </div>
        </div>

        <!-- URL Share Strip -->
        <div class="relative z-10 mt-6 pt-5 border-t border-white/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-2 text-slate-300">
                <span class="text-slate-400 font-semibold">Public Link:</span>
                <span class="font-mono text-indigo-300 underline font-bold select-all">{{ $website->public_url }}</span>
            </div>
            <button type="button" onclick="navigator.clipboard.writeText('{{ $website->public_url }}'); alert('Link copied to clipboard!');" class="px-3 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white font-semibold transition-all">
                Copy Link
            </button>
        </div>
    </div>

    <!-- RSVP Statistics Cards Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Card 1: Total Responses -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Responses</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black font-heading text-slate-900">{{ $stats['total_responses'] }}</span>
                <span class="text-xs font-semibold text-slate-400">guests</span>
            </div>
        </div>

        <!-- Card 2: Confirmed Attending -->
        <div class="bg-white rounded-2xl p-4 border border-emerald-100 shadow-sm bg-gradient-to-br from-emerald-50/50 to-white flex flex-col justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Confirmed (Yes)</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black font-heading text-emerald-600">{{ $stats['confirmed'] }}</span>
                <span class="text-xs font-semibold text-emerald-700">attending</span>
            </div>
        </div>

        <!-- Card 3: Total Expected Heads (+1s) -->
        <div class="bg-white rounded-2xl p-4 border border-indigo-100 shadow-sm bg-gradient-to-br from-indigo-50/50 to-white flex flex-col justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-700">Expected Heads</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black font-heading text-indigo-600">{{ $stats['expected_attendance'] }}</span>
                <span class="text-xs font-semibold text-indigo-700">seats</span>
            </div>
        </div>

        <!-- Card 4: Declined -->
        <div class="bg-white rounded-2xl p-4 border border-rose-100 shadow-sm bg-gradient-to-br from-rose-50/50 to-white flex flex-col justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-rose-700">Declined (No)</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black font-heading text-rose-600">{{ $stats['declined'] }}</span>
                <span class="text-xs font-semibold text-rose-700">declined</span>
            </div>
        </div>

        <!-- Card 5: Tentative -->
        <div class="bg-white rounded-2xl p-4 border border-amber-100 shadow-sm bg-gradient-to-br from-amber-50/50 to-white flex flex-col justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Tentative (Maybe)</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black font-heading text-amber-600">{{ $stats['tentative'] }}</span>
                <span class="text-xs font-semibold text-amber-700">unsure</span>
            </div>
        </div>

        <!-- Card 6: Checked-In on Event Day -->
        <div class="bg-white rounded-2xl p-4 border border-purple-100 shadow-sm bg-gradient-to-br from-purple-50/50 to-white flex flex-col justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-purple-700">Door Check-In</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black font-heading text-purple-600">{{ $stats['checked_in'] }}</span>
                <span class="text-xs font-semibold text-purple-700">checked-in</span>
            </div>
        </div>
    </div>

    @php
        $activeTab = request('tab', 'overview');
    @endphp

    <!-- Main Navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 overflow-x-auto pb-1 custom-scrollbar" id="ew-tab-nav">
        <button type="button" onclick="ewSwitchTab('overview')" id="ew-btn-overview" class="ew-tab-btn px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors flex items-center gap-2 {{ $activeTab === 'overview' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Overview &amp; QR Pass</span>
        </button>

        <button type="button" onclick="ewSwitchTab('theme')" id="ew-btn-theme" class="ew-tab-btn px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors flex items-center gap-2 {{ $activeTab === 'theme' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4 4 4 0 014-4 4 4 0 014 4 4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
            <span>Theme &amp; Branding</span>
        </button>

        <button type="button" onclick="ewSwitchTab('sections')" id="ew-btn-sections" class="ew-tab-btn px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors flex items-center gap-2 {{ $activeTab === 'sections' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
            <span>Sections &amp; Content (13)</span>
        </button>

        <button type="button" onclick="ewSwitchTab('rsvps')" id="ew-btn-rsvps" class="ew-tab-btn px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors flex items-center gap-2 {{ $activeTab === 'rsvps' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>Guest RSVP List ({{ $stats['total_responses'] }})</span>
        </button>

        <button type="button" onclick="ewSwitchTab('custom_fields')" id="ew-btn-custom_fields" class="ew-tab-btn px-4 py-3 border-b-2 text-sm whitespace-nowrap transition-colors flex items-center gap-2 {{ $activeTab === 'custom_fields' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>RSVP Form &amp; Questions</span>
        </button>
    </div>

    <!-- ==================== TAB 1: OVERVIEW & SHARE ==================== -->
    <div id="tab-panel-overview" class="ew-tab-panel space-y-6 {{ $activeTab === 'overview' ? '' : 'hidden' }}">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left: QR Code and Sharing Cards -->
            <div class="lg:col-span-5 space-y-6">
                <!-- QR Code & Link Sharing Box -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm text-center space-y-4">
                    <h3 class="font-heading font-black text-lg text-slate-900">Website QR Code &amp; Link</h3>
                    <p class="text-xs text-slate-500">Scan to open the event website instantly on any mobile device.</p>
                    
                    <div class="w-56 h-56 mx-auto p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-center shadow-inner">
                        <img src="{{ $websiteQrCodeUrl }}" alt="Event QR Code" class="w-full h-full object-contain">
                    </div>

                    <!-- Direct Public Link Copy Strip -->
                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/80 flex items-center justify-between gap-2 text-left">
                        <div class="min-w-0 flex-1">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Public Event Link</span>
                            <span class="text-xs font-mono font-bold text-indigo-600 truncate block">{{ $website->public_url }}</span>
                        </div>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $website->public_url }}'); this.innerText='Copied!'; setTimeout(() => this.innerText='Copy', 2000);" class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shrink-0 transition-all shadow-xs">
                            Copy
                        </button>
                    </div>

                    <!-- WhatsApp One-Click Direct Share -->
                    @php
                        $shareText = urlencode("Hello! You are cordially invited to celebrate " . $website->title . ".\n\nPlease view our official event website and confirm your attendance (RSVP) here:\n" . $website->public_url);
                        $waShareUrl = "https://api.whatsapp.com/send?text=" . $shareText;
                    @endphp
                    <a href="{{ $waShareUrl }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-[#25D366] hover:bg-[#20BA5A] text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.698.073-2.072-.497-1.758-.729-2.883-2.518-2.97-2.634-.088-.117-.714-.951-.714-1.815 0-.864.453-1.289.614-1.464.161-.176.351-.22.468-.22.117 0 .234.001.336.006.107.005.252-.04.394.301.146.353.498 1.216.542 1.304.044.088.073.191.015.308-.059.117-.088.19-.176.293-.088.102-.185.228-.264.307-.088.088-.18.183-.078.359.102.176.455.751.977 1.216.671.597 1.238.782 1.414.87.176.088.278.073.38-.044.102-.117.439-.512.556-.688.117-.176.234-.146.395-.088.161.059 1.025.483 1.201.571.176.088.293.132.336.205.044.073.044.425-.1.83z"/></svg>
                        <span>Share Invite via WhatsApp</span>
                    </a>

                    <div class="flex items-center justify-center gap-3 pt-1">
                        <a href="{{ $websiteQrCodeUrl }}" download="{{ $website->slug }}-qrcode.svg" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md transition-all flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Download QR Code</span>
                        </a>
                        <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Print Stand</span>
                        </button>
                    </div>
                </div>

                <!-- Live Countdown Design Preview -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-heading font-black text-sm text-slate-900">Active Countdown Clock</h3>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" title="Live Clock"></span>
                    </div>
                    <p class="text-xs text-slate-500">Target: {{ $website->event_date ? $website->event_date->format('M d, Y') : 'Not Set' }} at {{ $website->event_time ?: '18:00' }}</p>

                    <div class="grid grid-cols-4 gap-2 pt-2 text-center" id="host-preview-countdown">
                        <div class="bg-slate-900 text-amber-400 p-2.5 rounded-xl border border-amber-500/20 shadow-sm">
                            <span id="dash-cd-days" class="text-lg font-black font-heading block">00</span>
                            <span class="text-[9px] uppercase tracking-wider text-slate-400">Days</span>
                        </div>
                        <div class="bg-slate-900 text-amber-400 p-2.5 rounded-xl border border-amber-500/20 shadow-sm">
                            <span id="dash-cd-hours" class="text-lg font-black font-heading block">00</span>
                            <span class="text-[9px] uppercase tracking-wider text-slate-400">Hours</span>
                        </div>
                        <div class="bg-slate-900 text-amber-400 p-2.5 rounded-xl border border-amber-500/20 shadow-sm">
                            <span id="dash-cd-mins" class="text-lg font-black font-heading block">00</span>
                            <span class="text-[9px] uppercase tracking-wider text-slate-400">Mins</span>
                        </div>
                        <div class="bg-slate-900 text-amber-400 p-2.5 rounded-xl border border-amber-500/20 shadow-sm">
                            <span id="dash-cd-secs" class="text-lg font-black font-heading block">00</span>
                            <span class="text-[9px] uppercase tracking-wider text-slate-400">Secs</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Website Quick Details & Publishing Settings Form -->
            <div class="lg:col-span-7 space-y-6">
                <form action="{{ route('host.website.general.update') }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
                    @csrf
                    
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-heading font-black text-lg text-slate-900">General Information</h3>
                            <p class="text-xs text-slate-500">Configure key details displayed across your event website.</p>
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_published" value="1" {{ $website->is_published ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            <span class="text-xs font-bold text-slate-700">Published</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Website Title</label>
                            <input type="text" name="title" value="{{ old('title', $website->title) }}" required class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Website URL Slug</label>
                            <div class="flex rounded-xl shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-slate-200 bg-slate-50 text-slate-500 text-xs">/e/</span>
                                <input type="text" name="slug" value="{{ old('slug', $website->slug) }}" required class="w-full rounded-none rounded-r-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Event Category</label>
                            <select name="category" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 capitalize">
                                @foreach(['birthday', 'wedding', 'send_off', 'kitchen_party', 'corporate', 'anniversary', 'graduation', 'gala', 'special_celebration'] as $cat)
                                    <option value="{{ $cat }}" {{ $website->category === $cat ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $cat)) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Celebrant / Host Name(s)</label>
                            <input type="text" name="host_names" value="{{ old('host_names', $website->host_names) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. Alice Mrema">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Tagline / Welcome Message</label>
                            <input type="text" name="tagline" value="{{ old('tagline', $website->tagline) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Event Date</label>
                            <input type="date" name="event_date" value="{{ old('event_date', $website->event_date?->format('Y-m-d')) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Event Time</label>
                            <input type="text" name="event_time" value="{{ old('event_time', $website->event_time) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. 18:00 or 6:00 PM">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Venue Name</label>
                            <input type="text" name="venue_name" value="{{ old('venue_name', $website->venue_name) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. Grand Hyatt">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Venue Address</label>
                            <input type="text" name="venue_address" value="{{ old('venue_address', $website->venue_address) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Google Maps Direct Link</label>
                            <input type="url" name="google_maps_url" value="{{ old('google_maps_url', $website->google_maps_url) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://maps.google.com/...">
                        </div>
                    </div>

                    <!-- Hidden fields required by updateGeneral -->
                    <input type="hidden" name="theme" value="{{ $website->theme }}">
                    <input type="hidden" name="countdown_style" value="{{ $website->countdown_style }}">
                    <input type="hidden" name="max_plus_ones" value="{{ $website->max_plus_ones }}">

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md transition-all">
                            Save Changes
                        </button>
                    </div>
                </form>

                <!-- Media Uploads Form (Cover photo & Background Music) -->
                <form action="{{ route('host.website.media.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
                    @csrf
                    <div>
                        <h3 class="font-heading font-black text-lg text-slate-900">Media Assets &amp; Background Music</h3>
                        <p class="text-xs text-slate-500">Elevate your website with hero photography and celebratory background music.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Cover Image -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">Cover Image / Hero Artwork</label>
                            @if($website->cover_image)
                                <div class="w-full h-32 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 mb-2">
                                    <img src="{{ Storage::url($website->cover_image) }}" alt="Cover" class="w-full h-full object-cover">
                                </div>
                            @endif
                            <input type="file" name="cover_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>

                        <!-- Music MP3 -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">Background Music (MP3)</label>
                            @if($website->music_url)
                                <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200 text-xs flex items-center justify-between">
                                    <span class="truncate font-mono text-[11px] text-slate-700">{{ $website->music_title ?: 'Active Audio Track' }}</span>
                                    <audio controls class="h-6 w-36">
                                        <source src="{{ $website->music_url }}">
                                    </audio>
                                </div>
                            @endif
                            <input type="file" name="music_file" accept="audio/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <input type="text" name="music_title" value="{{ old('music_title', $website->music_title) }}" placeholder="Song / Melody Title" class="w-full rounded-xl border-slate-200 text-xs mt-2">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm shadow-md transition-all">
                            Upload Media
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- ==================== TAB 2: THEME & BRANDING ==================== -->
    <div id="tab-panel-theme" class="ew-tab-panel space-y-6 {{ $activeTab === 'theme' ? '' : 'hidden' }}">
        <form action="{{ route('host.website.general.update') }}" method="POST" class="space-y-8">
            @csrf
            <!-- Preserve general fields -->
            <input type="hidden" name="title" value="{{ $website->title }}">
            <input type="hidden" name="slug" value="{{ $website->slug }}">
            <input type="hidden" name="category" value="{{ $website->category }}">
            <input type="hidden" name="tagline" value="{{ $website->tagline }}">
            <input type="hidden" name="host_names" value="{{ $website->host_names }}">
            <input type="hidden" name="event_date" value="{{ $website->event_date?->format('Y-m-d') }}">
            <input type="hidden" name="event_time" value="{{ $website->event_time }}">
            <input type="hidden" name="venue_name" value="{{ $website->venue_name }}">
            <input type="hidden" name="venue_address" value="{{ $website->venue_address }}">
            <input type="hidden" name="google_maps_url" value="{{ $website->google_maps_url }}">
            <input type="hidden" name="is_published" value="{{ $website->is_published ? '1' : '0' }}">
            <input type="hidden" name="max_plus_ones" value="{{ $website->max_plus_ones }}">

            <!-- Preset Themes Grid -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
                <div>
                    <h3 class="font-heading font-black text-xl text-slate-900">Curated Luxury Themes</h3>
                    <p class="text-xs text-slate-500 mt-1">Select one of our meticulously crafted visual styles for your event.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($themes as $themeKey => $t)
                        <label class="relative rounded-2xl border-2 p-5 cursor-pointer transition-all flex flex-col justify-between hover:shadow-md {{ $website->theme === $themeKey ? 'border-indigo-600 bg-indigo-50/20 ring-2 ring-indigo-600/20' : 'border-slate-200 hover:border-slate-300' }}">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                                        {{ $t['badge'] }}
                                    </span>
                                    <input type="radio" name="theme" value="{{ $themeKey }}" {{ $website->theme === $themeKey ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                </div>
                                <h4 class="font-bold text-slate-900 text-sm">{{ $t['name'] }}</h4>
                                
                                <!-- Color Swatches preview -->
                                <div class="flex items-center gap-2 pt-1">
                                    <span class="w-6 h-6 rounded-full border border-black/10 shadow-sm" style="background-color: {{ $t['primary'] }}" title="Primary"></span>
                                    <span class="w-6 h-6 rounded-full border border-black/10 shadow-sm" style="background-color: {{ $t['secondary'] }}" title="Secondary"></span>
                                    <span class="w-6 h-6 rounded-full border border-black/10 shadow-sm" style="background-color: {{ $t['accent'] }}" title="Accent"></span>
                                    <span class="w-6 h-6 rounded-full border border-black/10 shadow-sm" style="background-color: {{ $t['background'] }}" title="Background"></span>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-500 flex justify-between">
                                <span>Typography:</span>
                                <span class="font-semibold text-slate-700">{{ $t['heading_font'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Countdown Timer Style Selector -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
                <div>
                    <h3 class="font-heading font-black text-xl text-slate-900">Countdown Timer Design</h3>
                    <p class="text-xs text-slate-500 mt-1">Choose how the live countdown clock is presented on the public website.</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach([
                        'cards' => 'Luxury Cards (Signature)',
                        'flip' => 'Digital Flip Box',
                        'circular' => 'Circular Rings',
                        'minimalist' => 'Clean Minimalist'
                    ] as $styleKey => $styleName)
                        <label class="p-4 rounded-2xl border-2 cursor-pointer transition-all text-center {{ $website->countdown_style === $styleKey ? 'border-indigo-600 bg-indigo-50/30 font-bold' : 'border-slate-200 hover:border-slate-300' }}">
                            <input type="radio" name="countdown_style" value="{{ $styleKey }}" {{ $website->countdown_style === $styleKey ? 'checked' : '' }} class="sr-only">
                            <span class="text-xs block text-slate-800">{{ $styleName }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Custom Color Overrides & Typography -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
                <div>
                    <h3 class="font-heading font-black text-xl text-slate-900">Custom Colors &amp; Typography (Optional)</h3>
                    <p class="text-xs text-slate-500 mt-1">Override preset colors with your exact palette hex codes.</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Primary Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" onchange="this.nextElementSibling.value = this.value" value="{{ $website->primary_color ?: '#D4AF37' }}" class="w-8 h-8 rounded-lg cursor-pointer border-0 p-0">
                            <input type="text" name="primary_color" value="{{ old('primary_color', $website->primary_color) }}" placeholder="#D4AF37" class="w-full text-xs font-mono rounded-lg border-slate-200">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Secondary Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" onchange="this.nextElementSibling.value = this.value" value="{{ $website->secondary_color ?: '#1F2937' }}" class="w-8 h-8 rounded-lg cursor-pointer border-0 p-0">
                            <input type="text" name="secondary_color" value="{{ old('secondary_color', $website->secondary_color) }}" placeholder="#1F2937" class="w-full text-xs font-mono rounded-lg border-slate-200">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Accent Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" onchange="this.nextElementSibling.value = this.value" value="{{ $website->accent_color ?: '#FBBF24' }}" class="w-8 h-8 rounded-lg cursor-pointer border-0 p-0">
                            <input type="text" name="accent_color" value="{{ old('accent_color', $website->accent_color) }}" placeholder="#FBBF24" class="w-full text-xs font-mono rounded-lg border-slate-200">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Background Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" onchange="this.nextElementSibling.value = this.value" value="{{ $website->background_color ?: '#0F172A' }}" class="w-8 h-8 rounded-lg cursor-pointer border-0 p-0">
                            <input type="text" name="background_color" value="{{ old('background_color', $website->background_color) }}" placeholder="#0F172A" class="w-full text-xs font-mono rounded-lg border-slate-200">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Text Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" onchange="this.nextElementSibling.value = this.value" value="{{ $website->text_color ?: '#F8FAFC' }}" class="w-8 h-8 rounded-lg cursor-pointer border-0 p-0">
                            <input type="text" name="text_color" value="{{ old('text_color', $website->text_color) }}" placeholder="#F8FAFC" class="w-full text-xs font-mono rounded-lg border-slate-200">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Heading Font Family</label>
                        <select name="font_heading" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500">
                            @foreach(['Playfair Display', 'Cormorant Garamond', 'Cinzel', 'Montserrat', 'Great Vibes', 'Plus Jakarta Sans', 'Inter'] as $font)
                                <option value="{{ $font }}" {{ $website->font_heading === $font ? 'selected' : '' }}>{{ $font }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Body Font Family</label>
                        <select name="font_body" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500">
                            @foreach(['Inter', 'Plus Jakarta Sans', 'Lato', 'Open Sans', 'Roboto'] as $font)
                                <option value="{{ $font }}" {{ $website->font_body === $font ? 'selected' : '' }}>{{ $font }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-8 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-lg shadow-indigo-600/20 transition-all">
                        Apply Theme Changes
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- ==================== TAB 3: SECTIONS & CONTENT MANAGER ==================== -->
    <div id="tab-panel-sections" class="ew-tab-panel space-y-6 {{ $activeTab === 'sections' ? '' : 'hidden' }}">
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-heading font-black text-xl text-slate-900">Website Sections Manager</h3>
                    <p class="text-xs text-slate-500 mt-1">Enable, disable, and customize each section of your event website.</p>
                </div>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($sections as $section)
                    <div class="py-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ $section->sort_order }}
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-slate-900 text-sm capitalize">{{ str_replace('_', ' ', $section->section_key) }}</h4>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold {{ $section->is_enabled ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $section->is_enabled ? 'Active' : 'Disabled' }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $section->title ?: 'No custom title' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 shrink-0">
                                <button type="button" onclick="ewToggleSectionEditor({{ $section->id }})" class="px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-xs font-bold text-slate-700 transition-colors flex items-center gap-1.5">
                                    <span>Edit Content</span>
                                    <svg id="section-chevron-{{ $section->id }}" class="w-3.5 h-3.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Accordion Content Editor for this Section -->
                        <div id="section-editor-{{ $section->id }}" class="hidden w-full mt-4 pt-4 border-t border-slate-100">
                            <form action="{{ route('host.website.sections.update', $section->id) }}" method="POST" enctype="multipart/form-data" class="bg-slate-50 p-5 rounded-2xl border border-slate-200/80 space-y-4">
                                @csrf
                                <div class="flex items-center justify-between">
                                    <h5 class="text-xs font-bold uppercase tracking-wider text-slate-700">Section Settings</h5>
                                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                                        <input type="checkbox" name="is_enabled" value="1" {{ $section->is_enabled ? 'checked' : '' }} class="rounded text-indigo-600 focus:ring-indigo-500">
                                        <span>Show on Website</span>
                                    </label>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 mb-1">Section Title</label>
                                        <input type="text" name="title" value="{{ $section->title }}" class="w-full rounded-xl border-slate-200 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 mb-1">Section Subtitle / Tagline</label>
                                        <input type="text" name="subtitle" value="{{ $section->subtitle }}" class="w-full rounded-xl border-slate-200 text-xs">
                                    </div>
                                </div>

                                <!-- Specific content fields depending on section_key -->
                                @if($section->section_key === 'about')
                                    <div class="space-y-3 pt-2">
                                        <label class="block text-xs font-bold text-slate-600">Celebrant Bio &amp; Story</label>
                                        <textarea name="content[story]" rows="3" class="w-full rounded-xl border-slate-200 text-xs">{{ $section->content['story'] ?? '' }}</textarea>
                                        
                                        <label class="block text-xs font-bold text-slate-600">Celebration Quote</label>
                                        <input type="text" name="content[quote]" value="{{ $section->content['quote'] ?? '' }}" class="w-full rounded-xl border-slate-200 text-xs">

                                        <label class="block text-xs font-bold text-slate-600">Celebrant Portrait Photo</label>
                                        <input type="file" name="about_image" accept="image/*" class="w-full text-xs text-slate-500">
                                    </div>
                                @elseif($section->section_key === 'dress_code')
                                    <div class="space-y-3 pt-2">
                                        <label class="block text-xs font-bold text-slate-600">Attire Style</label>
                                        <input type="text" name="content[style]" value="{{ $section->content['style'] ?? 'Black Tie / Evening Wear' }}" class="w-full rounded-xl border-slate-200 text-xs">
                                        
                                        <label class="block text-xs font-bold text-slate-600">Dress Guidance Notes</label>
                                        <textarea name="content[notes]" rows="2" class="w-full rounded-xl border-slate-200 text-xs">{{ $section->content['notes'] ?? '' }}</textarea>
                                    </div>
                                @elseif($section->section_key === 'gallery')
                                    <div class="space-y-3 pt-2">
                                        <label class="block text-xs font-bold text-slate-600">Upload Additional Gallery Photos</label>
                                        <input type="file" name="gallery_images[]" multiple accept="image/*" class="w-full text-xs text-slate-500">
                                    </div>
                                @endif

                                <div class="flex justify-end pt-2">
                                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm">
                                        Save Section
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ==================== TAB 4: RSVP & GUEST LIST ==================== -->
    <div id="tab-panel-rsvps" class="ew-tab-panel space-y-6 {{ $activeTab === 'rsvps' ? '' : 'hidden' }}">
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
            
            <!-- RSVP Actions Toolbar -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="font-heading font-black text-xl text-slate-900">Guest RSVP Responses</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Track attendance responses, accompanying guests, and door check-in status.</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <!-- Export to Excel (CSV) -->
                    <a href="{{ route('host.website.rsvps.export.excel') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Export Excel (CSV)</span>
                    </a>

                    <!-- Export PDF -->
                    <a href="{{ route('host.website.rsvps.export.pdf') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <span>Export PDF</span>
                    </a>

                    <!-- Print Door Roster -->
                    <a href="{{ route('host.website.rsvps.print') }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Print Roster</span>
                    </a>

                    <!-- Manual RSVP Button -->
                    <button type="button" onclick="document.getElementById('manual-rsvp-modal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Guest RSVP</span>
                    </button>
                </div>
            </div>

            <!-- Filter and Search bar -->
            <form action="{{ route('host.website.index') }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <input type="hidden" name="tab" value="rsvps">
                <div class="flex items-center gap-2 overflow-x-auto w-full sm:w-auto pb-1">
                    @foreach(['all' => 'All Responses', 'attending' => 'Attending (Yes)', 'declined' => 'Declined (No)', 'tentative' => 'Tentative', 'checked_in' => 'Checked-In'] as $filterKey => $filterName)
                        <a href="{{ route('host.website.index', ['tab' => 'rsvps', 'status' => $filterKey, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-colors {{ $statusFilter === $filterKey ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            {{ $filterName }}
                        </a>
                    @endforeach
                </div>

                <div class="w-full sm:w-64">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search name or phone..." class="w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500">
                </div>
            </form>

            <!-- Table of RSVPs -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                        <tr>
                            <th class="p-3">Guest Details</th>
                            <th class="p-3">Pass Code</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-center">+1 Guests</th>
                            <th class="p-3">Dietary</th>
                            <th class="p-3">Door Check-In</th>
                            <th class="p-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($rsvps as $rsvp)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="p-3">
                                    <div class="font-bold text-slate-900 text-sm">{{ $rsvp->full_name }}</div>
                                    <div class="text-[11px] text-slate-500 font-mono">{{ $rsvp->phone }} &bull; {{ $rsvp->email ?: 'No email' }}</div>
                                </td>
                                <td class="p-3 font-mono font-bold text-indigo-600">{{ $rsvp->confirmation_code }}</td>
                                <td class="p-3">
                                    @if($rsvp->attendance_status === 'attending')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            Attending
                                        </span>
                                    @elseif($rsvp->attendance_status === 'declined')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                            Declined
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                            Tentative
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3 text-center font-bold text-slate-700">
                                    {{ $rsvp->plus_one_count }}
                                </td>
                                <td class="p-3">
                                    @if($rsvp->dietary_requirements)
                                        <span class="inline-block px-2 py-0.5 rounded bg-amber-50 text-amber-700 font-semibold text-[10px] border border-amber-200">
                                            {{ $rsvp->dietary_requirements }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">Standard</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    <form action="{{ route('host.website.rsvps.checkin', $rsvp->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition-all {{ $rsvp->is_checked_in ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                            {{ $rsvp->is_checked_in ? '✓ Checked In' : 'Mark Check-In' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="p-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- WhatsApp Chat Link -->
                                        @php
                                            $waMsg = urlencode("Hello {$rsvp->full_name}, thank you for your RSVP for {$website->title}!");
                                            $waLink = "https://wa.me/" . preg_replace('/[^0-9]/', '', $rsvp->phone) . "?text={$waMsg}";
                                        @endphp
                                        <a href="{{ $waLink }}" target="_blank" class="p-1 text-emerald-600 hover:bg-emerald-50 rounded" title="Message via WhatsApp">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.698.073-2.072-.497-1.758-.729-2.883-2.518-2.97-2.634-.088-.117-.714-.951-.714-1.815 0-.864.453-1.289.614-1.464.161-.176.351-.22.468-.22.117 0 .234.001.336.006.107.005.252-.04.394.301.146.353.498 1.216.542 1.304.044.088.073.191.015.308-.059.117-.088.19-.176.293-.088.102-.185.228-.264.307-.088.088-.18.183-.078.359.102.176.455.751.977 1.216.671.597 1.238.782 1.414.87.176.088.278.073.38-.044.102-.117.439-.512.556-.688.117-.176.234-.146.395-.088.161.059 1.025.483 1.201.571.176.088.293.132.336.205.044.073.044.425-.1.83z"/></svg>
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('host.website.rsvps.destroy', $rsvp->id) }}" method="POST" onsubmit="return confirm('Remove this RSVP?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 rounded">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400">
                                    No RSVP responses recorded yet. Share your event link to start collecting responses!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div>
                {{ $rsvps->links() }}
            </div>
        </div>
    </div>

    <!-- ==================== TAB 5: RSVP FORM & QUESTIONS ==================== -->
    <div id="tab-panel-custom_fields" class="ew-tab-panel space-y-6 {{ $activeTab === 'custom_fields' ? '' : 'hidden' }}">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left: RSVP Form Restrictions & Policy -->
            <div class="lg:col-span-5 space-y-6">
                <form action="{{ route('host.website.general.update') }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-5">
                    @csrf
                    <!-- Preserve general fields -->
                    <input type="hidden" name="title" value="{{ $website->title }}">
                    <input type="hidden" name="slug" value="{{ $website->slug }}">
                    <input type="hidden" name="category" value="{{ $website->category }}">
                    <input type="hidden" name="tagline" value="{{ $website->tagline }}">
                    <input type="hidden" name="host_names" value="{{ $website->host_names }}">
                    <input type="hidden" name="event_date" value="{{ $website->event_date?->format('Y-m-d') }}">
                    <input type="hidden" name="event_time" value="{{ $website->event_time }}">
                    <input type="hidden" name="venue_name" value="{{ $website->venue_name }}">
                    <input type="hidden" name="venue_address" value="{{ $website->venue_address }}">
                    <input type="hidden" name="google_maps_url" value="{{ $website->google_maps_url }}">
                    <input type="hidden" name="theme" value="{{ $website->theme }}">
                    <input type="hidden" name="countdown_style" value="{{ $website->countdown_style }}">
                    <input type="hidden" name="is_published" value="{{ $website->is_published ? '1' : '0' }}">

                    <div>
                        <h3 class="font-heading font-black text-lg text-slate-900">RSVP Rules &amp; Limits</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Control registration deadlines and companion rules.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Registration Deadline</label>
                        <input type="date" name="rsvp_deadline" value="{{ old('rsvp_deadline', $website->rsvp_deadline?->format('Y-m-d')) }}" class="w-full rounded-xl border-slate-200 text-sm">
                        <span class="text-[11px] text-slate-400 mt-1 block">Submissions after this date will be politely closed.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Max Accompanying Guests (+1s)</label>
                        <input type="number" name="max_plus_ones" min="0" max="10" value="{{ old('max_plus_ones', $website->max_plus_ones) }}" class="w-full rounded-xl border-slate-200 text-sm">
                        <span class="text-[11px] text-slate-400 mt-1 block">Set to 0 if no extra companions are permitted.</span>
                    </div>

                    <div class="space-y-3 pt-3 border-t border-slate-100">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="allow_guest_updates" value="1" {{ $website->allow_guest_updates ? 'checked' : '' }} class="rounded text-indigo-600 focus:ring-indigo-500">
                            <span class="text-xs font-semibold text-slate-700">Allow guests to update their RSVP submission</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="require_phone" value="1" {{ $website->require_phone ? 'checked' : '' }} class="rounded text-indigo-600 focus:ring-indigo-500">
                            <span class="text-xs font-semibold text-slate-700">Require mobile phone number</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="require_email" value="1" {{ $website->require_email ? 'checked' : '' }} class="rounded text-indigo-600 focus:ring-indigo-500">
                            <span class="text-xs font-semibold text-slate-700">Require guest email address</span>
                        </label>
                    </div>

                    <div class="pt-3 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md">
                            Save RSVP Rules
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right: Custom Questions Builder -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Add Question Form -->
                <form action="{{ route('host.website.custom-fields.store') }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-5">
                    @csrf
                    <div>
                        <h3 class="font-heading font-black text-lg text-slate-900">Add Custom RSVP Question</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Collect specific details such as hotel shuttle needs, song requests, or greetings.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-600 mb-1">Question Label</label>
                            <input type="text" name="field_label" required placeholder="e.g. Will you require the hotel shuttle bus?" class="w-full rounded-xl border-slate-200 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Answer Type</label>
                            <select name="field_type" class="w-full rounded-xl border-slate-200 text-sm">
                                <option value="text">Short Text Answer</option>
                                <option value="textarea">Long Text Paragraph</option>
                                <option value="select">Dropdown Choice</option>
                                <option value="radio">Single Choice (Radio)</option>
                                <option value="checkbox">Multiple Choice (Checkboxes)</option>
                            </select>
                        </div>

                        <div class="flex items-center pt-5">
                            <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                                <input type="checkbox" name="is_required" value="1" class="rounded text-indigo-600">
                                <span>Mandatory Field</span>
                            </label>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-600 mb-1">Options (One option per line, for Dropdown/Radio/Checkboxes)</label>
                            <textarea name="options" rows="3" placeholder="Yes, I need transport&#10;No, I have my own ride" class="w-full rounded-xl border-slate-200 text-xs font-mono"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md">
                            Add Question
                        </button>
                    </div>
                </form>

                <!-- Existing Custom Fields List -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
                    <h3 class="font-heading font-black text-sm text-slate-900">Configured Questions ({{ $customFields->count() }})</h3>
                    <div class="divide-y divide-slate-100">
                        @forelse($customFields as $field)
                            <div class="py-3 flex items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-900 text-xs">{{ $field->field_label }}</span>
                                        @if($field->is_required)
                                            <span class="text-[9px] px-1.5 py-0.5 rounded bg-rose-100 text-rose-700 font-bold uppercase">Required</span>
                                        @endif
                                    </div>
                                    <span class="text-[11px] text-slate-400 capitalize">Type: {{ $field->field_type }}</span>
                                </div>

                                <form action="{{ route('host.website.custom-fields.destroy', $field->id) }}" method="POST" onsubmit="return confirm('Remove question?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-4 text-center">No custom RSVP questions added yet.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>

<!-- Modal: Add Manual RSVP -->
<div id="manual-rsvp-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-5 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-heading font-black text-lg text-slate-900">Record Guest RSVP</h3>
            <button type="button" onclick="document.getElementById('manual-rsvp-modal').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('host.website.rsvps.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Full Name *</label>
                <input type="text" name="full_name" required class="w-full rounded-xl border-slate-200 text-sm">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Phone Number *</label>
                    <input type="text" name="phone" required placeholder="07XXXXXXXX" class="w-full rounded-xl border-slate-200 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Email (Optional)</label>
                    <input type="email" name="email" class="w-full rounded-xl border-slate-200 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Attendance Status *</label>
                    <select name="attendance_status" required class="w-full rounded-xl border-slate-200 text-sm">
                        <option value="attending">Attending (Yes)</option>
                        <option value="declined">Declined (No)</option>
                        <option value="tentative">Tentative (Maybe)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Plus-One Guests</label>
                    <input type="number" name="plus_one_count" min="0" max="10" value="0" class="w-full rounded-xl border-slate-200 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Dietary Requirements</label>
                <input type="text" name="dietary_requirements" placeholder="e.g. Vegetarian, Halal, Allergies" class="w-full rounded-xl border-slate-200 text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Special Wishes / Notes</label>
                <textarea name="special_requests" rows="2" class="w-full rounded-xl border-slate-200 text-xs"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('manual-rsvp-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md">Record RSVP</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function ewSwitchTab(tab) {
    // 1. Hide all panels
    const panels = document.querySelectorAll('.ew-tab-panel');
    panels.forEach(p => p.classList.add('hidden'));

    // 2. Show target panel
    const targetPanel = document.getElementById('tab-panel-' + tab);
    if (targetPanel) {
        targetPanel.classList.remove('hidden');
    }

    // 3. Reset all tab buttons
    const buttons = document.querySelectorAll('.ew-tab-btn');
    buttons.forEach(btn => {
        btn.classList.remove('border-indigo-600', 'text-indigo-600', 'font-bold');
        btn.classList.add('border-transparent', 'text-slate-500', 'hover:text-slate-800');
    });

    // 4. Activate current button
    const targetBtn = document.getElementById('ew-btn-' + tab);
    if (targetBtn) {
        targetBtn.classList.remove('border-transparent', 'text-slate-500', 'hover:text-slate-800');
        targetBtn.classList.add('border-indigo-600', 'text-indigo-600', 'font-bold');
    }

    // 5. Update URL query without page reload
    const url = new URL(window.location);
    url.searchParams.set('tab', tab);
    window.history.replaceState({}, '', url);
}

function ewToggleSectionEditor(id) {
    const editor = document.getElementById('section-editor-' + id);
    const chevron = document.getElementById('section-chevron-' + id);
    if (editor) {
        editor.classList.toggle('hidden');
    }
    if (chevron) {
        chevron.classList.toggle('rotate-180');
    }
}

// Live Dashboard Countdown Clock
document.addEventListener('DOMContentLoaded', function() {
    const targetDateStr = "{{ $website->event_date ? $website->event_date->format('Y-m-d') : '' }} {{ $website->event_time ?: '18:00' }}".trim();
    if (targetDateStr) {
        const targetDate = new Date(targetDateStr.replace(' ', 'T')).getTime();
        function updateDashCountdown() {
            const now = new Date().getTime();
            const distance = targetDate - now;
            if (distance > 0) {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                const dEl = document.getElementById('dash-cd-days');
                const hEl = document.getElementById('dash-cd-hours');
                const mEl = document.getElementById('dash-cd-mins');
                const sEl = document.getElementById('dash-cd-secs');

                if (dEl) dEl.innerText = String(days).padStart(2, '0');
                if (hEl) hEl.innerText = String(hours).padStart(2, '0');
                if (mEl) mEl.innerText = String(minutes).padStart(2, '0');
                if (sEl) sEl.innerText = String(seconds).padStart(2, '0');
            }
        }
        updateDashCountdown();
        setInterval(updateDashCountdown, 1000);
    }
});
</script>
@endpush

