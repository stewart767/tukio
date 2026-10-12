@extends('layouts.landing')

@php
    $brandSettings = $systemSettings ?? [];
    $brandName = $brandSettings['name'] ?? 'Tukio 360';
@endphp

@section('title', $template->name . ' - ' . $brandName)

@section('content')
<div class="min-h-screen bg-slate-50 pt-10 pb-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <!-- Superadmin Bar if logged in -->
        @if(auth()->check() && auth()->user()->isSuperAdmin())
        <div class="glass-panel rounded-2xl p-4 flex items-center justify-between border-indigo-200 bg-indigo-50/50">
            <div class="flex items-center space-x-3">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 animate-ping"></span>
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
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-500 to-pink-500 flex items-center justify-center text-3xl mx-auto shadow-lg">
                                    🎨
                                </div>
                                <h3 class="text-2xl font-black font-heading">{{ $template->name }}</h3>
                                <p class="text-xs text-slate-300 max-w-sm">Royal Digital Card Layout with dynamic RSVP confirmation and QR entry gate pass.</p>
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
                            {{ $template->description ?? 'A high-impact digital invitation design optimized for seamless WhatsApp sending, guest RSVP tracking, and VIP gate check-ins.' }}
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
