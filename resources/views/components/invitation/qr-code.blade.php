@props([
    'qrUrl' => null,
    'code' => 'TUKIO-PASS',
    'guestName' => 'Mgeni Wetu',
    'table' => null,
    'class' => ''
])

<div class="inline-block p-6 sm:p-8 rounded-3xl bg-white text-slate-900 shadow-2xl border-4 border-amber-400/40 text-center max-w-xs mx-auto relative overflow-hidden {{ $class }}">
    <!-- Decorative Corner Badges -->
    <div class="absolute top-0 right-0 w-12 h-12 bg-amber-400/20 rounded-bl-3xl flex items-center justify-center">
        <span class="text-xs">✨</span>
    </div>

    <div class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-amber-800 bg-amber-100 py-1 px-3 rounded-full inline-block mb-3">
        Official Entry Pass
    </div>

    <!-- QR Code Canvas / Image Container -->
    <div class="w-44 h-44 mx-auto p-2.5 bg-white rounded-2xl border-2 border-slate-200/80 shadow-inner flex items-center justify-center relative">
        @if($qrUrl)
            <img src="{{ $qrUrl }}" alt="QR Code" class="w-full h-full object-contain">
        @else
            <!-- Fallback SVG QR placeholder -->
            <div class="w-full h-full flex flex-col items-center justify-center bg-slate-50 rounded-xl text-slate-400 text-xs">
                <svg class="w-16 h-16 text-slate-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
                <span>Valid QR Pass</span>
            </div>
        @endif
    </div>

    <!-- Code Display -->
    <div class="mt-4">
        <span class="text-xs text-slate-500 font-medium block">INVITATION CODE</span>
        <span class="text-base font-mono font-black tracking-wider text-slate-900 bg-slate-100 px-3 py-1 rounded-lg inline-block mt-0.5 border border-slate-200">
            {{ $code }}
        </span>
    </div>

    @if($table)
        <div class="mt-2 text-xs font-bold text-amber-900 bg-amber-50 px-2 py-1 rounded-md inline-block">
            🪑 {{ $table }}
        </div>
    @endif

    <p class="mt-3 text-[11px] text-slate-500 leading-tight">
        Tafadhali onyesha QR code hii mlangoni wakati wa kuingia.
    </p>
</div>
