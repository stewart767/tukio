@extends('layouts.landing')

@php
    $brandSettings = $systemSettings ?? [];
    $brandName = $brandSettings['name'] ?? 'Tukio 360';
    $socialLinks = $brandSettings['social'] ?? [];
    
    $settingsService = app(\App\Services\SystemSettingsService::class);
    $contactEmail = $settingsService->get('contact.email') ?? $brandSettings['email'] ?? 'info@tukio360.co.tz';
    $contactPhone = $settingsService->get('contact.phone') ?? $brandSettings['phone'] ?? '+255 662 199 071';
    $contactWhatsApp = $settingsService->get('contact.whatsapp') ?? $settingsService->get('system.whatsapp') ?? $settingsService->get('whatsapp.support_phone') ?? $brandSettings['whatsapp'] ?? $contactPhone;
    $cleanWhatsApp = $settingsService->normalizePhone($contactWhatsApp, '255662199071');
    $contactAddress = $settingsService->get('contact.address') ?? $brandSettings['address'] ?? 'Dar es Salaam, Tanzania';
@endphp

@section('title', __('common.contact_us') . ' - ' . $brandName)

@section('content')
<div class="min-h-screen bg-[#f3ffe3] pt-10 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Page Header -->
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <h1 class="text-4xl sm:text-5xl font-black font-heading text-slate-900 tracking-tight">
                {{ __('common.contact_us') }}
            </h1>
            <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                {{ __('common.contact_description') }}
            </p>
        </div>

        <!-- Success Message Alert -->
        @if(session('success'))
            <div class="max-w-4xl mx-auto p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center space-x-3 shadow-md">
                <svg class="w-6 h-6 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="text-sm font-semibold">{{ session('success') }}</div>
            </div>
        @endif

        <div class="grid lg:grid-cols-12 gap-10 lg:gap-12 max-w-6xl mx-auto">
            
            <!-- Left Column: Contact Cards & Info -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Working Hours Info -->
                <div class="glass-card rounded-2xl p-4 flex items-center justify-between border border-slate-200">
                    <span class="text-xs font-bold text-slate-700">Masaa ya Kazi:</span>
                    <span class="text-xs font-semibold text-slate-600">Jumatatu &ndash; Ijumaa &bull; Saa 3:00 Asubuhi &ndash; 12:00 Jioni</span>
                </div>

                <!-- Email Card -->
                <div class="glass-card rounded-3xl p-6 hover:border-indigo-300 transition-all flex items-start space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-0.5">{{ __('common.email_us') }}</h4>
                        <a href="mailto:{{ $contactEmail }}" class="text-base font-bold text-slate-900 hover:text-indigo-600 transition-colors">
                            {{ $contactEmail }}
                        </a>
                        <p class="text-xs text-slate-500 mt-1">{{ __('common.we_respond_within') }}</p>
                    </div>
                </div>

                <!-- Phone Card -->
                <div class="glass-card rounded-3xl p-6 hover:border-indigo-300 transition-all flex items-start space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-0.5">{{ __('common.call_us') }}</h4>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone) }}" class="text-base font-bold text-slate-900 hover:text-indigo-600 transition-colors">
                            {{ $contactPhone }}
                        </a>
                        <p class="text-xs text-slate-500 mt-1">{{ __('common.mon_fri_hours') }}</p>
                    </div>
                </div>

                <!-- Office Location Card -->
                <div class="glass-card rounded-3xl p-6 hover:border-emerald-300 transition-all flex items-start space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-0.5">{{ __('common.visit_us') }}</h4>
                        <div class="text-base font-bold text-slate-900">
                            {{ $contactAddress }}
                        </div>
                        <p class="text-xs text-slate-500 mt-1">{{ __('common.available_in_person') }}</p>
                    </div>
                </div>

                <!-- Direct WhatsApp Trigger -->
                <a href="https://wa.me/{{ $cleanWhatsApp }}" target="_blank" rel="noopener noreferrer" class="w-full p-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm flex items-center justify-center space-x-2 shadow-lg shadow-emerald-600/25 transition-all">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.966.527 1.76.812 2.795.812 3.179 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.999-5.767-5.999zm3.385 8.163c-.144.405-.837.774-1.17.822-.312.043-.699.077-2.029-.472-1.696-.703-2.779-2.457-2.863-2.569-.084-.112-.686-.914-.686-1.743 0-.829.434-1.237.588-1.408.155-.171.339-.214.452-.214.113 0 .226.002.324.007.104.005.243-.039.38.291.144.344.492 1.2.535 1.288.043.088.071.191.014.305-.056.113-.085.184-.17.283-.085.1-.179.223-.255.3-.099.099-.202.207-.087.404.115.197.51 1.042 1.093 1.562.752.669 1.386.877 1.583.974.197.098.312.085.428-.049.115-.133.493-.574.625-.771.132-.197.265-.164.444-.099.18.066 1.139.537 1.336.635.197.099.329.148.378.23.049.083.049.48-.095.885z"/></svg>
                    <span>Chat with Us on WhatsApp</span>
                </a>
            </div>

            <!-- Right Column: Interactive Form -->
            <div class="lg:col-span-7">
                <div class="glass-card rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-xl space-y-6">
                    <div>
                        <h3 class="text-2xl font-bold font-heading text-slate-900">{{ __('common.send_us_message') }}</h3>
                        <p class="text-slate-600 text-xs mt-1">{{ __('common.contact_form_description') }}</p>
                    </div>

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="name" class="block text-xs font-semibold text-slate-700">{{ __('common.your_name') }} <span class="text-rose-500">*</span></label>
                                <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="e.g. David Minja" class="form-input-modern text-sm">
                                @error('name')
                                    <p class="text-xs text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1">
                                <label for="email" class="block text-xs font-semibold text-slate-700">{{ __('common.email_address') }} <span class="text-rose-500">*</span></label>
                                <input type="email" id="email" name="email" required value="{{ old('email') }}" placeholder="you@example.com" class="form-input-modern text-sm">
                                @error('email')
                                    <p class="text-xs text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="phone" class="block text-xs font-semibold text-slate-700">Phone / WhatsApp</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+255 712 345 678" class="form-input-modern text-sm">
                            </div>

                            <div class="space-y-1">
                                <label for="subject" class="block text-xs font-semibold text-slate-700">{{ __('common.subject') }} <span class="text-rose-500">*</span></label>
                                <input type="text" id="subject" name="subject" required value="{{ old('subject', request('subject')) }}" placeholder="e.g. Wedding Invitation Setup" class="form-input-modern text-sm">
                                @error('subject')
                                    <p class="text-xs text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label for="message" class="block text-xs font-semibold text-slate-700">{{ __('common.message') }} <span class="text-rose-500">*</span></label>
                            <textarea id="message" name="message" rows="5" required placeholder="{{ __('common.tell_us_more') }}" class="form-input-modern text-sm">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn-primary w-full text-center text-sm py-3 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            <span>{{ __('common.send_message') }}</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
