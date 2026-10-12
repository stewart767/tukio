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

    <title>{{ config('app.name', 'Tukio 360') }} - @yield('title', 'Admin Command Center')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Chart.js for Interactive Intelligence Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    
    @stack('styles')
</head>
<body class="h-full bg-slate-50/70 text-slate-800 font-sans antialiased selection:bg-indigo-500 selection:text-white">
    <div class="min-h-full flex flex-col">
        <!-- Mobile sidebar overlay -->
        <div class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300" id="mobile-sidebar-overlay"></div>
        
        <!-- Modern Frosted Glass Sidebar -->
        <aside class="fixed inset-y-0 z-50 w-72 flex-col transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 admin-glass-sidebar custom-scrollbar overflow-y-auto" id="mobile-sidebar">
            <div class="flex grow flex-col gap-y-6 px-6 pb-6 pt-5">
                
                <!-- Brand Header -->
                <div class="flex h-14 shrink-0 items-center justify-between border-b border-slate-100 pb-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        @if(!empty($brandLogo) && is_string($brandLogo))
                            <div class="relative h-10 w-auto max-w-[170px]">
                                <img src="{{ $brandLogo }}" alt="Tukio 360" class="h-full w-auto object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div style="display: none;">
                                    <x-logo size="default" :showTagline="false" />
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-2.5">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-purple-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-transform duration-300">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-extrabold text-xl tracking-tight text-slate-900 font-heading">Tukio<span class="text-indigo-600 font-black">360</span></span>
                                    <span class="text-[10px] font-semibold tracking-wider text-slate-400 uppercase">Admin Hub</span>
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

                <!-- Navigation Links -->
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-6">
                        <li>
                            <!-- Main Section -->
                            <div class="text-[11px] font-bold leading-5 text-slate-400 uppercase tracking-wider px-3 mb-2">Main</div>
                            <ul role="list" class="space-y-1">
                                <li>
                                    <a href="{{ route('dashboard') }}" class="admin-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                        </svg>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Account Section (for Admins) -->
                        @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'event_admin')
                        <li>
                            <div class="text-[11px] font-bold leading-5 text-slate-400 uppercase tracking-wider px-3 mb-2">Account</div>
                            <ul role="list" class="space-y-1">
                                <li>
                                    <a href="{{ route('admin.profile.show') }}" class="admin-nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.profile.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                        </svg>
                                        <span>My Profile</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        <!-- Super Admin Management Section -->
                        @if(auth()->user()->role === 'super_admin')
                        <li>
                            <div class="text-[11px] font-bold leading-5 text-slate-400 uppercase tracking-wider px-3 mb-2">Management</div>
                            <ul role="list" class="space-y-1">
                                <li>
                                    <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.users.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                        </svg>
                                        <span>Users</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.hosts.index') }}" class="admin-nav-link {{ request()->routeIs('admin.hosts.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.hosts.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.5l3.5-2.625a2.25 2.25 0 012.75 0l3.5 2.625m-9.75 0V15a2.25 2.25 0 002.25 2.25h2.25M2.25 12.5v4.125A2.25 2.25 0 004.5 18.875h2.25M21.75 12.5l-3.5-2.625a2.25 2.25 0 00-2.75 0l-3.5 2.625m9.75 0V15a2.25 2.25 0 01-2.25 2.25H16m5.75-4.75v4.125A2.25 2.25 0 0119.5 18.875H17.25M11.25 18.75h1.5" />
                                        </svg>
                                        <span>Hosts</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.payments.index') }}" class="admin-nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }} flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.payments.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                            </svg>
                                            <span>Host Payments</span>
                                        </div>
                                        @php
                                            $pendingReviewCount = \App\Models\Invoice::where('status', 'under_review')->count();
                                        @endphp
                                        @if($pendingReviewCount > 0)
                                            <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 font-mono">{{ $pendingReviewCount }}</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Fleet & Car Hire Section -->
                        <li>
                            <div class="text-[11px] font-bold leading-5 text-slate-400 uppercase tracking-wider px-3 mb-2">Fleet &amp; Mobility</div>
                            <ul role="list" class="space-y-1">
                                <li>
                                    <a href="{{ route('admin.vehicles.index') }}" class="admin-nav-link {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.vehicles.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.175V3.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75" />
                                        </svg>
                                        <span>Vehicles &amp; Fleet</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.car-bookings.index') }}" class="admin-nav-link {{ request()->routeIs('admin.car-bookings.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.car-bookings.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                        </svg>
                                        <span>Car Bookings</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Content Studio Section -->
                        <li>
                            <div class="text-[11px] font-bold leading-5 text-slate-400 uppercase tracking-wider px-3 mb-2">Content &amp; Event Assets</div>
                            <ul role="list" class="space-y-1">
                                <li>
                                    <a href="{{ route('admin.milestones.index') }}" class="admin-nav-link {{ request()->routeIs('admin.milestones.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.milestones.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                                        </svg>
                                        <span>Milestones &amp; Occasions</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.venues.index') }}" class="admin-nav-link {{ request()->routeIs('admin.venues.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.venues.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A2.25 2.25 0 0018.25 8.25H5.75A2.25 2.25 0 003.5 10.333V21M3 21h18" />
                                        </svg>
                                        <span>Venues &amp; Spaces</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.templates.index') }}" class="admin-nav-link {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.templates.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        <span>Templates</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.pages.index') }}" class="admin-nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.pages.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span>Pages</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Intelligence & Platform Section -->
                        <li>
                            <div class="text-[11px] font-bold leading-5 text-slate-400 uppercase tracking-wider px-3 mb-2">Intelligence &amp; System</div>
                            <ul role="list" class="space-y-1">
                                <li>
                                    <a href="{{ route('admin.reports.index') }}" class="admin-nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.reports.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                        </svg>
                                        <span>Reports &amp; Analytics</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.settings.index') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.settings.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h.75M12 6h1.5M13.5 6h.75m-4.5 0a.75.75 0 01.75-.75h4.5A.75.75 0 0115 6m-4.5 0v.75m4.5-.75v.75m-7.5 12h9m-10.125-1.5l1.5-12A1.5 1.5 0 0110.875 3h2.25a1.5 1.5 0 011.5 1.35l1.5 12a1.5 1.5 0 01-1.5 1.65h-4.5a1.5 1.5 0 01-1.5-1.65z" />
                                        </svg>
                                        <span>System Settings</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        <!-- Event Admin Specific Section -->
                        @if(auth()->user()->role === 'event_admin')
                        <li>
                            <div class="text-[11px] font-bold leading-5 text-slate-400 uppercase tracking-wider px-3 mb-2">Event Operations</div>
                            <ul role="list" class="space-y-1">
                                <li>
                                    <a href="{{ route('host.events.index') }}" class="admin-nav-link {{ request()->routeIs('host.events.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('host.events.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                                        </svg>
                                        <span>Events</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('host.guests.index') }}" class="admin-nav-link {{ request()->routeIs('host.guests.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('host.guests.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                        </svg>
                                        <span>Guests</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('host.invitations.index') }}" class="admin-nav-link {{ request()->routeIs('host.invitations.*') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('host.invitations.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                        </svg>
                                        <span>Invitations</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        <!-- Staff Specific Section -->
                        @if(auth()->user()->role === 'staff')
                        <li>
                            <div class="text-[11px] font-bold leading-5 text-slate-400 uppercase tracking-wider px-3 mb-2">Staff Terminal</div>
                            <ul role="list" class="space-y-1">
                                <li>
                                    <a href="{{ route('staff.scanner') }}" class="admin-nav-link {{ request()->routeIs('staff.scanner') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('staff.scanner') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                        </svg>
                                        <span>QR Scanner</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('staff.attendance') }}" class="admin-nav-link {{ request()->routeIs('staff.attendance') ? 'active' : '' }}">
                                        <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('staff.attendance') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Attendance</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </nav>

                <!-- Sidebar Footer Status Card -->
                <div class="mt-auto pt-4 border-t border-slate-100">
                    <div class="p-3.5 rounded-2xl bg-gradient-to-br from-indigo-50/80 to-purple-50/50 border border-indigo-100/60 shadow-xs">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="status-indicator-live">
                                    <span class="ping bg-emerald-400"></span>
                                    <span class="bg-emerald-500"></span>
                                </span>
                                <span class="text-xs font-bold text-slate-800">System Healthy</span>
                            </div>
                            <span class="text-[10px] font-semibold text-indigo-700 bg-indigo-100/80 px-2 py-0.5 rounded-full">v2.4 Pro</span>
                        </div>
                        <div class="mt-2.5 flex items-center justify-between text-[11px] text-slate-500">
                            <span>Uptime: 99.98%</span>
                            <span class="text-emerald-600 font-medium">● 24ms Latency</span>
                        </div>
                    </div>
                </div>

            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="lg:pl-72 flex flex-col min-h-screen">
            
            <!-- Sleek Top Header Command Bar -->
            <header class="sticky top-0 z-40 flex h-18 shrink-0 items-center justify-between gap-x-4 border-b border-slate-200/70 bg-white/80 backdrop-blur-md px-4 sm:px-6 lg:px-8 shadow-xs">
                
                <!-- Left: Mobile Menu + Quick Search Trigger -->
                <div class="flex items-center gap-3">
                    <button type="button" class="p-2 -ml-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 lg:hidden transition-colors" id="mobile-menu-button" aria-label="Open sidebar">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <!-- Global Quick Search Trigger -->
                    <button type="button" id="command-palette-trigger" class="flex items-center gap-2.5 px-3.5 py-2 text-sm text-slate-400 bg-slate-100/80 hover:bg-slate-100 hover:text-slate-600 rounded-xl border border-slate-200/60 transition-all duration-200 sm:w-64 lg:w-72 shadow-xs group text-left">
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="flex-1 truncate">Quick jump or search...</span>
                        <kbd class="hidden sm:inline-flex items-center gap-0.5 px-1.5 py-0.5 text-[10px] font-semibold text-slate-500 bg-white border border-slate-200 rounded-md shadow-xs">Ctrl K</kbd>
                    </button>
                </div>

                <!-- Right: Clock, Quick Actions, Notifications, User Menu -->
                <div class="flex items-center gap-x-3 sm:gap-x-4">
                    
                    <!-- Live Dynamic Clock & Date -->
                    <div class="hidden md:flex items-center gap-2.5 px-3.5 py-1.5 rounded-xl bg-slate-50 border border-slate-200/60 shadow-xs">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span id="live-digital-clock" class="font-mono text-xs font-bold text-slate-700 tracking-tight">--:--:--</span>
                        <span class="text-slate-300">|</span>
                        <span class="text-xs text-slate-500 font-medium">{{ now()->format('D, M d') }}</span>
                    </div>

                    <!-- Quick Action (+ Create) Dropdown -->
                    @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'event_admin')
                    <div class="relative" id="quick-action-container">
                        <button type="button" id="quick-action-button" class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-3.5 sm:py-2 text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 rounded-xl shadow-sm shadow-indigo-500/25 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="hidden sm:inline">Quick Action</span>
                            <svg class="w-3.5 h-3.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Quick Action Dropdown Menu -->
                        <div id="quick-action-menu" class="hidden absolute right-0 mt-2 w-56 rounded-2xl bg-white p-2 shadow-2xl border border-slate-100 ring-1 ring-black/5 z-50 transform origin-top-right transition-all">
                            <div class="px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400">Create New</div>
                            @if(auth()->user()->role === 'super_admin')
                                <a href="{{ route('admin.hosts.create') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-slate-700 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <span class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">👥</span>
                                    <span>Register Host</span>
                                </a>
                                <a href="{{ route('admin.vehicles.create') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-slate-700 rounded-xl hover:bg-pink-50 hover:text-pink-600 transition-colors">
                                    <span class="w-6 h-6 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center">🚗</span>
                                    <span>Add Fleet Vehicle</span>
                                </a>
                                <a href="{{ route('admin.users.create') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-slate-700 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                    <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">👤</span>
                                    <span>Create System User</span>
                                </a>
                                <a href="{{ route('admin.templates.create') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-slate-700 rounded-xl hover:bg-purple-50 hover:text-purple-600 transition-colors">
                                    <span class="w-6 h-6 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">🎨</span>
                                    <span>New Template</span>
                                </a>
                            @endif
                            @if(auth()->user()->role === 'event_admin')
                                <a href="{{ route('host.events.index') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-slate-700 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <span class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">🎉</span>
                                    <span>Manage Events</span>
                                </a>
                                <a href="{{ route('host.guests.create') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-slate-700 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                    <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">✉️</span>
                                    <span>Add Guest</span>
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Notifications Dropdown -->
                    <div class="relative" id="notifications-container">
                        <button type="button" id="notifications-button" class="relative p-2 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-100 border border-slate-200/60 shadow-xs transition-colors" title="Notifications">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            <!-- Animated notification dot -->
                            <span class="absolute top-1.5 right-1.5 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                            </span>
                        </button>

                        <!-- Notifications Dropdown Menu -->
                        <div id="notifications-menu" class="hidden absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl bg-white p-4 shadow-2xl border border-slate-100 ring-1 ring-black/5 z-50">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-sm text-slate-900">Notifications</h4>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700">3 New</span>
                                </div>
                                <button type="button" class="text-xs text-indigo-600 font-semibold hover:underline" onclick="document.querySelectorAll('.unread-badge').forEach(e => e.remove());">Mark all read</button>
                            </div>
                            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto custom-scrollbar my-2">
                                <div class="py-3 flex items-start gap-3 hover:bg-slate-50/70 p-2 rounded-xl transition-colors cursor-pointer">
                                    <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">⚡</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-slate-900 truncate">System Health Optimal</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">All services running with 99.98% uptime</p>
                                        <span class="text-[10px] text-slate-400 mt-1 block">Just now</span>
                                    </div>
                                    <span class="unread-badge w-2 h-2 rounded-full bg-indigo-600"></span>
                                </div>
                                <div class="py-3 flex items-start gap-3 hover:bg-slate-50/70 p-2 rounded-xl transition-colors cursor-pointer">
                                    <div class="w-8 h-8 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center shrink-0">🚗</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-slate-900 truncate">Fleet Service Ready</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Vehicles and bookings module active</p>
                                        <span class="text-[10px] text-slate-400 mt-1 block">15m ago</span>
                                    </div>
                                    <span class="unread-badge w-2 h-2 rounded-full bg-indigo-600"></span>
                                </div>
                                <div class="py-3 flex items-start gap-3 hover:bg-slate-50/70 p-2 rounded-xl transition-colors cursor-pointer">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">🎉</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-slate-900 truncate">Event Management Active</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Real-time attendance syncing enabled</p>
                                        <span class="text-[10px] text-slate-400 mt-1 block">1h ago</span>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-2 border-t border-slate-100 text-center">
                                <a href="{{ route('admin.reports.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">View All System Activity &rarr;</a>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Dropdown Capsule -->
                    <div class="relative" id="user-menu-container">
                        <button type="button" id="user-menu-button" class="flex items-center gap-2.5 p-1.5 sm:px-3 sm:py-1.5 rounded-2xl hover:bg-slate-100 border border-slate-200/60 shadow-xs transition-colors group">
                            <div class="relative">
                                <div class="h-8 w-8 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                            </div>
                            <div class="hidden sm:flex flex-col text-left">
                                <span class="text-xs font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ auth()->user()->name }}</span>
                                <span class="text-[10px] font-semibold text-indigo-600 capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</span>
                            </div>
                            <svg class="hidden sm:block w-3.5 h-3.5 text-slate-400 group-hover:text-slate-600 transition-colors ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- User Profile Menu -->
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-64 rounded-2xl bg-white p-3 shadow-2xl border border-slate-100 ring-1 ring-black/5 z-50">
                            <div class="px-3 py-2 border-b border-slate-100">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                <div class="mt-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                                </div>
                            </div>
                            <div class="py-2 space-y-1">
                                @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'event_admin')
                                <a href="{{ route('admin.profile.show') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-700 rounded-xl hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>Profile Settings</span>
                                </a>
                                @endif
                                @if(auth()->user()->role === 'super_admin')
                                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-700 rounded-xl hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>System Config</span>
                                </a>
                                @endif
                            </div>
                            <div class="pt-2 border-t border-slate-100">
                                <form action="{{ route('logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-rose-600 rounded-xl hover:bg-rose-50 transition-colors">
                                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </header>

            <!-- Page Main Content -->
            <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8">
                @yield('content')
            </main>
            
            <!-- Modern Executive Footer -->
            <footer class="bg-white/60 backdrop-blur-md border-t border-slate-200/60 mt-auto">
                <div class="px-4 sm:px-6 lg:px-8 py-5">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-500">
                        <div class="flex items-center gap-2.5">
                            <span class="font-bold text-slate-800">Tukio 360 &copy; {{ now()->year }}</span>
                            <span class="text-slate-300">•</span>
                            <span>Enterprise Event &amp; Fleet Ecosystem</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-medium border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                SSL 256-Bit Encrypted
                            </span>
                            <a href="{{ route('admin.settings.index') }}" class="hover:text-indigo-600 transition-colors">Settings</a>
                            <a href="{{ route('admin.reports.index') }}" class="hover:text-indigo-600 transition-colors">Analytics</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Command Palette Spotlight Modal (Ctrl+K) -->
    <div id="command-palette-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-start justify-center pt-20 px-4">
        <div class="w-full max-w-xl bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="p-4 border-b border-slate-100 flex items-center gap-3">
                <svg class="w-5 h-5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" id="command-palette-input" placeholder="Type a command or jump to page..." class="w-full text-sm font-medium text-slate-900 focus:outline-none placeholder:text-slate-400">
                <kbd class="px-2 py-0.5 text-[10px] font-bold text-slate-500 bg-slate-100 rounded-md border border-slate-200">ESC</kbd>
            </div>
            <div class="p-3 max-h-80 overflow-y-auto custom-scrollbar" id="command-palette-results">
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-3 py-1.5">Navigation Shortcuts</div>
                <a href="{{ route('dashboard') }}" class="palette-item flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                    <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs">📊</span>
                    <span>Dashboard Overview</span>
                </a>
                @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('admin.hosts.index') }}" class="palette-item flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                    <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs">👥</span>
                    <span>Hosts Management</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="palette-item flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">👤</span>
                    <span>Users &amp; Roles</span>
                </a>
                <a href="{{ route('admin.vehicles.index') }}" class="palette-item flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                    <span class="w-7 h-7 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center text-xs">🚗</span>
                    <span>Vehicles &amp; Fleet Management</span>
                </a>
                <a href="{{ route('admin.car-bookings.index') }}" class="palette-item flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                    <span class="w-7 h-7 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-xs">📅</span>
                    <span>Car Hire Bookings</span>
                </a>
                <a href="{{ route('admin.templates.index') }}" class="palette-item flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                    <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-xs">🎨</span>
                    <span>Card &amp; Invitation Templates</span>
                </a>
                <a href="{{ route('admin.reports.index') }}" class="palette-item flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                    <span class="w-7 h-7 rounded-lg bg-violet-100 text-violet-600 flex items-center justify-center text-xs">📈</span>
                    <span>Operations &amp; Intelligence Reports</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="palette-item flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                    <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs">⚙️</span>
                    <span>System Settings</span>
                </a>
                @endif
            </div>
            <div class="p-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                <span>Use &uarr; &darr; to navigate, Enter to select</span>
                <span>Press ESC to close</span>
            </div>
        </div>
    </div>

    <!-- Scripts & Realtime Controls -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile sidebar controls
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const mobileSidebarOverlay = document.getElementById('mobile-sidebar-overlay');
        const mobileSidebarClose = document.getElementById('mobile-sidebar-close');
        
        function openSidebar() {
            mobileSidebar.classList.remove('-translate-x-full');
            mobileSidebar.classList.add('translate-x-0');
            mobileSidebarOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeSidebar() {
            mobileSidebar.classList.add('-translate-x-full');
            mobileSidebar.classList.remove('translate-x-0');
            mobileSidebarOverlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        if (mobileMenuButton) mobileMenuButton.addEventListener('click', openSidebar);
        if (mobileSidebarOverlay) mobileSidebarOverlay.addEventListener('click', closeSidebar);
        if (mobileSidebarClose) mobileSidebarClose.addEventListener('click', closeSidebar);

        // Real-time digital clock
        const clockEl = document.getElementById('live-digital-clock');
        function updateClock() {
            if (!clockEl) return;
            const now = new Date();
            clockEl.textContent = now.toLocaleTimeString();
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Generic Dropdown Toggle Helper
        function setupDropdown(triggerId, menuId) {
            const trigger = document.getElementById(triggerId);
            const menu = document.getElementById(menuId);
            if (!trigger || !menu) return;

            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                const isHidden = menu.classList.contains('hidden');
                // Close any open dropdowns
                document.querySelectorAll('#quick-action-menu, #notifications-menu, #user-menu').forEach(m => m.classList.add('hidden'));
                if (isHidden) {
                    menu.classList.remove('hidden');
                }
            });
        }

        setupDropdown('quick-action-button', 'quick-action-menu');
        setupDropdown('notifications-button', 'notifications-menu');
        setupDropdown('user-menu-button', 'user-menu');

        // Close dropdowns on document click
        document.addEventListener('click', function() {
            document.querySelectorAll('#quick-action-menu, #notifications-menu, #user-menu').forEach(m => m.classList.add('hidden'));
        });

        // Command Palette (Ctrl+K)
        const paletteModal = document.getElementById('command-palette-modal');
        const paletteTrigger = document.getElementById('command-palette-trigger');
        const paletteInput = document.getElementById('command-palette-input');
        const paletteItems = document.querySelectorAll('.palette-item');

        function openPalette() {
            paletteModal.classList.remove('hidden');
            paletteModal.classList.add('flex');
            setTimeout(() => paletteInput.focus(), 50);
        }

        function closePalette() {
            paletteModal.classList.add('hidden');
            paletteModal.classList.remove('flex');
            paletteInput.value = '';
            paletteItems.forEach(item => item.style.display = 'flex');
        }

        if (paletteTrigger) paletteTrigger.addEventListener('click', openPalette);
        if (paletteModal) {
            paletteModal.addEventListener('click', function(e) {
                if (e.target === paletteModal) closePalette();
            });
        }

        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                if (paletteModal.classList.contains('hidden')) {
                    openPalette();
                } else {
                    closePalette();
                }
            } else if (e.key === 'Escape' && !paletteModal.classList.contains('hidden')) {
                closePalette();
            }
        });

        // Search filter inside palette
        if (paletteInput) {
            paletteInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                paletteItems.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(query)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
    });
    </script>

    @stack('scripts')
</body>
</html>

