@extends('layouts.landing')

@section('title', __('common.event_categories') . ' - Tukio 360')

@section('content')
<div class="min-h-screen bg-[#f3ffe3] pt-10 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-14">
        
        <!-- Page Header -->
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <h1 class="text-4xl sm:text-5xl font-black font-heading text-slate-900 tracking-tight">
                Designed for Every Celebration
            </h1>
            <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                {{ __('common.categories_description') }}
            </p>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $activeMilestones = isset($milestones) && $milestones->count() > 0 
                    ? $milestones 
                    : \App\Models\Milestone::where('is_active', true)->orderBy('sort_order', 'asc')->get();
            @endphp

            @forelse($activeMilestones as $m)
                @php
                    $theme = $m->theme_classes ?? [
                        'badge_bg' => 'bg-white/90 text-purple-800',
                        'border_hover' => 'hover:border-purple-300',
                        'tag_bg' => 'bg-purple-50 text-purple-700',
                        'primary' => 'text-purple-600',
                    ];
                    $imgSrc = $m->image_url_formatted;
                @endphp
                <div class="group relative bg-white/95 backdrop-blur-md rounded-3xl border border-slate-200/80 {{ $theme['border_hover'] }} shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="relative h-48 w-full overflow-hidden bg-slate-100">
                            <img src="{{ $imgSrc }}" alt="{{ $m->title }}" class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700 ease-out">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-slate-950/20 to-transparent"></div>
                            
                            <div class="absolute top-3.5 left-3.5 right-3.5 flex items-center justify-between z-10">
                                @if($m->badge)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-wide backdrop-blur-md {{ $theme['badge_bg'] }} shadow-sm border border-white/50">
                                        {{ $m->badge }}
                                    </span>
                                @else
                                    <span></span>
                                @endif
                                <div class="w-10 h-10 rounded-xl bg-white/95 backdrop-blur-md flex items-center justify-center text-indigo-600 shadow-md border border-white/60">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                </div>
                            </div>

                            @if($m->tagline)
                            <div class="absolute bottom-3 left-4 right-4 z-10">
                                <span class="text-[11px] font-semibold text-white/95 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span> {{ $m->tagline }}
                                </span>
                            </div>
                            @endif
                        </div>

                        <div class="p-6 sm:p-7 space-y-4">
                            <div>
                                <h3 class="text-2xl font-bold font-heading text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $m->title }}</h3>
                                <p class="text-slate-600 text-xs mt-1 leading-relaxed">
                                    {{ $m->description }}
                                </p>
                            </div>

                            @if(!empty($m->tags) && is_array($m->tags))
                            <ul class="space-y-2.5 pt-2 text-xs text-slate-600 border-t border-slate-100">
                                @foreach($m->tags as $tag)
                                <li class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-indigo-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span>{{ $tag }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>

                    <div class="px-6 pb-6 sm:px-7 sm:pb-7">
                        <a href="{{ route('host.register') }}" class="btn-primary w-full text-center text-xs py-2.5">
                            {{ $m->button_text ?: 'Plan Event' }} &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 text-slate-400">
                    No event categories configured yet.
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
