@extends('layouts.dashboard')

@section('title', 'Milestones & Occasions Management')
@section('page-title', 'Tailored for Every Milestone')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-indigo-50/40 to-slate-50 p-8 sm:p-10 shadow-xl backdrop-blur-md">
        <div class="absolute -top-12 -right-12 h-44 w-44 rounded-full bg-gradient-to-br from-indigo-400/20 to-purple-400/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 h-48 w-48 rounded-full bg-gradient-to-tr from-indigo-400/15 to-purple-400/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full border border-indigo-200/60 bg-indigo-50/80 px-3.5 py-1 text-xs font-semibold text-indigo-700 shadow-xs">
                    <span class="inline-flex h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    Homepage &amp; Categories Engine
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-slate-900 to-indigo-900 text-white shadow-lg shadow-indigo-500/25">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Tailored for Every Milestone</h1>
                        <p class="mt-1 text-sm sm:text-base text-slate-600 max-w-2xl">Manage celebration categories, occasion cards, feature tags, themes, and landing page milestone highlights.</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ url('/#milestones') }}" target="_blank" class="btn-secondary text-xs sm:text-sm py-2.5 px-4 shadow-sm">
                    <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Live Section
                </a>
                <a href="{{ route('admin.milestones.create') }}" class="btn-primary text-xs sm:text-sm py-2.5 px-5 shadow-lg shadow-indigo-500/20">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Add Milestone Card
                </a>
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

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-sm backdrop-blur-md flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Milestones</div>
                <div class="text-3xl font-black text-slate-900 font-heading">{{ $stats['total'] }}</div>
                <div class="text-xs text-slate-500">Configured occasion categories</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-sm backdrop-blur-md flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-bold uppercase tracking-wider text-emerald-600">Active / Published</div>
                <div class="text-3xl font-black text-emerald-600 font-heading">{{ $stats['active'] }}</div>
                <div class="text-xs text-slate-500">Live on homepage and categories</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <span class="w-3.5 h-3.5 rounded-full bg-emerald-500"></span>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-sm backdrop-blur-md flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Hidden / Drafts</div>
                <div class="text-3xl font-black text-slate-600 font-heading">{{ $stats['inactive'] }}</div>
                <div class="text-xs text-slate-500">Not visible to public visitors</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-md backdrop-blur-md">
        <form action="{{ route('admin.milestones.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="relative w-full md:w-96">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, badge, or tags..." 
                       class="form-input-modern text-sm pl-10">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <select name="status" class="form-input-modern text-xs py-2 w-full md:w-44" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Published Only</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Hidden Only</option>
                </select>

                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.milestones.index') }}" class="btn-secondary text-xs py-2 px-3">
                        Clear Filters
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Milestones Grid -->
    @if($milestones->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
        @foreach($milestones as $milestone)
        <div class="group relative rounded-3xl border border-slate-200/80 bg-white/95 backdrop-blur-md shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col justify-between">
            
            <div>
                <!-- Card Image Banner -->
                <div class="relative h-48 w-full overflow-hidden bg-slate-900">
                    <img src="{{ $milestone->image_url_formatted }}" alt="{{ $milestone->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                    
                    <!-- Floating Top Badge & Emoji -->
                    <div class="absolute top-3.5 left-3.5 right-3.5 flex items-center justify-between z-10">
                        @if($milestone->badge)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-wide backdrop-blur-md bg-white/90 text-slate-800 shadow-sm border border-white/50">
                                {{ $milestone->badge }}
                            </span>
                        @else
                            <span></span>
                        @endif

                        <div class="w-10 h-10 rounded-xl bg-white/95 backdrop-blur-md flex items-center justify-center text-indigo-600 shadow-md border border-white/60 group-hover:rotate-6 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                    </div>

                    <!-- Bottom Tagline -->
                    @if($milestone->tagline)
                    <div class="absolute bottom-3 left-4 right-4 z-10">
                        <span class="text-[11px] font-semibold text-white/95 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span> {{ $milestone->tagline }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Card Body -->
                <div class="p-6 space-y-4">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h3 class="text-xl font-bold font-heading text-slate-900 group-hover:text-indigo-600 transition-colors">
                                {{ $milestone->title }}
                            </h3>
                            <div class="font-mono text-[11px] text-indigo-600 mt-0.5">slug: /{{ $milestone->slug }}</div>
                        </div>

                        <!-- Published Status Pill -->
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $milestone->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $milestone->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                            {{ $milestone->is_active ? 'Active' : 'Draft' }}
                        </span>
                    </div>

                    <p class="text-slate-600 text-xs leading-relaxed line-clamp-2">
                        {{ $milestone->description ?: 'No description entered.' }}
                    </p>

                    <!-- Feature Tags -->
                    @if(!empty($milestone->tags) && is_array($milestone->tags))
                    <div class="flex flex-wrap gap-1.5 pt-2 border-t border-slate-100">
                        @foreach($milestone->tags as $tag)
                            <span class="text-[11px] font-medium bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded-md">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                    @endif

                    <div class="flex items-center justify-between text-[11px] text-slate-400 pt-2">
                        <span>Theme: <strong class="text-slate-700 capitalize">{{ $milestone->theme_color }}</strong></span>
                        <span>Sort Order: <strong class="text-slate-700">#{{ $milestone->sort_order }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Actions Footer -->
            <div class="p-6 pt-0 border-t border-slate-100 mt-2 flex items-center justify-between gap-2">
                <form action="{{ route('admin.milestones.toggle-active', $milestone) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-secondary text-xs py-2 px-3" title="{{ $milestone->is_active ? 'Click to Hide' : 'Click to Publish' }}">
                        {{ $milestone->is_active ? 'Hide' : 'Publish' }}
                    </button>
                </form>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.milestones.edit', $milestone) }}" class="btn-primary text-xs py-2 px-3 shadow-xs">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>

                    <form action="{{ route('admin.milestones.destroy', $milestone) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete milestone \'{{ $milestone->title }}\'?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors" title="Delete Milestone">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $milestones->links() }}
    </div>

    @else
    <!-- Empty State -->
    <div class="rounded-3xl border border-dashed border-slate-300 bg-white/70 p-12 text-center max-w-lg mx-auto space-y-4">
        <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto shadow-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900 font-heading">No Milestones Found</h3>
        <p class="text-xs text-slate-500 leading-relaxed">No milestone category cards match your current search or filter query.</p>
        <a href="{{ route('admin.milestones.create') }}" class="btn-primary text-xs py-2.5 px-4 inline-flex">
            Create First Milestone Card
        </a>
    </div>
    @endif
</div>
@endsection
