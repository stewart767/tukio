@extends('layouts.dashboard')

@section('title', 'Edit Template - ' . $template->name)
@section('page-title', 'Template Studio Foundry')

@section('content')
<div class="space-y-8 pb-12 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 rounded-full border border-purple-200/60 bg-purple-50/80 px-3 py-1 text-xs font-semibold text-purple-700">
                <span class="h-1.5 w-1.5 rounded-full bg-purple-500"></span>
                Template Editor &bull; {{ $template->slug }}
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Edit {{ $template->name }}</h1>
            <p class="text-sm text-slate-500">Modify dynamic data fields, monetization tier, view paths, and visual attributes.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.templates.preview', $template) }}" target="_blank" class="btn-primary text-xs sm:text-sm">
                Live Preview
            </a>
            <a href="{{ route('admin.templates.index') }}" class="btn-secondary text-xs sm:text-sm">
                Back to Templates
            </a>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white shadow-xl overflow-hidden">
        <div class="border-b border-slate-100 bg-slate-50/70 px-8 py-5 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900 font-heading">Template Architecture &amp; Field Configuration</h2>
                <p class="text-xs text-slate-500 mt-0.5">Version {{ $template->version ?? '1.0' }} &bull; {{ ucfirst($template->template_type) }} Tier</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $template->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}">
                {{ $template->is_active ? 'Active Published' : 'Draft Inactive' }}
            </span>
        </div>

        <form action="{{ route('admin.templates.update', $template) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Section 1: General Info & Categorization -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">1. General Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Template Title <span class="text-rose-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $template->name) }}" required
                               class="form-input-modern text-sm @error('name') border-rose-300 ring-1 ring-rose-300 @enderror">
                    </div>

                    <div>
                        <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Slug Identifier <span class="text-rose-500">*</span></label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $template->slug) }}" required
                               class="form-input-modern text-sm @error('slug') border-rose-300 ring-1 ring-rose-300 @enderror">
                    </div>

                    <div>
                        <label for="category" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Event Category <span class="text-rose-500">*</span></label>
                        <select id="category" name="category" required class="form-input-modern text-sm">
                            @foreach($categories as $value => $label)
                                <option value="{{ $value }}" {{ old('category', $template->category) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label for="template_type" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Template Tier <span class="text-rose-500">*</span></label>
                        <select id="template_type" name="template_type" required class="form-input-modern text-sm">
                            <option value="standard" {{ old('template_type', $template->template_type) == 'standard' ? 'selected' : '' }}>Standard (Free / Included)</option>
                            <option value="premium" {{ old('template_type', $template->template_type) == 'premium' ? 'selected' : '' }}>Premium (Paid Package)</option>
                            <option value="custom" {{ old('template_type', $template->template_type) == 'custom' ? 'selected' : '' }}>Custom Bespoke (Client Specific)</option>
                        </select>
                    </div>

                    <div>
                        <label for="price" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Price (TZS)</label>
                        <input type="number" id="price" name="price" value="{{ old('price', $template->price) }}" min="0" step="5000"
                               class="form-input-modern text-sm">
                    </div>

                    <div>
                        <label for="version" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Template Version</label>
                        <input type="text" id="version" name="version" value="{{ old('version', $template->version ?? '1.0') }}"
                               class="form-input-modern text-sm">
                    </div>
                </div>

                <div class="mt-6">
                    <label for="view_path" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Blade View Path</label>
                    <input type="text" id="view_path" name="view_path" value="{{ old('view_path', $template->view_path) }}"
                           class="form-input-modern text-sm font-mono text-xs">
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="2"
                              class="form-input-modern text-sm">{{ old('description', $template->description) }}</textarea>
                </div>
            </div>

            <!-- Section 2: Upload Artwork / Preview -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">2. Preview Thumbnail Artwork</h3>
                
                <div class="flex flex-col sm:flex-row items-center gap-6 p-6 rounded-3xl bg-slate-50 border border-slate-200">
                    @if($template->preview_image)
                        <div class="w-32 h-24 rounded-2xl overflow-hidden border border-slate-200 shadow-sm shrink-0 bg-slate-900">
                            <img src="{{ storage_url($template->preview_image) }}" alt="{{ $template->name }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Replace Preview Image</label>
                        <input type="file" name="template_image" accept="image/*" class="text-xs text-slate-500">
                        <p class="text-[11px] text-slate-400 mt-1">PNG, JPG, WebP up to 10MB</p>
                    </div>
                </div>
            </div>

            <!-- Section 3: Supported Dynamic Fields -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">3. Supported Dynamic Fields</h3>
                <p class="text-xs text-slate-500 mb-4">Select which fields are supported and populated automatically by this template:</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($standardFields as $fieldKey => $meta)
                        @php
                            $isChecked = in_array($fieldKey, $activeFieldKeys);
                        @endphp
                        <label class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-slate-100/80 transition-colors">
                            <input type="checkbox" name="fields[]" value="{{ $fieldKey }}" {{ $isChecked ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                            <div>
                                <span class="text-xs font-bold text-slate-800 block">{{ $meta['label'] }}</span>
                                <span class="text-[10px] font-mono text-purple-600 block">&#123;&#123;{{ $fieldKey }}&#125;&#125;</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.templates.index') }}" class="btn-secondary text-sm">Cancel</a>
                <button type="submit" class="btn-primary text-sm shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection