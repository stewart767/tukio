<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=1">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta name="theme-color" content="#667eea">

    <title>{{ config('app.name', 'Tukio 360') }} - @yield('title', 'Access Your Account')</title>

    @vite(['resources/css/app.css', 'resources/css/modern-animations.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="h-full bg-[#f3ffe3] text-slate-800 font-sans antialiased" style="background-color: #f3ffe3;">
    <div id="app" class="min-h-full flex flex-col relative bg-[#f3ffe3]">
        <!-- Animated background gradient -->
        <div class="fixed inset-0 bg-[#f3ffe3]">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(99,102,241,0.08),transparent_50%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_20%,rgba(139,92,246,0.08),transparent_50%)]"></div>
        </div>
        
        <div class="relative z-10 flex flex-col min-h-full">
            <x-navigation />

            <main class="flex-1 relative z-10">
                @yield('content')
            </main>

            <footer class="relative bg-white/70 backdrop-blur-md border-t border-slate-200/80 text-slate-600">
                <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col gap-3 md:flex-row md:items-center md:justify-between text-sm">
                    <div class="flex flex-col gap-1">
                        <p class="text-slate-600">&copy; {{ now()->year }} <span class="font-semibold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">Tukio 360</span>. All rights reserved.</p>
                        <p class="text-slate-500 text-xs">Event Management Excellence</p>
                    </div>
                    <div class="flex gap-4 text-slate-500">
                        <a href="#" class="hover:text-primary-600 transition-colors duration-200 hover:underline decoration-primary-400 underline-offset-4">Privacy</a>
                        <a href="#" class="hover:text-primary-600 transition-colors duration-200 hover:underline decoration-primary-400 underline-offset-4">Terms</a>
                        <a href="#" class="hover:text-primary-600 transition-colors duration-200 hover:underline decoration-primary-400 underline-offset-4">Support</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

