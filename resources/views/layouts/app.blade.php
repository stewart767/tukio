<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $brandSettings = $systemSettings ?? [];
        $brandFavicon = $brandSettings['favicon_url'] ?? null;
        $waFloatingUrl = $brandSettings['whatsapp_url'] ?? ('https://wa.me/' . ($brandSettings['whatsapp_clean'] ?? '255662199071'));
    @endphp

    <!-- Favicon -->
    @if($brandFavicon)
        <link rel="icon" type="image/png" href="{{ $brandFavicon }}">
        <link rel="alternate icon" href="{{ $brandFavicon }}">
    @else
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=1">
        <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
        <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <meta name="theme-color" content="#667eea">
    @endif

    <title>{{ config('app.name', 'Tukio 360') }} - @yield('title', 'Event Management Platform')</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/modern-animations.css', 'resources/js/app.js', 'resources/js/responsive-test.js'])
    
    @stack('styles')
</head>
<body class="h-full bg-gradient-to-br from-slate-50 via-white to-slate-50 font-sans antialiased">
    <div id="app" class="min-h-full">
        <!-- Navigation -->
        <x-navigation />
        
        <!-- Main Content -->
        <main class="relative">
            @yield('content')
        </main>
        
        <!-- Footer - Enhanced with African-inspired design -->
        <footer class="relative bg-gradient-to-br from-neutral-900 via-neutral-800 to-neutral-900 text-white border-t border-neutral-700/50">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDM0VjIySDI0djEySDM2em0yIDJoLTJ2MmgtMnYtMkgxNHYtMkgyMnYtMkgxNHYtMkgyMnYtMkgxNHYtMmgyMnYtMmgtMlYxNGgtMlYyMmgydjJIMTRWMTJoLTIzdi4wMDFoMTB2MTAuOTk5SDEyVjIzLjAwMWgxMFYzNGgxMHYtNGgydjRoMTBWMzRoMnYtNGgydjRoMTAuMDAxdi0xMGgtMlYyMkg0NlYzNGgtMnYtMkgzNnoiLz48L2c+PC9nPjwvc3ZnPg==')] opacity-20"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                    <!-- Brand Column -->
                    <div class="lg:col-span-2">
                        <h3 class="text-xl font-bold mb-4 bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent">
                            Tukio360 – Your Complete Events Experience
                        </h3>
                        <p class="text-neutral-300 text-sm leading-relaxed mb-6 max-w-md">
                            Create, manage, and celebrate unforgettable events with our comprehensive event management platform. 
                            Professional, modern, and designed for the African events industry.
                        </p>
                        <!-- Social Media Icons -->
                        <div class="flex gap-4">
                            <a href="#" class="w-10 h-10 rounded-lg bg-neutral-800 hover:bg-primary-600 flex items-center justify-center transition-all duration-300 hover:scale-110" aria-label="Facebook">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.675 0h-21.35C.595 0 0 .594 0 1.326v21.348C0 23.406.595 24 1.325 24h11.49v-9.294H9.691v-3.622h3.124V8.413c0-3.1 1.894-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.796.715-1.796 1.763v2.312h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.406 24 22.674V1.326C24 .594 23.406 0 22.675 0z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-lg bg-neutral-800 hover:bg-primary-600 flex items-center justify-center transition-all duration-300 hover:scale-110" aria-label="Twitter">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557a9.83 9.83 0 01-2.828.775 4.932 4.932 0 002.165-2.724 9.864 9.864 0 01-3.127 1.195 4.916 4.916 0 00-8.38 4.482A13.94 13.94 0 011.671 3.149a4.916 4.916 0 001.523 6.574 4.897 4.897 0 01-2.229-.616c-.054 2.28 1.581 4.415 3.949 4.89a4.93 4.93 0 01-2.224.084 4.928 4.928 0 004.6 3.417A9.867 9.867 0 010 21.543a13.94 13.94 0 007.548 2.212c9.142 0 14.307-7.721 13.995-14.646A9.935 9.935 0 0024 4.557z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-lg bg-neutral-800 hover:bg-primary-600 flex items-center justify-center transition-all duration-300 hover:scale-110" aria-label="Instagram">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.919C2.163 15.6 2.151 15.22 2.151 12c0-3.204.012-3.583.069-4.849.149-3.228 1.664-4.771 4.919-4.919 1.265-.058 1.645-.07 4.849-.07zm0-2.163C8.741 0 8.332.014 7.052.072 2.694.272.272 2.694.072 7.052.014 8.332 0 8.741 0 12s.014 3.668.072 4.948c.2 4.358 2.622 6.78 6.98 6.98 1.28.058 1.689.072 4.948.072s3.668-.014 4.948-.072c4.354-.2 6.782-2.618 6.979-6.98.059-1.281.073-1.689.073-4.948s-.014-3.668-.072-4.948C23.728 2.694 21.306.272 16.948.072 15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998zm6.406-11.845a1.44 1.44 0 11-2.881 0 1.44 1.44 0 012.881 0z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-lg bg-neutral-800 hover:bg-primary-600 flex items-center justify-center transition-all duration-300 hover:scale-110" aria-label="LinkedIn">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452H17.25v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.053v1.561h.044c.426-.806 1.468-1.654 3.023-1.654 3.233 0 3.829 2.129 3.829 4.897v6.648zM5.337 7.433a1.956 1.956 0 110-3.912 1.956 1.956 0 010 3.912zM6.765 20.452H3.91V9h2.855v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Quick Links Column -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4 text-white">Quick Links</h4>
                        <ul class="space-y-3">
                            <li><a href="{{ route('home') }}" class="text-neutral-300 hover:text-primary-400 transition-colors duration-200 text-sm">Home</a></li>
                            <li><a href="#features" class="text-neutral-300 hover:text-primary-400 transition-colors duration-200 text-sm">Features</a></li>
                            <li><a href="#templates" class="text-neutral-300 hover:text-primary-400 transition-colors duration-200 text-sm">Templates</a></li>
                            <li><a href="#pricing" class="text-neutral-300 hover:text-primary-400 transition-colors duration-200 text-sm">Pricing</a></li>
                            <li><a href="#contact" class="text-neutral-300 hover:text-primary-400 transition-colors duration-200 text-sm">Contact</a></li>
                        </ul>
                    </div>
                    
                    <!-- Contact Details Column -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4 text-white">Contact Details</h4>
                        <ul class="space-y-3">
                            <li class="flex items-start text-neutral-300 text-sm">
                                <svg class="w-5 h-5 mr-2 text-primary-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span>info@tukio360.co.tz</span>
                            </li>
                            <li class="flex items-start text-neutral-300 text-sm">
                                <svg class="w-5 h-5 mr-2 text-primary-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span>+255 XXX XXX XXX</span>
                            </li>
                            <li class="flex items-start text-neutral-300 text-sm">
                                <svg class="w-5 h-5 mr-2 text-primary-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Tanzania</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Bottom Bar -->
                <div class="border-t border-neutral-700/50 pt-8 mt-8">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex flex-col gap-2">
                            <p class="text-neutral-300 text-sm font-medium">&copy; {{ now()->year }} <span class="font-bold bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent">Tukio 360</span>. All rights reserved.</p>
                            <p class="text-neutral-400 text-xs">Your Complete Events Experience</p>
                        </div>
                        <div class="flex gap-6 text-sm">
                            <a href="#" class="text-neutral-400 hover:text-primary-400 transition-colors duration-200 hover:underline decoration-primary-400 underline-offset-4">Privacy Policy</a>
                            <a href="#" class="text-neutral-400 hover:text-primary-400 transition-colors duration-200 hover:underline decoration-primary-400 underline-offset-4">Terms of Service</a>
                            <a href="#" class="text-neutral-400 hover:text-primary-400 transition-colors duration-200 hover:underline decoration-primary-400 underline-offset-4">Support</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Floating WhatsApp Widget -->
    <div class="fixed bottom-6 right-6 z-50 flex items-center group">
        <!-- Floating Tooltip / Label -->
        <a href="{{ $waFloatingUrl }}" target="_blank" rel="noopener noreferrer"
           class="mr-3 hidden sm:flex items-center gap-2 bg-slate-900/95 text-white text-xs font-semibold px-4 py-2 rounded-2xl shadow-xl backdrop-blur-md border border-white/10 opacity-0 translate-x-3 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300 pointer-events-none group-hover:pointer-events-auto">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Chat on WhatsApp</span>
        </a>

        <!-- Floating Action Button -->
        <a href="{{ $waFloatingUrl }}" target="_blank" rel="noopener noreferrer" 
           class="relative flex items-center justify-center w-14 h-14 bg-gradient-to-tr from-emerald-600 via-emerald-500 to-green-500 hover:from-emerald-500 hover:to-green-400 text-white rounded-full shadow-2xl shadow-emerald-500/40 hover:shadow-emerald-500/60 transition-all duration-300 transform hover:scale-110 active:scale-95 focus:outline-none"
           aria-label="Chat on WhatsApp">
            <!-- Pulsing ambient glow -->
            <span class="absolute -inset-1 rounded-full bg-emerald-500/30 animate-ping opacity-75 pointer-events-none"></span>
            <!-- Live online status dot -->
            <span class="absolute top-0 right-0 w-3.5 h-3.5 bg-emerald-400 border-2 border-white rounded-full shadow-xs"></span>
            <!-- WhatsApp Icon -->
            <svg class="w-7 h-7 relative z-10 fill-current" viewBox="0 0 24 24">
                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.966.527 1.76.812 2.795.812 3.179 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.999-5.767-5.999zm3.385 8.163c-.144.405-.837.774-1.17.822-.312.043-.699.077-2.029-.472-1.696-.703-2.779-2.457-2.863-2.569-.084-.112-.686-.914-.686-1.743 0-.829.434-1.237.588-1.408.155-.171.339-.214.452-.214.113 0 .226.002.324.007.104.005.243-.039.38.291.144.344.492 1.2.535 1.288.043.088.071.191.014.305-.056.113-.085.184-.17.283-.085.1-.179.223-.255.3-.099.099-.202.207-.087.404.115.197.51 1.042 1.093 1.562.752.669 1.386.877 1.583.974.197.098.312.085.428-.049.115-.133.493-.574.625-.771.132-.197.265-.164.444-.099.18.066 1.139.537 1.336.635.197.099.329.148.378.23.049.083.049.48-.095.885z"/>
            </svg>
        </a>
    </div>

    @stack('scripts')
</body>
</html>

