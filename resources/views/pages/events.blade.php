@extends('layouts.landing')

@section('title', __('common.events') . ' - Tukio 360')

@section('content')
<div class="min-h-screen bg-[#f3ffe3] pt-10 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Page Header -->
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <h1 class="text-4xl sm:text-5xl font-black font-heading text-slate-900 tracking-tight">
                {{ __('common.discover_amazing_events') }}
            </h1>
            <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                {{ __('common.events_description') }}
            </p>
        </div>

        <!-- Filter & Search Bar -->
        <div class="glass-card rounded-3xl p-4 sm:p-6 shadow-lg border border-slate-200">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Search -->
                <div class="relative w-full md:w-96">
                    <svg class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input id="event-search" type="text" placeholder="Search events, organizers, or cities..." class="form-input-modern pl-10 text-sm">
                </div>

                <!-- Category Pills -->
                <div class="flex items-center space-x-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                    <button type="button" class="tab-chip active text-xs whitespace-nowrap filter-btn" data-category="all">All Events</button>
                    <button type="button" class="tab-chip text-xs whitespace-nowrap filter-btn" data-category="wedding">Weddings</button>
                    <button type="button" class="tab-chip text-xs whitespace-nowrap filter-btn" data-category="corporate">Corporate</button>
                    <button type="button" class="tab-chip text-xs whitespace-nowrap filter-btn" data-category="party">Parties</button>
                    <button type="button" class="tab-chip text-xs whitespace-nowrap filter-btn" data-category="festival">Festivals</button>
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="events-grid">
            <!-- Event Card 1 -->
            <div class="glass-card rounded-3xl overflow-hidden group event-card" data-category="wedding">
                <div class="relative h-56 overflow-hidden bg-slate-100">
                    <img src="https://images.unsplash.com/photo-1519741497674-611481863552?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Wedding Celebration" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/30"></div>
                    <div class="absolute top-4 left-4">
                        <span class="badge-pill bg-purple-600/90 backdrop-blur-md text-white text-xs">Wedding</span>
                    </div>
                    <div class="absolute top-4 right-4">
                        <span class="badge-pill bg-white/90 backdrop-blur-md text-slate-800 text-xs font-bold">24 Oct</span>
                    </div>
                    <div class="absolute bottom-4 left-4 right-4 text-white">
                        <h3 class="text-xl font-bold font-heading">David &amp; Sarah's Reception</h3>
                        <p class="text-xs text-slate-300 flex items-center mt-1">
                            <svg class="w-3.5 h-3.5 mr-1 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            Serena Hotel, Dar es Salaam
                        </p>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <p class="text-slate-600 text-xs leading-relaxed">
                        An evening of dinner, music, and celebration as we mark the holy union of David and Sarah.
                    </p>
                    <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
                        <span class="font-medium">Digital Pass Required</span>
                        <span class="text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-md">RSVP Open</span>
                    </div>
                    <a href="{{ route('host.register') }}" class="btn-primary w-full text-center text-xs py-2.5">
                        Host an Event Like This
                    </a>
                </div>
            </div>

            <!-- Event Card 2 -->
            <div class="glass-card rounded-3xl overflow-hidden group event-card" data-category="corporate">
                <div class="relative h-56 overflow-hidden bg-slate-100">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Tech Conference" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/30"></div>
                    <div class="absolute top-4 left-4">
                        <span class="badge-pill bg-blue-600/90 backdrop-blur-md text-white text-xs">Corporate</span>
                    </div>
                    <div class="absolute top-4 right-4">
                        <span class="badge-pill bg-white/90 backdrop-blur-md text-slate-800 text-xs font-bold">12 Nov</span>
                    </div>
                    <div class="absolute bottom-4 left-4 right-4 text-white">
                        <h3 class="text-xl font-bold font-heading">Tanzania Tech &amp; AI Summit</h3>
                        <p class="text-xs text-slate-300 flex items-center mt-1">
                            <svg class="w-3.5 h-3.5 mr-1 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            JNICC, Dar es Salaam
                        </p>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <p class="text-slate-600 text-xs leading-relaxed">
                        Join over 500 tech innovators, founders, and investors for keynotes, workshops, and high-level networking.
                    </p>
                    <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
                        <span class="font-medium">QR Gate Badge</span>
                        <span class="text-blue-600 font-bold bg-blue-50 px-2 py-0.5 rounded-md">Selling Fast</span>
                    </div>
                    <a href="{{ route('host.register') }}" class="btn-primary w-full text-center text-xs py-2.5">
                        Host an Event Like This
                    </a>
                </div>
            </div>

            <!-- Event Card 3 -->
            <div class="glass-card rounded-3xl overflow-hidden group event-card" data-category="party">
                <div class="relative h-56 overflow-hidden bg-slate-100">
                    <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Birthday Gala" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/30"></div>
                    <div class="absolute top-4 left-4">
                        <span class="badge-pill bg-indigo-600/90 backdrop-blur-md text-white text-xs">Birthday</span>
                    </div>
                    <div class="absolute top-4 right-4">
                        <span class="badge-pill bg-white/90 backdrop-blur-md text-slate-800 text-xs font-bold">30 Nov</span>
                    </div>
                    <div class="absolute bottom-4 left-4 right-4 text-white">
                        <h3 class="text-xl font-bold font-heading">Nelly's Golden 30th Gala</h3>
                        <p class="text-xs text-slate-300 flex items-center mt-1">
                            <svg class="w-3.5 h-3.5 mr-1 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            White Sands Resort, Mbezi Beach
                        </p>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <p class="text-slate-600 text-xs leading-relaxed">
                        A tropical beachside celebration with live saxophone, cocktail tasting, and gourmet dinner.
                    </p>
                    <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
                        <span class="font-medium">VIP Guestlist</span>
                        <span class="text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded-md">Invite Only</span>
                    </div>
                    <a href="{{ route('host.register') }}" class="btn-primary w-full text-center text-xs py-2.5">
                        Host an Event Like This
                    </a>
                </div>
            </div>
        </div>

        <!-- Call to Action Banner -->
        <div class="bg-gradient-to-r from-indigo-900 via-purple-900 to-slate-900 rounded-3xl p-8 sm:p-12 text-white text-center space-y-6 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <h2 class="text-3xl sm:text-4xl font-black font-heading">{{ __('common.ready_to_create_event') }}</h2>
            <p class="text-slate-300 text-sm max-w-2xl mx-auto leading-relaxed">
                {{ __('common.ready_to_create_desc') }}
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
                <a href="{{ route('host.register') }}" class="btn-primary">
                    {{ __('common.create_event_button') }} &rarr;
                </a>
                <a href="{{ route('contact') }}" class="btn-ghost-dark">
                    {{ __('common.contact_us') }}
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.event-card');
    const searchInput = document.getElementById('event-search');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const cat = this.getAttribute('data-category');

            cards.forEach(card => {
                if (cat === 'all' || card.getAttribute('data-category') === cat) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(query)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endpush
