@extends('layouts.dashboard')

@section('title', 'Create Invitation Template')
@section('page-title', 'Template Studio Foundry')

@section('content')
<div class="space-y-8 pb-12 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 rounded-full border border-purple-200/60 bg-purple-50/80 px-3 py-1 text-xs font-semibold text-purple-700">
                <span class="h-1.5 w-1.5 rounded-full bg-purple-500"></span>
                Dynamic Template Engine
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Create Invitation Template</h1>
            <p class="text-sm text-slate-500">Upload an invitation card design for AI layout detection, or register a manual Blade template.</p>
        </div>
        <a href="{{ route('admin.templates.index') }}" class="btn-secondary text-xs sm:text-sm">
            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Templates
        </a>
    </div>

    <!-- Mode Selector Tabs -->
    <div class="flex p-1.5 bg-slate-100/90 rounded-2xl border border-slate-200 max-w-md">
        <button type="button" id="tab-ai-btn" class="flex-1 py-2.5 px-4 rounded-xl font-bold text-xs transition-all bg-white text-purple-700 shadow-sm flex items-center justify-center gap-2">
            <span>AI Artwork Detection</span>
        </button>
        <button type="button" id="tab-manual-btn" class="flex-1 py-2.5 px-4 rounded-xl font-bold text-xs transition-all text-slate-600 hover:text-slate-900 flex items-center justify-center gap-2">
            <span>Manual View Path</span>
        </button>
    </div>

    <!-- Mode 1: AI Artwork Upload Form Card -->
    <div id="ai-upload-section" class="rounded-3xl border border-purple-100 bg-white shadow-xl overflow-hidden transition-all">
        <div class="border-b border-purple-50 bg-gradient-to-r from-purple-50/70 via-indigo-50/50 to-indigo-50/40 px-8 py-5">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-xl bg-purple-600 text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 font-heading">AI-Assisted Card Layout Detection</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Upload a wedding, party, or corporate invitation card image. AI will automatically detect invitee names, text zones, and recommend QR placement.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.templates.upload-ai') }}" method="POST" enctype="multipart/form-data" id="ai-upload-form" class="p-8 space-y-8">
            @csrf

            <!-- Artwork Dropzone -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Invitation Card Artwork (JPG, JPEG, PNG, WebP) <span class="text-rose-500">*</span></label>
                <div class="relative border-2 border-dashed border-purple-200 rounded-3xl p-8 sm:p-12 text-center hover:border-purple-400 hover:bg-purple-50/30 transition-all duration-300 bg-slate-50/50 group cursor-pointer" id="artwork-dropzone">
                    <input type="file" id="artwork" name="artwork" accept="image/jpeg,image/png,image/jpg,image/webp" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    
                    <div id="ai-upload-placeholder" class="space-y-3 pointer-events-none">
                        <div class="w-16 h-16 bg-purple-100 rounded-2xl mx-auto flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 group-hover:text-purple-600 transition-colors">Click or drag &amp; drop high-resolution artwork</h4>
                            <p class="text-xs text-slate-500 mt-1">Recommended: Clean artwork without pre-printed recipient names &bull; Up to 15MB</p>
                        </div>
                    </div>

                    <div id="ai-preview-container" class="hidden pointer-events-none">
                        <img id="ai-preview-img" src="#" alt="Artwork Preview" class="max-h-72 rounded-2xl mx-auto shadow-md object-contain border border-purple-200">
                        <p class="text-xs font-bold text-purple-700 mt-3" id="ai-file-name-display"></p>
                    </div>
                </div>
                @error('artwork')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- General Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="ai_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Template Title <span class="text-rose-500">*</span></label>
                    <input type="text" id="ai_name" name="name" value="{{ old('name') }}" required placeholder="e.g. Royal Gold Nuptials"
                           class="form-input-modern text-sm">
                </div>

                <div>
                    <label for="ai_category" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Event Category <span class="text-rose-500">*</span></label>
                    <select id="ai_category" name="category" required class="form-input-modern text-sm">
                        @foreach($categories as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="ai_template_type" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Tier <span class="text-rose-500">*</span></label>
                    <select id="ai_template_type" name="template_type" required class="form-input-modern text-sm">
                        <option value="standard">Standard (Included)</option>
                        <option value="premium">Premium (Paid Package)</option>
                        <option value="custom">Custom (Client Specific)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="ai_price" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Price (TZS)</label>
                    <input type="number" id="ai_price" name="price" value="0" min="0" step="5000" class="form-input-modern text-sm">
                </div>
                <div>
                    <label for="ai_description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Description</label>
                    <input type="text" id="ai_description" name="description" placeholder="Aesthetic description..." class="form-input-modern text-sm">
                </div>
            </div>

            <!-- Submit Button & Processing Indicator -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                <div id="ai-loading-indicator" class="hidden flex items-center gap-2 text-xs font-bold text-purple-700">
                    <svg class="animate-spin h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Uploading artwork &amp; detecting zones... This will take a few seconds.</span>
                </div>
                <div class="ml-auto">
                    <button type="submit" id="ai-submit-btn" class="btn-primary text-sm shadow-md bg-slate-900 hover:bg-slate-800">
                        <span>Start AI Analysis &amp; Open Visual Editor</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Mode 2: Manual Creation Form Card (Hidden by default, shown via tab) -->
    <div id="manual-upload-section" class="hidden rounded-3xl border border-slate-200/80 bg-white shadow-xl overflow-hidden transition-all">
        <div class="border-b border-slate-100 bg-slate-50/70 px-8 py-5">
            <h2 class="text-base font-bold text-slate-900 font-heading">Manual Blade Template View Path</h2>
            <p class="text-xs text-slate-500 mt-0.5">Register an existing Blade file view under resources/views/invitations/templates/.</p>
        </div>

        <form action="{{ route('admin.templates.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Template Title <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Royal Emerald Nuptials" class="form-input-modern text-sm">
                </div>

                <div>
                    <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Slug Identifier</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" placeholder="e.g. royal-emerald" class="form-input-modern text-sm">
                </div>

                <div>
                    <label for="category" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Event Category <span class="text-rose-500">*</span></label>
                    <select id="category" name="category" class="form-input-modern text-sm">
                        @foreach($categories as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="template_type" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Template Tier <span class="text-rose-500">*</span></label>
                    <select id="template_type" name="template_type" class="form-input-modern text-sm">
                        <option value="standard">Standard (Free / Included)</option>
                        <option value="premium">Premium (Paid Package)</option>
                        <option value="custom">Custom Bespoke (Client Specific)</option>
                    </select>
                </div>

                <div>
                    <label for="price" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Price (TZS)</label>
                    <input type="number" id="price" name="price" value="0" min="0" step="5000" class="form-input-modern text-sm">
                </div>

                <div>
                    <label for="view_path" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Blade View Path</label>
                    <input type="text" id="view_path" name="view_path" placeholder="invitations.templates.luxury-gold.invitation" class="form-input-modern text-sm font-mono text-xs">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.templates.index') }}" class="btn-secondary text-sm">Cancel</a>
                <button type="submit" class="btn-primary text-sm shadow-md">
                    <span>Save Manual Template</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabAiBtn = document.getElementById('tab-ai-btn');
    const tabManualBtn = document.getElementById('tab-manual-btn');
    const aiSection = document.getElementById('ai-upload-section');
    const manualSection = document.getElementById('manual-upload-section');

    tabAiBtn.addEventListener('click', function() {
        tabAiBtn.classList.add('bg-white', 'text-purple-700', 'shadow-sm');
        tabAiBtn.classList.remove('text-slate-600');
        tabManualBtn.classList.remove('bg-white', 'text-purple-700', 'shadow-sm');
        tabManualBtn.classList.add('text-slate-600');
        aiSection.classList.remove('hidden');
        manualSection.classList.add('hidden');
    });

    tabManualBtn.addEventListener('click', function() {
        tabManualBtn.classList.add('bg-white', 'text-purple-700', 'shadow-sm');
        tabManualBtn.classList.remove('text-slate-600');
        tabAiBtn.classList.remove('bg-white', 'text-purple-700', 'shadow-sm');
        tabAiBtn.classList.add('text-slate-600');
        manualSection.classList.remove('hidden');
        aiSection.classList.add('hidden');
    });

    // Artwork preview
    const artworkInput = document.getElementById('artwork');
    const aiPlaceholder = document.getElementById('ai-upload-placeholder');
    const aiPreviewContainer = document.getElementById('ai-preview-container');
    const aiPreviewImg = document.getElementById('ai-preview-img');
    const aiFileName = document.getElementById('ai-file-name-display');
    const aiNameInput = document.getElementById('ai_name');

    if (artworkInput) {
        artworkInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    aiPreviewImg.src = evt.target.result;
                    aiPlaceholder.classList.add('hidden');
                    aiPreviewContainer.classList.remove('hidden');
                    aiFileName.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';

                    // Auto populate template name if empty
                    if (!aiNameInput.value) {
                        const cleanName = file.name.replace(/\.[^/.]+$/, "").replace(/[-_]/g, ' ');
                        aiNameInput.value = cleanName.charAt(0).toUpperCase() + cleanName.slice(1);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Submit loading indicator
    const aiForm = document.getElementById('ai-upload-form');
    const aiLoading = document.getElementById('ai-loading-indicator');
    const aiSubmitBtn = document.getElementById('ai-submit-btn');

    if (aiForm) {
        aiForm.addEventListener('submit', function() {
            aiLoading.classList.remove('hidden');
            aiSubmitBtn.disabled = true;
            aiSubmitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        });
    }
});
</script>
@endsection