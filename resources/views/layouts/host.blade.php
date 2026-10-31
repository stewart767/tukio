<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $brandSettings = $systemSettings ?? [];
        $brandLogo = $brandSettings['logo_url'] ?? null;
        $brandFavicon = $brandSettings['favicon_url'] ?? null;
        $hostUser = auth('host')->user();
    @endphp

    <!-- Favicon -->
    @if($brandFavicon)
        <link rel="icon" type="image/png" href="{{ $brandFavicon }}">
        <link rel="alternate icon" href="{{ $brandFavicon }}">
    @else
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=1">
        <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
        <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <meta name="theme-color" content="#4f46e5">
    @endif

    <title>{{ config('app.name', 'Tukio 360') }} - Host Dashboard - @yield('title', 'Dashboard')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="h-full bg-[#f3ffe3] text-slate-800 font-sans antialiased selection:bg-indigo-500 selection:text-white" style="background-color: #f3ffe3;">
    <div class="min-h-full flex flex-col bg-[#f3ffe3]">
        <!-- Mobile sidebar overlay -->
        <div class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300" id="mobile-sidebar-overlay"></div>
        
        <!-- Modern Frosted Glass Sidebar -->
        <aside class="fixed inset-y-0 z-50 w-72 flex-col transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 host-glass-sidebar custom-scrollbar overflow-y-auto" id="mobile-sidebar">
            <div class="flex grow flex-col gap-y-6 px-6 pb-6 pt-5">
                
                <!-- Brand Header -->
                <div class="flex h-14 shrink-0 items-center justify-between border-b border-slate-100 pb-4">
                    <a href="{{ route('host.dashboard') }}" class="flex items-center gap-3 group">
                        @if(!empty($brandLogo) && is_string($brandLogo))
                            <div class="relative h-10 w-auto max-w-[170px]">
                                <img src="{{ $brandLogo }}" alt="Tukio 360" class="h-full w-auto object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div style="display: none;">
                                    <x-logo size="default" :showTagline="false" />
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-2.5">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-transform duration-300">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-extrabold text-xl tracking-tight text-slate-900 font-heading">Tukio<span class="text-indigo-600 font-black">360</span></span>
                                    <span class="text-[10px] font-semibold tracking-wider text-slate-400 uppercase">Host Dashboard</span>
                                </div>
                            </div>
                        @endif
                    </a>

                    <!-- Mobile Close Button -->
                    <button type="button" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors" id="mobile-sidebar-close" aria-label="Close sidebar">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Event Context Badge (If host is logged in) -->
                @if($hostUser)
                    <div class="p-3.5 rounded-2xl bg-gradient-to-r from-indigo-50 via-purple-50 to-indigo-50/50 border border-indigo-100/80">
                        <div class="flex items-center justify-between">
                                <div class="w-7 h-7 rounded-lg bg-indigo-600/10 text-indigo-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-800 capitalize leading-tight">
                                        {{ ucfirst(str_replace('_', ' ', $hostUser->event_type ?? 'Event')) }}
                                    </span>
                                    <span class="text-[11px] text-slate-500 font-medium">
                                        {{ $hostUser->number_of_guests ?? 0 }} Invited Guests
                                    </span>
                                </div>
                            </div>
                            @if($hostUser->hasConfiguredEvent())
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 animate-pulse">
                                    Setup
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Navigation Links -->
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-6">
                        <li>
                            <div class="text-[11px] font-bold leading-5 text-slate-400 uppercase tracking-wider px-3 mb-2">Event Center</div>
                            <ul role="list" class="space-y-1.5">
                                <li>
                                    <a href="{{ route('host.dashboard') }}" class="host-nav-link {{ request()->routeIs('host.dashboard') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('host.dashboard') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                        </svg>
                                        <span>Dashboard</span>
                                    </a>
                                </li>

                                @if($hostUser && $hostUser->hasConfiguredEvent())
                                <li>
                                    <a href="{{ route('host.website.index') }}" class="host-nav-link justify-between {{ request()->routeIs('host.website.*') ? 'active' : '' }}">
                                        <div class="flex items-center gap-3">
                                            <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('host.website.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                            </svg>
                                            <span>Event Website &amp; RSVP</span>
                                        </div>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-black bg-gradient-to-r from-amber-500 to-amber-600 text-white uppercase tracking-wider shadow-sm">
                                            Premium
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('host.guests.index') }}" class="host-nav-link {{ request()->routeIs('host.guests.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('host.guests.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                        </svg>
                                        <span>Guest Management</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('host.invitations.index') }}" class="host-nav-link {{ request()->routeIs('host.invitations.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('host.invitations.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.902l-5.4 2.7a2.25 2.25 0 01-2.13 0l-5.4-2.7a2.25 2.25 0 01-1.07-1.902V6.75m19.5 0H4.5" />
                                        </svg>
                                        <span>Digital Invitations</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('host.car-hire.index') }}" class="host-nav-link {{ request()->routeIs('host.car-hire.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('host.car-hire.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.175V3.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75" />
                                        </svg>
                                        <span>Car Hire & Fleet</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('host.payments.index') }}" class="host-nav-link justify-between {{ request()->routeIs('host.payments.*') ? 'active' : '' }}">
                                        <div class="flex items-center gap-3">
                                            <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('host.payments.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                            </svg>
                                            <span>Billing & Payments</span>
                                        </div>
                                        @php
                                            $latestInv = $hostUser ? $hostUser->latestInvoice : null;
                                        @endphp
                                        @if($latestInv)
                                            @if($latestInv->isPaid())
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Paid</span>
                                            @elseif($latestInv->isUnderReview())
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">Review</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 animate-pulse">Pay</span>
                                            @endif
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('host.reports.index') }}" class="host-nav-link {{ request()->routeIs('host.reports.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('host.reports.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                                        </svg>
                                        <span>Reports & Analytics</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('host.event-config') }}" class="host-nav-link {{ request()->routeIs('host.event-config') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('host.event-config') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Event Settings</span>
                                    </a>
                                </li>
                                @else
                                <li>
                                    <a href="{{ route('host.event-config') }}" class="host-nav-link active">
                                        <svg class="h-5 w-5 shrink-0 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Event Setup Required</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </nav>

                <!-- Sidebar Footer / Host Profile Card -->
                <div class="pt-4 border-t border-slate-100 mt-auto">
                    <div class="flex items-center justify-between p-2.5 rounded-2xl bg-slate-50 border border-slate-100/80">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white text-xs font-bold shadow-md shadow-indigo-500/20 shrink-0">
                                {{ $hostUser ? strtoupper(substr($hostUser->full_name, 0, 1)) : 'H' }}
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-xs font-bold text-slate-900 truncate">
                                    {{ $hostUser ? $hostUser->full_name : 'Event Host' }}
                                </span>
                                <span class="text-[10px] text-slate-500 truncate">
                                    {{ $hostUser ? $hostUser->phone : 'Host Account' }}
                                </span>
                            </div>
                        </div>
                        <form action="{{ route('host.logout') }}" method="POST" class="shrink-0">
                            @csrf
                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Sign Out">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="lg:pl-72 flex flex-col min-h-screen bg-[#f3ffe3]">
            
            <!-- Frosted Top Navigation Bar -->
            <header class="sticky top-0 z-40 flex h-16 shrink-0 items-center justify-between gap-x-4 border-b border-slate-200/80 bg-white/80 backdrop-blur-md px-4 sm:px-6 lg:px-8 shadow-sm">
                
                <!-- Left: Mobile Toggle & Page Title -->
                <div class="flex items-center gap-3">
                    <button type="button" class="lg:hidden p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors" id="mobile-menu-button" aria-label="Open sidebar">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" id="mobile-menu-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <div>
                        <h1 class="text-lg font-bold text-slate-900 font-heading leading-tight">
                            @yield('page-title', 'Dashboard')
                        </h1>
                        <p class="text-[11px] text-slate-500 font-medium hidden sm:block">
                            Tukio 360 &bull; Host Event Workspace
                        </p>
                    </div>
                </div>

                <!-- Right: Quick Actions & Profile -->
                <div class="flex items-center gap-3 sm:gap-4">
                    
                    @if($hostUser && $hostUser->hasConfiguredEvent())
                        <!-- Quick Add Guest / Invitation Shortcut -->
                        <a href="{{ route('host.guests.create') }}" class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-xs font-semibold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Add Guest</span>
                        </a>
                    @endif

                    <!-- WhatsApp Support Shortcut -->
                    <a href="{{ $brandSettings['whatsapp_url'] ?? 'https://wa.me/' . ($brandSettings['whatsapp_clean'] ?? '255662199071') }}" target="_blank" rel="noopener noreferrer" 
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-semibold transition-all border border-emerald-200/50">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="hidden sm:inline">Support</span>
                    </a>

                    <div class="h-6 w-px bg-slate-200"></div>

                    <!-- Host Avatar Pill -->
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                            {{ $hostUser ? strtoupper(substr($hostUser->full_name, 0, 1)) : 'H' }}
                        </div>
                        <div class="hidden sm:flex flex-col text-left">
                            <span class="text-xs font-bold text-slate-900 leading-tight">
                                {{ $hostUser ? $hostUser->full_name : 'Guest Host' }}
                            </span>
                            <span class="text-[10px] text-slate-400 capitalize">
                                {{ $hostUser ? str_replace('_', ' ', $hostUser->event_type) : 'Host' }}
                            </span>
                        </div>
                    </div>

                </div>

            </header>

            <!-- Main Content Canvas -->
            <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8">
                
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50/90 backdrop-blur border border-emerald-200 text-emerald-800 shadow-sm flex items-start gap-3 animate-fade-in">
                        <div class="p-1 rounded-lg bg-emerald-100 text-emerald-600 shrink-0 mt-0.5">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="flex-1 text-sm font-medium">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50/90 backdrop-blur border border-rose-200 text-rose-800 shadow-sm flex items-start gap-3 animate-fade-in">
                        <div class="p-1 rounded-lg bg-rose-100 text-rose-600 shrink-0 mt-0.5">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="flex-1 text-sm font-medium">
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @yield('content')

            </main>

            <!-- Modern Footer -->
            <footer class="bg-white/70 backdrop-blur-sm border-t border-slate-200/70 mt-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                        <div class="flex items-center gap-2">
                            <p class="text-xs text-slate-500">&copy; {{ now()->year }} <span class="font-bold text-slate-800">Tukio 360</span>. All rights reserved.</p>
                        </div>
                        <div class="flex items-center gap-4 text-xs font-medium text-slate-500">
                            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
                            <a href="{{ route('contact') }}" class="hover:text-indigo-600 transition-colors">Help & Contact</a>
                            <a href="#" class="hover:text-indigo-600 transition-colors">Terms</a>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    <!-- Floating WhatsApp Widget -->
    <a href="{{ $brandSettings['whatsapp_url'] ?? 'https://wa.me/' . ($brandSettings['whatsapp_clean'] ?? '255662199071') }}" target="_blank" rel="noopener noreferrer" 
       class="fixed bottom-6 right-6 z-50 bg-[#25D366] hover:bg-[#20BA5A] text-white rounded-2xl p-3.5 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-110 group flex items-center gap-2"
       aria-label="Contact us on WhatsApp">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.23-.382a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
        </svg>
        <span class="text-xs font-bold hidden md:inline pr-1">Need Help?</span>
    </a>

    <!-- Scripts -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const mobileSidebarOverlay = document.getElementById('mobile-sidebar-overlay');
        const mobileSidebarClose = document.getElementById('mobile-sidebar-close');
        
        function openSidebar() {
            if (mobileSidebar) {
                mobileSidebar.classList.remove('-translate-x-full');
                mobileSidebar.classList.add('translate-x-0');
            }
            if (mobileSidebarOverlay) {
                mobileSidebarOverlay.classList.remove('hidden');
            }
            document.body.style.overflow = 'hidden';
        }
        
        function closeSidebar() {
            if (mobileSidebar) {
                mobileSidebar.classList.add('-translate-x-full');
                mobileSidebar.classList.remove('translate-x-0');
            }
            if (mobileSidebarOverlay) {
                mobileSidebarOverlay.classList.add('hidden');
            }
            document.body.style.overflow = 'auto';
        }
        
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', openSidebar);
        }
        
        if (mobileSidebarClose) {
            mobileSidebarClose.addEventListener('click', closeSidebar);
        }
        
        if (mobileSidebarOverlay) {
            mobileSidebarOverlay.addEventListener('click', closeSidebar);
        }
        
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });
    });
    </script>
    @stack('scripts')
</body>
</html>
