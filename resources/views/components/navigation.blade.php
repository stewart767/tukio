@php
    $navSettings = $systemSettings ?? [];
    $brandLogo = $navSettings['logo_url'] ?? null;
    $contactPhone = $navSettings['phone'] ?? '+255 123 456 789';
    $contactEmail = $navSettings['email'] ?? 'info@tukio360.co.tz';
    $socialLinks = $navSettings['social'] ?? [];
@endphp

<!-- Top Header -->
<div class="bg-gradient-to-r from-purple-600 via-pink-600 to-fuchsia-700 text-white py-2 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between text-sm space-y-2 md:space-y-0">
            <div class="flex flex-wrap items-center gap-4">
                @if($contactPhone)
                    <div class="flex items-center hover:text-purple-200 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>{{ $contactPhone }}</span>
                    </div>
                @endif
                @if($contactEmail)
                    <div class="flex items-center hover:text-purple-200 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ $contactEmail }}</span>
                    </div>
                @endif
            </div>
            <div class="flex items-center space-x-4">
                @foreach(['facebook','twitter','instagram','linkedin','youtube'] as $platform)
                    @if(!empty($socialLinks[$platform]))
                        <a href="{{ $socialLinks[$platform] }}" class="hover:text-purple-300 transition-colors hover:scale-110 transform duration-200" target="_blank" rel="noopener" aria-label="{{ ucfirst($platform) }}">
                            @if($platform === 'facebook')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35C.595 0 0 .594 0 1.326v21.348C0 23.406.595 24 1.325 24h11.49v-9.294H9.691v-3.622h3.124V8.413c0-3.1 1.894-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.796.715-1.796 1.763v2.312h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.406 24 22.674V1.326C24 .594 23.406 0 22.675 0z"/></svg>
                            @elseif($platform === 'twitter')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557a9.83 9.83 0 01-2.828.775 4.932 4.932 0 002.165-2.724 9.864 9.864 0 01-3.127 1.195 4.916 4.916 0 00-8.38 4.482A13.94 13.94 0 011.671 3.149a4.916 4.916 0 001.523 6.574 4.897 4.897 0 01-2.229-.616c-.054 2.28 1.581 4.415 3.949 4.89a4.93 4.93 0 01-2.224.084 4.928 4.928 0 004.6 3.417A9.867 9.867 0 010 21.543a13.94 13.94 0 007.548 2.212c9.142 0 14.307-7.721 13.995-14.646A9.935 9.935 0 0024 4.557z"/></svg>
                            @elseif($platform === 'instagram')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07s-3.584-.012-4.849-.07c-3.26-.149-4.771-1.699-4.919-4.919C2.163 15.6 2.151 15.22 2.151 12c0-3.204.012-3.583.069-4.849.149-3.228 1.664-4.771 4.919-4.919 1.265-.058 1.645-.07 4.849-.07zm0-2.163C8.741 0 8.332.014 7.052.072 2.694.272.272 2.694.072 7.052.014 8.332 0 8.741 0 12s.014 3.668.072 4.948c.2 4.358 2.622 6.78 6.98 6.98 1.28.058 1.689.072 4.948.072s3.668-.014 4.948-.072c4.354-.2 6.782-2.618 6.979-6.98.059-1.281.073-1.69.073-4.948s-.014-3.668-.072-4.948C23.728 2.694 21.306.272 16.948.072 15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998zm6.406-11.845a1.44 1.44 0 11-2.881 0 1.44 1.44 0 012.881 0z"/></svg>
                            @elseif($platform === 'linkedin')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452H17.25v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H10.07V9h3.053v1.561h.044c.426-.806 1.468-1.654 3.023-1.654 3.233 0 3.829 2.129 3.829 4.897v6.648zM5.337 7.433a1.956 1.956 0 110-3.912 1.956 1.956 0 010 3.912zM6.765 20.452H3.91V9h2.855v11.452zM22.225 0H1.771C.792 0 0 .793 0 1.772v20.456C0 23.207.792 24 1.771 24h20.451C23.2 24 24 23.207 24 22.228V1.772C24 .793 23.2 0 22.225 0z"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a2.978 2.978 0 00-2.088-2.103C19.414 3.5 12 3.5 12 3.5s-7.414 0-9.41.583c-.99.269-1.788 1.067-2.055 2.103C0 8.186 0 12 0 12s0 3.814.535 5.814a2.978 2.978 0 002.088 2.103c1.996.583 9.41.583 9.41.583s7.414 0 9.41-.583a2.978 2.978 0 002.088-2.103C24 15.814 24 12 24 12s0-3.814-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            @endif
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Main Navigation -->
<nav id="main-navigation" class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-gray-200/30 shadow-sm transition-all duration-300" role="navigation" aria-label="Main navigation">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center group focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 rounded-lg transition-transform hover:scale-105" aria-label="Tukio 360 Home">
                    @if(!empty($brandLogo) && is_string($brandLogo))
                        <div class="relative h-12 w-auto max-w-[150px]">
                            <img src="{{ $brandLogo }}" alt="Tukio 360" class="h-full w-auto max-w-full rounded-lg object-contain transition-transform group-hover:scale-105" onerror="this.parentElement.innerHTML='<x-logo size=\"small\" :showTagline=\"false\" />';">
                        </div>
                    @else
                        <x-logo size="small" :showTagline="true" />
                    @endif
                </a>
            </div>

            <!-- Desktop Navigation - Left-aligned -->
            <div class="hidden lg:flex flex-1 items-center ml-8">
                <div class="flex items-center space-x-1">
                    <a href="{{ route('home') }}" class="nav-link-desktop relative px-4 py-2 text-sm font-medium text-gray-700 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 {{ request()->routeIs('home') ? 'nav-link-active' : '' }}" data-route="home" aria-current="{{ request()->routeIs('home') ? 'page' : 'false' }}">
                        <span class="relative z-10">Home</span>
                    </a>
                    <a href="{{ route('events') }}" class="nav-link-desktop relative px-4 py-2 text-sm font-medium text-gray-700 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 {{ request()->routeIs('events') ? 'nav-link-active' : '' }}" data-route="events" aria-current="{{ request()->routeIs('events') ? 'page' : 'false' }}">
                        <span class="relative z-10">Events</span>
                    </a>
                    <a href="{{ route('categories') }}" class="nav-link-desktop relative px-4 py-2 text-sm font-medium text-gray-700 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 {{ request()->routeIs('categories') ? 'nav-link-active' : '' }}" data-route="categories" aria-current="{{ request()->routeIs('categories') ? 'page' : 'false' }}">
                        <span class="relative z-10">Categories</span>
                    </a>
                    <a href="{{ route('venues') }}" class="nav-link-desktop relative px-4 py-2 text-sm font-medium text-gray-700 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 {{ request()->routeIs('venues') ? 'nav-link-active' : '' }}" data-route="venues" aria-current="{{ request()->routeIs('venues') ? 'page' : 'false' }}">
                        <span class="relative z-10">Venues</span>
                    </a>
                    <a href="{{ route('contact') }}" class="nav-link-desktop relative px-4 py-2 text-sm font-medium text-gray-700 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 {{ request()->routeIs('contact') ? 'nav-link-active' : '' }}" data-route="contact" aria-current="{{ request()->routeIs('contact') ? 'page' : 'false' }}">
                        <span class="relative z-10">Contact</span>
                    </a>
                </div>
            </div>

            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center space-x-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-nav-secondary px-4 py-2 text-sm font-medium text-gray-700 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2" aria-label="Go to dashboard">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="btn-nav-secondary px-4 py-2 text-sm font-medium text-gray-700 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2" aria-label="Logout">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-nav-secondary px-4 py-2.5 text-sm font-medium text-gray-700 rounded-lg border border-gray-300 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2" aria-label="Admin login">
                        Login
                    </a>
                    <a href="{{ route('host.register') }}" class="btn-nav-primary px-5 py-2.5 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-purple-600 via-pink-600 to-fuchsia-600 shadow-md transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2" aria-label="Create or post event">
                        Post Event
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" id="mobile-menu-toggle" class="p-2 rounded-lg text-gray-700 hover:bg-purple-50 hover:text-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors" aria-label="Toggle mobile menu" aria-expanded="false" aria-controls="mobile-menu">
                    <svg id="mobile-menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="mobile-menu-close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Menu -->
