@extends('layouts.landing')

@section('title', 'Track Invitation Status & QR Gate Pass | Fuatilia Mwaliko')
@section('description', 'Track your digital event invitation, RSVP status, QR entrance pass, or car hire booking reference.')

@section('content')
<div class="relative bg-[#f3ffe3] min-h-[80vh] py-16 lg:py-24 overflow-hidden">
    <!-- Ambient Glow Background -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-gradient-to-b from-indigo-500/10 via-purple-500/5 to-transparent rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Header -->
        <div class="text-center space-y-3 mb-10">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black font-heading text-slate-900 tracking-tight">
                Fuatilia Mwaliko &amp; Hali ya RSVP
            </h1>
            <p class="text-slate-700 text-sm sm:text-base max-w-xl mx-auto leading-relaxed font-semibold">
                Weka namba yako ya simu au namba ya kadi (Invitation Code) uliyopewa ili kuthibitisha mahudhurio au kupakua QR Pass yako.
            </p>
        </div>

        <!-- Track Card Container -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl shadow-slate-200/60 p-6 sm:p-10 mb-10">
            @if(session('track_error'))
                <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-start space-x-3">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <div class="font-bold">Mwaliko au Booking Haijapatikana</div>
                        <div class="text-xs text-amber-700 mt-0.5">{{ session('track_error') }}</div>
                    </div>
                </div>
            @endif

            <form action="{{ route('track') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label for="track_query" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Namba ya Simu au Namba ya Mwaliko / Control Number
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" id="track_query" name="query" value="{{ request('query') }}" placeholder="Mfano: 0712345678, INV-9482, au CB-2026-001" required autofocus class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 text-sm sm:text-base font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-inner">
                    </div>
                    <p class="text-[11px] text-slate-500">
                        Unaweza kuweka namba yako ya simu (mf. 07XXXXXXXX au 255XXXXXXXXX), namba ya mwaliko (Invitation Code), au booking reference ya gari.
                    </p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-emerald-600 via-emerald-700 to-teal-800 hover:from-emerald-700 hover:to-teal-900 text-white font-bold text-base shadow-xl shadow-emerald-700/25 transition-all transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                        <span>Fuatilia Sasa &bull; Track Status Now</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>

            <!-- Instructions Grid (Supa inspired) -->
            <div class="mt-10 pt-8 border-t border-slate-100 grid grid-cols-1 md:grid-cols-3 gap-6 text-slate-600 text-xs">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center flex-shrink-0">1</div>
                    <div>
                        <strong class="text-slate-900 block font-semibold">Uhakiki wa RSVP</strong>
                        Angalia kama RSVP yako imepokelewa na kiti chako kimethibitishwa.
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 font-bold flex items-center justify-center flex-shrink-0">2</div>
                    <div>
                        <strong class="text-slate-900 block font-semibold">QR Gate Pass</strong>
                        Pakua au onyesha QR pass yako langoni ili kuruhusiwa kuingia ukumbini bila foleni.
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 font-bold flex items-center justify-center flex-shrink-0">3</div>
                    <div>
                        <strong class="text-slate-900 block font-semibold">Booking ya Magari</strong>
                        Fuatilia hali ya safari, dereva aliyepangwa, na mkataba wa msafara wa harusi.
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Help Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Je, huna namba ya mwaliko?</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Wasiliana na muandaaji au ofisi yetu kwa msaada wa haraka.</p>
                </div>
                <a href="{{ route('contact') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700 transition-colors">
                    Wasiliana Nasi
                </a>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Unataka Kuandaa Sherehe?</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Tengeneza kadi yako na mfumo wa WhatsApp bure.</p>
                </div>
                <a href="{{ route('host.register') }}" class="px-3.5 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-xs font-bold text-indigo-700 transition-colors">
                    Jisajili Bure
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
