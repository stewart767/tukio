@extends('layouts.host')

@section('title', 'WhatsApp Cloud API Hub')
@section('page-title', 'WhatsApp Integration Hub')

@section('content')
<div class="space-y-8 animate-fade-in" x-data="{ activeTab: 'connection' }">

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <!-- Header Banner -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xl shadow-slate-200/30 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1.5">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full {{ $host->hasConnectedWhatsApp() ? 'bg-emerald-50 border border-emerald-200 text-emerald-700' : 'bg-amber-50 border border-amber-200 text-amber-700' }} text-xs font-semibold">
                <span class="w-2 h-2 rounded-full {{ $host->hasConnectedWhatsApp() ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500' }}"></span>
                <span>{{ $host->hasConnectedWhatsApp() ? 'WhatsApp Cloud API Imeshikamana' : 'Inasubiri Muunganisho wa WhatsApp' }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black font-heading text-slate-900 tracking-tight">WhatsApp Business Hub</h1>
            <p class="text-xs sm:text-sm text-slate-500 max-w-2xl">
                Tuma mialiko ya kidijitali, tiketi za QR, vikumbusho vya siku ya sherehe, na upokee uthibitisho wa RSVP moja kwa moja kupitia nambari yako ya WhatsApp Business (WABA).
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 shrink-0">
            @if($host->hasConnectedWhatsApp())
                <form action="{{ route('host.whatsapp.sync-templates') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="modern-button modern-button-secondary text-xs sm:text-sm">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Landanisha Violezo</span>
                    </button>
                </form>
                <button type="button" @click="activeTab = 'broadcast'" class="modern-button modern-button-primary text-xs sm:text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <span>Tuma Mialiko / Vikumbusho</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Quick Metrics Bar -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Jumla Iliyotumwa</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900">{{ number_format($stats['total'] ?? 0) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Ujumbe wote uliotumwa</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Zilizowasili</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-emerald-600">{{ number_format($stats['delivered'] ?? 0) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Imefika kwenye simu za wageni</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Zilizosomwa</span>
                <div class="w-8 h-8 rounded-lg bg-sky-50 flex items-center justify-center text-sky-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-sky-600">{{ number_format($stats['read'] ?? 0) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Mgeni amefungua ujumbe</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Zilizoshindwa</span>
                <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-rose-600">{{ number_format($stats['failed'] ?? 0) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Nambari zisizo sahihi / block</div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-slate-200">
        <nav class="flex space-x-8 -mb-px overflow-x-auto">
            <button type="button" @click="activeTab = 'connection'" :class="activeTab === 'connection' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>Muunganisho (WABA)</span>
            </button>

            <button type="button" @click="activeTab = 'templates'" :class="activeTab === 'templates' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Violezo vya Ujumbe</span>
                @if($templates->count() > 0)
                    <span class="ml-1.5 px-2 py-0.5 text-[10px] font-bold rounded-full bg-slate-100 text-slate-600">{{ $templates->count() }}</span>
                @endif
            </button>

            <button type="button" @click="activeTab = 'broadcast'" :class="activeTab === 'broadcast' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                <span>Tuma Mialiko & Vikumbusho</span>
            </button>

            <button type="button" @click="activeTab = 'logs'" :class="activeTab === 'logs' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                <span>Kumbukumbu za Ujumbe (Outbox)</span>
                @if($messages->total() > 0)
                    <span class="ml-1.5 px-2 py-0.5 text-[10px] font-bold rounded-full bg-slate-100 text-slate-600">{{ $messages->total() }}</span>
                @endif
            </button>

            <button type="button" @click="activeTab = 'privacy'" :class="activeTab === 'privacy' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Ulinzi & Faragha (Opt-Outs)</span>
            </button>
        </nav>
    </div>

    <!-- TAB 1: CONNECTION (WABA ONBOARDING & SETTINGS) -->
    <div x-show="activeTab === 'connection'" class="space-y-6">
        @if(!$host->hasConnectedWhatsApp())
            <!-- Onboarding Card (Disconnected) -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-xl shadow-slate-200/30">
                <div class="max-w-2xl">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.23-.382a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-black text-slate-900 mb-2">Unganisha WhatsApp Business ya Tukio Lako</h2>
                    <p class="text-sm text-slate-600 mb-6 leading-relaxed">
                        Kupitia Meta Embedded Signup, unganisha nambari yako ya simu ya WhatsApp Business kwa kubonyeza kitufe hapa chini. Mfumo utakusaidia kusajili nambari, kuthibitisha SMS OTP ya Meta, na kuanza kutuma mialiko na tiketi rasmi kwa jina na nembo ya tukio lako.
                    </p>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 mb-6 text-xs text-slate-600 space-y-2">
                        <div class="font-semibold text-slate-900 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Mahitaji ya Kujiunga:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1 pl-1">
                            <li>Akaunti ya Meta (Facebook) inayomiliki biashara au tukio.</li>
                            <li>Nambari ya simu inayoweza kupokea SMS/simu ya uthibitisho (ambayo haitumiki sasa kwenye programu binafsi ya WhatsApp).</li>
                            <li>Jina rasmi la tukio au kampuni kwa ajili ya kuonekana kwa wageni (Verified Name).</li>
                        </ul>
                    </div>

                    <!-- Connect with Facebook Button -->
                    <button type="button" id="meta-signup-btn" onclick="launchMetaSignup()" class="inline-flex items-center gap-3 px-6 py-3.5 rounded-2xl bg-[#1877F2] hover:bg-[#166fe5] text-white font-bold text-sm shadow-lg shadow-[#1877F2]/20 transition-all hover:scale-[1.01] active:scale-[0.99]">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <span>Unganisha na Meta WhatsApp Business</span>
                    </button>

                    <div id="meta-signup-status" class="mt-4 text-xs font-semibold text-slate-500 hidden"></div>
                </div>
            </div>
        @else
            <!-- Connected Account Card -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xl shadow-slate-200/30 space-y-6">
                    <div class="flex items-center justify-between pb-6 border-b border-slate-100">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.23-.382a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900">{{ $phoneNumber->verified_name ?? $account->name ?? 'WhatsApp Business' }}</h3>
                                <p class="text-xs text-slate-500 font-mono">{{ $phoneNumber->display_phone_number ?? 'Nambari Imesajiliwa' }}</p>
                            </div>
                        </div>

                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-700">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Kikamilifu</span>
                        </span>
                    </div>

                    <!-- Connection Attributes -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">WABA ID (Meta)</div>
                            <div class="text-sm font-mono font-bold text-slate-800">{{ $account->waba_id }}</div>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Phone Number ID</div>
                            <div class="text-sm font-mono font-bold text-slate-800">{{ $phoneNumber->phone_number_id ?? 'N/A' }}</div>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Kiwango cha Ubora (Quality)</div>
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ ($phoneNumber->quality_rating ?? 'GREEN') === 'GREEN' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                <span class="text-sm font-bold text-slate-800">{{ $phoneNumber->quality_rating ?? 'GREEN (Bora)' }}</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Kiwango cha Kutuma (Messaging Tier)</div>
                            <div class="text-sm font-bold text-slate-800">{{ $phoneNumber->messaging_limit_tier ?? 'TIER_1K (Ujumbe 1,000 / siku)' }}</div>
                        </div>
                    </div>

                    <!-- Disconnect action -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <p class="text-xs text-slate-400">Je, ungependa kubadilisha nambari au kutenganisha akaunti hii?</p>
                        <form action="{{ route('host.whatsapp.disconnect') }}" method="POST" onsubmit="return confirm('Una uhakika unataka kutenganisha WhatsApp Business Account? Ujumbe hautatumika tena hadi uunganishe upya.')">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800 hover:underline">
                                Tenganisha WhatsApp
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Live Test Send Widget -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-xl shadow-slate-200/30 space-y-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-base font-black text-slate-900">Ujumbe wa Jaribio</h3>
                    </div>
                    <p class="text-xs text-slate-500">Tuma ujumbe wa majaribio kwenye nambari yako ya simu kuthibitisha muunganisho wa Meta.</p>

                    <form id="test-send-form" onsubmit="handleTestSend(event)" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nambari ya Simu</label>
                            <input type="text" name="phone_number" id="test_phone_number" value="{{ $host->phone_number ?? '' }}" placeholder="Mfano: 0712345678" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Ujumbe (Hiari)</label>
                            <textarea name="message" id="test_message" rows="3" placeholder="Ujumbe wa jaribio..." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">Habari kutoka Tukio360! Muunganisho wako wa WhatsApp Cloud API unafanya kazi kikamilifu.</textarea>
                        </div>
                        <button type="submit" id="test-send-submit" class="w-full modern-button modern-button-primary justify-center text-xs py-2.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            <span>Tuma Ujumbe wa Majaribio</span>
                        </button>
                    </form>
                    <div id="test-send-alert" class="hidden text-xs p-3 rounded-xl"></div>
                </div>
            </div>
        @endif
    </div>

    <!-- TAB 2: META MESSAGE TEMPLATES -->
    <div x-show="activeTab === 'templates'" class="space-y-6">
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xl shadow-slate-200/30">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div>
                    <h2 class="text-xl font-black text-slate-900">Violezo vya Ujumbe Vilivyoidhinishwa</h2>
                    <p class="text-xs text-slate-500 mt-1">Sera za Meta zinalazimu ujumbe unaotumwa nje ya masaa 24 kutumia kiolezo kilichoidhinishwa (Utility / Marketing).</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <form action="{{ route('host.whatsapp.register-templates') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="type" value="invitation">
                        <button type="submit" class="modern-button modern-button-secondary text-xs">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Tengeneza Kiolezo cha Mwaliko</span>
                        </button>
                    </form>
                    <form action="{{ route('host.whatsapp.sync-templates') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="modern-button modern-button-primary text-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Landanisha kutoka Meta</span>
                        </button>
                    </form>
                </div>
            </div>

            @if($templates->isEmpty())
                <div class="text-center py-12">
                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Hakuna violezo vilivyopatikana</p>
                    <p class="text-xs text-slate-400 max-w-md mx-auto mt-1">Bofya "Landanisha kutoka Meta" au "Tengeneza Kiolezo cha Mwaliko" kuunda violezo rasmi vya Tukio360 kwenye akaunti yako.</p>
                </div>
            @else
                <div class="overflow-x-auto mt-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="py-3 px-4">Jina la Kiolezo</th>
                                <th class="py-3 px-4">Kategoria</th>
                                <th class="py-3 px-4">Lugha</th>
                                <th class="py-3 px-4">Hali (Status)</th>
                                <th class="py-3 px-4">Muhtasari wa Maudhui</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @foreach($templates as $tmpl)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900">{{ $tmpl->template_name }}</td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold {{ $tmpl->category === 'UTILITY' ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                            {{ $tmpl->category }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 uppercase font-semibold text-slate-600">{{ $tmpl->language }}</td>
                                    <td class="py-3.5 px-4">
                                        @if($tmpl->status === 'APPROVED')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                APPROVED
                                            </span>
                                        @elseif($tmpl->status === 'PENDING')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                IN REVIEW
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                {{ $tmpl->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600 max-w-md truncate">
                                        @php
                                            $bodyComp = collect($tmpl->components)->firstWhere('type', 'BODY');
                                        @endphp
                                        {{ $bodyComp['text'] ?? '...' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- TAB 3: BROADCAST & INVITATIONS -->
    <div x-show="activeTab === 'broadcast'" class="space-y-6">
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xl shadow-slate-200/30">
            <h2 class="text-xl font-black text-slate-900 mb-1">Tuma Mialiko ya Kidijitali &amp; Vikumbusho</h2>
            <p class="text-xs text-slate-500 mb-6">Chagua aina ya ujumbe unaotaka kuwatumia wageni wako wa tukio kupitia nambari yako ya WhatsApp Business.</p>

            <form action="{{ route('host.whatsapp.broadcast') }}" method="POST" onsubmit="return confirm('Una uhakika unataka kuanza kutuma ujumbe huu kwa wageni waliochaguliwa?')">
                @csrf
                <div class="space-y-6 max-w-3xl">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Aina ya Ujumbe / Matangazo</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="relative flex flex-col p-4 rounded-2xl border-2 border-slate-200 cursor-pointer hover:border-emerald-500 transition-all has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40">
                                <input type="radio" name="broadcast_type" value="invitation" checked class="sr-only">
                                <div class="font-bold text-sm text-slate-900">Mwaliko Rasmi (Card)</div>
                                <div class="text-[11px] text-slate-500 mt-1">Ujumbe wa kadi ya mwaliko, kiungo cha tovuti na kitufe cha RSVP.</div>
                            </label>

                            <label class="relative flex flex-col p-4 rounded-2xl border-2 border-slate-200 cursor-pointer hover:border-emerald-500 transition-all has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40">
                                <input type="radio" name="broadcast_type" value="reminder_3day" class="sr-only">
                                <div class="font-bold text-sm text-slate-900">Kikumbusho (Siku 3 Kabla)</div>
                                <div class="text-[11px] text-slate-500 mt-1">Kukumbusha wageni kuwa zimesalia siku 3 kufika ukumbini.</div>
                            </label>

                            <label class="relative flex flex-col p-4 rounded-2xl border-2 border-slate-200 cursor-pointer hover:border-emerald-500 transition-all has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40">
                                <input type="radio" name="broadcast_type" value="reminder_1day" class="sr-only">
                                <div class="font-bold text-sm text-slate-900">Kikumbusho (Siku 1 Kabla)</div>
                                <div class="text-[11px] text-slate-500 mt-1">Kikumbusho cha mwisho na kuonyesha tiketi ya QR kwa ajili ya check-in.</div>
                            </label>
                        </div>
                    </div>

                    <div x-data="{ recipientScope: 'all' }">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Walengwa (Wapokeaji)</label>
                        <div class="flex gap-4 mb-4">
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                                <input type="radio" name="recipient_scope" value="all" x-model="recipientScope" class="text-emerald-600 focus:ring-emerald-500">
                                <span>Wageni Wote Wenye Nambari za Simu ({{ $guests->whereNotNull('phone_number')->count() }})</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                                <input type="radio" name="recipient_scope" value="selected" x-model="recipientScope" class="text-emerald-600 focus:ring-emerald-500">
                                <span>Chagua Wageni Maalum</span>
                            </label>
                        </div>

                        <div x-show="recipientScope === 'selected'" class="max-h-60 overflow-y-auto border border-slate-200 rounded-2xl p-3 divide-y divide-slate-100 bg-slate-50/50">
                            @foreach($guests as $g)
                                <label class="flex items-center justify-between py-2 px-2 hover:bg-white rounded-lg cursor-pointer transition-colors text-xs">
                                    <div class="flex items-center gap-2.5">
                                        <input type="checkbox" name="guest_ids[]" value="{{ $g->id }}" class="rounded text-emerald-600 focus:ring-emerald-500">
                                        <span class="font-bold text-slate-800">{{ $g->name }}</span>
                                    </div>
                                    <span class="font-mono text-slate-500">{{ $g->phone_number ?? 'Bila nambari' }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="modern-button modern-button-primary px-8 py-3.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span>Anzisha Matangazo ya WhatsApp Sasa</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 4: MESSAGE LOGS (OUTBOX) -->
    <div x-show="activeTab === 'logs'" class="space-y-6">
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xl shadow-slate-200/30">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div>
                    <h2 class="text-xl font-black text-slate-900">Kumbukumbu za Ujumbe (Outbox)</h2>
                    <p class="text-xs text-slate-500 mt-1">Ufuatiliaji wa papo hapo wa hali ya kila ujumbe uliotumwa kupitia nambari yako ya WhatsApp.</p>
                </div>

                <!-- Filters -->
                <form method="GET" class="flex flex-wrap items-center gap-2 text-xs">
                    <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs text-slate-700 focus:ring-2 focus:ring-emerald-500">
                        <option value="">Hadhi Zote</option>
                        <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent (Zimetumwa)</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered (Zilizowasili)</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read (Zilizosomwa)</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed (Zilizoshindwa)</option>
                    </select>

                    <select name="type" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs text-slate-700 focus:ring-2 focus:ring-emerald-500">
                        <option value="">Aina Zote</option>
                        <option value="invitation" {{ request('type') === 'invitation' ? 'selected' : '' }}>Mialiko</option>
                        <option value="reminder" {{ request('type') === 'reminder' ? 'selected' : '' }}>Vikumbusho</option>
                        <option value="rsvp_confirmation" {{ request('type') === 'rsvp_confirmation' ? 'selected' : '' }}>RSVP Passes</option>
                        <option value="text" {{ request('type') === 'text' ? 'selected' : '' }}>Ujumbe wa Moja kwa Moja</option>
                    </select>
                </form>
            </div>

            @if($messages->isEmpty())
                <div class="text-center py-12">
                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Hakuna kumbukumbu za ujumbe bado</p>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">Ujumbe utakaotumwa kwa wageni au majaribio yataonekana hapa pamoja na risiti za kusomwa kutoka Meta.</p>
                </div>
            @else
                <div class="overflow-x-auto mt-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="py-3 px-4">Mpokeaji</th>
                                <th class="py-3 px-4">Aina</th>
                                <th class="py-3 px-4">Hadhi</th>
                                <th class="py-3 px-4">Muda Uliotumwa</th>
                                <th class="py-3 px-4">Muda wa Kufika / Kusomwa</th>
                                <th class="py-3 px-4 text-right">Hatua</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @foreach($messages as $msg)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-900">{{ $msg->guest->name ?? 'Mgeni' }}</div>
                                        <div class="text-[11px] font-mono text-slate-500">{{ $msg->recipient_phone }}</div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 uppercase">
                                            {{ $msg->type ?? 'text' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @if($msg->status === 'read')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                                <svg class="w-3 h-3 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Zimesomwa (Read)
                                            </span>
                                        @elseif($msg->status === 'delivered')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Zilipokelewa (Delivered)
                                            </span>
                                        @elseif($msg->status === 'sent')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                                Imetumwa (Sent)
                                            </span>
                                        @elseif($msg->status === 'failed')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200" title="{{ $msg->error_message }}">
                                                Imeshindwa (Failed)
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                                                {{ $msg->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-500 font-mono text-[11px]">
                                        {{ $msg->sent_at ? $msg->sent_at->format('d/m/Y H:i') : ($msg->created_at ? $msg->created_at->format('d/m/Y H:i') : '-') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-500 font-mono text-[11px]">
                                        @if($msg->read_at)
                                            <span class="text-sky-700 font-semibold">Soma: {{ $msg->read_at->format('d/m H:i') }}</span>
                                        @elseif($msg->delivered_at)
                                            <span class="text-emerald-700">Fika: {{ $msg->delivered_at->format('d/m H:i') }}</span>
                                        @elseif($msg->error_message)
                                            <span class="text-rose-600 truncate max-w-xs block" title="{{ $msg->error_message }}">{{ $msg->error_message }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        @if($msg->status === 'failed')
                                            <form action="{{ route('host.whatsapp.retry', $msg->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-[11px] font-bold transition-all">
                                                    Jaribu Tena
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $messages->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- TAB 5: PRIVACY & CONSENT (OPT-OUTS) -->
    <div x-show="activeTab === 'privacy'" class="space-y-6">
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xl shadow-slate-200/30">
            <h2 class="text-xl font-black text-slate-900 mb-1">Ulinzi wa Faragha &amp; Sera za WhatsApp</h2>
            <p class="text-xs text-slate-500 mb-6">Kwa mujibu wa miongozo ya Meta Business Messaging, wageni wana hiari ya kuzuia ujumbe wa matangazo wakati wowote kwa kujibu STOP, ONDOA, au SITAKI.</p>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3 px-4">Nambari ya Simu</th>
                            <th class="py-3 px-4">Hali ya Ruhusa</th>
                            <th class="py-3 px-4">Chanzo cha Idhini</th>
                            <th class="py-3 px-4">Tarehe ya Kujiondoa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($consents as $c)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-900">{{ $c->phone_number }}</td>
                                <td class="py-3.5 px-4">
                                    @if($c->has_opted_out)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            Amejiondoa (Opted Out)
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Anaruhusiwa (Active)
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">{{ $c->opt_in_source ?? 'rsvp_submission' }}</td>
                                <td class="py-3.5 px-4 text-slate-500 font-mono text-[11px]">
                                    {{ $c->opted_out_at ? $c->opted_out_at->format('d/m/Y H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-xs text-slate-400">Hakuna mgeni aliyejiondoa kwenye orodha yako ya mawasiliano.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Meta Embedded Signup JavaScript SDK -->
<script>
    window.fbAsyncInit = function() {
        @if(!empty($metaConfig['app_id']))
            FB.init({
                appId: '{{ $metaConfig['app_id'] }}',
                autoLogAppEvents: true,
                xfbml: true,
                version: '{{ $metaConfig['graph_version'] ?? 'v22.0' }}'
            });
        @endif
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    // Trigger Meta Embedded Signup
    function launchMetaSignup() {
        var statusEl = document.getElementById('meta-signup-status');
        if (statusEl) {
            statusEl.classList.remove('hidden');
            statusEl.innerText = "Inafungua dirisha la Meta WhatsApp Signup...";
        }

        if (typeof FB === 'undefined') {
            alert('Meta SDK haijapakuliwa bado au haipo mtandaoni. Tafadhali hakikisha umeunganishwa na intaneti.');
            return;
        }

        FB.login(function(response) {
            if (response.authResponse && response.authResponse.code) {
                var code = response.authResponse.code;
                if (statusEl) {
                    statusEl.innerText = "Meta authentication imefaulu! Inaunganisha nambari kwenye mfumo...";
                }

                // Send authorization code to Host WhatsApp controller
                fetch('{{ route('host.whatsapp.connect') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        code: code
                    })
                })
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.success) {
                        alert(data.message || 'WhatsApp Business imeunganishwa kikamilifu!');
                        window.location.reload();
                    } else {
                        alert(data.message || 'Hitilafu wakati wa kuunganisha WABA.');
                        if (statusEl) { statusEl.innerText = data.message; }
                    }
                })
                .catch(function(err) {
                    console.error('Meta connection error:', err);
                    alert('Hitilafu ya mtandao wakati wa kutuma ombi kwa seva.');
                });

            } else {
                console.log('User cancelled login or did not fully authorize.');
                if (statusEl) {
                    statusEl.innerText = "Mchakato uliahirishwa na mtumiaji.";
                }
            }
        }, {
            config_id: '{{ $metaConfig['config_id'] ?? '' }}',
            response_type: 'code',
            override_default_response_type: true,
            extras: {
                setup: {}
            }
        });
    }

    // Handle AJAX test message send
    function handleTestSend(e) {
        e.preventDefault();
        var submitBtn = document.getElementById('test-send-submit');
        var alertEl = document.getElementById('test-send-alert');
        var phone = document.getElementById('test_phone_number').value;
        var message = document.getElementById('test_message').value;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span>Inatuma ujumbe...</span>';
        alertEl.classList.add('hidden');

        fetch('{{ route('host.whatsapp.test-send') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                phone_number: phone,
                message: message
            })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>Tuma Ujumbe wa Majaribio</span>';
            alertEl.classList.remove('hidden');

            if (data.success) {
                alertEl.className = 'text-xs p-3 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200';
                alertEl.innerText = data.message;
            } else {
                alertEl.className = 'text-xs p-3 rounded-xl bg-rose-50 text-rose-800 border border-rose-200';
                alertEl.innerText = data.message || 'Hitilafu wakati wa kutuma ujumbe.';
            }
        })
        .catch(function(err) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>Tuma Ujumbe wa Majaribio</span>';
            alertEl.classList.remove('hidden');
            alertEl.className = 'text-xs p-3 rounded-xl bg-rose-50 text-rose-800 border border-rose-200';
            alertEl.innerText = 'Hitilafu ya mtandao wakati wa kutuma ujumbe.';
        });
    }
</script>
@endsection
