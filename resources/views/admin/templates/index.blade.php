@extends('layouts.dashboard')

@section('title', 'Templates Management')
@section('page-title', 'Design Studio & Templates')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Creative Studio Header -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-purple-50/40 to-slate-50 p-8 sm:p-10 shadow-xl backdrop-blur-md">
        <div class="absolute -top-12 -right-12 h-44 w-44 rounded-full bg-gradient-to-br from-purple-400/20 to-pink-400/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 h-48 w-48 rounded-full bg-gradient-to-tr from-indigo-400/15 to-blue-400/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full border border-purple-200/60 bg-purple-50/80 px-3.5 py-1 text-xs font-semibold text-purple-700 shadow-xs">
                    <span class="inline-flex h-2 w-2 rounded-full bg-purple-500 animate-pulse"></span>
                    Dynamic Invitation Engine
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-purple-600 via-indigo-600 to-pink-500 text-white shadow-lg shadow-purple-500/25">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Invitation Templates Foundry</h1>
                        <p class="mt-1 text-sm sm:text-base text-slate-600 max-w-xl">Architect visual HTML/CSS designs, configure dynamic customer placeholders, set pricing packages, and publish to the host designer library.</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <form action="{{ route('admin.templates.sync') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-white border border-slate-200 px-5 py-3 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-all">
                        <svg class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Sync File Templates</span>
                    </button>
                </form>

                <a href="{{ route('admin.templates.create') }}" class="group inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-purple-600 via-indigo-600 to-pink-600 px-6 py-3 text-xs font-bold text-white shadow-lg shadow-purple-600/30 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-purple-600/40">
                    <svg class="h-4 w-4 transition-transform duration-300 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                    </svg>
                    <span>Create Template</span>
                </a>
            </div>
        </div>

        <!-- Metric KPI Cards -->
        <div class="relative z-10 mt-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div class="rounded-2xl border border-slate-200/60 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-500">
                    <span>Total Templates</span>
                    <span class="text-purple-600">🎨</span>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ $templates->total() }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Published &amp; drafts</div>
            </div>

            <div class="rounded-2xl border border-pink-100 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-pink-600">
                    <span>Weddings</span>
                    <span>💍</span>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ \App\Models\Template::where('category', 'wedding')->count() }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Nuptials &amp; Send-offs</div>
            </div>

            <div class="rounded-2xl border border-amber-100 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-amber-600">
                    <span>Premium Paid</span>
                    <span>💎</span>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ \App\Models\Template::where('is_paid', true)->orWhere('price', '>', 0)->count() }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Monetized designs</div>
            </div>

            <div class="rounded-2xl border border-emerald-100 bg-white/90 p-4 sm:p-5 shadow-xs backdrop-blur-sm">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-emerald-600">
                    <span>Active Published</span>
                    <span>🟢</span>
                </div>
                <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ \App\Models\Template::where('is_active', true)->count() }}</div>
                <div class="mt-1 text-[11px] text-slate-400">Available for host selection</div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm flex flex-wrap items-center justify-between gap-4">
        <form action="{{ route('admin.templates.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search templates..."
                   class="rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:ring-2 focus:ring-purple-500 w-full sm:w-48">

            <select name="category" onchange="this.form.submit()" class="rounded-xl border border-slate-200 px-3 py-2 text-xs focus:ring-2 focus:ring-purple-500">
                <option value="">All Categories</option>
                @foreach($categories as $key => $label)
                    <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <select name="type" onchange="this.form.submit()" class="rounded-xl border border-slate-200 px-3 py-2 text-xs focus:ring-2 focus:ring-purple-500">
                <option value="">All Types</option>
                <option value="standard" {{ request('type') === 'standard' ? 'selected' : '' }}>Standard</option>
                <option value="premium" {{ request('type') === 'premium' ? 'selected' : '' }}>Premium</option>
                <option value="custom" {{ request('type') === 'custom' ? 'selected' : '' }}>Custom Client</option>
            </select>

            <select name="status" onchange="this.form.submit()" class="rounded-xl border border-slate-200 px-3 py-2 text-xs focus:ring-2 focus:ring-purple-500">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            @if(request()->anyFilled(['search', 'category', 'type', 'status']))
                <a href="{{ route('admin.templates.index') }}" class="text-xs text-rose-600 font-bold hover:underline">Reset</a>
            @endif
        </form>
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

    <!-- Template Grid Showcase -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($templates as $template)
        <div class="group relative rounded-3xl border border-slate-200/80 bg-white/90 shadow-md backdrop-blur-md overflow-hidden hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
            <div>
                <!-- Thumbnail Preview Container -->
                <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-900 flex items-center justify-center">
                    @if($template->preview_image)
                        <img src="{{ storage_url($template->preview_image) }}" alt="{{ $template->name }}" 
                             class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                             loading="lazy">
                        <div class="hidden h-full w-full items-center justify-center bg-gradient-to-br from-purple-900 to-indigo-950 text-4xl">🎨</div>
                    @else
                        <div class="h-full w-full flex flex-col items-center justify-center bg-gradient-to-br from-purple-900 via-indigo-950 to-slate-950 text-white p-4 text-center">
                            <span class="text-3xl mb-1">✨</span>
                            <span class="text-xs font-bold font-heading">{{ $template->name }}</span>
                            <span class="text-[10px] text-purple-300">{{ $template->slug }}</span>
                        </div>
                    @endif

                    <!-- Category Pill (Top Left) -->
                    <div class="absolute top-3 left-3 flex flex-col gap-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold bg-white/95 backdrop-blur-md text-slate-900 shadow-sm border border-white/60 uppercase">
                            {{ ucfirst(str_replace('_', ' ', $template->category)) }}
                        </span>
                        @if($template->artwork_path)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-purple-600 text-white shadow-xs">
                                ✨ AI Detected
                            </span>
                        @endif
                    </div>

                    <!-- Price Badge (Top Right) -->
                    <div class="absolute top-3 right-3 flex items-center gap-1">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black shadow-sm backdrop-blur-md {{ $template->isPaid() ? 'bg-amber-400 text-slate-950' : 'bg-emerald-500 text-white' }}">
                            {{ $template->formatted_price }}
                        </span>
                    </div>

                    <!-- Version tag (Bottom Left) -->
                    <div class="absolute bottom-2 left-3">
                        <span class="text-[10px] font-mono text-white/90 bg-black/60 px-2 py-0.5 rounded-md backdrop-blur-xs">
                            v{{ $template->version ?? '1.0' }}
                        </span>
                    </div>
                </div>

                <!-- Template Metadata -->
                <div class="p-5 space-y-2">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-slate-900 group-hover:text-purple-600 transition-colors line-clamp-1 font-heading">
                            {{ $template->name }}
                        </h3>
                    </div>
                    
                    <p class="text-xs text-slate-500 line-clamp-2 min-h-[32px]">
                        {{ $template->description ?? 'Custom bespoke invitation template layout.' }}
                    </p>

                    <!-- Features Tags Preview -->
                    <div class="flex flex-wrap gap-1 pt-1">
                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 border border-purple-100 font-medium">QR Pass</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100 font-medium">RSVP</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100 font-medium">Countdown</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="p-5 pt-0 space-y-2 border-t border-slate-100/80">
                <div class="grid grid-cols-2 gap-2 pt-3">
                    <a href="{{ route('admin.templates.editor', $template) }}" class="btn-secondary text-xs py-2 px-3 justify-center text-purple-700 font-bold bg-purple-50/70 hover:bg-purple-100/80">
                        <svg class="w-3.5 h-3.5 mr-1 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editor
                    </a>
                    <a href="{{ route('admin.templates.preview', $template) }}" target="_blank" class="btn-secondary text-xs py-2 px-3 justify-center text-slate-700 font-bold">
                        <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Preview
                    </a>
                </div>

                <div class="flex items-center gap-2">
                    <!-- Duplicate Button -->
                    <form action="{{ route('admin.templates.duplicate', $template) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-1.5 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Duplicate / Clone Template">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </form>

                    <!-- Toggle Active -->
                    <form action="{{ route('admin.templates.toggle-active', $template) }}" method="POST" class="flex-1">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full text-center text-xs font-bold py-1.5 px-2 rounded-xl transition-colors {{ $template->is_active ? 'bg-slate-100 hover:bg-slate-200 text-slate-700' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700' }}">
                            {{ $template->is_active ? 'Deactivate' : 'Publish Active' }}
                        </button>
                    </form>

                    <!-- Delete -->
                    <form action="{{ route('admin.templates.destroy', $template) }}" method="POST" onsubmit="return confirm('Delete template {{ $template->name }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Delete Template">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-16 text-center shadow-lg">
                <div class="w-20 h-20 bg-purple-50 rounded-3xl mx-auto flex items-center justify-center text-purple-600 text-3xl shadow-inner mb-4">
                    🎨
                </div>
                <h3 class="text-lg font-bold text-slate-900 font-heading">No templates found</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">Get started by synchronizing the starter templates or creating a new design.</p>
                <div class="flex items-center justify-center gap-3 mt-6">
                    <form action="{{ route('admin.templates.sync') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-secondary text-xs">Sync Starter Templates</button>
                    </form>
                    <a href="{{ route('admin.templates.create') }}" class="btn-primary text-xs">Create Template</a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($templates->hasPages())
    <div class="flex justify-center pt-4">
        {{ $templates->links() }}
    </div>
    @endif
</div>
@endsection
