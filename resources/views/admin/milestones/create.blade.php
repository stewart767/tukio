@extends('layouts.dashboard')

@section('title', 'Add Milestone Card - Admin')
@section('page-title', 'Create Milestone Occasion')

@section('content')
<div class="space-y-8 pb-12 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 rounded-full border border-indigo-200/60 bg-indigo-50/80 px-3 py-1 text-xs font-semibold text-indigo-700">
                <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                Tailored for Every Milestone
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Add Milestone Category Card</h1>
            <p class="text-sm text-slate-500">Create a new milestone occasion card for the landing page grid and categories catalog.</p>
        </div>
        <a href="{{ route('admin.milestones.index') }}" class="btn-secondary text-xs sm:text-sm">
            &larr; Back to Milestones
        </a>
    </div>

    <!-- Create Form -->
    <form action="{{ route('admin.milestones.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left 2 Cols: Form Inputs -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Main Card Details -->
                <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md p-7 sm:p-8 space-y-6">
                    <h2 class="text-base font-bold text-slate-900 font-heading border-b border-slate-100 pb-3">Occasion Details</h2>

                    <!-- Title & Slug -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Occasion Title <span class="text-rose-500">*</span></label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required 
                                   class="form-input-modern text-sm @error('title') border-rose-300 ring-1 ring-rose-300 @enderror" 
                                   placeholder="e.g. Anniversaries &amp; Jubilees">
                            @error('title')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Category Key / Slug</label>
                            <input type="text" id="slug" name="slug" value="{{ old('slug') }}" 
                                   class="form-input-modern text-sm font-mono @error('slug') border-rose-300 ring-1 ring-rose-300 @enderror" 
                                   placeholder="e.g. anniversary (auto-generated if blank)">
                            @error('slug')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Badge, Tagline & Icon -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label for="badge" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Top Badge Pill</label>
                            <input type="text" id="badge" name="badge" value="{{ old('badge', 'Special Occasion') }}" 
                                   class="form-input-modern text-sm" placeholder="e.g. Most Popular, Corporate, Gala">
                        </div>

                        <div>
                            <label for="tagline" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Banner Tagline</label>
                            <input type="text" id="tagline" name="tagline" value="{{ old('tagline', 'Celebration Nights') }}" 
                                   class="form-input-modern text-sm" placeholder="e.g. Royal Celebrations">
                        </div>

                        <div>
                            <input type="hidden" id="icon" name="icon" value="">
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Description Paragraph</label>
                        <textarea id="description" name="description" rows="3" 
                                  class="form-input-modern text-sm leading-relaxed" 
                                  placeholder="Describe the tools, invitations, and features tailored for this event type...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Feature Tags -->
                    <div>
                        <label for="tags_text" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Feature Tags (Comma or new line separated)</label>
                        <textarea id="tags_text" name="tags_text" rows="2" 
                                  class="form-input-modern text-xs font-mono" 
                                  placeholder="Digital Cards, RSVP Seating, Pledge Tracker, Map Directions">{{ old('tags_text') }}</textarea>
                        <p class="mt-1 text-[11px] text-slate-400">These will appear as rounded pill tags on the bottom of the card.</p>
                    </div>
                </div>

                <!-- Theme Color & Button Settings -->
                <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md p-7 sm:p-8 space-y-6">
                    <h2 class="text-base font-bold text-slate-900 font-heading border-b border-slate-100 pb-3">Styling &amp; Call-to-Action</h2>

                    <!-- Theme Color Radios -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Accent Theme Color</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @php
                                $themes = [
                                    'purple' => ['label' => 'Purple', 'bg' => 'bg-purple-600', 'border' => 'border-purple-300'],
                                    'blue' => ['label' => 'Corporate Blue', 'bg' => 'bg-blue-600', 'border' => 'border-blue-300'],
                                    'slate' => ['label' => 'Slate Luxury', 'bg' => 'bg-slate-700', 'border' => 'border-slate-300'],
                                    'teal' => ['label' => 'Teal / Cyan', 'bg' => 'bg-teal-500', 'border' => 'border-teal-300'],
                                    'amber' => ['label' => 'Amber / Gold', 'bg' => 'bg-amber-500', 'border' => 'border-amber-300'],
                                    'violet' => ['label' => 'Violet / Music', 'bg' => 'bg-violet-600', 'border' => 'border-violet-300'],
                                    'indigo' => ['label' => 'Indigo Modern', 'bg' => 'bg-indigo-600', 'border' => 'border-indigo-300'],
                                ];
                            @endphp

                            @foreach($themes as $themeKey => $theme)
                            <label class="flex items-center gap-2.5 p-3 rounded-2xl border cursor-pointer hover:bg-slate-50 transition-colors {{ old('theme_color', 'purple') === $themeKey ? 'border-indigo-600 bg-indigo-50/50' : 'border-slate-200' }}">
                                <input type="radio" name="theme_color" value="{{ $themeKey }}" {{ old('theme_color', 'purple') === $themeKey ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-3.5 h-3.5 rounded-full {{ $theme['bg'] }}"></span>
                                    <span class="text-xs font-semibold text-slate-700">{{ $theme['label'] }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Button Text & URL -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="button_text" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Action Button Label</label>
                            <input type="text" id="button_text" name="button_text" value="{{ old('button_text', 'Explore Features') }}" 
                                   class="form-input-modern text-sm" placeholder="e.g. Explore Wedding Features">
                        </div>

                        <div>
                            <label for="button_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Action Destination URL</label>
                            <input type="text" id="button_url" name="button_url" value="{{ old('button_url') }}" 
                                   class="form-input-modern text-sm font-mono" placeholder="categories?cat=wedding (or custom link)">
                        </div>
                    </div>
                </div>

                <!-- Media & Display Options -->
                <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md p-7 sm:p-8 space-y-6">
                    <h2 class="text-base font-bold text-slate-900 font-heading border-b border-slate-100 pb-3">Banner Photo &amp; Publishing</h2>

                    <!-- Image File Upload or URL -->
                    <div class="space-y-4">
                        <div>
                            <label for="image_file" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Upload Banner Image File</label>
                            <input type="file" id="image_file" name="image_file" accept="image/*" 
                                   class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                            <p class="mt-1 text-[11px] text-slate-400">Accepted: JPG, PNG, WebP (Max: 5MB). Landscape orientation recommended.</p>
                        </div>

                        <div class="relative flex items-center justify-center">
                            <div class="border-t border-slate-200 w-full"></div>
                            <span class="bg-white px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 absolute">OR Enter Image URL / Asset Path</span>
                        </div>

                        <div>
                            <label for="image_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Image URL or Asset Path</label>
                            <input type="text" id="image_url" name="image_url" value="{{ old('image_url', 'media/categories/wedding.jpg') }}" 
                                   class="form-input-modern text-xs font-mono" placeholder="e.g. media/categories/wedding.jpg or https://images.unsplash.com/...">
                        </div>
                    </div>

                    <!-- Sort Order & Publish Status -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-4 border-t border-slate-100">
                        <div>
                            <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Sort Order Priority</label>
                            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 10) }}" 
                                   class="form-input-modern text-sm" placeholder="e.g. 1, 2, 3">
                            <p class="mt-1 text-[11px] text-slate-400">Lower numbers appear first on the landing page.</p>
                        </div>

                        <div class="flex items-center">
                            <label class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-slate-100/80 transition-colors w-full">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <div class="text-xs font-bold text-slate-800">Card is Published</div>
                                    <div class="text-[11px] text-slate-500">Visible on homepage and categories</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right 1 Col: Live Preview Box -->
            <div class="space-y-6">
                <div class="sticky top-24 space-y-4">
                    <div class="rounded-3xl border border-indigo-200/80 bg-gradient-to-br from-indigo-50/50 via-white to-purple-50/40 p-5 shadow-lg backdrop-blur-md">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-700 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                                Live Card Preview
                            </span>
                            <span class="text-[10px] text-slate-400">Simulation</span>
                        </div>

                        <!-- Simulated Card -->
                        <div class="bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden flex flex-col">
                            <!-- Image Banner -->
                            <div class="relative h-44 w-full bg-slate-900 overflow-hidden">
                                <div id="preview-image" class="w-full h-full bg-cover bg-center" style="background-image: url('{{ asset('media/categories/wedding.jpg') }}');"></div>
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                                
                                <div class="absolute top-3 left-3 right-3 flex items-center justify-between z-10">
                                    <span id="preview-badge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold backdrop-blur-md bg-white/90 text-purple-800 shadow-sm border border-white/50">
                                        Most Popular
                                    </span>
                                    <div id="preview-icon" class="w-8 h-8 rounded-xl bg-white/95 backdrop-blur-md flex items-center justify-center text-indigo-600 shadow-sm border border-white/60">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                    </div>
                                </div>

                                <div class="absolute bottom-2.5 left-3.5 right-3.5 z-10">
                                    <span id="preview-tagline" class="text-[10px] font-semibold text-white uppercase tracking-wider flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span> Celebration Nights
                                    </span>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-5 space-y-3">
                                <div>
                                    <h3 id="preview-title" class="text-base font-bold text-slate-900">
                                        Anniversaries &amp; Jubilees
                                    </h3>
                                    <p id="preview-desc" class="text-slate-500 text-[11px] leading-relaxed mt-1 line-clamp-3">
                                        Describe the tools, invitations, and features tailored for this event type...
                                    </p>
                                </div>

                                <div id="preview-tags" class="flex flex-wrap gap-1 pt-2 border-t border-slate-100">
                                    <span class="text-[10px] bg-slate-100 text-slate-700 px-2 py-0.5 rounded">Digital Cards</span>
                                    <span class="text-[10px] bg-slate-100 text-slate-700 px-2 py-0.5 rounded">RSVP Seating</span>
                                </div>

                                <div class="pt-2 flex items-center justify-between text-xs font-semibold text-indigo-600">
                                    <span id="preview-button">Explore Features</span>
                                    <span class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">&rarr;</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col gap-3">
                        <button type="submit" class="btn-primary text-sm py-3 justify-center shadow-lg shadow-indigo-500/25">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Create Milestone Card
                        </button>
                        <a href="{{ route('admin.milestones.index') }}" class="btn-secondary text-xs py-2.5 justify-center">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const badgeInput = document.getElementById('badge');
    const taglineInput = document.getElementById('tagline');
    const iconInput = document.getElementById('icon');
    const descInput = document.getElementById('description');
    const tagsInput = document.getElementById('tags_text');
    const buttonInput = document.getElementById('button_text');
    const imageUrlInput = document.getElementById('image_url');
    const imageFileInput = document.getElementById('image_file');

    const previewTitle = document.getElementById('preview-title');
    const previewBadge = document.getElementById('preview-badge');
    const previewTagline = document.getElementById('preview-tagline');
    const previewIcon = document.getElementById('preview-icon');
    const previewDesc = document.getElementById('preview-desc');
    const previewTags = document.getElementById('preview-tags');
    const previewButton = document.getElementById('preview-button');
    const previewImage = document.getElementById('preview-image');

    function updateCardPreview() {
        if (previewTitle) previewTitle.textContent = titleInput.value || 'Milestone Title';
        if (previewBadge) previewBadge.textContent = badgeInput.value || 'Featured';
        if (previewTagline) previewTagline.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-indigo-400 inline-block mr-1"></span> ' + (taglineInput.value || 'Celebration');
        if (previewIcon) previewIcon.textContent = iconInput.value || '';
        if (previewDesc) previewDesc.textContent = descInput.value || 'Description will appear here...';
        if (previewButton) previewButton.textContent = buttonInput.value || 'Explore Features';

        if (previewTags && tagsInput) {
            const raw = tagsInput.value.split(/[\n,]+/).map(t => t.trim()).filter(t => t.length > 0);
            if (raw.length > 0) {
                previewTags.innerHTML = raw.map(t => `<span class="text-[10px] bg-slate-100 text-slate-700 px-2 py-0.5 rounded">${t}</span>`).join('');
            } else {
                previewTags.innerHTML = '<span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded">Tag 1</span>';
            }
        }

        if (imageUrlInput && imageUrlInput.value) {
            const path = imageUrlInput.value.startsWith('http') ? imageUrlInput.value : '{{ asset("") }}' + imageUrlInput.value;
            previewImage.style.backgroundImage = `url('${path}')`;
        }
    }

    [titleInput, badgeInput, taglineInput, iconInput, descInput, tagsInput, buttonInput, imageUrlInput].forEach(el => {
        if (el) el.addEventListener('input', updateCardPreview);
    });

    if (imageFileInput) {
        imageFileInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(re) {
                    previewImage.style.backgroundImage = `url('${re.target.result}')`;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }

    // Emoji Picker clicks
    document.querySelectorAll('.emoji-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (iconInput) {
                iconInput.value = this.dataset.emoji;
                updateCardPreview();
            }
        });
    });

    updateCardPreview();
});
</script>
@endsection
