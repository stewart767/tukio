<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $website->title }} — Official Event Website</title>
    <meta name="description" content="{{ $website->meta_description ?: $website->tagline }}">

    <!-- OpenGraph / Social Sharing -->
    <meta property="og:title" content="{{ $website->title }}">
    <meta property="og:description" content="{{ $website->tagline }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $website->public_url }}">
    @if($website->cover_image)
        <meta property="og:image" content="{{ Storage::url($website->cover_image) }}">
    @endif

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Great+Vibes&family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,900;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js / Dynamic Theme CSS -->
    <style>
        :root {
            --primary-color: {{ $theme['primary'] }};
            --secondary-color: {{ $theme['secondary'] }};
            --accent-color: {{ $theme['accent'] }};
            --bg-color: {{ $theme['background'] }};
            --text-color: {{ $theme['text'] }};
            --font-heading: '{{ $theme['heading_font'] }}', serif;
            --font-body: '{{ $theme['body_font'] }}', sans-serif;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--bg-color);
            color: var(--text-color);
        }

        .font-theme-heading {
            font-family: var(--font-heading);
        }

        .font-script {
            font-family: 'Great Vibes', cursive;
        }

        .text-theme-primary {
            color: var(--primary-color);
        }

        .text-theme-accent {
            color: var(--accent-color);
        }

        .bg-theme-primary {
            background-color: var(--primary-color);
        }

        .bg-theme-secondary {
            background-color: var(--secondary-color);
        }

        .bg-theme-accent {
            background-color: var(--accent-color);
        }

        .border-theme-primary {
            border-color: var(--primary-color);
        }

        .border-theme-accent {
            border-color: var(--accent-color);
        }

        .gold-shimmer {
            background: linear-gradient(135deg, #ffffff 0%, #fde68a 25%, var(--primary-color) 50%, var(--accent-color) 75%, #ffffff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.7));
        }

        /* Equalizer sound animation */
        @keyframes soundBars {
            0%, 100% { height: 4px; }
            50% { height: 18px; }
        }
        .animate-bar-1 { animation: soundBars 0.8s ease-in-out infinite; }
        .animate-bar-2 { animation: soundBars 1.2s ease-in-out infinite 0.2s; }
        .animate-bar-3 { animation: soundBars 0.9s ease-in-out infinite 0.4s; }
        .animate-bar-4 { animation: soundBars 1.1s ease-in-out infinite 0.1s; }
    </style>
</head>
<body class="selection:bg-amber-400 selection:text-slate-900 antialiased overflow-x-hidden">

    <!-- Preview Mode Notification Banner -->
    @if($isPreview && !$website->is_published)
        <div class="fixed top-0 inset-x-0 z-50 bg-amber-500 text-slate-900 px-4 py-2 text-center text-xs font-bold shadow-lg flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>PREVIEW MODE — This digital event website is currently in draft status. Visible only to you.</span>
            @if(auth('host')->check())
                <a href="{{ route('host.website.index') }}" class="underline ml-2">Open Host Editor &rarr;</a>
            @endif
        </div>
    @endif

    <!-- Floating Background Music Player -->
    @if($website->music_url)
        <div id="audio-widget" class="fixed bottom-6 right-6 z-40">
            <audio id="bg-audio" loop preload="none">
                <source src="{{ $website->music_url }}">
            </audio>

            <button type="button" id="audio-toggle-btn" onclick="toggleAudio()" class="group flex items-center gap-3 px-4 py-2.5 rounded-full bg-slate-900/90 text-white border border-white/20 shadow-2xl backdrop-blur-md hover:scale-105 transition-all">
                <div class="w-7 h-7 rounded-full bg-amber-400/20 text-amber-300 flex items-center justify-center">
                    <svg id="play-icon" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
                    <svg id="pause-icon" class="w-3.5 h-3.5 hidden" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                </div>
                
                <div class="text-left hidden sm:block">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-300 block">Music</span>
                    <span class="text-xs font-semibold text-slate-200 truncate max-w-[140px] block" id="audio-status-label">{{ $website->music_title ?: 'Play Melody' }}</span>
                </div>

                <div id="sound-bars" class="flex items-end gap-1 h-5 px-1 opacity-40">
                    <span class="w-1 bg-amber-400 rounded-full h-1"></span>
                    <span class="w-1 bg-amber-400 rounded-full h-1"></span>
                    <span class="w-1 bg-amber-400 rounded-full h-1"></span>
                    <span class="w-1 bg-amber-400 rounded-full h-1"></span>
                </div>
            </button>
        </div>
    @endif

    <!-- Navigation Bar -->
    <header class="sticky top-0 z-40 bg-slate-900/80 backdrop-blur-md border-b border-white/10 text-white transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Logo / Event Monogram -->
            <a href="#home" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-full border border-amber-400/40 bg-amber-400/10 flex items-center justify-center text-amber-300 font-theme-heading font-black text-lg group-hover:scale-105 transition-transform">
                    {{ strtoupper(substr($website->title, 0, 1)) }}
                </div>
                <div class="flex flex-col">
                    <span class="font-theme-heading font-black text-base sm:text-lg tracking-wider text-slate-100 group-hover:text-amber-300 transition-colors">
                        {{ Str::limit($website->title, 26) }}
                    </span>
                    <span class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold">
                        {{ $website->event_date ? $website->event_date->format('F j, Y') : 'Celebration' }}
                    </span>
                </div>
            </a>

            <!-- Desktop Links -->
            <nav class="hidden lg:flex items-center gap-6 text-xs font-semibold tracking-wider uppercase text-slate-300">
                <a href="#home" class="hover:text-amber-300 transition-colors">Home</a>
                @if($website->isSectionEnabled('about'))
                    <a href="#about" class="hover:text-amber-300 transition-colors">Story</a>
                @endif
                @if($website->isSectionEnabled('schedule'))
                    <a href="#schedule" class="hover:text-amber-300 transition-colors">Itinerary</a>
                @endif
                @if($website->isSectionEnabled('venue'))
                    <a href="#venue" class="hover:text-amber-300 transition-colors">Venue</a>
                @endif
                @if($website->isSectionEnabled('dress_code'))
                    <a href="#dress-code" class="hover:text-amber-300 transition-colors">Dress Code</a>
                @endif
                @if($website->isSectionEnabled('faq'))
                    <a href="#faq" class="hover:text-amber-300 transition-colors">FAQs</a>
                @endif
                @if($website->isSectionEnabled('gallery'))
                    <a href="#gallery" class="hover:text-amber-300 transition-colors">Gallery</a>
                @endif
                @if($website->isSectionEnabled('contact'))
                    <a href="#contact" class="hover:text-amber-300 transition-colors">Contact</a>
                @endif
            </nav>

            <!-- RSVP Button & Mobile Toggle -->
            <div class="flex items-center gap-3">
                <a href="#rsvp" class="px-5 py-2.5 rounded-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 font-bold text-xs uppercase tracking-wider shadow-lg shadow-amber-400/20 transition-all hover:scale-105">
                    RSVP Now
                </a>

                <!-- Mobile Menu Button -->
                <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="lg:hidden p-2 text-slate-300 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu" class="hidden lg:hidden bg-slate-900 border-b border-white/10 px-6 py-4 space-y-3 text-sm font-semibold uppercase tracking-wider text-slate-300">
            <a href="#home" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="block py-1 hover:text-amber-300">Home</a>
            <a href="#about" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="block py-1 hover:text-amber-300">Story</a>
            <a href="#schedule" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="block py-1 hover:text-amber-300">Itinerary</a>
            <a href="#venue" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="block py-1 hover:text-amber-300">Venue</a>
            <a href="#dress-code" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="block py-1 hover:text-amber-300">Dress Code</a>
            <a href="#faq" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="block py-1 hover:text-amber-300">FAQs</a>
            <a href="#gallery" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="block py-1 hover:text-amber-300">Gallery</a>
            <a href="#contact" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="block py-1 hover:text-amber-300">Contact</a>
        </div>
    </header>

    <!-- SECTION 1: HERO / WELCOME -->
    <section id="home" class="relative min-h-[92vh] flex items-center justify-center text-center px-4 py-24 overflow-hidden" style="background-color: var(--bg-color);">
        <!-- Background Image / Video with Gradient Overlay -->
        @php
            $heroPhoto = $website->getHeroPhotoUrl();
        @endphp
        @if($heroPhoto)
            <div class="absolute inset-0 z-0">
                <img src="{{ storage_url($heroPhoto) }}" alt="Hero Background" class="w-full h-full object-cover opacity-35">
                <div class="absolute inset-0 bg-gradient-to-t from-[var(--bg-color)] via-black/60 to-black/80"></div>
            </div>
        @else
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-amber-500/10 via-black/75 to-[var(--bg-color)]"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-black/85 via-black/55 to-[var(--bg-color)]"></div>
            </div>
        @endif

        <div class="relative z-10 max-w-4xl mx-auto space-y-8 animate-fade-in">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-amber-400/40 bg-slate-900/80 backdrop-blur-md text-amber-300 text-xs font-bold uppercase tracking-widest shadow-lg shadow-black/40">
                <span>✦</span>
                <span>{{ $website->getSection('hero')?->content['badge'] ?? 'Celebration of a Lifetime' }}</span>
                <span>✦</span>
            </div>

            <!-- Host / Celebrant Names in Luxury Typography -->
            <div class="space-y-4">
                <span class="font-script text-4xl sm:text-6xl text-amber-300 block drop-shadow-[0_2px_10px_rgba(0,0,0,0.8)]">
                    {{ $website->host_names ?: 'Celebrating' }}
                </span>
                <h1 class="font-theme-heading text-4xl sm:text-6xl md:text-7xl font-black tracking-tight leading-none gold-shimmer uppercase">
                    {{ $website->title }}
                </h1>
                <p class="text-base sm:text-xl text-slate-100 max-w-2xl mx-auto font-light leading-relaxed drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)]">
                    {{ $website->tagline }}
                </p>
            </div>

            <!-- Event Date & Venue Ribbon -->
            <div class="inline-flex flex-col sm:flex-row items-center gap-4 sm:gap-8 px-6 py-4 rounded-3xl bg-slate-900/85 border border-amber-400/30 backdrop-blur-md shadow-2xl text-xs uppercase tracking-wider text-slate-100">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="font-bold text-white">{{ $website->event_date ? $website->event_date->format('l, F j, Y') : 'Date to be announced' }}</span>
                </div>
                <span class="hidden sm:inline text-amber-400/50">&bull;</span>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-bold text-white">{{ $website->event_time ?: '18:00' }}</span>
                </div>
                <span class="hidden sm:inline text-amber-400/50">&bull;</span>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    <span class="font-bold text-white">{{ $website->venue_name }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
                <a href="#rsvp" class="px-8 py-4 rounded-full bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 hover:from-amber-300 hover:to-amber-500 text-slate-950 font-black text-sm uppercase tracking-wider shadow-xl shadow-amber-500/30 transition-all hover:scale-105">
                    Confirm Attendance (RSVP)
                </a>
                <a href="#schedule" class="px-8 py-4 rounded-full bg-slate-900/80 hover:bg-slate-800 text-white font-bold text-sm border border-white/30 backdrop-blur-md transition-all shadow-lg hover:border-amber-400/50 hover:scale-105">
                    View Celebration Program
                </a>
            </div>
        </div>
    </section>

    <!-- SECTION 2: LIVE REAL-TIME COUNTDOWN TIMER -->
    @if($website->isSectionEnabled('countdown'))
        <section id="countdown-section" class="py-16 px-4 border-y border-white/10 bg-slate-950/60 backdrop-blur-md">
            <div class="max-w-4xl mx-auto text-center space-y-8">
                <div>
                    <span class="font-script text-3xl text-amber-300 block">Anticipation</span>
                    <h2 class="font-theme-heading text-2xl sm:text-3xl font-black text-white uppercase tracking-wider">
                        {{ $website->getSection('countdown')?->title ?: 'Countdown to the Celebration' }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-400 mt-2">
                        {{ $website->getSection('countdown')?->subtitle ?: 'Every second brings us closer to celebrating together.' }}
                    </p>
                </div>

                <!-- Clock Boxes -->
                <div id="countdown-container" class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-2xl mx-auto">
                    <!-- Days -->
                    <div class="p-6 rounded-3xl bg-slate-900/90 border border-amber-400/20 shadow-xl shadow-black/30 flex flex-col items-center">
                        <span id="timer-days" class="font-theme-heading text-4xl sm:text-5xl font-black text-amber-300 block">--</span>
                        <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-slate-400 mt-1">Days</span>
                    </div>

                    <!-- Hours -->
                    <div class="p-6 rounded-3xl bg-slate-900/90 border border-amber-400/20 shadow-xl shadow-black/30 flex flex-col items-center">
                        <span id="timer-hours" class="font-theme-heading text-4xl sm:text-5xl font-black text-amber-300 block">--</span>
                        <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-slate-400 mt-1">Hours</span>
                    </div>

                    <!-- Minutes -->
                    <div class="p-6 rounded-3xl bg-slate-900/90 border border-amber-400/20 shadow-xl shadow-black/30 flex flex-col items-center">
                        <span id="timer-minutes" class="font-theme-heading text-4xl sm:text-5xl font-black text-amber-300 block">--</span>
                        <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-slate-400 mt-1">Minutes</span>
                    </div>

                    <!-- Seconds -->
                    <div class="p-6 rounded-3xl bg-slate-900/90 border border-amber-400/20 shadow-xl shadow-black/30 flex flex-col items-center">
                        <span id="timer-seconds" class="font-theme-heading text-4xl sm:text-5xl font-black text-amber-300 block">--</span>
                        <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-slate-400 mt-1">Seconds</span>
                    </div>
                </div>

                <!-- Banner when event has started -->
                <div id="countdown-completed" class="hidden p-6 rounded-3xl bg-gradient-to-r from-amber-500/20 via-amber-400/30 to-amber-500/20 border border-amber-400/40 text-amber-200">
                    <span class="text-2xl font-black font-theme-heading block">🎉 The Celebration is Underway! 🎉</span>
                    <p class="text-sm mt-1 text-slate-200">Welcome to all our guests. Let the music play and the celebration begin!</p>
                </div>
            </div>
        </section>
    @endif

    <!-- SECTION 3: ABOUT THE CELEBRANT / HOST -->
    @if($website->isSectionEnabled('about'))
        @php
            $aboutSec = $website->getSection('about');
            $aboutContent = $aboutSec?->content ?? [];
        @endphp
        <section id="about" class="py-24 px-4 max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Celebrant Portrait / Artwork -->
                <div class="lg:col-span-5">
                    <div class="relative mx-auto max-w-sm">
                        <div class="absolute -inset-3 rounded-3xl bg-gradient-to-tr from-amber-400 via-amber-500 to-amber-200 opacity-30 blur-xl"></div>
                        <div class="relative rounded-3xl overflow-hidden border-2 border-amber-400/30 bg-slate-900 shadow-2xl aspect-[4/5] flex items-center justify-center">
                            @php
                                $aboutPhoto = $website->getAboutPhotoUrl();
                            @endphp
                            @if($aboutPhoto)
                                <img src="{{ storage_url($aboutPhoto) }}" alt="{{ $website->host_names ?: $website->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center p-8 space-y-3">
                                    <span class="w-20 h-20 rounded-full border border-amber-400/40 bg-amber-400/10 flex items-center justify-center text-4xl text-amber-300 font-theme-heading font-black mx-auto">
                                        {{ strtoupper(substr($website->host_names ?: $website->title, 0, 1)) }}
                                    </span>
                                    <span class="text-sm font-theme-heading text-slate-300 block uppercase tracking-widest">{{ $website->host_names }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Story & Bio -->
                <div class="lg:col-span-7 space-y-6 text-left">
                    <div>
                        <span class="font-script text-3xl text-amber-300 block">Honoring</span>
                        <h2 class="font-theme-heading text-3xl sm:text-4xl font-black text-white uppercase tracking-tight">
                            {{ $aboutSec->title ?: 'About the Celebration' }}
                        </h2>
                    </div>

                    <p class="text-sm sm:text-base text-slate-300 leading-relaxed font-light">
                        {{ $aboutContent['story'] ?? 'We come together to honor an extraordinary milestone filled with achievements, love, laughter, and timeless heritage. Thank you for walking this journey with us and making this celebration unforgettable.' }}
                    </p>

                    @if(!empty($aboutContent['quote']))
                        <blockquote class="p-6 rounded-2xl bg-white/5 border-l-4 border-amber-400 text-amber-200 italic font-theme-heading text-sm sm:text-base leading-relaxed">
                            {{ $aboutContent['quote'] }}
                        </blockquote>
                    @endif

                    <div class="pt-2">
                        <a href="#rsvp" class="inline-flex items-center gap-2 text-xs uppercase font-bold tracking-wider text-amber-400 hover:text-amber-300 transition-colors">
                            <span>RSVP to join the celebration</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

            </div>
        </section>
    @endif

    <!-- SECTION 5: ITINERARY / TIMELINE -->
    @if($website->isSectionEnabled('schedule'))
        @php
            $schedSec = $website->getSection('schedule');
            $schedItems = $schedSec?->content['items'] ?? [];
        @endphp
        <section id="schedule" class="py-24 px-4 bg-slate-950/40 border-t border-white/10">
            <div class="max-w-4xl mx-auto text-center space-y-12">
                <div>
                    <span class="font-script text-3xl text-amber-300 block">The Program</span>
                    <h2 class="font-theme-heading text-3xl sm:text-4xl font-black text-white uppercase tracking-tight">
                        {{ $schedSec->title ?: 'Event Itinerary' }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-400 mt-2">
                        {{ $schedSec->subtitle ?: 'Timeline of festivities and moments to share.' }}
                    </p>
                </div>

                <!-- Vertical Timeline -->
                <div class="relative border-l-2 border-amber-400/30 ml-4 sm:ml-32 space-y-10 text-left">
                    @foreach($schedItems as $item)
                        <div class="relative pl-8 sm:pl-10 group">
                            <!-- Bullet Marker -->
                            <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-slate-900 border-2 border-amber-400 group-hover:scale-125 transition-transform"></div>

                            <!-- Desktop Time Tag -->
                            <span class="sm:absolute sm:-left-32 sm:top-1 text-xs font-mono font-bold text-amber-300 block mb-1 sm:mb-0">
                                {{ $item['time'] ?? '' }}
                            </span>

                            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md space-y-2 hover:border-amber-400/40 transition-colors">
                                <h3 class="font-theme-heading font-black text-lg text-white">
                                    {{ $item['title'] ?? '' }}
                                </h3>
                                <p class="text-xs text-slate-300 leading-relaxed">
                                    {{ $item['description'] ?? '' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- SECTION 6: VENUE & LOCATION -->
    @if($website->isSectionEnabled('venue'))
        <section id="venue" class="py-24 px-4 max-w-5xl mx-auto">
            <div class="text-center space-y-10">
                <div>
                    <span class="font-script text-3xl text-amber-300 block">Where to Find Us</span>
                    <h2 class="font-theme-heading text-3xl sm:text-4xl font-black text-white uppercase tracking-tight">
                        Venue &amp; Location
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-400 mt-2">
                        Join us at the prestigious venue setting.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center text-left">
                    <div class="p-8 rounded-3xl bg-white/5 border border-white/10 space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-400/10 text-amber-300 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-amber-300">Venue Setting</span>
                            <h3 class="font-theme-heading text-2xl font-black text-white mt-1">{{ $website->venue_name }}</h3>
                            <p class="text-sm text-slate-300 mt-2">{{ $website->venue_address }}</p>
                        </div>

                        <p class="text-xs text-slate-400 border-t border-white/10 pt-4">
                            {{ $website->getSection('venue')?->content['parking_notes'] ?? 'Complimentary secure on-site parking and valet available.' }}
                        </p>

                        @if($website->google_maps_url)
                            <div class="pt-2">
                                <a href="{{ $website->google_maps_url }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs uppercase tracking-wider transition-all">
                                    <span>Open in Google Maps</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Interactive Map Card -->
                    <div class="rounded-3xl overflow-hidden border border-white/10 bg-slate-900 aspect-square sm:aspect-video md:aspect-square relative flex items-center justify-center p-6 text-center">
                        <div class="space-y-4">
                            <div class="w-16 h-16 rounded-full bg-amber-400/20 text-amber-300 flex items-center justify-center mx-auto">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            </div>
                            <h4 class="font-bold text-lg text-white">Interactive Navigation</h4>
                            <p class="text-xs text-slate-300 max-w-xs mx-auto">Tap below to launch GPS navigation directly to the celebration gate.</p>
                            @if($website->google_maps_url)
                                <a href="{{ $website->google_maps_url }}" target="_blank" class="inline-block px-6 py-2.5 rounded-full bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition-all">
                                    Launch GPS Navigation
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- SECTION 7: DRESS CODE & PALETTE -->
    @if($website->isSectionEnabled('dress_code'))
        @php
            $dressSec = $website->getSection('dress_code');
            $dressContent = $dressSec?->content ?? [];
            $colors = $dressContent['colors'] ?? [
                ['name' => 'Metallic Gold', 'hex' => '#D4AF37'],
                ['name' => 'Royal Emerald', 'hex' => '#0F4D3C'],
                ['name' => 'Champagne Cream', 'hex' => '#F4E8D1'],
                ['name' => 'Midnight Slate', 'hex' => '#0D1117']
            ];
        @endphp
        <section id="dress-code" class="py-24 px-4 bg-slate-950/40 border-t border-white/10">
            <div class="max-w-4xl mx-auto text-center space-y-10">
                <div>
                    <span class="font-script text-3xl text-amber-300 block">Attire Guidelines</span>
                    <h2 class="font-theme-heading text-3xl sm:text-4xl font-black text-white uppercase tracking-tight">
                        Dress Code &amp; Palette
                    </h2>
                    <p class="text-lg font-theme-heading text-amber-300 font-bold mt-2">
                        {{ $dressContent['style'] ?? 'Black Tie / Evening Glamour' }}
                    </p>
                    <p class="text-xs sm:text-sm text-slate-400 max-w-xl mx-auto mt-2 leading-relaxed">
                        {{ $dressContent['notes'] ?? 'We encourage you to dress with sophistication. Feel free to incorporate shades from our celebration palette.' }}
                    </p>
                </div>

                <!-- Palette Circles -->
                <div class="flex flex-wrap items-center justify-center gap-6 pt-4">
                    @foreach($colors as $col)
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-white/20 shadow-xl" style="background-color: {{ $col['hex'] }}"></div>
                            <span class="text-xs font-bold text-slate-200">{{ $col['name'] }}</span>
                            <span class="text-[10px] font-mono text-slate-400">{{ $col['hex'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- SECTION 10: FAQS ACCORDION -->
    @if($website->isSectionEnabled('faq'))
        @php
            $faqSec = $website->getSection('faq');
            $faqs = $faqSec?->content['items'] ?? [];
        @endphp
        <section id="faq" class="py-24 px-4 max-w-4xl mx-auto">
            <div class="text-center space-y-10">
                <div>
                    <span class="font-script text-3xl text-amber-300 block">Clarifications</span>
                    <h2 class="font-theme-heading text-3xl sm:text-4xl font-black text-white uppercase tracking-tight">
                        Frequently Asked Questions
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-400 mt-2">
                        Quick answers to assist in your planning.
                    </p>
                </div>

                <div class="space-y-4 text-left">
                    @foreach($faqs as $idx => $faq)
                        <div class="rounded-2xl bg-white/5 border border-white/10 p-5 cursor-pointer hover:border-amber-400/30 transition-all" onclick="this.querySelector('.faq-answer').classList.toggle('hidden'); this.querySelector('.faq-icon').classList.toggle('rotate-180')">
                            <div class="flex items-center justify-between gap-4">
                                <h3 class="font-bold text-sm sm:text-base text-white">{{ $faq['q'] ?? '' }}</h3>
                                <svg class="faq-icon w-5 h-5 text-amber-400 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <div class="faq-answer hidden mt-3 pt-3 border-t border-white/10 text-xs sm:text-sm text-slate-300 leading-relaxed">
                                {{ $faq['a'] ?? '' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- SECTION 11: PHOTO GALLERY -->
    @if($website->isSectionEnabled('gallery'))
        @php
            $galImages = $website->getGalleryPhotos();
        @endphp
        @if(count($galImages) > 0)
            <section id="gallery" class="py-24 px-4 bg-slate-950/40 border-t border-white/10">
                <div class="max-w-6xl mx-auto text-center space-y-10">
                    <div>
                        <span class="font-script text-3xl text-amber-300 block">Moments</span>
                        <h2 class="font-theme-heading text-3xl sm:text-4xl font-black text-white uppercase tracking-tight">
                            Celebration Gallery
                        </h2>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($galImages as $img)
                            <div class="group relative rounded-2xl overflow-hidden aspect-square border border-white/10 bg-slate-900 shadow-lg">
                                <img src="{{ storage_url($img['url']) }}" alt="{{ $img['caption'] ?? 'Moment' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @if(!empty($img['caption']))
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-4 flex items-end text-xs text-white">
                                        {{ $img['caption'] }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    @endif

    <!-- SECTION 12: GUEST RSVP & REGISTRATION (INTERACTIVE ENGINE) -->
    <section id="rsvp" class="py-24 px-4 border-t border-white/10 bg-gradient-to-b from-slate-950 via-[var(--bg-color)] to-slate-950">
        <div class="max-w-3xl mx-auto space-y-10">
            
            <div class="text-center space-y-3">
                <span class="font-script text-4xl text-amber-300 block">Your Presence</span>
                <h2 class="font-theme-heading text-3xl sm:text-5xl font-black text-white uppercase tracking-tight">
                    RSVP &amp; Registration
                </h2>
                <p class="text-sm text-slate-300 max-w-xl mx-auto">
                    Kindly complete the confirmation form below. Upon submission, you will receive your personal entry pass.
                </p>
                @if($website->rsvp_deadline)
                    <div class="inline-block px-3 py-1 rounded-full bg-amber-400/10 border border-amber-400/20 text-amber-300 text-xs font-bold">
                        RSVP Deadline: {{ $website->rsvp_deadline->format('l, F j, Y') }}
                    </div>
                @endif
            </div>

            <!-- RSVP Confirmation Box (Shown after successful submission) -->
            <div id="rsvp-success-card" class="hidden p-8 sm:p-10 rounded-3xl bg-slate-900/90 border-2 border-amber-400/40 shadow-2xl space-y-6 text-center">
                <div class="w-16 h-16 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>

                <div class="space-y-2">
                    <h3 class="font-theme-heading text-2xl sm:text-3xl font-black text-white" id="success-headline">Attendance Confirmed</h3>
                    <p class="text-sm text-slate-300 max-w-md mx-auto" id="success-message"></p>
                </div>

                <!-- Digital Pass Details Card -->
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 max-w-md mx-auto space-y-4 text-left">
                    <div class="flex items-center justify-between border-b border-white/10 pb-3">
                        <span class="text-xs uppercase tracking-wider text-slate-400 font-bold">Entry Pass Code</span>
                        <span class="font-mono text-base font-black text-amber-400" id="pass-code"></span>
                    </div>

                    <div class="flex items-center justify-between border-b border-white/10 pb-3 text-xs">
                        <span class="text-slate-400 font-bold">Guest Name</span>
                        <span class="font-bold text-white" id="pass-name"></span>
                    </div>

                    <div class="flex items-center justify-between border-b border-white/10 pb-3 text-xs">
                        <span class="text-slate-400 font-bold">Total Party Size</span>
                        <span class="font-bold text-white" id="pass-party-size"></span>
                    </div>

                    <!-- Pass QR Code -->
                    <div class="text-center pt-2">
                        <div class="w-40 h-40 mx-auto p-3 bg-white rounded-xl shadow-inner flex items-center justify-center">
                            <img id="pass-qr-img" src="" alt="RSVP QR Code" class="w-full h-full object-contain">
                        </div>
                        <span class="text-[10px] text-slate-400 mt-2 block">Present this QR code to the door ushers upon arrival.</span>
                    </div>
                </div>

                <!-- Action Buttons: Calendar & WhatsApp -->
                <div class="flex flex-wrap items-center justify-center gap-3 pt-4">
                    <a id="success-calendar-btn" href="{{ $googleCalendarUrl }}" target="_blank" class="px-5 py-2.5 rounded-full bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Add to Google Calendar</span>
                    </a>

                    <button type="button" onclick="document.getElementById('rsvp-success-card').classList.add('hidden'); document.getElementById('rsvp-form').classList.remove('hidden');" class="px-5 py-2.5 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs transition-all">
                        Update Response
                    </button>
                </div>
            </div>

            <!-- Interactive RSVP Form -->
            <form id="rsvp-form" onsubmit="submitRsvpForm(event)" class="p-8 sm:p-10 rounded-3xl bg-slate-900/80 border border-white/10 shadow-2xl space-y-6 text-left backdrop-blur-md">
                @csrf
                <!-- Anti-spam Honeypot (hidden) -->
                <input type="text" name="spambot_trap" class="hidden" tabindex="-1" autocomplete="off">

                <div id="rsvp-error-alert" class="hidden p-4 rounded-xl bg-rose-500/20 border border-rose-500/40 text-rose-200 text-xs font-semibold"></div>

                <!-- 1. Full Name -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Full Name *</label>
                    <input type="text" name="full_name" required placeholder="e.g. Sarah Mrema" class="w-full rounded-2xl bg-white/5 border border-white/15 p-3.5 text-sm text-white focus:border-amber-400 focus:ring-amber-400 placeholder-slate-500">
                </div>

                <!-- 2. Phone & Email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Mobile Phone Number *</label>
                        <input type="tel" name="phone" required placeholder="07XXXXXXXX or +255..." class="w-full rounded-2xl bg-white/5 border border-white/15 p-3.5 text-sm text-white focus:border-amber-400 focus:ring-amber-400 placeholder-slate-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Email Address {{ $website->require_email ? '*' : '(Optional)' }}</label>
                        <input type="email" name="email" {{ $website->require_email ? 'required' : '' }} placeholder="sarah@example.com" class="w-full rounded-2xl bg-white/5 border border-white/15 p-3.5 text-sm text-white focus:border-amber-400 focus:ring-amber-400 placeholder-slate-500">
                    </div>
                </div>

                <!-- 3. Attendance Status Choice -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Will you attend? *</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-white/15 bg-white/5 cursor-pointer hover:border-amber-400 transition-colors">
                            <input type="radio" name="attendance_status" value="attending" checked onchange="togglePlusOne(true)" class="text-amber-400 focus:ring-amber-400">
                            <span class="text-xs font-bold text-white">Joyfully Accept</span>
                        </label>
                        <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-white/15 bg-white/5 cursor-pointer hover:border-rose-400 transition-colors">
                            <input type="radio" name="attendance_status" value="declined" onchange="togglePlusOne(false)" class="text-rose-400 focus:ring-rose-400">
                            <span class="text-xs font-bold text-white">Regretfully Decline</span>
                        </label>
                        <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-white/15 bg-white/5 cursor-pointer hover:border-amber-400 transition-colors">
                            <input type="radio" name="attendance_status" value="tentative" onchange="togglePlusOne(false)" class="text-amber-400 focus:ring-amber-400">
                            <span class="text-xs font-bold text-white">Tentative / Unsure</span>
                        </label>
                    </div>
                </div>

                <!-- 4. Number of Accompanying Guests (+1s) -->
                @if($website->max_plus_ones > 0)
                    <div id="plus-one-container">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Number of Accompanying Guests (Max {{ $website->max_plus_ones }})
                        </label>
                        <select name="plus_one_count" class="w-full rounded-2xl bg-slate-900 border border-white/15 p-3.5 text-sm text-white focus:border-amber-400">
                            @for($i = 0; $i <= $website->max_plus_ones; $i++)
                                <option value="{{ $i }}">{{ $i === 0 ? 'Just myself (0 companions)' : '+' . $i . ' Accompanying Guest' . ($i > 1 ? 's' : '') }}</option>
                            @endfor
                        </select>
                    </div>
                @endif

                <!-- 5. Dietary Requirements -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Dietary Requirements</label>
                    <select name="dietary_requirements" class="w-full rounded-2xl bg-slate-900 border border-white/15 p-3.5 text-sm text-white focus:border-amber-400">
                        <option value="">No special dietary restrictions (Standard banquet)</option>
                        <option value="Vegetarian">Vegetarian</option>
                        <option value="Vegan">Vegan</option>
                        <option value="Halal">Halal</option>
                        <option value="Gluten-Free">Gluten-Free</option>
                        <option value="Nut / Seafood Allergies">Nut / Seafood Allergies</option>
                        <option value="Other">Other (Specify below)</option>
                    </select>
                </div>

                <!-- 6. Special Requests / Heartfelt Wishes -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Congratulatory Message / Special Requests</label>
                    <textarea name="special_requests" rows="3" placeholder="Leave a heartfelt note or any specific arrival requests for the celebrant..." class="w-full rounded-2xl bg-white/5 border border-white/15 p-3.5 text-sm text-white focus:border-amber-400 focus:ring-amber-400 placeholder-slate-500"></textarea>
                </div>

                <!-- 7. Dynamic Custom Questions -->
                @foreach($website->customFields as $field)
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            {{ $field->field_label }} {{ $field->is_required ? '*' : '' }}
                        </label>
                        @if($field->field_type === 'textarea')
                            <textarea name="custom_answers[{{ $field->field_key }}]" {{ $field->is_required ? 'required' : '' }} rows="2" class="w-full rounded-2xl bg-white/5 border border-white/15 p-3.5 text-sm text-white focus:border-amber-400"></textarea>
                        @elseif($field->field_type === 'select')
                            <select name="custom_answers[{{ $field->field_key }}]" {{ $field->is_required ? 'required' : '' }} class="w-full rounded-2xl bg-slate-900 border border-white/15 p-3.5 text-sm text-white focus:border-amber-400">
                                <option value="">Select an option...</option>
                                @foreach($field->options ?? [] as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" name="custom_answers[{{ $field->field_key }}]" {{ $field->is_required ? 'required' : '' }} class="w-full rounded-2xl bg-white/5 border border-white/15 p-3.5 text-sm text-white focus:border-amber-400">
                        @endif
                    </div>
                @endforeach

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" id="rsvp-submit-btn" class="w-full py-4 rounded-full bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 hover:from-amber-300 hover:to-amber-500 text-slate-950 font-black text-sm uppercase tracking-wider shadow-xl shadow-amber-400/25 transition-all flex items-center justify-center gap-2">
                        <span>Submit My RSVP Confirmation</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>

        </div>
    </section>

    <!-- SECTION 13: CONTACT & HOST INFORMATION -->
    @if($website->isSectionEnabled('contact'))
        @php
            $contactSec = $website->getSection('contact');
            $contactContent = $contactSec?->content ?? [];
            $contactPhone = $contactContent['contact_phone'] ?? '+255 700 000 000';
            $waContactLink = "https://wa.me/" . preg_replace('/[^0-9]/', '', $contactPhone) . "?text=" . urlencode("Hello, I am reaching out regarding the celebration for " . $website->title);
        @endphp
        <section id="contact" class="py-20 px-4 max-w-4xl mx-auto text-center space-y-8">
            <div>
                <span class="font-script text-3xl text-amber-300 block">Assistance</span>
                <h2 class="font-theme-heading text-3xl font-black text-white uppercase tracking-tight">
                    Contact &amp; Event Coordinator
                </h2>
                <p class="text-xs text-slate-400 mt-1">For concierge assistance, directions, or urgent inquiries.</p>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-6">
                <a href="{{ $waContactLink }}" target="_blank" class="px-6 py-3 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-emerald-600/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.698.073-2.072-.497-1.758-.729-2.883-2.518-2.97-2.634-.088-.117-.714-.951-.714-1.815 0-.864.453-1.289.614-1.464.161-.176.351-.22.468-.22.117 0 .234.001.336.006.107.005.252-.04.394.301.146.353.498 1.216.542 1.304.044.088.073.191.015.308-.059.117-.088.19-.176.293-.088.102-.185.228-.264.307-.088.088-.18.183-.078.359.102.176.455.751.977 1.216.671.597 1.238.782 1.414.87.176.088.278.073.38-.044.102-.117.439-.512.556-.688.117-.176.234-.146.395-.088.161.059 1.025.483 1.201.571.176.088.293.132.336.205.044.073.044.425-.1.83z"/></svg>
                    <span>Chat on WhatsApp</span>
                </a>

                <a href="tel:{{ $contactPhone }}" class="px-6 py-3 rounded-full bg-white/10 hover:bg-white/20 text-white font-bold text-xs uppercase tracking-wider border border-white/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span>{{ $contactPhone }}</span>
                </a>
            </div>
        </section>
    @endif

    <!-- FOOTER -->
    <footer class="py-12 px-4 border-t border-white/10 text-center text-xs text-slate-500 space-y-3">
        <p class="font-theme-heading text-sm text-slate-300 font-bold uppercase tracking-wider">{{ $website->title }}</p>
        <p>&copy; {{ date('Y') }} All rights reserved &bull; Powered by <a href="{{ route('home') }}" class="text-amber-400 hover:underline font-bold">Tukio360</a></p>
    </footer>

    <!-- JAVASCRIPT: Real-time Countdown, Audio Player & AJAX RSVP Engine -->
    <script>
        // 1. Live Countdown Engine
        const eventDateStr = "{{ $website->event_date ? $website->event_date->format('Y-m-d') : date('Y-m-d') }}";
        const eventTimeStr = "{{ $website->event_time ?: '18:00' }}";
        const targetDateTime = new Date(`${eventDateStr}T${eventTimeStr}:00`).getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const difference = targetDateTime - now;

            const daysSpan = document.getElementById('timer-days');
            const hoursSpan = document.getElementById('timer-hours');
            const minsSpan = document.getElementById('timer-minutes');
            const secsSpan = document.getElementById('timer-seconds');
            const container = document.getElementById('countdown-container');
            const completedBanner = document.getElementById('countdown-completed');

            if (difference <= 0) {
                if (container) container.classList.add('hidden');
                if (completedBanner) completedBanner.classList.remove('hidden');
                return;
            }

            const days = Math.floor(difference / (1000 * 60 * 60 * 24));
            const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((difference % (1000 * 60)) / 1000);

            if (daysSpan) daysSpan.textContent = String(days).padStart(2, '0');
            if (hoursSpan) hoursSpan.textContent = String(hours).padStart(2, '0');
            if (minsSpan) minsSpan.textContent = String(minutes).padStart(2, '0');
            if (secsSpan) secsSpan.textContent = String(seconds).padStart(2, '0');
        }

        setInterval(updateCountdown, 1000);
        updateCountdown();

        // 2. Background Audio Controller
        function toggleAudio() {
            const audio = document.getElementById('bg-audio');
            const playIcon = document.getElementById('play-icon');
            const pauseIcon = document.getElementById('pause-icon');
            const soundBars = document.getElementById('sound-bars');
            const statusLabel = document.getElementById('audio-status-label');

            if (!audio) return;

            if (audio.paused) {
                audio.play().then(() => {
                    playIcon.classList.add('hidden');
                    pauseIcon.classList.remove('hidden');
                    soundBars.classList.remove('opacity-40');
                    soundBars.children[0].classList.add('animate-bar-1');
                    soundBars.children[1].classList.add('animate-bar-2');
                    soundBars.children[2].classList.add('animate-bar-3');
                    soundBars.children[3].classList.add('animate-bar-4');
                    if (statusLabel) statusLabel.textContent = 'Playing Music';
                }).catch(err => {
                    console.log('Audio autoplay prevented:', err);
                });
            } else {
                audio.pause();
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
                soundBars.classList.add('opacity-40');
                soundBars.children[0].classList.remove('animate-bar-1');
                soundBars.children[1].classList.remove('animate-bar-2');
                soundBars.children[2].classList.remove('animate-bar-3');
                soundBars.children[3].classList.remove('animate-bar-4');
                if (statusLabel) statusLabel.textContent = 'Music Paused';
            }
        }

        function togglePlusOne(show) {
            const container = document.getElementById('plus-one-container');
            if (container) {
                container.style.display = show ? 'block' : 'none';
            }
        }

        // 3. Interactive AJAX RSVP Submission
        async function submitRsvpForm(e) {
            e.preventDefault();
            const form = document.getElementById('rsvp-form');
            const submitBtn = document.getElementById('rsvp-submit-btn');
            const errorAlert = document.getElementById('rsvp-error-alert');
            const successCard = document.getElementById('rsvp-success-card');

            errorAlert.classList.add('hidden');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin h-4 w-4 text-slate-950" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Processing Confirmation...</span>
            `;

            try {
                const formData = new FormData(form);
                const response = await fetch("{{ route('event.website.rsvp', $website->slug) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    form.classList.add('hidden');
                    successCard.classList.remove('hidden');

                    document.getElementById('success-headline').textContent = data.rsvp.attendance_status === 'attending' 
                        ? 'Attendance Confirmed! 🎉' 
                        : (data.rsvp.attendance_status === 'declined' ? 'Response Recorded' : 'Status Recorded');

                    document.getElementById('success-message').textContent = data.message;
                    document.getElementById('pass-code').textContent = data.rsvp.confirmation_code;
                    document.getElementById('pass-name').textContent = data.rsvp.full_name;
                    document.getElementById('pass-party-size').textContent = data.rsvp.total_party_size + ' Seat(s) Reserved';
                    
                    if (data.rsvp.qr_code_url) {
                        document.getElementById('pass-qr-img').src = data.rsvp.qr_code_url;
                    }

                    // Smooth scroll to success card
                    successCard.scrollIntoView({ behavior: 'smooth' });
                } else {
                    errorAlert.textContent = data.message || 'Validation error. Please verify the submitted information.';
                    errorAlert.classList.remove('hidden');
                }
            } catch (err) {
                errorAlert.textContent = 'An unexpected network error occurred. Please try again.';
                errorAlert.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <span>Submit My RSVP Confirmation</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                `;
            }
        }
    </script>
</body>
</html>

