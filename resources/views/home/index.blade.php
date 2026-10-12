@extends('layouts.app')

@php
    $brandSettings = $systemSettings ?? [];
    $brandName = $brandSettings['name'] ?? 'Tukio 360';
    $brandTagline = $brandSettings['tagline'] ?? 'Digital Invitation Management System';
    $brandLogo = $brandSettings['logo_url'] ?? null;
    $contactPhone = $brandSettings['phone'] ?? null;
    $contactEmail = $brandSettings['email'] ?? null;
    $contactAddress = $brandSettings['address'] ?? null;
    $socialLinks = $brandSettings['social'] ?? [];
@endphp

@section('title', $brandName . ' - Digital Invitation Management System')

@section('content')
<!-- Hero Section -->
<section class="hero-gradient pt-16 relative overflow-hidden min-h-screen">
    <!-- Background Image with Shadow Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.pexels.com/photos/19970594/pexels-photo-19970594.jpeg" 
             alt="Hero Background" 
             class="w-full h-full object-cover"
             loading="eager">
        <!-- Dark shadow overlay for better readability -->
        <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/60 to-black/70"></div>
        <!-- Additional color overlay for brand consistency -->
        <div class="absolute inset-0 bg-gradient-to-br from-primary-900/40 via-primary-800/30 to-secondary-900/40"></div>
    </div>
    
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 z-[1]">
        <div class="absolute top-20 left-10 w-32 h-32 glass-card rounded-full floating-element opacity-30 animate-combo-float-pulse"></div>
        <div class="absolute top-40 right-20 w-24 h-24 glass-card rounded-full floating-element-wave opacity-20"></div>
        <div class="absolute bottom-20 left-20 w-16 h-16 glass-card rounded-full floating-element-pulse opacity-25"></div>
        <div class="absolute top-1/2 right-10 w-20 h-20 glass-card rounded-full floating-element-infinity opacity-15 animate-combo-rotate-pulse"></div>
        <div class="absolute bottom-32 right-1/3 w-12 h-12 glass-card rounded-full floating-element-bounce opacity-35"></div>
        <!-- Additional floating elements with different animations -->
        <div class="absolute top-1/3 left-1/4 w-10 h-10 glass-card rounded-full floating-element-delay-1 opacity-20 animate-combo-wave-glow"></div>
        <div class="absolute top-2/3 right-1/4 w-14 h-14 glass-card rounded-full floating-element-delay-2 opacity-15"></div>
        <div class="absolute bottom-1/3 left-1/3 w-8 h-8 glass-card rounded-full floating-element-delay-3 opacity-25 animate-combo-float-pulse"></div>
        <!-- New animated gradient elements -->
        <div class="absolute top-1/4 right-1/3 w-20 h-20 rounded-full opacity-20 gradient-animated-rainbow animate-rotate-slow"></div>
        <div class="absolute bottom-1/4 left-1/3 w-16 h-16 rounded-full opacity-15 gradient-animated-diagonal animate-pulse-slow"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center py-20">
        <div class="animate-fade-in-up">
            <!-- Badge -->
            <div class="inline-flex items-center px-4 py-2 rounded-full glass-card text-white text-sm font-medium mb-8 animate-pulse-glow shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Now with AI-powered features
            </div>
            
            <h1 class="text-6xl md:text-8xl font-bold text-white font-display mb-8 leading-tight drop-shadow-2xl">
                <span class="block animate-fade-in-left">Digital Invitations</span>
                <span class="block text-white animate-fade-in-right animate-stagger-1">Reimagined</span>
            </h1>
            
            <p class="text-xl md:text-2xl text-white mb-12 max-w-4xl mx-auto leading-relaxed animate-fade-in-up animate-stagger-2 drop-shadow-lg font-medium">
                Create stunning, interactive digital invitations with AI assistance. Send via WhatsApp, 
                track RSVPs in real-time, and manage events like never before.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center animate-fade-in-up animate-stagger-3">
                <a href="{{ route('host.register') }}" class="modern-button modern-button-primary modern-button-lg button-glow button-magnetic group button-hover-lift">
                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Your Event
                    <div class="absolute inset-0 rounded-lg bg-gradient-to-r from-purple-400 via-pink-400 to-fuchsia-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                </a>
                
                <a href="{{ route('host.login') }}" class="modern-button modern-button-secondary modern-button-lg glass-card group button-hover-pop">
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Host Login
                </a>
                
                <button class="modern-button modern-button-ghost modern-button-lg group button-hover-lift" onclick="scrollToSection('features')">
                    <svg class="w-5 h-5 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    Discover More
                </button>
            </div>
            
            <!-- Stats Section - Modern Floating Cards -->
            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto animate-fade-in-up animate-stagger-4">
                <div class="text-center floating-card p-8 rounded-[20px] backdrop-blur-md group hover:scale-105 transition-all duration-500">
                    <div class="text-4xl md:text-5xl font-bold text-white mb-3 drop-shadow-lg group-hover:scale-110 transition-transform duration-300">10,000+</div>
                    <div class="text-white/90 text-sm font-medium">Events Created</div>
                </div>
                <div class="text-center floating-card p-8 rounded-[20px] backdrop-blur-md group hover:scale-105 transition-all duration-500">
                    <div class="text-4xl md:text-5xl font-bold text-white mb-3 drop-shadow-lg group-hover:scale-110 transition-transform duration-300">500K+</div>
                    <div class="text-white/90 text-sm font-medium">Invitations Sent</div>
                </div>
                <div class="text-center floating-card p-8 rounded-[20px] backdrop-blur-md group hover:scale-105 transition-all duration-500">
                    <div class="text-4xl md:text-5xl font-bold text-white mb-3 drop-shadow-lg group-hover:scale-110 transition-transform duration-300">99.9%</div>
                    <div class="text-white/90 text-sm font-medium">Uptime</div>
                </div>
                <div class="mt-8 text-gray-300 space-y-1 text-sm">
                    @if($contactEmail)<p>{{ $contactEmail }}</p>@endif
                    @if($contactPhone)<p>{{ $contactPhone }}</p>@endif
                    @if($contactAddress)<p>{{ $contactAddress }}</p>@endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce z-10">
        <div class="w-6 h-10 border-2 border-white/50 rounded-full flex justify-center">
            <div class="w-1 h-3 bg-white/50 rounded-full mt-2 animate-pulse"></div>
        </div>
    </div>
