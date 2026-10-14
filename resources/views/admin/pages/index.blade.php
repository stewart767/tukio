@extends('layouts.dashboard')

@section('title', 'Pages Management')
@section('page-title', 'CMS & Website Pages')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-indigo-50/40 to-slate-50 p-8 sm:p-10 shadow-xl backdrop-blur-md">
        <div class="absolute -top-12 -right-12 h-44 w-44 rounded-full bg-gradient-to-br from-indigo-400/20 to-blue-400/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 h-48 w-48 rounded-full bg-gradient-to-tr from-purple-400/15 to-indigo-400/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full border border-indigo-200/60 bg-indigo-50/80 px-3.5 py-1 text-xs font-semibold text-indigo-700 shadow-xs">
                    <span class="inline-flex h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    Content Management Engine
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 via-blue-600 to-purple-600 text-white shadow-lg shadow-indigo-500/25">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Website Pages &amp; Static Content</h1>
                        <p class="mt-1 text-sm sm:text-base text-slate-600 max-w-xl">Customize landing page copywriting, SEO meta titles, FAQs, and static platform documents.</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="px-5 py-3 rounded-2xl bg-white/90 border border-slate-200/80 shadow-xs text-right">
                    <div class="text-2xl font-black text-indigo-600">{{ $pages->count() }}</div>
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">CMS Pages</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
    <div class="relative overflow-hidden rounded-2xl border border-emerald-200 bg-gradient-to-r from-emerald-50 via-teal-50 to-green-50 px-6 py-4 text-emerald-900 shadow-md">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-sm font-semibold">{{ session('success') }}</div>
        </div>
    </div>
    @endif

    <!-- Modern Pages Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($pages as $page)
        <div class="group relative rounded-3xl border border-slate-200/80 bg-white/90 p-7 shadow-md backdrop-blur-md hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
            <div>
                <!-- Top Header with Icon & Status Badge -->
                <div class="flex items-start justify-between gap-3 mb-5">
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-300">
                        @if($page->slug === 'homepage')
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        @elseif($page->slug === 'about-us' || $page->slug === 'about')
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        @elseif($page->slug === 'terms' || $page->slug === 'terms-of-use')
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        @elseif($page->slug === 'privacy' || $page->slug === 'privacy-policy')
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        @endif
                    </div>

                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold shadow-2xs {{ $page->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $page->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                        {{ $page->is_active ? 'Published' : 'Draft' }}
                    </span>
                </div>

                <!-- Page Title & Slug -->
                <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors font-heading">
                    {{ $page->title }}
                </h3>
                <div class="mt-1 font-mono text-xs text-indigo-600 bg-indigo-50/60 px-2.5 py-1 rounded-lg inline-block border border-indigo-100/60">
                    /{{ $page->slug }}
                </div>

                <!-- Content Preview -->
                <p class="text-xs text-slate-500 mt-4 line-clamp-3 leading-relaxed">
                    @if($page->content)
                        {{ strip_tags($page->content) }}
                    @else
                        <span class="italic text-slate-400">Content configured via custom hero and modular benefit sections.</span>
                    @endif
                </p>

                <!-- SEO Metadata Indicator -->
                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center gap-3 text-[11px] text-slate-400">
                    <div class="flex items-center gap-1">
                        <span class="{{ $page->meta_title ? 'text-emerald-600 font-bold' : 'text-slate-400' }}">
                            {{ $page->meta_title ? '✓' : '○' }} SEO Title
                        </span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="{{ $page->meta_description ? 'text-emerald-600 font-bold' : 'text-slate-400' }}">
                            {{ $page->meta_description ? '✓' : '○' }} Meta Desc
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-2">
                <a href="{{ route('admin.pages.edit', $page) }}" class="btn-primary text-xs py-2.5 flex-1 justify-center shadow-sm">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Content
                </a>

                @if($page->slug !== 'homepage')
                    <a href="{{ url('/' . $page->slug) }}" target="_blank" class="btn-secondary text-xs py-2.5 px-3" title="View Public Page">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                @else
                    <a href="{{ url('/') }}" target="_blank" class="btn-secondary text-xs py-2.5 px-3" title="View Homepage">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