<div id="mobile-menu" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-label="Mobile navigation menu">
    <!-- Overlay -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300" aria-hidden="true"></div>
    
    <!-- Menu Panel -->
    <div id="mobile-menu-panel" class="fixed inset-y-0 left-0 w-80 max-w-[85vw] bg-white shadow-2xl transform -translate-x-full transition-transform duration-300 ease-out overflow-y-auto">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50 sticky top-0 z-10">
            <div class="flex items-center">
                @if(!empty($brandLogo) && is_string($brandLogo))
                    <div class="relative h-10 w-auto max-w-[140px]">
                        <img src="{{ $brandLogo }}" alt="Tukio 360" class="h-full w-auto max-w-full rounded-lg object-contain" onerror="this.parentElement.innerHTML='<x-logo size=\"small\" :showTagline=\"false\" />';">
                    </div>
                @else
                    <x-logo size="small" :showTagline="false" />
                @endif
            </div>
            <button id="mobile-menu-close" type="button" class="p-2 rounded-lg text-gray-700 hover:bg-white hover:text-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors" aria-label="Close mobile menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Menu Content -->
        <div class="p-6 space-y-1 bg-white">
            <!-- Navigation Links -->
            <nav class="space-y-1 mb-6" aria-label="Mobile navigation">
                <a href="{{ route('home') }}" class="mobile-menu-link flex items-center px-4 py-3.5 text-base font-medium text-gray-900 rounded-lg hover:bg-purple-50 hover:text-purple-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 {{ request()->routeIs('home') ? 'bg-purple-50 text-purple-600 font-semibold' : '' }}" aria-current="{{ request()->routeIs('home') ? 'page' : 'false' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Home
                </a>
                <a href="{{ route('events') }}" class="mobile-menu-link flex items-center px-4 py-3.5 text-base font-medium text-gray-900 rounded-lg hover:bg-purple-50 hover:text-purple-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 {{ request()->routeIs('events') ? 'bg-purple-50 text-purple-600 font-semibold' : '' }}" aria-current="{{ request()->routeIs('events') ? 'page' : 'false' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Events
                </a>
                <a href="{{ route('categories') }}" class="mobile-menu-link flex items-center px-4 py-3.5 text-base font-medium text-gray-900 rounded-lg hover:bg-purple-50 hover:text-purple-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 {{ request()->routeIs('categories') ? 'bg-purple-50 text-purple-600 font-semibold' : '' }}" aria-current="{{ request()->routeIs('categories') ? 'page' : 'false' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Categories
                </a>
                <a href="{{ route('venues') }}" class="mobile-menu-link flex items-center px-4 py-3.5 text-base font-medium text-gray-900 rounded-lg hover:bg-purple-50 hover:text-purple-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 {{ request()->routeIs('venues') ? 'bg-purple-50 text-purple-600 font-semibold' : '' }}" aria-current="{{ request()->routeIs('venues') ? 'page' : 'false' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Venues
                </a>
                <a href="{{ route('contact') }}" class="mobile-menu-link flex items-center px-4 py-3.5 text-base font-medium text-gray-900 rounded-lg hover:bg-purple-50 hover:text-purple-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 {{ request()->routeIs('contact') ? 'bg-purple-50 text-purple-600 font-semibold' : '' }}" aria-current="{{ request()->routeIs('contact') ? 'page' : 'false' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Contact
                </a>
            </nav>

            <!-- Divider -->
            <div class="border-t border-gray-200 my-6" aria-hidden="true"></div>

            <!-- Auth Buttons -->
            <div class="space-y-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-full px-4 py-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-purple-50 hover:border-purple-300 hover:text-purple-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="flex items-center justify-center w-full px-4 py-3 text-base font-medium text-white bg-gradient-to-r from-red-500 to-red-600 rounded-lg hover:from-red-600 hover:to-red-700 shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="mobile-menu-link flex items-center justify-center w-full px-4 py-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-purple-50 hover:border-purple-300 hover:text-purple-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Login
                    </a>
                    <a href="{{ route('host.register') }}" class="mobile-menu-link flex items-center justify-center w-full px-4 py-3 text-base font-semibold text-white bg-gradient-to-r from-purple-600 via-pink-600 to-fuchsia-600 rounded-lg hover:from-purple-700 hover:via-pink-700 hover:to-fuchsia-700 shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Post Event
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mainNavigation = document.getElementById('main-navigation');
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuPanel = document.getElementById('mobile-menu-panel');
    const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    const mobileMenuIcon = document.getElementById('mobile-menu-icon');
    const mobileMenuCloseIcon = document.getElementById('mobile-menu-close-icon');
    const mobileMenuLinks = document.querySelectorAll('.mobile-menu-link');

    // Scroll-based navigation background effects
    let lastScrollTop = 0;
    function handleScroll() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 20) {
            mainNavigation.classList.remove('bg-white/80');
            mainNavigation.classList.add('bg-white/95', 'shadow-md');
        } else {
            mainNavigation.classList.remove('bg-white/95', 'shadow-md');
            mainNavigation.classList.add('bg-white/80');
        }
        
        lastScrollTop = scrollTop;
    }

    // Throttled scroll handler
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        if (scrollTimeout) {
            window.cancelAnimationFrame(scrollTimeout);
        }
        scrollTimeout = window.requestAnimationFrame(handleScroll);
    });

    // Mobile menu functions
    function openMenu() {
        if (!mobileMenu || !mobileMenuPanel || !mobileMenuOverlay) return;
        
        mobileMenu.classList.remove('hidden');
        mobileMenuToggle.setAttribute('aria-expanded', 'true');
        
        setTimeout(() => {
            mobileMenuPanel.classList.remove('-translate-x-full');
            mobileMenuOverlay.classList.add('opacity-100');
        }, 10);
        
        if (mobileMenuIcon) mobileMenuIcon.classList.add('hidden');
        if (mobileMenuCloseIcon) mobileMenuCloseIcon.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        if (!mobileMenu || !mobileMenuPanel || !mobileMenuOverlay) return;
        
        mobileMenuPanel.classList.add('-translate-x-full');
        mobileMenuOverlay.classList.remove('opacity-100');
        mobileMenuToggle.setAttribute('aria-expanded', 'false');
        
        setTimeout(() => {
            mobileMenu.classList.add('hidden');
        }, 300);
        
        if (mobileMenuIcon) mobileMenuIcon.classList.remove('hidden');
        if (mobileMenuCloseIcon) mobileMenuCloseIcon.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Mobile menu event listeners
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            if (mobileMenu.classList.contains('hidden')) {
                openMenu();
            } else {
                closeMenu();
            }
        });

        // Keyboard navigation for mobile menu toggle
        mobileMenuToggle.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                if (mobileMenu.classList.contains('hidden')) {
                    openMenu();
                } else {
                    closeMenu();
                }
            }
        });
    }

    if (mobileMenuClose) {
        mobileMenuClose.addEventListener('click', closeMenu);
        mobileMenuClose.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                closeMenu();
            }
        });
    }

    if (mobileMenuOverlay) {
        mobileMenuOverlay.addEventListener('click', closeMenu);
    }

    // Close mobile menu when clicking on any link
    mobileMenuLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Small delay to allow navigation to start
            setTimeout(closeMenu, 100);
        });
    });

    // Close mobile menu on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            closeMenu();
        }
    });

    // Keyboard navigation: Close menu on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileMenu && !mobileMenu.classList.contains('hidden')) {
            closeMenu();
        }
    });

    // Smooth scroll for anchor links (only for same-page anchors, not route links)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            // Only handle anchor links that exist on the current page
            if (href !== '#' && href.startsWith('#') && document.querySelector(href)) {
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu if open
                    if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                        closeMenu();
                    }
                }
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
/* Navigation Link Hover Effects with Underline Slide */
.nav-link-desktop {
    position: relative;
    overflow: hidden;
}

