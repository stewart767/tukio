@extends('layouts.landing')

@php
    $brandSettings = $systemSettings ?? [];
    $brandName = $brandSettings['name'] ?? 'Tukio 360';
@endphp

@section('title', ($page->meta_title ?? $page->title) . ' - ' . $brandName)

@if($page->meta_description)
@push('meta')
<meta name="description" content="{{ $page->meta_description }}">
@endpush
@endif

@section('content')
<div class="min-h-screen bg-slate-50 pt-10 pb-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Page Header -->
        <div class="text-center space-y-3">
            <h1 class="text-3xl sm:text-5xl font-black font-heading text-slate-900 tracking-tight">
                {{ $page->title }}
            </h1>
            @if($page->meta_description)
                <p class="text-slate-600 text-sm max-w-xl mx-auto leading-relaxed">
                    {{ $page->meta_description }}
                </p>
            @endif
        </div>

        <!-- Main Content Card -->
        <div class="glass-card rounded-3xl p-8 sm:p-12 border border-slate-200 shadow-xl">
            <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-sm sm:text-base space-y-4">
                {!! $page->content !!}
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center pt-4">
            <a href="{{ route('home') }}" class="btn-secondary text-xs">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to Home</span>
            </a>
        </div>
    </div>
</div>
@endsection
