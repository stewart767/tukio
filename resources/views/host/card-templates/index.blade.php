@extends('layouts.host')

@section('title', 'Card Design Studio')
@section('page-title', 'Custom Card Templates')

@section('content')
<div class="space-y-8 animate-fade-in max-w-7xl mx-auto">

    <!-- Top Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-700 via-indigo-700 to-indigo-900 p-8 sm:p-10 text-white shadow-xl shadow-indigo-900/20">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-xs font-semibold uppercase tracking-wider text-purple-100">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Visual Card Studio</span>
                    <span>&bull;</span>
                    <span>{{ ucfirst(str_replace('_', ' ', $event->category ?? 'Event')) }}</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black font-heading tracking-tight text-white">
                    Custom Card Templates
                </h1>
                <p class="text-purple-100/90 text-sm sm:text-base max-w-2xl">
                    Upload your printed invitation artwork, adjust text zones and entrance QR codes in the visual editor, and dispatch personalized digital cards to all your guests.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('host.invitations.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-xs font-bold text-white transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <span>Dispatch Cards</span>
                </a>
                <a href="{{ route('host.card-templates.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-xs font-bold text-white shadow-lg shadow-emerald-950/40 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Upload Card Design</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 flex items-center gap-3 text-emerald-800 text-sm">
            <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('warning'))
        <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 flex items-center gap-3 text-amber-800 text-sm">
            <svg class="w-5 h-5 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    <!-- Section 1: Your Custom Uploaded Templates -->
    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold font-heading text-slate-900">Your Uploaded Card Designs</h2>
                <p class="text-xs sm:text-sm text-slate-500">Custom invitation cards uploaded specifically for your event.</p>
            </div>
            <a href="{{ route('host.card-templates.create') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                <span>+ Upload Another</span>
                <span>&rarr;</span>
            </a>
        </div>

        @if($myTemplates->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($myTemplates as $t)
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col justify-between group">
                        <div>
                            <!-- Card Preview Frame -->
                            <div class="relative h-64 bg-slate-900 overflow-hidden flex items-center justify-center">
                                @if($t->preview_image || $t->artwork_path)
                                    <img src="{{ $t->artwork_url }}" alt="{{ $t->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="text-center p-6 text-slate-400">
                                        <svg class="w-12 h-12 mx-auto mb-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-xs font-semibold text-white block">{{ $t->name }}</span>
                                    </div>
                                @endif

                                <!-- Category Badge -->
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-white/95 backdrop-blur text-slate-800 uppercase shadow-sm">
                                    {{ ucfirst(str_replace('_', ' ', $t->category)) }}
                                </span>

                                <!-- Resolution Badge -->
                                @if($t->artwork_width && $t->artwork_height)
                                    <span class="absolute top-3 right-3 px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-slate-900/80 backdrop-blur text-purple-200">
                                        {{ $t->artwork_width }}&times;{{ $t->artwork_height }}px
                                    </span>
                                @endif

                                <!-- Status Overlay on hover -->
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                        <span class="text-xs font-bold text-white">Active Custom Template</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Details -->
                            <div class="p-6 space-y-3">
                                <div class="flex items-start justify-between gap-2">
                                    <h3 class="font-extrabold text-base text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-1">
                                        {{ $t->name }}
                                    </h3>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 shrink-0">
                                        {{ count($t->getNormalizedFields()) }} Zones
                                    </span>
                                </div>

                                @if($t->description)
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                        {{ $t->description }}
                                    </p>
                                @endif

                                <div class="pt-2 flex flex-wrap items-center gap-2 text-[11px] text-slate-500">
                                    <span class="inline-flex items-center gap-1 font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        QR Pass Enabled
                                    </span>
                                    <span class="text-slate-300">&bull;</span>
                                    <span>Uploaded {{ $t->created_at?->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Actions -->
                        <div class="p-6 pt-0 space-y-2.5">
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('host.card-templates.editor', $t) }}" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-xs font-bold text-white shadow-sm transition-all text-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>Edit Layout</span>
                                </a>

                                <a href="{{ route('host.card-templates.preview', $t) }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-700 transition-all text-center">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>Live Preview</span>
                                </a>
                            </div>

                            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                                <a href="{{ route('host.invitations.create') }}?template_id={{ $t->id }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                    <span>Use in Invitations</span>
                                    <span>&rarr;</span>
                                </a>

                                <form action="{{ route('host.card-templates.destroy', $t) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this custom card template?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="rounded-3xl border-2 border-dashed border-slate-300 bg-white p-10 text-center space-y-4">
                <div class="w-16 h-16 rounded-3xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="max-w-md mx-auto space-y-1">
                    <h3 class="text-lg font-bold text-slate-900">No Custom Cards Uploaded Yet</h3>
                    <p class="text-xs text-slate-500">
                        Upload your custom card design artwork (JPG, PNG, or WebP). Our AI will automatically detect text positions so your cards can be personalized for each guest.
                    </p>
                </div>
                <a href="{{ route('host.card-templates.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-xs font-bold text-white shadow-lg shadow-indigo-600/30 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Upload Your First Card Design</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Section 2: Ready-to-Use Platform Templates -->
    <div class="space-y-5 pt-6 border-t border-slate-200">
        <div>
            <h2 class="text-xl font-bold font-heading text-slate-900">Platform Ready-to-Use Templates</h2>
            <p class="text-xs sm:text-sm text-slate-500">Curated luxury invitation styles available to all hosts.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($catalogTemplates as $t)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="relative h-44 bg-slate-900 overflow-hidden flex items-center justify-center">
                            @if($t->preview_image)
                                <img src="{{ storage_url($t->preview_image) }}" alt="{{ $t->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center p-4 text-slate-400">
                                    <span class="text-xs font-bold text-white block">{{ $t->name }}</span>
                                </div>
                            @endif
                            <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-white/95 backdrop-blur text-slate-800 uppercase">
                                {{ ucfirst(str_replace('_', ' ', $t->category)) }}
                            </span>
                        </div>
                        <div class="p-4 space-y-1">
                            <h4 class="font-bold text-sm text-slate-900 truncate">{{ $t->name }}</h4>
                            <p class="text-[11px] text-slate-500 line-clamp-2">{{ $t->description }}</p>
                        </div>
                    </div>
                    <div class="p-4 pt-0 flex items-center justify-between border-t border-slate-100">
                        <a href="{{ route('admin.templates.preview', $t) }}" target="_blank" class="text-[11px] font-bold text-slate-500 hover:text-indigo-600">
                            Preview &nearr;
                        </a>
                        <a href="{{ route('host.invitations.create') }}?template_id={{ $t->id }}" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800">
                            Use Template &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection

