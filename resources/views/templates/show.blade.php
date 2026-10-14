@extends('layouts.landing')

@php
    $brandSettings = $systemSettings ?? [];
    $brandName = $brandSettings['name'] ?? 'Tukio 360';
@endphp

@section('title', $template->name . ' - ' . $brandName)

@section('content')
<div class="min-h-screen bg-[#f3ffe3] pt-10 pb-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <!-- Superadmin Bar if logged in -->
        @if(auth()->check() && auth()->user()->isSuperAdmin())
        <div class="glass-panel rounded-2xl p-4 flex items-center justify-between border-indigo-200 bg-indigo-50/50">
            <div class="flex items-center space-x-3">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                <span class="text-xs font-bold text-slate-900">Admin Control: {{ $template->name }}</span>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.templates.edit', $template) }}" class="px-3 py-1.5 bg-indigo-600 text-white rounded-xl text-xs font-semibold hover:bg-indigo-500 transition-colors">
                    Edit Template
                </a>
                <a href="{{ route('admin.templates.index') }}" class="px-3 py-1.5 bg-white text-slate-700 border border-slate-200 rounded-xl text-xs font-semibold hover:bg-slate-50 transition-colors">
                    Admin Panel
                </a>
            </div>
        </div>
        @endif

        <!-- Template Breadcrumb / Back Link -->
        <div class="flex items-center justify-between">
            <a href="{{ route('templates.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-indigo-600 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>All Templates</span>
            </a>
            <span class="badge-pill badge-indigo">
                {{ ucfirst(str_replace('_', ' ', $template->category ?? 'General')) }}
            </span>
        </div>

        <div class="grid lg:grid-cols-12 gap-10 lg:gap-12 items-start">
            <!-- Left: Device Mockup Card View -->
            <div class="lg:col-span-7">
                <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-2xl space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Live Card View</span>
                        <div class="flex items-center space-x-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        </div>
                    </div>

                    <div class="relative bg-slate-900 rounded-2xl overflow-hidden shadow-inner flex items-center justify-center min-h-[420px]">
                        @if($template->preview_image)
                            <img src="{{ storage_url($template->preview_image) }}" alt="{{ $template->name }}" class="w-full h-auto object-contain max-h-[520px]">
                        @else
                            <div class="p-10 text-center text-white space-y-3">
                                <div class="w-16 h-16 rounded-2xl bg-slate-800 text-indigo-400 flex items-center justify-center mx-auto shadow-lg border border-slate-700">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                                </div>
                                <h3 class="text-2xl font-black font-heading">{{ $template->name }}</h3>
                                <p class="text-xs text-slate-300 max-w-sm">Muundo wa kadi ya mwaliko pamoja na uthibitisho wa RSVP na QR code ya kuingilia.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right: Template Details & CTA -->
            <div class="lg:col-span-5 space-y-6">
                <div class="glass-card rounded-3xl p-8 border border-slate-200 shadow-xl space-y-6">
                    <div>
                        <h1 class="text-3xl font-black font-heading text-slate-900">{{ $template->name }}</h1>
                        <p class="text-slate-600 text-sm mt-2 leading-relaxed">
                            {{ $template->description ?? 'Kadi nzuri ya mwaliko inayotumwa kirahisi kupitia WhatsApp, yenye uthibitisho wa wageni (RSVP) na ukaguzi wa QR mlangoni.' }}
                        </p>
                    </div>

                    <div class="space-y-3 pt-3 border-t border-slate-100 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Category</span>
                            <span class="font-bold text-slate-800">{{ ucfirst(str_replace('_', ' ', $template->category ?? 'Event')) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Delivery Channel</span>
                            <span class="font-bold text-emerald-600">WhatsApp &amp; Web Link</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Gate Pass</span>
                            <span class="font-bold text-indigo-600">Included (Personalized QR)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Status</span>
                            <span class="font-bold text-slate-800">100% Free to Customize</span>
                        </div>
                    </div>

                    <div class="pt-4 space-y-3">
                        <a href="{{ route('host.register') }}" class="btn-primary w-full text-center text-sm py-3.5 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            <span>Use This Template Free</span>
                        </a>
                        <a href="{{ route('contact') }}?subject=Custom+Design+{{ urlencode($template->name) }}" class="btn-secondary w-full text-center text-xs py-2.5">
                            Request Custom Modifications
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