.nav-link-desktop::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #9333ea, #db2777);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateX(-50%);
}

.nav-link-desktop:hover::before,
.nav-link-desktop:focus::before {
    width: calc(100% - 2rem);
}

.nav-link-desktop:hover,
.nav-link-desktop:focus {
    color: #9333ea;
}

.nav-link-active::before {
    width: calc(100% - 2rem);
}

.nav-link-active {
    color: #9333ea;
    font-weight: 600;
}

/* Button Styles with 3D Press Effect */
.btn-nav-secondary {
    position: relative;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-nav-secondary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.btn-nav-secondary:active {
    transform: translateY(0);
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.btn-nav-primary {
    position: relative;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    background: linear-gradient(135deg, #9333ea 0%, #db2777 50%, #d946ef 100%);
    background-size: 200% 200%;
    animation: gradient-shift 3s ease infinite;
}

.btn-nav-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(147, 51, 234, 0.4), 0 4px 6px -2px rgba(147, 51, 234, 0.2);
    animation: gradient-shift 1s ease infinite;
}

.btn-nav-primary:active {
    transform: translateY(0);
    box-shadow: 0 4px 6px -1px rgba(147, 51, 234, 0.3);
}

@keyframes gradient-shift {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

/* Enhanced Focus States for Accessibility */
.nav-link-desktop:focus-visible,
.btn-nav-secondary:focus-visible,
.btn-nav-primary:focus-visible {
    outline: 2px solid #9333ea;
    outline-offset: 2px;
}

/* Mobile Menu Link Improvements */
.mobile-menu-link {
    position: relative;
    touch-action: manipulation;
    -webkit-tap-highlight-color: transparent;
}

.mobile-menu-link:active {
    transform: scale(0.98);
}

/* Smooth transitions for navigation bar scroll */
#main-navigation {
    will-change: background-color, backdrop-filter, box-shadow;
}

/* Improve mobile menu animation performance */
#mobile-menu-panel {
    will-change: transform;
}

/* Tablet responsive adjustments */
@media (min-width: 768px) and (max-width: 1023px) {
    .nav-link-desktop {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
}

/* Ensure proper contrast for accessibility */
@media (prefers-reduced-motion: reduce) {
    .nav-link-desktop::before,
    .btn-nav-secondary,
    .btn-nav-primary,
    .mobile-menu-link {
        transition: none;
        animation: none;
    }
}
</style>
@endpush