</section>

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.floating-element {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.animate-pulse-glow {
    animation: pulse-glow 2s ease-in-out infinite;
}

@keyframes pulse-glow {
    0%, 100% { box-shadow: 0 0 20px rgba(255, 255, 255, 0.3); }
    50% { box-shadow: 0 0 40px rgba(255, 255, 255, 0.5); }
}

.gradient-text {
    background: linear-gradient(to right, #9333ea, #ec4899, #c026d3);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>

<!-- Features Section -->
<section id="features" class="modern-section bg-gradient-to-br from-gray-50 via-white to-gray-50 relative">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5 parallax-bg">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(249,115,22,0.3) 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>
    
    <div class="modern-container relative z-10">
        <div class="text-center mb-20 animate-fade-in-up">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-primary-100 to-secondary-100 text-primary-700 text-sm font-medium mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
                Powerful Features
            </div>
            
            <h2 class="text-5xl md:text-6xl font-bold text-gray-900 font-display mb-8 leading-tight">
                Everything You Need for
                <span class="gradient-text block">Perfect Events</span>
            </h2>
            
            <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                From elegant wedding invitations to corporate event management, 
                {{ $brandName }} provides all the tools you need to create memorable experiences with cutting-edge technology.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="floating-card p-8 animate-fade-in-up animate-stagger-1 card-glow group card-hover-enhanced transition-all-ease">
                <div class="w-16 h-16 bg-gradient-to-br from-pink-400 to-rose-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300 animate-heartbeat morph-shape">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-primary-600 transition-colors-ease">Beautiful Templates</h3>
                <p class="text-gray-600 leading-relaxed">
                    Choose from professionally designed templates for weddings, parties, and corporate events. 
                    Customize colors, fonts, and layouts to match your style with our intuitive editor.
                </p>
                <div class="mt-6 flex items-center text-primary-600 font-medium group-hover:translate-x-2 transition-transform-ease">
                    <span class="text-sm">Explore Templates</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="floating-card p-8 animate-fade-in-up animate-stagger-2 card-glow group card-hover-3d transition-all-ease">
                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300 animate-swing morph-shape">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-primary-600 transition-colors-ease">WhatsApp Integration</h3>
                <p class="text-gray-600 leading-relaxed">
                    Send invitations directly via WhatsApp with QR codes for easy check-in. 
                    Track delivery status and guest responses in real-time with our advanced analytics.
                </p>
                <div class="mt-6 flex items-center text-primary-600 font-medium group-hover:translate-x-2 transition-transform-ease">
                    <span class="text-sm">Learn More</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="floating-card p-8 animate-fade-in-up animate-stagger-3 card-glow group card-hover-morph transition-all-ease">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-400 via-pink-400 to-fuchsia-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300 animate-rotate-in morph-shape">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-primary-600 transition-colors-ease">Real-time Analytics</h3>
                <p class="text-gray-600 leading-relaxed">
                    Monitor invitation delivery, guest responses, and attendance tracking with comprehensive dashboards. 
                    Get detailed reports and insights for your events.
                </p>
                <div class="mt-6 flex items-center text-primary-600 font-medium group-hover:translate-x-2 transition-transform-ease">
                    <span class="text-sm">View Demo</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Feature 4 -->
            <div class="floating-card p-8 animate-fade-in-up animate-stagger-4 card-glow group card-hover-rainbow transition-all-ease">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-violet-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300 animate-bounce-in morph-shape">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-primary-600 transition-colors-ease">Smart Guest Management</h3>
                <p class="text-gray-600 leading-relaxed">
                    Import guest lists, manage RSVPs, and track attendance with AI-powered QR code scanning. 
                    Perfect for events of any size with automated notifications.
                </p>
                <div class="mt-6 flex items-center text-primary-600 font-medium group-hover:translate-x-2 transition-transform-ease">
                    <span class="text-sm">Try It Out</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Feature 5 -->
            <div class="floating-card p-8 animate-fade-in-up animate-stagger-5 card-glow group card-hover-enhanced transition-all-ease">
                <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300 animate-flip-in-x morph-shape">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-primary-600 transition-colors-ease">Instant QR Check-in</h3>
                <p class="text-gray-600 leading-relaxed">
                    Staff can scan QR codes at the event entrance for instant check-in with offline support. 
                    No more manual guest lists or long queues - seamless entry experience.
                </p>
                <div class="mt-6 flex items-center text-primary-600 font-medium group-hover:translate-x-2 transition-transform-ease">
                    <span class="text-sm">See Features</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Feature 6 -->
            <div class="floating-card p-8 animate-fade-in-up animate-stagger-6 card-glow group card-hover-3d transition-all-ease">
                <div class="w-16 h-16 bg-gradient-to-br from-cyan-400 to-teal-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300 animate-flip-in-y morph-shape">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-primary-600 transition-colors-ease">Smart Billing</h3>
                <p class="text-gray-600 leading-relaxed">
                    Automatic invoice generation based on guest count with flexible payment options. 
                    Transparent pricing at 3000 TSH per guest with detailed billing and tax handling.
                </p>
                <div class="mt-6 flex items-center text-primary-600 font-medium group-hover:translate-x-2 transition-transform-ease">
                    <span class="text-sm">View Pricing</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Call to Action -->
        <div class="text-center mt-16 animate-fade-in-up animate-stagger-6">
            <a href="{{ route('host.register') }}" class="modern-button modern-button-primary modern-button-lg button-glow button-magnetic">
                Start Creating Events
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Event Categories Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5 parallax-bg">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(37,99,235,0.3) 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-20 animate-fade-in-up">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-primary-100 to-secondary-100 text-primary-700 text-sm font-medium mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                </svg>
                Event Categories
            </div>
            
            <h2 class="text-5xl md:text-6xl font-bold text-gray-900 font-display mb-8 leading-tight">
                Perfect for Every
                <span class="gradient-text block">Occasion</span>
            </h2>
            
            <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                Whether you're planning an intimate wedding or a corporate conference, 
                we have the perfect solution for your event with specialized templates and features.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Wedding -->
            <div class="relative overflow-hidden rounded-[24px] p-8 text-white animate-fade-in-up animate-stagger-1 floating-card gradient-border-animated transition-all-ease" style="background: linear-gradient(to bottom right, #1e3a8a, #1e40af) !important; border: 1px solid rgba(255, 255, 255, 0.2);">
                <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-white/10 floating-element-pulse animate-combo-float-pulse"></div>
                <div class="absolute -bottom-10 -left-10 w-24 h-24 rounded-full bg-white/10 floating-element-wave animate-combo-wave-glow"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm animate-heartbeat morph-shape">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H9L3 7V9H21ZM3 11V21C3 22.1 3.9 23 5 23H19C20.1 23 21 22.1 21 21V11H3Z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 transition-colors-ease">Weddings</h3>
                    <p class="text-white leading-relaxed mb-6">
                        Create elegant wedding invitations with custom designs and seamless guest management.
                    </p>
                    <a href="{{ route('host.register') }}" class="inline-flex items-center text-white font-medium group">
                        <span class="text-sm transition-colors-ease group-hover:text-primary-200">Create Wedding</span>
                        <svg class="w-4 h-4 ml-1 transition-transform-ease group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Kitchen Party -->
            <div class="relative overflow-hidden rounded-[24px] p-8 text-white animate-fade-in-up animate-stagger-2 floating-card gradient-border-animated transition-all-ease" style="background: linear-gradient(to bottom right, #1e3a8a, #1e40af) !important; border: 1px solid rgba(255, 255, 255, 0.2);">
                <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-white/10 floating-element-bounce animate-combo-rotate-pulse"></div>
                <div class="absolute -bottom-10 -left-10 w-24 h-24 rounded-full bg-white/10 floating-element-infinity animate-combo-float-pulse"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm animate-swing morph-shape">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2Z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 transition-colors-ease">Kitchen Parties</h3>
                    <p class="text-white leading-relaxed mb-6">
                        Celebrate special moments with vibrant kitchen party invitations and fun themes.
                    </p>
                    <a href="{{ route('host.register') }}" class="inline-flex items-center text-white font-medium group">
                        <span class="text-sm transition-colors-ease group-hover:text-primary-200">Plan Party</span>
                        <svg class="w-4 h-4 ml-1 transition-transform-ease group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Send-off -->
            <div class="relative overflow-hidden rounded-[24px] p-8 text-white animate-fade-in-up animate-stagger-3 floating-card gradient-border-animated transition-all-ease" style="background: linear-gradient(to bottom right, #1e3a8a, #1e40af) !important; border: 1px solid rgba(255, 255, 255, 0.2);">
                <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-white/10 floating-element-delay-1 animate-combo-wave-glow"></div>
                <div class="absolute -bottom-10 -left-10 w-24 h-24 rounded-full bg-white/10 floating-element-delay-2 animate-combo-rotate-pulse"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm animate-bounce-in morph-shape">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7L12 12L22 7L12 2ZM2 17L12 22L22 17M2 12L12 17L22 12"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 transition-colors-ease">Send-offs</h3>
                    <p class="text-white leading-relaxed mb-6">
                        Bid farewell in style with memorable send-off invitations and guest coordination.
                    </p>
                    <a href="{{ route('host.register') }}" class="inline-flex items-center text-white font-medium group">
                        <span class="text-sm transition-colors-ease group-hover:text-primary-200">Create Send-off</span>
                        <svg class="w-4 h-4 ml-1 transition-transform-ease group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Corporate -->
            <div class="relative overflow-hidden rounded-[24px] p-8 text-white animate-fade-in-up animate-stagger-4 floating-card gradient-border-animated transition-all-ease" style="background: linear-gradient(to bottom right, #1e3a8a, #1e40af) !important; border: 1px solid rgba(255, 255, 255, 0.2);">
                <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-white/10 floating-element-delay-3 animate-combo-float-pulse"></div>
                <div class="absolute -bottom-10 -left-10 w-24 h-24 rounded-full bg-white/10 floating-element-pulse animate-combo-wave-glow"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm animate-flip-in-x morph-shape">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 7V3H2V21H22V7H12M6 19H4V17H6V19M6 15H4V13H6V15M6 11H4V9H6V11M6 7H4V5H6V7M10 19H8V17H10V19M10 15H8V13H10V15M10 11H8V9H10V11M10 7H8V5H10V7M20 19H12V17H14V15H12V13H14V11H12V9H20V19M18 11H16V13H18V11M18 15H16V17H18V15Z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 transition-colors-ease">Corporate Events</h3>
                    <p class="text-white leading-relaxed mb-6">
                        Professional event management for conferences, meetings, and corporate gatherings.
                    </p>
                    <a href="{{ route('host.register') }}" class="inline-flex items-center text-white font-medium group">
                        <span class="text-sm transition-colors-ease group-hover:text-primary-200">Plan Corporate</span>
                        <svg class="w-4 h-4 ml-1 transition-transform-ease group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Additional CTA -->
        <div class="text-center mt-16 animate-fade-in-up animate-stagger-5">
            <a href="{{ route('host.register') }}" class="modern-button modern-button-primary modern-button-lg button-glow button-magnetic">
                Explore All Categories
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-purple-600 via-pink-600 to-fuchsia-700 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 opacity-10 parallax-bg">
        <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle at 10% 20%, rgba(255,255,255,0.2) 1px, transparent 0); background-size: 40px 40px;"></div>
        <!-- Floating elements in CTA section -->
        <div class="absolute top-1/4 left-1/4 w-8 h-8 rounded-full bg-white/20 floating-element-pulse animate-combo-float-pulse"></div>
        <div class="absolute bottom-1/3 right-1/3 w-12 h-12 rounded-full bg-white/10 floating-element-wave animate-combo-wave-glow"></div>
        <div class="absolute top-2/3 left-1/3 w-6 h-6 rounded-full bg-white/30 floating-element-bounce animate-combo-rotate-pulse"></div>
        <!-- Animated gradient elements -->
        <div class="absolute top-1/3 right-1/4 w-10 h-10 rounded-full opacity-20 gradient-animated-diagonal animate-pulse-slow"></div>
        <div class="absolute bottom-1/4 left-1/4 w-14 h-14 rounded-full opacity-15 gradient-animated-rainbow animate-rotate-slow"></div>
    </div>
    
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/20 text-white/90 text-sm font-medium mb-8 animate-pulse-glow">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Limited Time Offer
        </div>
        
        <h2 class="text-4xl md:text-6xl font-bold text-white font-display mb-8 leading-tight animate-fade-in-up">
            Ready to Create <span class="block gradient-text">Amazing Events?</span>
        </h2>
        
        <p class="text-xl text-white/90 mb-12 leading-relaxed max-w-3xl mx-auto animate-fade-in-up animate-stagger-1">
            Join thousands of event organizers who trust {{ $brandName }} for their digital invitation needs. 
            Start creating beautiful invitations today with our 14-day free trial.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-6 justify-center items-center animate-fade-in-up animate-stagger-2">
            <a href="{{ route('host.register') }}" class="modern-button modern-button-primary modern-button-xl button-glow button-magnetic group transition-all-ease">
                <svg class="w-6 h-6 group-hover:rotate-12 transition-transform-ease" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Start Free Trial
                <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-purple-400 via-pink-400 to-fuchsia-400 opacity-0 group-hover:opacity-100 transition-opacity-ease -z-10"></div>
            </a>
            
            <a href="{{ route('contact') }}" class="modern-button modern-button-secondary modern-button-xl glass-card group transition-all-ease">
                <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform-ease" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                Contact Sales
            </a>
        </div>
        
        <div class="mt-12 text-white/70 text-sm animate-fade-in-up animate-stagger-3">
            <p>No credit card required • Cancel anytime • 14-day free trial</p>
        </div>
    </div>
</section>

<!-- Footer - Modern Design -->
<footer class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white py-20 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5 parallax-bg">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.3) 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>
    
    <!-- Floating decorative elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-32 h-32 rounded-full bg-primary-500/10 blur-3xl floating-element"></div>
        <div class="absolute bottom-20 right-10 w-40 h-40 rounded-full bg-secondary-500/10 blur-3xl floating-element-delay-1"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
            <!-- Brand Column -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center mb-6">
                    @if($brandLogo)
                        <img src="{{ $brandLogo }}" alt="{{ $brandName }}" class="w-[300px] h-[100px] rounded-2xl object-cover">
                    @else
                        <x-logo size="large" variant="light" :showTagline="true" />
                    @endif
                </div>
                
                <p class="text-gray-400 leading-relaxed mb-6 max-w-md">
                    {{ $brandTagline }}. Create, send, and track beautiful invitations with ease.
                </p>
                
                <div class="flex flex-wrap gap-4">
                    @forelse($socialLinks as $platform => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener" class="w-14 h-14 floating-card rounded-full flex items-center justify-center hover:bg-purple-600 hover:scale-110 transition-all duration-300 group backdrop-blur-md bg-white/10 border border-white/20">
                            <span class="sr-only">{{ ucfirst($platform) }}</span>
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                                @if($platform === 'facebook')
                                    <path d="M22.675 0h-21.35C.595 0 0 .594 0 1.326v21.348C0 23.406.595 24 1.325 24h11.49v-9.294H9.691v-3.622h3.124V8.413c0-3.1 1.894-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.796.715-1.796 1.763v2.312h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.406 24 22.674V1.326C24 .594 23.406 0 22.675 0z"/>
                                @elseif($platform === 'twitter')
                                    <path d="M24 4.557a9.83 9.83 0 01-2.828.775 4.932 4.932 0 002.165-2.724 9.864 9.864 0 01-3.127 1.195 4.916 4.916 0 00-8.38 4.482A13.94 13.94 0 011.671 3.149a4.916 4.916 0 001.523 6.574 4.897 4.897 0 01-2.229-.616c-.054 2.28 1.581 4.415 3.949 4.89a4.93 4.93 0 01-2.224.084 4.928 4.928 0 004.6 3.417A9.867 9.867 0 010 21.543a13.94 13.94 0 007.548 2.212c9.142 0 14.307-7.721 13.995-14.646A9.935 9.935 0 0024 4.557z"/>
                                @elseif($platform === 'instagram')
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.919C2.163 15.6 2.151 15.22 2.151 12c0-3.204.012-3.583.069-4.849.149-3.228 1.664-4.771 4.919-4.919 1.265-.058 1.645-.07 4.849-.07zm0-2.163C8.741 0 8.332.014 7.052.072 2.694.272.272 2.694.072 7.052.014 8.332 0 8.741 0 12s.014 3.668.072 4.948c.2 4.358 2.622 6.78 6.98 6.98 1.28.058 1.689.072 4.948.072s3.668-.014 4.948-.072c4.354-.2 6.782-2.618 6.979-6.98.059-1.281.073-1.689.073-4.948s-.014-3.668-.072-4.948C23.728 2.694 21.306.272 16.948.072 15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998zm6.406-11.845a1.44 1.44 0 11-2.881 0 1.44 1.44 0 012.881 0z"/>
                                @elseif($platform === 'linkedin')
                                    <path d="M20.447 20.452H17.25v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.053v1.561h.044c.426-.806 1.468-1.654 3.023-1.654 3.233 0 3.829 2.129 3.829 4.897v6.648zM5.337 7.433a1.956 1.956 0 110-3.912 1.956 1.956 0 010 3.912zM6.765 20.452H3.91V9h2.855v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                @else
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.281.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                @endif
                            </svg>
                        </a>
                    @empty
                        <p class="text-sm text-gray-500">Connect with us on social media.</p>
                    @endforelse
                </div>
            </div>
            
            <!-- Product Column -->
            <div>
                <h4 class="text-lg font-semibold mb-6 relative inline-block">
                    <span class="relative z-10">Product</span>
                    <span class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 via-pink-500 to-fuchsia-500 rounded-full"></span>
                </h4>
                <ul class="space-y-3">
                    <li><a href="#features" class="text-gray-400 hover:text-white transition-all duration-300 flex items-center group hover:translate-x-1">
                        <svg class="w-4 h-4 mr-2 text-purple-500 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Features
                    </a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors flex items-center group">
                        <svg class="w-4 h-4 mr-2 text-primary-500 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Templates
                    </a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors flex items-center group">
                        <svg class="w-4 h-4 mr-2 text-primary-500 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Pricing
                    </a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors flex items-center group">
                        <svg class="w-4 h-4 mr-2 text-primary-500 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        API
                    </a></li>
                </ul>
            </div>
            
            <!-- Support Column -->
            <div>
                <h4 class="text-lg font-semibold mb-6 relative inline-block">
                    <span class="relative z-10">Support</span>
                    <span class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 via-pink-500 to-fuchsia-500 rounded-full"></span>
                </h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors flex items-center group">
                        <svg class="w-4 h-4 mr-2 text-purple-500 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Help Center
                    </a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors flex items-center group">
                        <svg class="w-4 h-4 mr-2 text-primary-500 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Contact Us
                    </a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors flex items-center group">
                        <svg class="w-4 h-4 mr-2 text-primary-500 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Status
                    </a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors flex items-center group">
                        <svg class="w-4 h-4 mr-2 text-primary-500 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Community
                    </a></li>
                </ul>
            </div>
            
            <!-- Admin Access Column -->
            <div>
                <h4 class="text-lg font-semibold mb-6 relative inline-block">
                    <span class="relative z-10">Admin Access</span>
                    <span class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 via-pink-500 to-fuchsia-500 rounded-full"></span>
                </h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors flex items-center group">
                        <svg class="w-4 h-4 mr-2 text-purple-500 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Admin Login
                    </a></li>
                    <li><a href="{{ route('host.login') }}" class="text-gray-400 hover:text-white transition-colors flex items-center group">
                        <svg class="w-4 h-4 mr-2 text-primary-500 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Host Login
                    </a></li>
                    <li><a href="{{ route('host.register') }}" class="text-gray-400 hover:text-white transition-colors flex items-center group">
                        <svg class="w-4 h-4 mr-2 text-primary-500 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Create Event
                    </a></li>
                    <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition-colors flex items-center group">
                        <svg class="w-4 h-4 mr-2 text-primary-500 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Staff Registration
                    </a></li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-800/50 mt-16 pt-8 text-center text-gray-400">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="mb-4 md:mb-0 text-sm">&copy; {{ now()->year }} {{ $brandName }}. All rights reserved. Developed by Dean Garlus.</p>
                <div class="flex flex-wrap justify-center gap-4 md:gap-6">
                    <a href="{{ route('page.show', 'privacy-policy') }}" class="hover:text-white transition-colors text-sm hover:underline">Privacy Policy</a>
                    <a href="{{ route('page.show', 'terms-of-use') }}" class="hover:text-white transition-colors text-sm hover:underline">Terms of Service</a>
                    <a href="{{ route('page.show', 'cookie-policy') }}" class="hover:text-white transition-colors text-sm hover:underline">Cookie Policy</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
@keyframes bounce-subtle {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}
.animate-bounce-subtle {
    animation: bounce-subtle 2s ease-in-out infinite;
}
</style>
@endsection

@push('scripts')
<script>
// Smooth scrolling function
function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({ 
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Magnetic button effect
document.addEventListener('DOMContentLoaded', function() {
    const magneticButtons = document.querySelectorAll('.button-magnetic');
    
    magneticButtons.forEach(button => {
        button.addEventListener('mousemove', function(e) {
            const rect = button.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const moveX = (x - centerX) / 10;
            const moveY = (y - centerY) / 10;
            
            button.style.transform = `translate(${moveX}px, ${moveY}px)`;
        });
        
        button.addEventListener('mouseleave', function() {
            button.style.transform = 'translate(0, 0)';
        });
    });
    
    // Parallax effect for floating elements
    const floatingElements = document.querySelectorAll('.floating-element, .floating-element-wave, .floating-element-pulse, .floating-element-infinity, .floating-element-bounce, .floating-element-delay-1, .floating-element-delay-2, .floating-element-delay-3, .gradient-animated-rainbow, .gradient-animated-diagonal');
    
    window.addEventListener('scroll', function() {
        const scrollPosition = window.scrollY;
        
        floatingElements.forEach((element, index) => {
            const speed = 0.1 + (index * 0.05);
            const yPos = -(scrollPosition * speed);
            // Add horizontal movement for more dynamic parallax
            const xPos = (scrollPosition * speed * 0.5);
            element.style.transform = `translate(${xPos}px, ${yPos}px)`;
        });
    });
    
    // Enhanced parallax for background elements
    const parallaxBackgrounds = document.querySelectorAll('.parallax-bg');
    
    window.addEventListener('scroll', function() {
        const scrollPosition = window.scrollY;
        
        parallaxBackgrounds.forEach((element, index) => {
            const speed = 0.05 + (index * 0.02);
            const yPos = -(scrollPosition * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    });
    
    // Animation on scroll
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, observerOptions);
    
    // Observe elements that should animate on scroll
    document.querySelectorAll('.card-modern, .animate-fade-in-up').forEach(el => {
        observer.observe(el);
    });
});
</script>
@endpush

