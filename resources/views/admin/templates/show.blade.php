@extends('layouts.dashboard')

@section('title', 'Template Details - ' . $template->name)
@section('page-title', 'Template Inspection Studio')

@section('content')
<div class="space-y-8 pb-12 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 rounded-full border border-purple-200/60 bg-purple-50/80 px-3 py-1 text-xs font-semibold text-purple-700">
                <span class="h-1.5 w-1.5 rounded-full bg-purple-500"></span>
                Template Inspection &bull; {{ $template->slug }}
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">{{ $template->name }}</h1>
            <p class="text-sm text-slate-500">Inspect dynamic field configurations, pricing, and live interactive rendering.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.templates.preview', $template) }}" target="_blank" class="btn-primary text-xs sm:text-sm shadow-md">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Live Preview
            </a>
            <a href="{{ route('admin.templates.edit', $template) }}" class="btn-secondary text-xs sm:text-sm">
                Edit Template
            </a>
            <a href="{{ route('admin.templates.index') }}" class="btn-secondary text-xs sm:text-sm">
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Visual Preview Frame (Left 7 cols) -->
        <div class="lg:col-span-7 rounded-3xl border border-slate-200/80 bg-white shadow-xl overflow-hidden">
            <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-4 flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Visual Layout Preview</h3>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold {{ $template->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $template->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                    {{ $template->is_active ? 'Active Published' : 'Draft' }}
                </span>
            </div>

            <div class="p-6 bg-slate-950 flex flex-col items-center justify-center min-h-[420px] text-center">
                @if($template->preview_image)
                    <div class="relative max-w-sm w-full rounded-2xl overflow-hidden shadow-2xl border border-white/20">
                        <img src="{{ storage_url($template->preview_image) }}" alt="{{ $template->name }}" class="w-full h-auto object-contain">
                    </div>
                @else
                    <div class="text-center text-slate-400 p-12">
                        <div class="mb-3 flex justify-center">
                            <svg class="w-12 h-12 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h4 class="text-base font-bold text-white font-heading">{{ $template->name }}</h4>
                        <p class="text-xs text-purple-400 mt-1">Blade View: {{ $template->view_path }}</p>
                        <a href="{{ route('admin.templates.preview', $template) }}" target="_blank" class="mt-4 inline-block btn-primary text-xs">
                            Launch Full Live Preview
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Template Specs & Field Mapping (Right 5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-xl space-y-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Template Specifications</h3>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500">Category</span>
                        <span class="font-bold text-slate-900">{{ ucfirst(str_replace('_', ' ', $template->category)) }}</span>
                    </div>

                    <div class="flex justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500">Tier &amp; Price</span>
                        <span class="font-bold text-slate-900">{{ ucfirst($template->template_type) }} &bull; {{ $template->formatted_price }}</span>
                    </div>

                    <div class="flex justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500">Version</span>
                        <span class="font-mono font-bold text-purple-700">v{{ $template->version ?? '1.0' }}</span>
                    </div>

                    <div class="flex justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500">View Path</span>
                        <span class="font-mono text-slate-600 text-[11px] truncate max-w-[200px]">{{ $template->view_path }}</span>
                    </div>
                </div>

                @if($template->description)
                <div class="p-4 rounded-2xl bg-purple-50/50 border border-purple-100 text-xs text-purple-900">
                    <span class="font-bold block mb-1">Description</span>
                    <p class="text-purple-800 leading-relaxed">{{ $template->description }}</p>
                </div>
                @endif
            </div>

            <!-- Active Placeholders & Dynamic Fields -->
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-xl space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Dynamic Fields ({{ $template->fields->count() }})</h3>
                </div>

                @if($template->fields->count() > 0)
                    <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        @foreach($template->fields as $field)
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                                <span class="font-bold text-slate-800">{{ $field->field_label }}</span>
                                <span class="font-mono text-purple-600 text-[11px]">&#123;&#123;{{ $field->field_key }}&#125;&#125;</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">No specific dynamic fields registered.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
