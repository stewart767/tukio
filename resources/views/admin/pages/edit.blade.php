@extends('layouts.dashboard')

@section('title', 'Edit Page - ' . $page->title)
@section('page-title', 'Edit CMS Page')

@section('content')
<div class="space-y-8 pb-12 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 rounded-full border border-indigo-200/60 bg-indigo-50/80 px-3 py-1 text-xs font-semibold text-indigo-700">
                <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                Page Editor
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Edit Page: {{ $page->title }}</h1>
            <p class="text-sm text-slate-500">Update page text, modular content sections, and search engine optimization (SEO) metadata.</p>
        </div>
        <div class="flex items-center gap-2">
            @if($page->slug !== 'homepage')
                <a href="{{ url('/' . $page->slug) }}" target="_blank" class="btn-secondary text-xs sm:text-sm">
                    View Live Page
                </a>
            @endif
            <a href="{{ route('admin.pages.index') }}" class="btn-secondary text-xs sm:text-sm">
                Back to Pages
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.pages.update', $page) }}" method="POST" class="space-y-8">
        @csrf
        @method('PATCH')

        <!-- Card 1: Core Content -->
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md overflow-hidden">
            <div class="border-b border-slate-100 bg-slate-50/70 px-8 py-5 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900 font-heading">Core Page Content &amp; Identity</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Primary headings and body copy</p>
                </div>
                <span class="font-mono text-xs text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100 font-bold">
                    /{{ $page->slug }}
                </span>
            </div>

            <div class="p-8 space-y-6">
                <!-- Title & Slug -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Page Title <span class="text-rose-500">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required 
                               class="form-input-modern text-sm @error('title') border-rose-300 ring-1 ring-rose-300 @enderror" 
                               placeholder="e.g. Terms of Service">
                        @error('title')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">URL Route Slug (Permanent)</label>
                        <input type="text" value="/{{ $page->slug }}" disabled
                               class="form-input-modern text-sm bg-slate-100 text-slate-500 cursor-not-allowed font-mono">
                    </div>
                </div>

                <!-- Page Content Body -->
                <div>
                    <label for="content" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Page Content Body</label>
                    <textarea id="content" name="content" rows="12" 
                              class="form-input-modern text-sm font-mono leading-relaxed @error('content') border-rose-300 ring-1 ring-rose-300 @enderror" 
                              placeholder="Enter HTML or plain text body...">{{ old('content', $page->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                    <p class="mt-1.5 text-[11px] text-slate-400">Supports standard HTML elements: &lt;p&gt;, &lt;h2&gt;, &lt;ul&gt;, &lt;strong&gt;, etc.</p>
                </div>

                <!-- Active Status Toggle -->
                <div class="pt-4 border-t border-slate-100">
                    <label class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-slate-100/80 transition-colors">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1" 
                               {{ old('is_active', $page->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <div class="text-xs font-bold text-slate-800">Page is Published &amp; Visible</div>
                            <div class="text-[11px] text-slate-500">When disabled, this page will return a draft or 404 response to public visitors.</div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Homepage Specific Sections -->
        @if($page->slug === 'homepage')
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md overflow-hidden">
            <div class="border-b border-slate-100 bg-slate-50/70 px-8 py-5">
                <h2 class="text-base font-bold text-slate-900 font-heading">Homepage Sections &amp; FAQs</h2>
                <p class="text-xs text-slate-500 mt-0.5">Customize interactive welcome cards, benefits grid, and questions</p>
            </div>

            <div class="p-8 space-y-6">
                <!-- Welcome Section -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Hero &amp; Welcome Headline</h3>
                    <div>
                        <label for="welcome_title" class="block text-xs font-bold text-slate-700 mb-1">Headline</label>
                        <input type="text" id="welcome_title" name="welcome_title" value="{{ old('welcome_title', $page->welcome_title) }}" 
                               class="form-input-modern text-sm" placeholder="e.g. Revolutionizing East African Events">
                    </div>
                    <div>
                        <label for="welcome_content" class="block text-xs font-bold text-slate-700 mb-1">Subheadline Description</label>
                        <textarea id="welcome_content" name="welcome_content" rows="2" 
                                  class="form-input-modern text-sm">{{ old('welcome_content', $page->welcome_content) }}</textarea>
                    </div>
                </div>

                <!-- Benefits Section -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Benefits &amp; Features Grid</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="benefits_title" class="block text-xs font-bold text-slate-700 mb-1">Section Title</label>
                            <input type="text" id="benefits_title" name="benefits_title" value="{{ old('benefits_title', $page->benefits_title) }}" 
                                   class="form-input-modern text-sm">
                        </div>
                        <div>
                            <label for="benefits_subtitle" class="block text-xs font-bold text-slate-700 mb-1">Section Subtitle</label>
                            <input type="text" id="benefits_subtitle" name="benefits_subtitle" value="{{ old('benefits_subtitle', $page->benefits_subtitle) }}" 
                                   class="form-input-modern text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="benefits_content" class="block text-xs font-bold text-slate-700 mb-1">Features JSON Array</label>
                        <textarea id="benefits_content" name="benefits_content" rows="4" 
                                  class="form-input-modern text-sm font-mono text-xs">{{ old('benefits_content', $page->benefits_content) }}</textarea>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">FAQ Accordion Section</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="faq_title" class="block text-xs font-bold text-slate-700 mb-1">FAQ Section Title</label>
                            <input type="text" id="faq_title" name="faq_title" value="{{ old('faq_title', $page->faq_title) }}" 
                                   class="form-input-modern text-sm">
                        </div>
                        <div>
                            <label for="faq_subtitle" class="block text-xs font-bold text-slate-700 mb-1">FAQ Subtitle</label>
                            <input type="text" id="faq_subtitle" name="faq_subtitle" value="{{ old('faq_subtitle', $page->faq_subtitle) }}" 
                                   class="form-input-modern text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="faq_content" class="block text-xs font-bold text-slate-700 mb-1">FAQ Items (JSON format)</label>
                        <textarea id="faq_content" name="faq_content" rows="4" 
                                  class="form-input-modern text-sm font-mono text-xs">{{ old('faq_content', $page->faq_content) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Card 2: SEO Meta & Google Snippet Preview -->
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md overflow-hidden">
            <div class="border-b border-slate-100 bg-slate-50/70 px-8 py-5">
                <h2 class="text-base font-bold text-slate-900 font-heading">Search Engine Optimization (SEO)</h2>
                <p class="text-xs text-slate-500 mt-0.5">Control how this page appears in Google search engine indexing</p>
            </div>

            <div class="p-8 space-y-6">
                <!-- Google Snippet Simulation -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-1 font-sans">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Google Search Preview</span>
                    <div class="text-xs text-slate-700 font-mono">{{ url('/') }}/{{ $page->slug }}</div>
                    <div class="text-base font-medium text-blue-700 hover:underline cursor-pointer" id="seo-preview-title">
                        {{ $page->meta_title ?: $page->title . ' - Tukio 360' }}
                    </div>
                    <div class="text-xs text-slate-600 line-clamp-2" id="seo-preview-desc">
                        {{ $page->meta_description ?: 'Experience seamless digital event invitations and luxury transport logistics with Tukio 360.' }}
                    </div>
                </div>

                <div>
                    <label for="meta_title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Meta Title</label>
                    <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" 
                           class="form-input-modern text-sm @error('meta_title') border-rose-300 ring-1 ring-rose-300 @enderror" 
                           placeholder="Recommended: 50 - 60 characters">
                    @error('meta_title')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="meta_description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" rows="3" 
                              class="form-input-modern text-sm @error('meta_description') border-rose-300 ring-1 ring-rose-300 @enderror" 
                              placeholder="Recommended: 150 - 160 characters summary...">{{ old('meta_description', $page->meta_description) }}</textarea>
                    @error('meta_description')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('admin.pages.index') }}" class="btn-secondary text-sm">Cancel</a>
            <button type="submit" class="btn-primary text-sm shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Page Content
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('meta_title');
    const mainTitleInput = document.getElementById('title');
    const descInput = document.getElementById('meta_description');
    const previewTitle = document.getElementById('seo-preview-title');
    const previewDesc = document.getElementById('seo-preview-desc');

    function updatePreview() {
        if (previewTitle) {
            previewTitle.textContent = (titleInput && titleInput.value) || (mainTitleInput && mainTitleInput.value) || 'Page Title - Tukio 360';
        }
        if (previewDesc) {
            previewDesc.textContent = (descInput && descInput.value) || 'Experience seamless digital event invitations and luxury transport logistics with Tukio 360.';
        }
    }

    if (titleInput) titleInput.addEventListener('input', updatePreview);
    if (mainTitleInput) mainTitleInput.addEventListener('input', updatePreview);
    if (descInput) descInput.addEventListener('input', updatePreview);
});
</script>
@endsection
