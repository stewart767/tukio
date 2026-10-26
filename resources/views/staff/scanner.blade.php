@extends('layouts.dashboard')

@section('title', 'Smart Gate Scanner & PIN Reception | Tukio 360')

@push('styles')
<style>
    #reader {
        border-radius: 16px;
        overflow: hidden;
        background: #000;
    }
    #reader video {
        width: 100% !important;
        height: auto !important;
        border-radius: 16px;
        object-fit: cover;
    }
    .numpad-btn {
        touch-action: manipulation;
        user-select: none;
        transition: all 0.1s ease;
    }
    .numpad-btn:active {
        transform: scale(0.92);
        background-color: #d1fae5;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-5 pb-16">

    <!-- 1. TOP HEADER: Event Selector & Live Counter (Clean Light Style) -->
    <div class="bg-white rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200/80">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex-1">
                <label for="event_selector" class="block text-xs font-bold uppercase tracking-wider text-emerald-800 mb-1 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Tukio Linalohudumiwa Getini (Active Event):</span>
                </label>
                @if(isset($assignedEvents) && $assignedEvents->count() > 0)
                    <select id="event_selector" class="w-full bg-slate-50 border-2 border-slate-300 hover:border-emerald-500 text-slate-900 font-bold rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-emerald-400 focus:outline-none transition-all">
                        @foreach($assignedEvents as $ev)
                            <option value="{{ $ev->id }}" {{ (isset($selectedEvent) && $selectedEvent->id === $ev->id) ? 'selected' : '' }}>
                                {{ $ev->title }} — {{ $ev->host?->full_name ?? 'Host' }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <div class="text-sm text-amber-700 font-medium">Hakuna tukio lililopangiwa kwa sasa.</div>
                @endif
            </div>

            <!-- Device Wake Lock -->
            <div class="flex items-center gap-2 self-end sm:self-center">
                <button id="wakelock_btn" class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-slate-100 border border-slate-300 text-slate-700 hover:bg-slate-200 shadow-xs transition">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span id="wakelock_label">Kioo Kipo Macho</span>
                </button>
            </div>
        </div>

        <!-- Live Gate Metrics -->
        <div class="grid grid-cols-3 gap-2.5 mt-4 pt-3 border-t border-slate-100 text-center">
            <div class="bg-emerald-50/70 rounded-2xl p-2.5 border border-emerald-100">
                <div class="text-[11px] text-emerald-800 font-bold uppercase tracking-wider">Walioingia</div>
                <div id="stat_attended" class="text-xl sm:text-2xl font-black text-emerald-700">{{ $stats['attended_guests'] ?? 0 }}</div>
            </div>
            <div class="bg-slate-50 rounded-2xl p-2.5 border border-slate-200">
                <div class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">Jumla ya Kadi</div>
                <div id="stat_total" class="text-xl sm:text-2xl font-black text-slate-900">{{ $stats['total_guests'] ?? 0 }}</div>
            </div>
            <div class="bg-indigo-50/70 rounded-2xl p-2.5 border border-indigo-100">
                <div class="text-[11px] text-indigo-800 font-bold uppercase tracking-wider">Mahudhurio</div>
                <div id="stat_percent" class="text-xl sm:text-2xl font-black text-indigo-700">{{ $stats['percent'] ?? 0 }}%</div>
            </div>
        </div>
    </div>

    <!-- 2. PROMINENT UNIFIED PIN & CODE ENTRY BAR (ALWAYS VISIBLE & IMMEDIATE) -->
    <div class="bg-white rounded-3xl shadow-lg border-2 border-emerald-500/80 p-4 sm:p-6 transition-all">
        <div class="flex items-center justify-between mb-2">
            <label for="unified_input" class="text-xs sm:text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                <span>🔢 PA KUWEKA PINI / NAMBA YA SIMU / KODI YA MWALIKO</span>
            </label>
            <span class="text-[11px] text-emerald-800 font-bold bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                Kitochi &bull; SMS &bull; WhatsApp
            </span>
        </div>

        <!-- Massive High-Contrast Input -->
        <div class="flex flex-col sm:flex-row gap-2.5">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <input type="text" id="unified_input" autofocus
                       placeholder="Andika PIN hapa (Mfano: 4821 au simu/jina)..."
                       class="w-full pl-12 pr-12 py-3.5 text-lg sm:text-2xl font-black bg-slate-50 border-2 border-slate-300 rounded-2xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 focus:outline-none tracking-wide transition-all">
                <button type="button" id="clear_input_btn" title="Futa"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-rose-600 text-2xl font-bold">
                    &times;
                </button>
            </div>

            <!-- Big Check In Button -->
            <button type="button" id="unified_submit_btn"
                    class="px-7 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-black text-base shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 transition-all">
                <span>THIBITISHA</span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </div>

        <div class="mt-2.5 flex items-center justify-between text-xs text-slate-500">
            <span class="flex items-center gap-1.5">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Andika <b>taratibu 4 za PIN</b> (mfano <b>4821</b>) — mfumo unathibitisha papo hapo.</span>
            </span>
            <span class="hidden sm:inline font-mono text-[11px] text-slate-600 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded">Au bonyeza Enter ↵</span>
        </div>
    </div>

    <!-- 3. REAL-TIME SCAN RESULT CARD (GREEN / RED / WARNING) -->
    <div id="scan_result_modal" class="hidden">
        <div id="result_card" class="rounded-3xl p-5 sm:p-6 border-2 shadow-2xl transition-all">
            <!-- Dynamic Content Injected Here -->
        </div>
    </div>

    <!-- 4. DUAL WORKSTATION: TOUCH NUMPAD & LIVE CAMERA VIEWER (SIDE BY SIDE ON DESKTOP, STACKED ON MOBILE) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <!-- Column A: Touch Numpad (Direct Gate Entry on Screen) -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                        🔢
                    </span>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Keypad ya Namba (Touch Numpad)</h4>
                        <p class="text-[11px] text-slate-500">Gusa namba kuingiza PIN ya kitochi kwa mkono mmoja</p>
                    </div>
                </div>
            </div>

            <!-- 0-9 Numpad Grid -->
            <div class="grid grid-cols-3 gap-2.5 max-w-xs mx-auto w-full my-auto">
                @for ($i = 1; $i <= 9; $i++)
                    <button type="button" data-digit="{{ $i }}" class="numpad-btn h-14 bg-slate-50 border border-slate-200 hover:bg-emerald-50 hover:border-emerald-400 text-slate-900 text-2xl font-bold rounded-2xl shadow-xs flex items-center justify-center">
                        {{ $i }}
                    </button>
                @endfor
                <button type="button" id="numpad_clear" class="numpad-btn h-14 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold rounded-2xl shadow-xs flex items-center justify-center">
                    FUTA
                </button>
                <button type="button" data-digit="0" class="numpad-btn h-14 bg-slate-50 border border-slate-200 hover:bg-emerald-50 hover:border-emerald-400 text-slate-900 text-2xl font-bold rounded-2xl shadow-xs flex items-center justify-center">
                    0
                </button>
                <button type="button" id="numpad_submit" class="numpad-btn h-14 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-2xl shadow-md flex items-center justify-center uppercase tracking-wider">
                    INGIZA
                </button>
            </div>
        </div>

        <!-- Column B: Camera QR Code Scanner -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                        📷
                    </span>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Kamera ya QR (Scanner)</h4>
                        <p class="text-[11px] text-slate-500">Skani kadi ya mwaliko kutoka WhatsApp au kadi iliyochapishwa</p>
                    </div>
                </div>
            </div>

            <!-- Viewport -->
            <div class="relative w-full max-w-xs mx-auto mb-2">
                <div id="reader" style="display: none;" class="w-full aspect-square rounded-2xl shadow-inner"></div>
                
                <div id="scanner_placeholder" class="w-full aspect-square bg-slate-900 rounded-2xl flex flex-col items-center justify-center p-5 text-center text-white shadow-inner">
                    <div class="w-14 h-14 rounded-full bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                    <p class="text-xs text-slate-300 mb-3">Kamera imezimwa. Bofya kitufe hapa chini kuskani QR codes getini:</p>
                    <button type="button" id="start_camera_btn" class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Washa Kamera (Start Camera)
                    </button>
                </div>

                <!-- Active Camera Controls -->
                <div id="camera_active_controls" class="hidden flex items-center justify-between gap-2 mt-2">
                    <button type="button" id="torch_btn" class="flex-1 py-2 px-3 rounded-xl bg-slate-100 hover:bg-amber-100 text-slate-800 font-bold text-xs border border-gray-300 flex items-center justify-center gap-1.5 transition">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        <span id="torch_label">Tochi</span>
                    </button>
                    <button type="button" id="stop_camera_btn" class="py-2 px-4 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 font-bold text-xs border border-rose-200 transition">
                        Zima Kamera
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- 5. 1-TAP QUICK GUEST CHIPS (CLICK TO VERIFY INSTANTLY) -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-5 space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <h4 class="font-bold text-slate-900 text-sm">Orodha ya Wageni wa Tukio Hili (Bofya Kuthibitisha Papo Hapo):</h4>
            </div>
            <!-- Fast search filter -->
            <input type="text" id="guest_filter_input" placeholder="Tafuta mgeni kwa jina au PIN..."
                   class="w-full sm:w-64 bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-1 focus:ring-emerald-500">
        </div>

        <div id="guest_chips_container" class="flex flex-wrap gap-2 max-h-48 overflow-y-auto pt-1">
            @if(isset($eventGuests) && $eventGuests->count() > 0)
                @foreach($eventGuests as $g)
                    <button type="button" onclick="submitCode('{{ $g->pin_code ?: $g->invitation_code }}')"
                            data-guest-name="{{ strtolower($g->full_name) }}"
                            data-guest-pin="{{ $g->pin_code }}"
                            class="guest-chip inline-flex items-center gap-2 px-3 py-2 rounded-xl border text-xs font-semibold shadow-xs transition-all {{ $g->attended ? 'bg-emerald-50 border-emerald-200 text-emerald-900 opacity-75' : 'bg-slate-50 border-slate-200 text-slate-800 hover:bg-emerald-50 hover:border-emerald-300' }}">
                        <span>{{ $g->full_name }}</span>
                        @if($g->pin_code)
                            <span class="font-mono text-[11px] font-bold px-1.5 py-0.5 rounded {{ $g->attended ? 'bg-emerald-200 text-emerald-950' : 'bg-slate-200 text-slate-900' }}">
                                PIN: {{ $g->pin_code }}
                            </span>
                        @endif
                        @if($g->attended)
                            <span class="text-emerald-700 font-bold">✓ Kishaingia</span>
                        @endif
                    </button>
                @endforeach
            @else
                <!-- Fallback defaults for testing -->
                <button type="button" onclick="submitCode('4821')" class="guest-chip inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 hover:bg-emerald-50 text-xs font-semibold text-slate-800">
                    <span>Hon. Juma Mwinyi</span>
                    <span class="font-mono text-[11px] font-bold bg-slate-200 text-slate-900 px-1.5 py-0.5 rounded">PIN: 4821</span>
                </button>
                <button type="button" onclick="submitCode('4825')" class="guest-chip inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 hover:bg-emerald-50 text-xs font-semibold text-slate-800">
                    <span>Asha Bakari (Kitochi)</span>
                    <span class="font-mono text-[11px] font-bold bg-slate-200 text-slate-900 px-1.5 py-0.5 rounded">PIN: 4825</span>
                </button>
                <button type="button" onclick="submitCode('4822')" class="guest-chip inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 hover:bg-emerald-50 text-xs font-semibold text-slate-800">
                    <span>Dr. Grace Massawe</span>
                    <span class="font-mono text-[11px] font-bold bg-slate-200 text-slate-900 px-1.5 py-0.5 rounded">PIN: 4822</span>
                </button>
            @endif
        </div>
    </div>

    <!-- 6. RECENT CHECK-INS STREAM -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span>Walioingia Punde Hivi (Recent Check-ins Stream)</span>
            </h3>
            <span id="recent_count" class="text-xs text-slate-500 font-medium">Muda Halisi</span>
        </div>
        <div id="recent_scans_list" class="divide-y divide-slate-100 max-h-64 overflow-y-auto">
            @if(isset($recentScans) && $recentScans->count() > 0)
                @foreach($recentScans as $rg)
                    <div class="p-3.5 flex items-center justify-between text-xs hover:bg-slate-50 transition">
                        <div class="flex items-center gap-2.5">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                                ✓
                            </div>
                            <div>
                                <div class="font-bold text-slate-900">{{ $rg->full_name }}</div>
                                <div class="text-slate-500">{{ $rg->table_number ?? 'Meza Kuu' }} &bull; ({{ $rg->pax_checked_in ?: 1 }}/{{ $rg->pax ?: 1 }} Watu)</div>
                            </div>
                        </div>
                        <span class="text-slate-400 font-mono text-[11px]">
                            {{ $rg->attended_at ? \Carbon\Carbon::parse($rg->attended_at)->format('H:i') : 'Leo' }}
                        </span>
                    </div>
                @endforeach
            @else
                <div class="p-4 text-center text-xs text-slate-400">Hakuna mgeni aliyeskaniwa bado katika kikao hiki.</div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // 1. Audio Synthesizer (Web Audio API - Crisp chime, Warning buzzer, Error boop)
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    
    function playBeep(type) {
        if (!audioCtx) return;
        if (audioCtx.state === 'suspended') {
            audioCtx.resume();
        }

        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.connect(gain);
        gain.connect(audioCtx.destination);

        const now = audioCtx.currentTime;

        if (type === 'success') {
            // High chime (Ding! 880Hz -> 1320Hz)
            osc.type = 'sine';
            osc.frequency.setValueAtTime(880, now);
            osc.frequency.exponentialRampToValueAtTime(1320, now + 0.15);
            gain.gain.setValueAtTime(0.3, now);
            gain.gain.exponentialRampToValueAtTime(0.01, now + 0.3);
            osc.start(now);
            osc.stop(now + 0.3);
            if (navigator.vibrate) navigator.vibrate(120);
        } else if (type === 'warning') {
            // Warning buzzer (Low frequency saw-tooth)
            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(220, now);
            gain.gain.setValueAtTime(0.4, now);
            gain.gain.exponentialRampToValueAtTime(0.01, now + 0.4);
            osc.start(now);
            osc.stop(now + 0.4);
            if (navigator.vibrate) navigator.vibrate([200, 100, 200]);
        } else {
            // Error sound
            osc.type = 'square';
            osc.frequency.setValueAtTime(150, now);
            gain.gain.setValueAtTime(0.4, now);
            gain.gain.exponentialRampToValueAtTime(0.01, now + 0.35);
            osc.start(now);
            osc.stop(now + 0.35);
            if (navigator.vibrate) navigator.vibrate([300, 100, 300]);
        }
    }

    // 2. Screen Wake Lock (Keep display awake during gate duty)
    let wakeLock = null;
    async function requestWakeLock() {
        if ('wakeLock' in navigator) {
            try {
                wakeLock = await navigator.wakeLock.request('screen');
                document.getElementById('wakelock_label').textContent = 'Kioo Kipo Macho';
            } catch (err) {
                console.log('WakeLock:', err);
            }
        }
    }
    requestWakeLock();
    document.addEventListener('visibilitychange', () => {
        if (wakeLock !== null && document.visibilityState === 'visible') {
            requestWakeLock();
        }
    });

    // 3. Event Selector
    const eventSelector = document.getElementById('event_selector');
    let currentEventId = eventSelector ? eventSelector.value : null;
    if (eventSelector) {
        eventSelector.addEventListener('change', function() {
            currentEventId = this.value;
            window.location.href = "{{ route('staff.scanner') }}?event_id=" + currentEventId;
        });
    }

    // 4. Unified Direct PIN Input & Keyboard Listener
    const unifiedInput = document.getElementById('unified_input');
    const unifiedSubmitBtn = document.getElementById('unified_submit_btn');
    const clearInputBtn = document.getElementById('clear_input_btn');

    clearInputBtn.addEventListener('click', () => {
        unifiedInput.value = '';
        unifiedInput.focus();
    });

    unifiedSubmitBtn.addEventListener('click', () => {
        if (unifiedInput.value.trim().length > 0) {
            submitCode(unifiedInput.value.trim());
        }
    });

    unifiedInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            if (unifiedInput.value.trim().length > 0) {
                submitCode(unifiedInput.value.trim());
            }
        }
    });

    // Auto-trigger when 4 digits are typed
    unifiedInput.addEventListener('input', () => {
        const val = unifiedInput.value.trim();
        if (/^\d{4}$/.test(val)) {
            submitCode(val);
        }
    });

    // 5. On-Screen Touch Numpad
    document.querySelectorAll('.numpad-btn[data-digit]').forEach(btn => {
        btn.addEventListener('click', function() {
            if (unifiedInput.value.length < 12) {
                unifiedInput.value += this.getAttribute('data-digit');
                if (unifiedInput.value.length === 4 && /^\d{4}$/.test(unifiedInput.value)) {
                    submitCode(unifiedInput.value);
                }
            }
        });
    });

    document.getElementById('numpad_clear').addEventListener('click', () => {
        unifiedInput.value = '';
        unifiedInput.focus();
    });

    document.getElementById('numpad_submit').addEventListener('click', () => {
        if (unifiedInput.value.trim().length > 0) {
            submitCode(unifiedInput.value.trim());
        }
    });

    // 6. Fast Search Filter for Guest Chips
    const guestFilterInput = document.getElementById('guest_filter_input');
    if (guestFilterInput) {
        guestFilterInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            document.querySelectorAll('.guest-chip').forEach(chip => {
                const name = chip.getAttribute('data-guest-name') || '';
                const pin = chip.getAttribute('data-guest-pin') || '';
                if (name.includes(query) || pin.includes(query)) {
                    chip.style.display = 'inline-flex';
                } else {
                    chip.style.display = 'none';
                }
            });
        });
    }

    // 7. QR Camera Scanner Logic
    let html5QrcodeScanner = null;
    let isCameraRunning = false;
    let isTorchOn = false;

    const startCameraBtn = document.getElementById('start_camera_btn');
    const stopCameraBtn = document.getElementById('stop_camera_btn');
    const torchBtn = document.getElementById('torch_btn');
    const readerDiv = document.getElementById('reader');
    const placeholderDiv = document.getElementById('scanner_placeholder');
    const cameraControls = document.getElementById('camera_active_controls');

    startCameraBtn.addEventListener('click', startScanner);
    stopCameraBtn.addEventListener('click', stopScanner);

    async function startScanner() {
        if (isCameraRunning) return;
        try {
            html5QrcodeScanner = new Html5Qrcode("reader");
            placeholderDiv.style.display = 'none';
            readerDiv.style.display = 'block';
            cameraControls.classList.remove('hidden');

            const config = {
                fps: 15,
                qrbox: { width: 220, height: 220 },
                aspectRatio: 1.0,
            };

            await html5QrcodeScanner.start(
                { facingMode: "environment" },
                config,
                onQrSuccess,
                () => {} // Frame error ignore
            );

            isCameraRunning = true;
        } catch (err) {
            alert('Haikuweza kuwasha kamera: ' + err);
            placeholderDiv.style.display = 'flex';
            readerDiv.style.display = 'none';
            cameraControls.classList.add('hidden');
        }
    }

    async function stopScanner() {
        if (!isCameraRunning || !html5QrcodeScanner) return;
        try {
            await html5QrcodeScanner.stop();
            html5QrcodeScanner = null;
            isCameraRunning = false;
            placeholderDiv.style.display = 'flex';
            readerDiv.style.display = 'none';
            cameraControls.classList.add('hidden');
        } catch (e) {
            console.log(e);
        }
    }

    // Flashlight toggle
    torchBtn.addEventListener('click', async () => {
        if (!html5QrcodeScanner || !isCameraRunning) return;
        try {
            isTorchOn = !isTorchOn;
            await html5QrcodeScanner.applyVideoConstraints({
                advanced: [{ torch: isTorchOn }]
            });
            document.getElementById('torch_label').textContent = isTorchOn ? 'Zima Tochi' : 'Tochi';
        } catch (e) {
            console.log('Torch error:', e);
        }
    });

    let scanCooldown = false;
    function onQrSuccess(decodedText) {
        if (scanCooldown) return;
        scanCooldown = true;
        submitCode(decodedText);
        setTimeout(() => { scanCooldown = false; }, 2500);
    }

    // 8. Core Check-In Pipeline: Connects PIN, QR, Phone, and Guest Chips
    async function submitCode(codeValue, paxCount = 1) {
        if (!codeValue) return;

        unifiedInput.value = codeValue;

        const modal = document.getElementById('scan_result_modal');
        const card = document.getElementById('result_card');
        modal.classList.remove('hidden');

        card.className = "rounded-3xl p-5 border-2 shadow-xl bg-slate-50 border-slate-300 text-slate-700 animate-pulse";
        card.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="w-6 h-6 border-2 border-emerald-600 border-t-transparent rounded-full animate-spin"></div>
                <div class="text-sm font-bold">Inathibitisha mwaliko wa: <b class="text-emerald-800">${codeValue}</b>...</div>
            </div>
        `;

        try {
            const response = await fetch("{{ route('staff.scan') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    code: codeValue,
                    qr_code: codeValue,
                    event_id: currentEventId,
                    pax_count: paxCount
                })
            });

            const data = await response.json();

            if (data.success) {
                // SUCCESS (GREEN)
                playBeep('success');
                card.className = "rounded-3xl p-6 border-2 border-emerald-500 bg-emerald-50 text-emerald-950 shadow-2xl";
                card.innerHTML = `
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-black text-2xl shadow-lg">
                                ✓
                            </div>
                            <div>
                                <div class="text-xs font-black uppercase tracking-wider text-emerald-700">MGENI AMETHIBITISHWA &bull; KARIBU NDANI</div>
                                <h2 class="text-2xl font-black text-emerald-950">${data.guest.name}</h2>
                            </div>
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-black bg-emerald-200 text-emerald-900 border border-emerald-300">
                            ${data.guest.pax > 1 ? data.guest.pax + ' Pass (VIP)' : 'Single Entry'}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 bg-white/90 rounded-2xl p-3.5 mb-3 border border-emerald-200 text-xs">
                        <div>
                            <span class="text-slate-500 font-semibold uppercase text-[10px]">Namba ya Meza:</span>
                            <div class="text-lg font-black text-slate-900">${data.guest.table_number || 'General Seating'}</div>
                        </div>
                        <div>
                            <span class="text-slate-500 font-semibold uppercase text-[10px]">Hali ya Wageni:</span>
                            <div class="text-lg font-black text-emerald-700">${data.guest.pax_checked_in} / ${data.guest.pax} Watu</div>
                        </div>
                    </div>

                    ${data.guest.pax > 1 && data.guest.remaining_pax > 0 ? `
                        <div class="bg-amber-100 border border-amber-300 rounded-xl p-3 text-xs text-amber-950 mb-3 flex items-center justify-between">
                            <span class="font-bold">Bado nafasi ${data.guest.remaining_pax} kwenye kadi hii ya mgeni.</span>
                            <button onclick="submitCode('${codeValue}', ${data.guest.remaining_pax})" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-extrabold rounded-lg text-xs shadow-sm">
                                Ingiza Waliobaki (+${data.guest.remaining_pax})
                            </button>
                        </div>
                    ` : ''}

                    <div class="flex items-center justify-between text-xs text-emerald-800 pt-1">
                        <span>PIN: <b class="font-mono">${data.guest.pin_code || 'QR'}</b></span>
                        <span class="font-bold">Muda: ${data.guest.attended_at}</span>
                    </div>
                `;

                // Update Metrics Counters immediately
                if (data.stats) {
                    document.getElementById('stat_attended').textContent = data.stats.attended_guests;
                    document.getElementById('stat_total').textContent = data.stats.total_guests;
                    document.getElementById('stat_percent').textContent = data.stats.percent + '%';
                }

                // Add to recent stream
                prependRecentScan(data.guest);

                // Clear input ready for next guest
                setTimeout(() => {
                    unifiedInput.value = '';
                    unifiedInput.focus();
                }, 1200);

            } else if (data.already_checked_in) {
                // WARNING - DUPLICATE SCAN (RED / AMBER)
                playBeep('warning');
                card.className = "rounded-3xl p-6 border-2 border-rose-500 bg-rose-50 text-rose-950 shadow-2xl";
                card.innerHTML = `
                    <div class="flex items-center gap-3 mb-3 text-rose-700">
                        <div class="w-12 h-12 rounded-2xl bg-rose-600 text-white flex items-center justify-center font-black text-2xl shadow-lg">
                            !
                        </div>
                        <div>
                            <div class="text-xs font-black uppercase tracking-wider text-rose-800">ONYO: KADI ILISHATUMIKA!</div>
                            <h2 class="text-xl font-black text-rose-950">${data.guest.name}</h2>
                        </div>
                    </div>
                    <p class="text-xs text-rose-900 mb-3 font-bold bg-white/70 p-2.5 rounded-xl border border-rose-200">
                        Kadi hii ilishathibitishwa na kutumika saa <b>${data.guest.attended_at}</b>. Hakuna nafasi ya ziada iliyobaki langoni!
                    </p>
                    <div class="text-xs flex items-center justify-between bg-white rounded-xl p-3 border border-rose-200">
                        <span>Meza: <b>${data.guest.table_number}</b></span>
                        <span>Walioingia: <b>${data.guest.pax_checked_in} / ${data.guest.pax} Watu</b></span>
                    </div>
                `;
            } else {
                // NOT FOUND
                playBeep('error');
                card.className = "rounded-3xl p-6 border-2 border-slate-400 bg-slate-100 text-slate-800 shadow-xl";
                card.innerHTML = `
                    <div class="flex items-center gap-3 mb-2 text-rose-600">
                        <span class="text-3xl font-black">✕</span>
                        <div>
                            <div class="font-black text-sm text-rose-700">Mwaliko Haujapatikana</div>
                            <div class="text-xs text-slate-600">${data.message}</div>
                        </div>
                    </div>
                    <div class="text-xs text-slate-500 bg-white p-2.5 rounded-xl border border-slate-200 mt-2">
                        Kodi uliyoingiza: <b class="font-mono text-slate-900">${codeValue}</b>
                    </div>
                `;
            }

        } catch (error) {
            playBeep('error');
            card.className = "rounded-3xl p-5 border border-rose-300 bg-rose-50 text-rose-900";
            card.innerHTML = `<div class="text-xs font-bold">Hitilafu ya mtandao: ${error}</div>`;
        }
    }

    window.submitCode = submitCode;

    // 9. Prepend Recent Scan Record
    function prependRecentScan(guest) {
        const list = document.getElementById('recent_scans_list');
        const emptyMsg = list.querySelector('.text-center');
        if (emptyMsg) emptyMsg.remove();

        const item = document.createElement('div');
        item.className = "p-3.5 flex items-center justify-between text-xs hover:bg-slate-50 transition border-b border-slate-100";
        item.innerHTML = `
            <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                    ✓
                </div>
                <div>
                    <div class="font-bold text-slate-900">${guest.name}</div>
                    <div class="text-slate-500">${guest.table_number || 'General'} &bull; (${guest.pax_checked_in}/${guest.pax} Watu)</div>
                </div>
            </div>
            <span class="text-slate-500 font-mono text-[11px] font-bold">${guest.attended_at}</span>
        `;
        list.prepend(item);
    }
});
</script>
@endpush
