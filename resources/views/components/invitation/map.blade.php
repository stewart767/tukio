@props([
    'venue' => 'Serena Hotel Ballroom',
    'address' => 'Ohio Street, Dar es Salaam',
    'mapUrl' => '#',
    'embedUrl' => null,
    'class' => ''
])

<div class="w-full max-w-xl mx-auto my-10 p-6 sm:p-8 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-xl text-center {{ $class }}">
    <div class="text-xs uppercase tracking-[0.25em] font-extrabold opacity-80 mb-2">
        LOCATION &amp; DIRECTIONS
    </div>
    <h3 class="text-2xl font-black font-heading mb-1">
        {{ $venue }}
    </h3>
    <p class="text-xs sm:text-sm opacity-80 mb-5 flex items-center justify-center gap-1.5">
        <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span>{{ $address }}</span>
    </p>

    @if($embedUrl)
        <div class="w-full h-48 sm:h-56 rounded-2xl overflow-hidden border border-white/20 shadow-inner mb-5">
            <iframe src="{{ $embedUrl }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    @endif

    <a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer" 
       class="inline-flex items-center gap-2 py-3 px-6 rounded-2xl bg-white/20 hover:bg-white/30 backdrop-blur-md text-white border border-white/30 font-bold text-xs tracking-wider transition-all duration-300 hover:scale-105 shadow-md">
        <svg class="w-4 h-4 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
        </svg>
        <span>Fungua Google Maps (Get Directions)</span>
    </a>
</div>
