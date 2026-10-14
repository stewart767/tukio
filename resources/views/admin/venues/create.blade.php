@extends('layouts.dashboard')

@section('title', 'Add New Venue - Admin')
@section('page-title', 'Create Venue Listing')

@section('content')
<div class="space-y-8 pb-12 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 rounded-full border border-indigo-200/60 bg-indigo-50/80 px-3 py-1 text-xs font-semibold text-indigo-700">
                <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                Venues &amp; Spaces Directory
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Add New Partner Venue</h1>
            <p class="text-sm text-slate-500">Create a new featured venue for the /venues page and event host discovery.</p>
        </div>
        <a href="{{ route('admin.venues.index') }}" class="btn-secondary text-xs sm:text-sm">
            &larr; Back to Venues
        </a>
    </div>

    <!-- Create Form -->
    <form action="{{ route('admin.venues.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left 2 Cols: Form Inputs -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Main Venue Information -->
                <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md p-7 sm:p-8 space-y-6">
                    <h2 class="text-base font-bold text-slate-900 font-heading border-b border-slate-100 pb-3">Venue Overview</h2>

                    <!-- Name & Slug -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Venue Name <span class="text-rose-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                                   class="form-input-modern text-sm @error('name') border-rose-300 ring-1 ring-rose-300 @enderror" 
                                   placeholder="e.g. Serena Crystal Grand Ballroom">
                            @error('name')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">URL Slug</label>
                            <input type="text" id="slug" name="slug" value="{{ old('slug') }}" 
                                   class="form-input-modern text-sm font-mono @error('slug') border-rose-300 ring-1 ring-rose-300 @enderror" 
                                   placeholder="e.g. serena-crystal-ballroom (auto-generated)">
                            @error('slug')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Category, Location & Capacity -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label for="category" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Category <span class="text-rose-500">*</span></label>
                            <input type="text" id="category" name="category" list="category-options" value="{{ old('category', 'Indoor Ballrooms') }}" required
                                   class="form-input-modern text-sm" placeholder="e.g. Indoor Ballrooms">
                            <datalist id="category-options">
                                <option value="Indoor Ballrooms">
                                <option value="Beach & Garden">
                                <option value="Conference Centers">
                                <option value="Luxury Resort & Villas">
                                <option value="Rooftop Terraces">
                                <option value="Private Banquet Hall">
                            </datalist>
                        </div>

                        <div>
                            <label for="location" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Location / City <span class="text-rose-500">*</span></label>
                            <input type="text" id="location" name="location" value="{{ old('location', 'Dar es Salaam') }}" required
                                   class="form-input-modern text-sm" placeholder="e.g. Dar es Salaam, Zanzibar">
                        </div>

                        <div>
                            <label for="capacity" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Guest Capacity</label>
                            <input type="text" id="capacity" name="capacity" value="{{ old('capacity', '500 – 1,500 Guests') }}" 
                                   class="form-input-modern text-sm" placeholder="e.g. 500 – 2,000 Guests">
                        </div>
                    </div>

                    <!-- Badge & Price Range -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="badge" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Top Category Badge</label>
                            <input type="text" id="badge" name="badge" value="{{ old('badge', 'Indoor Ballrooms') }}" 
                                   class="form-input-modern text-sm" placeholder="e.g. Indoor Ballrooms, Beach & Garden">
                        </div>

                        <div>
                            <label for="price_range" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Price Range / Estimate</label>
                            <input type="text" id="price_range" name="price_range" value="{{ old('price_range', 'From TZS 2,500,000 / event') }}" 
                                   class="form-input-modern text-sm" placeholder="e.g. From TZS 2,500,000 / event">
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Venue Description</label>
                        <textarea id="description" name="description" rows="3" 
                                  class="form-input-modern text-sm leading-relaxed" 
                                  placeholder="Describe the venue atmosphere, acoustic setup, private rooms, and amenities...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Amenities List -->
                    <div>
                        <label for="amenities_text" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Key Amenities &amp; Features (Comma or new line separated)</label>
                        <textarea id="amenities_text" name="amenities_text" rows="2" 
                                  class="form-input-modern text-xs font-mono" 
                                  placeholder="Climate Controlled, VIP Lounges, Stage Lighting, Audio/Visual Systems, Secure Parking">{{ old('amenities_text') }}</textarea>
                    </div>
                </div>

                <!-- Inquiry & Contact Setup -->
                <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md p-7 sm:p-8 space-y-6">
                    <h2 class="text-base font-bold text-slate-900 font-heading border-b border-slate-100 pb-3">Inquiry &amp; Lead Generation</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="inquiry_button_text" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Inquiry Button Label</label>
                            <input type="text" id="inquiry_button_text" name="inquiry_button_text" value="{{ old('inquiry_button_text', 'Inquire About Venue') }}" 
                                   class="form-input-modern text-sm" placeholder="e.g. Inquire About Ballrooms">
                        </div>

                        <div>
                            <label for="inquiry_subject" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Contact Form Subject Header</label>
                            <input type="text" id="inquiry_subject" name="inquiry_subject" value="{{ old('inquiry_subject') }}" 
                                   class="form-input-modern text-sm" placeholder="e.g. Venue Inquiry - Serena Ballroom">
                        </div>
                    </div>
                </div>

                <!-- Photo & Status Settings -->
                <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md p-7 sm:p-8 space-y-6">
                    <h2 class="text-base font-bold text-slate-900 font-heading border-b border-slate-100 pb-3">Photo &amp; Visibility</h2>

                    <!-- Image File Upload or URL -->
                    <div class="space-y-4">
                        <div>
                            <label for="image_file" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Upload Venue Photo</label>
                            <input type="file" id="image_file" name="image_file" accept="image/*" 
                                   class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                            <p class="mt-1 text-[11px] text-slate-400">Accepted: JPG, PNG, WebP (Max: 5MB). High resolution landscape photo.</p>
                        </div>

                        <div class="relative flex items-center justify-center">
                            <div class="border-t border-slate-200 w-full"></div>
                            <span class="bg-white px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 absolute">OR Enter Image URL</span>
                        </div>

                        <div>
                            <label for="image_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Image URL or Asset Path</label>
                            <input type="text" id="image_url" name="image_url" value="{{ old('image_url', 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80') }}" 
                                   class="form-input-modern text-xs font-mono" placeholder="https://images.unsplash.com/...">
                        </div>
                    </div>

                    <!-- Sort Order, Featured & Publish Status -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 pt-4 border-t border-slate-100">
                        <div>
                            <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Sort Order Priority</label>
                            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 10) }}" 
                                   class="form-input-modern text-sm" placeholder="e.g. 1, 2, 3">
                        </div>

                        <div>
                            <label class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-slate-100/80 transition-colors h-full">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" id="is_featured" name="is_featured" value="1" 
                                       {{ old('is_featured', '1') == '1' ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                                <div>
                                    <div class="text-xs font-bold text-slate-800">Featured Venue</div>
                                    <div class="text-[10px] text-slate-500">Top placement &amp; badge</div>
                                </div>
                            </label>
                        </div>

                        <div>
                            <label class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-slate-100/80 transition-colors h-full">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <div class="text-xs font-bold text-slate-800">Published Live</div>
                                    <div class="text-[10px] text-slate-500">Visible on /venues</div>
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
                                Live Venue Card Preview
                            </span>
                            <span class="text-[10px] text-slate-400">Simulation</span>
                        </div>

                        <!-- Simulated Card -->
                        <div class="bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden flex flex-col">
                            <!-- Image Banner -->
                            <div class="relative h-48 w-full bg-slate-900 overflow-hidden">
                                <div id="preview-image" class="w-full h-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1519167758481-83f550bb49b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80');"></div>
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent"></div>
                                
                                <div class="absolute top-3 left-3 right-3 flex items-center justify-between z-10">
                                    <span id="preview-badge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold backdrop-blur-md bg-white/90 text-slate-800 shadow-sm border border-white/50">
                                        Indoor Ballrooms
                                    </span>
                                </div>

                                <div class="absolute bottom-2.5 left-3.5 right-3.5 text-white z-10">
                                    <h3 id="preview-name" class="text-sm font-bold font-heading line-clamp-1">
                                        Serena Crystal Grand Ballroom
                                    </h3>
                                    <p class="text-[11px] text-slate-300 flex items-center gap-1.5 mt-0.5">
                                        <span id="preview-capacity">500 – 1,500 Guests</span>
                                        <span>&bull;</span>
                                        <span id="preview-location">Dar es Salaam</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-5 space-y-3">
                                <p id="preview-desc" class="text-slate-600 text-xs leading-relaxed line-clamp-3">
                                    Describe the venue atmosphere, acoustic setup, private rooms, and amenities...
                                </p>

                                <div id="preview-amenities" class="flex flex-wrap gap-1 pt-2 border-t border-slate-100">
                                    <span class="text-[10px] bg-slate-100 text-slate-700 px-2 py-0.5 rounded">Climate Controlled</span>
                                    <span class="text-[10px] bg-slate-100 text-slate-700 px-2 py-0.5 rounded">VIP Lounges</span>
                                </div>

                                <div class="pt-2">
                                    <div id="preview-button" class="btn-primary w-full text-center text-xs py-2 shadow-xs justify-center pointer-events-none">
                                        Inquire About Venue
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col gap-3">
                        <button type="submit" class="btn-primary text-sm py-3 justify-center shadow-lg shadow-indigo-500/25">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Create Venue Listing
                        </button>
                        <a href="{{ route('admin.venues.index') }}" class="btn-secondary text-xs py-2.5 justify-center">
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
    const nameInput = document.getElementById('name');
    const badgeInput = document.getElementById('badge');
    const locationInput = document.getElementById('location');
    const capacityInput = document.getElementById('capacity');
    const descInput = document.getElementById('description');
    const amenitiesInput = document.getElementById('amenities_text');
    const buttonInput = document.getElementById('inquiry_button_text');
    const imageUrlInput = document.getElementById('image_url');
    const imageFileInput = document.getElementById('image_file');

    const previewName = document.getElementById('preview-name');
    const previewBadge = document.getElementById('preview-badge');
    const previewLocation = document.getElementById('preview-location');
    const previewCapacity = document.getElementById('preview-capacity');
    const previewDesc = document.getElementById('preview-desc');
    const previewAmenities = document.getElementById('preview-amenities');
    const previewButton = document.getElementById('preview-button');
    const previewImage = document.getElementById('preview-image');

    function updateCardPreview() {
        if (previewName) previewName.textContent = nameInput.value || 'Venue Name';
        if (previewBadge) previewBadge.textContent = badgeInput.value || 'Featured Venue';
        if (previewLocation) previewLocation.textContent = locationInput.value || 'Location';
        if (previewCapacity) previewCapacity.textContent = capacityInput.value || 'Capacity';
        if (previewDesc) previewDesc.textContent = descInput.value || 'Description will appear here...';
        if (previewButton) previewButton.textContent = buttonInput.value || 'Inquire About Venue';

        if (previewAmenities && amenitiesInput) {
            const raw = amenitiesInput.value.split(/[\n,]+/).map(t => t.trim()).filter(t => t.length > 0);
            if (raw.length > 0) {
                previewAmenities.innerHTML = raw.map(t => `<span class="text-[10px] bg-slate-100 text-slate-700 px-2 py-0.5 rounded">${t}</span>`).join('');
            } else {
                previewAmenities.innerHTML = '<span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded">Climate Controlled</span>';
            }
        }

        if (imageUrlInput && imageUrlInput.value) {
            const path = imageUrlInput.value.startsWith('http') ? imageUrlInput.value : '{{ asset("") }}' + imageUrlInput.value;
            previewImage.style.backgroundImage = `url('${path}')`;
        }
    }

    [nameInput, badgeInput, locationInput, capacityInput, descInput, amenitiesInput, buttonInput, imageUrlInput].forEach(el => {
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

    updateCardPreview();
});
</script>
@endsection
