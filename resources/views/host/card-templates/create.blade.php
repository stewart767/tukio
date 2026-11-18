@extends('layouts.host')

@section('title', 'Upload Card Template')
@section('page-title', 'Upload Card Artwork')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-fade-in">

    <!-- Top Header Bar -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xl shadow-slate-200/30 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-50 border border-purple-100 text-xs font-semibold text-purple-700 mb-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>AI Visual Layout Engine</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black font-heading text-slate-900 tracking-tight">Upload Custom Card Artwork</h1>
            <p class="text-xs sm:text-sm text-slate-500">Upload your card flyer or graphic. The visual editor will let you position each guest's name, event details, and gate QR code pass.</p>
        </div>

        <a href="{{ route('host.card-templates.index') }}" class="modern-button modern-button-ghost shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Back to Studio</span>
        </a>
    </div>

    <!-- Error Alerts -->
    @if($errors->any())
        <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 text-rose-800 text-xs sm:text-sm space-y-1">
            <div class="font-bold flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Please correct the following errors:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Upload Form Card -->
    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/90 shadow-xl shadow-slate-200/30">
        <form action="{{ route('host.card-templates.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="upload-card-form">
            @csrf

            <!-- Section 1: Artwork File Upload -->
            <div class="space-y-3">
                <label class="block text-sm font-bold text-slate-900">
                    Step 1: Choose Card Artwork Image <span class="text-rose-500">*</span>
                </label>

                <div id="drop-zone" class="relative rounded-3xl border-2 border-dashed border-slate-300 hover:border-purple-400 bg-slate-50/50 hover:bg-purple-50/30 p-8 sm:p-12 text-center transition-all cursor-pointer group">
                    <input type="file" name="artwork" id="artwork-input" accept="image/jpeg,image/png,image/webp" class="sr-only" required>

                    <!-- Empty state prompt -->
                    <div id="upload-prompt" class="space-y-4">
                        <div class="w-16 h-16 rounded-2xl bg-white shadow-md border border-slate-200 text-purple-600 flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">
                                Click to select image or drag and drop your artwork file here
                            </p>
                            <p class="text-xs text-slate-400 mt-1">
                                High-resolution PNG, JPG, or WebP &bull; Maximum file size 15 MB
                            </p>
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-slate-600 text-xs font-semibold shadow-xs border border-slate-200">
                            <span>Recommended: Portrait 1200 &times; 1600 px or higher</span>
                        </div>
                    </div>

                    <!-- Selected image preview -->
                    <div id="upload-preview-container" class="hidden space-y-4">
                        <div class="relative max-w-xs mx-auto rounded-2xl overflow-hidden shadow-xl border-2 border-purple-500 bg-slate-900 aspect-[3/4] flex items-center justify-center">
                            <img id="upload-preview" src="#" alt="Artwork Preview" class="w-full h-full object-contain">
                            <button type="button" id="btn-change-image" class="absolute top-2 right-2 p-1.5 rounded-xl bg-slate-900/80 text-white hover:bg-rose-600 transition-colors shadow">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <p id="file-meta" class="text-xs font-mono font-bold text-purple-700"></p>
                    </div>
                </div>
            </div>

            <!-- Section 2: Metadata Fields -->
            <div class="space-y-6 pt-4 border-t border-slate-100">
                <label class="block text-sm font-bold text-slate-900">
                    Step 2: Template Information
                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Card Name / Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               placeholder="e.g. Our Royal Wedding Invitation"
                               value="{{ old('name', ($event ? $event->title . ' Invitation' : 'Our Event Card')) }}"
                               class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm font-medium text-slate-900">
                        <p class="text-[11px] text-slate-400">Give your template a clear name so you can recognize it.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="category" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Event Category
                        </label>
                        <select name="category" id="category"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm font-medium text-slate-900">
                            @php
                                $currentCat = old('category', $event?->category ?: ($host->event_type ?: 'wedding'));
                            @endphp
                            <option value="wedding" {{ $currentCat === 'wedding' ? 'selected' : '' }}>Wedding</option>
                            <option value="birthday" {{ $currentCat === 'birthday' ? 'selected' : '' }}>Birthday</option>
                            <option value="kitchen_party" {{ $currentCat === 'kitchen_party' ? 'selected' : '' }}>Kitchen Party</option>
                            <option value="send_off" {{ $currentCat === 'send_off' ? 'selected' : '' }}>Send Off</option>
                            <option value="corporate" {{ $currentCat === 'corporate' ? 'selected' : '' }}>Corporate Event</option>
                            <option value="traditional" {{ $currentCat === 'traditional' ? 'selected' : '' }}>Traditional Ceremony</option>
                            <option value="other" {{ $currentCat === 'other' ? 'selected' : '' }}>Other Celebration</option>
                        </select>
                        <p class="text-[11px] text-slate-400">Assists AI in placing appropriate celebration headers.</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Notes or Description (Optional)
                    </label>
                    <textarea name="description" id="description" rows="2"
                              placeholder="e.g. Gold foil floral wedding card with space for guest name and table number"
                              class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm text-slate-900">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Helpful Tips Card -->
            <div class="rounded-2xl bg-indigo-50/70 border border-indigo-100 p-5 flex items-start gap-4">
                <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-xs text-indigo-900 space-y-1">
                    <p class="font-bold">What happens next?</p>
                    <p class="text-indigo-800/80 leading-relaxed">
                        Once uploaded, our layout engine automatically scans the card and sets up draggable bounding boxes for the <strong>Guest Name</strong>, <strong>Event Title</strong>, <strong>Date</strong>, <strong>Venue</strong>, and <strong>QR Pass</strong>. You will be taken immediately to the Visual Editor to tweak fonts, colors, and coordinates before sending.
                    </p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex items-center justify-end gap-4 border-t border-slate-100">
                <a href="{{ route('host.card-templates.index') }}" class="px-5 py-3 rounded-2xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all">
                    Cancel
                </a>
                <button type="submit" id="btn-submit" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-xs font-black uppercase tracking-wider text-white shadow-xl shadow-purple-600/30 transition-all">
                    <span>Upload &amp; Launch Visual Editor</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('drop-zone');
    const artworkInput = document.getElementById('artwork-input');
    const uploadPrompt = document.getElementById('upload-prompt');
    const previewContainer = document.getElementById('upload-preview-container');
    const uploadPreview = document.getElementById('upload-preview');
    const fileMeta = document.getElementById('file-meta');
    const btnChange = document.getElementById('btn-change-image');
    const submitBtn = document.getElementById('btn-submit');
    const form = document.getElementById('upload-card-form');

    dropZone.addEventListener('click', function(e) {
        if (e.target !== btnChange) {
            artworkInput.click();
        }
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.add('border-purple-600', 'bg-purple-50');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-purple-600', 'bg-purple-50');
        });
    });

    dropZone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files && files.length > 0) {
            artworkInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    artworkInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            handleFileSelect(this.files[0]);
        }
    });

    btnChange.addEventListener('click', function(e) {
        e.stopPropagation();
        artworkInput.value = '';
        uploadPreview.src = '#';
        previewContainer.classList.add('hidden');
        uploadPrompt.classList.remove('hidden');
    });

    function handleFileSelect(file) {
        if (!file.type.match('image.*')) {
            alert('Please select a valid image file (PNG, JPG, or WebP).');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            uploadPreview.src = e.target.result;
            fileMeta.textContent = file.name + ' (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';
            uploadPrompt.classList.add('hidden');
            previewContainer.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Analyzing Layout &amp; Uploading...</span>
        `;
    });
});
</script>
@endsection

