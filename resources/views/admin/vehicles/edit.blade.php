@extends('layouts.dashboard')

@section('title', 'Edit Vehicle - ' . $vehicle->name)
@section('page-title', 'Edit Fleet Vehicle')

@section('content')
<div class="space-y-8 pb-12 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 rounded-full border border-indigo-200/60 bg-indigo-50/80 px-3 py-1 text-xs font-semibold text-indigo-700">
                <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                Fleet Specifications Update
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Edit Vehicle: {{ $vehicle->name }}</h1>
            <p class="text-sm text-slate-500">Adjust charter rates, passenger capacity, amenities, and catalog status.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('car-hire.show', $vehicle->slug) }}" target="_blank" class="btn-secondary text-xs sm:text-sm">
                View Public Page
            </a>
            <a href="{{ route('admin.vehicles.index') }}" class="btn-secondary text-xs sm:text-sm">
                Back to Fleet
            </a>
        </div>
    </div>

    <!-- Edit Vehicle Form Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md overflow-hidden">
        <div class="border-b border-slate-100 bg-slate-50/70 px-8 py-5 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900 font-heading">Vehicle Specifications &amp; Pricing</h2>
                <p class="text-xs text-slate-500 mt-0.5">Update technical data, pricing, and inclusions</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $vehicle->is_available ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ $vehicle->is_available ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                {{ $vehicle->is_available ? 'Available for Booking' : 'Unavailable / In Maintenance' }}
            </span>
        </div>

        <form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            @method('PUT')

            <!-- Section 1: Identification & Category -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">1. Vehicle Model &amp; Category</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Vehicle Name / Model <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $vehicle->name) }}"
                               class="form-input-modern text-sm @error('name') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('name')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Category Segment <span class="text-rose-500">*</span></label>
                        <select name="category" id="category" required
                                class="form-input-modern text-sm @error('category') border-rose-300 ring-1 ring-rose-300 @enderror">
                            <option value="luxury_bride_groom" {{ old('category', $vehicle->category) === 'luxury_bride_groom' ? 'selected' : '' }}>Luxury Bride &amp; Groom (Flagship)</option>
                            <option value="guest_coaster_bus" {{ old('category', $vehicle->category) === 'guest_coaster_bus' ? 'selected' : '' }}>Guest Coasters &amp; Buses (Group Transit)</option>
                            <option value="vip_suv_convoy" {{ old('category', $vehicle->category) === 'vip_suv_convoy' ? 'selected' : '' }}>VIP SUV &amp; Convoy (Escort &amp; Executive)</option>
                            <option value="host_logistics" {{ old('category', $vehicle->category) === 'host_logistics' ? 'selected' : '' }}>Host &amp; Event Logistics (Utility)</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label for="brand" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Brand / Make</label>
                        <input type="text" name="brand" id="brand" value="{{ old('brand', $vehicle->brand) }}" placeholder="e.g. Mercedes-Benz"
                               class="form-input-modern text-sm">
                    </div>
                    <div>
                        <label for="model_year" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Model Year</label>
                        <input type="text" name="model_year" id="model_year" value="{{ old('model_year', $vehicle->model_year) }}" placeholder="e.g. 2024"
                               class="form-input-modern text-sm">
                    </div>
                    <div>
                        <label for="color" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Exterior Color</label>
                        <input type="text" name="color" id="color" value="{{ old('color', $vehicle->color) }}" placeholder="e.g. Pearl White"
                               class="form-input-modern text-sm">
                    </div>
                </div>
            </div>

            <!-- Section 2: Technical Specifications -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">2. Technical &amp; Capacity Specifications</h3>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                    <div>
                        <label for="seating_capacity" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Passenger Seats <span class="text-rose-500">*</span></label>
                        <input type="number" name="seating_capacity" id="seating_capacity" required min="1" max="100" value="{{ old('seating_capacity', $vehicle->seating_capacity) }}"
                               class="form-input-modern text-sm @error('seating_capacity') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('seating_capacity')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="luggage_capacity" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Luggage Capacity</label>
                        <input type="text" name="luggage_capacity" id="luggage_capacity" value="{{ old('luggage_capacity', $vehicle->luggage_capacity) }}"
                               class="form-input-modern text-sm">
                    </div>

                    <div>
                        <label for="transmission" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Transmission <span class="text-rose-500">*</span></label>
                        <select name="transmission" id="transmission" class="form-input-modern text-sm">
                            <option value="Automatic" {{ old('transmission', $vehicle->transmission) === 'Automatic' ? 'selected' : '' }}>Automatic</option>
                            <option value="Manual" {{ old('transmission', $vehicle->transmission) === 'Manual' ? 'selected' : '' }}>Manual</option>
                        </select>
                    </div>

                    <div>
                        <label for="fuel_type" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Fuel Type <span class="text-rose-500">*</span></label>
                        <select name="fuel_type" id="fuel_type" class="form-input-modern text-sm">
                            <option value="Petrol" {{ old('fuel_type', $vehicle->fuel_type) === 'Petrol' ? 'selected' : '' }}>Petrol</option>
                            <option value="Diesel" {{ old('fuel_type', $vehicle->fuel_type) === 'Diesel' ? 'selected' : '' }}>Diesel</option>
                            <option value="Hybrid" {{ old('fuel_type', $vehicle->fuel_type) === 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                            <option value="Electric" {{ old('fuel_type', $vehicle->fuel_type) === 'Electric' ? 'selected' : '' }}>Electric</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 3: Pricing & Inclusions -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">3. Pricing &amp; Operating Zone</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label for="price_per_day" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Daily Rate (TZS) <span class="text-rose-500">*</span></label>
                        <input type="number" name="price_per_day" id="price_per_day" required step="1000" min="0" value="{{ old('price_per_day', (int)$vehicle->price_per_day) }}"
                               class="form-input-modern text-sm font-mono @error('price_per_day') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('price_per_day')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price_per_hour" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Hourly Rate (Optional TZS)</label>
                        <input type="number" name="price_per_hour" id="price_per_hour" step="1000" min="0" value="{{ old('price_per_hour', (int)$vehicle->price_per_hour) }}"
                               class="form-input-modern text-sm font-mono">
                    </div>

                    <div>
                        <label for="location" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Base Operational Area</label>
                        <input type="text" name="location" id="location" value="{{ old('location', $vehicle->location) }}"
                               class="form-input-modern text-sm">
                    </div>
                </div>

                <!-- Inclusion Badges -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
                    <label class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-slate-100/80 transition-colors">
                        <input type="checkbox" name="has_driver" value="1" {{ $vehicle->has_driver ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <div class="text-xs font-bold text-slate-800">Professional Chauffeur</div>
                            <div class="text-[10px] text-slate-500">Uniformed executive driver included.</div>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-slate-100/80 transition-colors">
                        <input type="checkbox" name="has_fuel_included" value="1" {{ $vehicle->has_fuel_included ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <div class="text-xs font-bold text-slate-800">Event Fuel Included</div>
                            <div class="text-[10px] text-slate-500">Standard intra-city event coverage.</div>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-slate-100/80 transition-colors">
                        <input type="checkbox" name="has_ribbon_decoration" value="1" {{ $vehicle->has_ribbon_decoration ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <div class="text-xs font-bold text-slate-800">Wedding Ribbon Decor</div>
                            <div class="text-[10px] text-slate-500">Silk ribbon &amp; floral bonnet setup.</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Section 4: Media & Description -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">4. Media, Highlights &amp; Features</h3>
                <div class="space-y-6">
                    <!-- Photo Upload Zone & Current/New Preview -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                        <div class="lg:col-span-2 space-y-4">
                            <div>
                                <label for="image_file" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Replace Vehicle Photo File</label>
                                <input type="file" id="image_file" name="image_file" accept="image/*"
                                       class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-800 hover:file:bg-slate-200 cursor-pointer border border-slate-200 rounded-2xl p-1 bg-slate-50/50">
                                <p class="mt-1 text-[11px] text-slate-400">Accepted: JPG, PNG, WebP (Max: 5MB). Leave empty to keep existing photo.</p>
                                @error('image_file')
                                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="relative flex items-center justify-center">
                                <div class="border-t border-slate-200 w-full"></div>
                                <span class="bg-white px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 absolute">OR Image URL / Asset Path</span>
                            </div>

                            <div>
                                <label for="image_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Direct Image Link</label>
                                <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $vehicle->image_url) }}" placeholder="https://..."
                                       class="form-input-modern text-xs font-mono">
                            </div>
                        </div>

                        <!-- Current / Live Vehicle Photo Preview Box -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center flex flex-col items-center justify-center min-h-[180px]">
                            <span id="preview-badge-txt" class="text-[10px] uppercase font-bold text-slate-400 block mb-2">Current Photo</span>
                            <div class="w-full h-36 rounded-xl overflow-hidden bg-slate-900 border border-slate-200 shadow-sm relative">
                                <img id="vehicle-preview-img" src="{{ $vehicle->image_url_formatted }}" 
                                     alt="{{ $vehicle->name }}" class="w-full h-full object-cover">
                            </div>
                            <span id="vehicle-filename-txt" class="text-[11px] text-slate-500 font-mono mt-2 truncate max-w-full">
                                {{ $vehicle->image_url ? basename($vehicle->image_url) : 'No image' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        <div>
                            <label for="features_text" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Key Highlights (1 per line)</label>
                            @php
                                $featuresFormatted = is_array($vehicle->features) ? implode("\n", $vehicle->features) : $vehicle->features;
                            @endphp
                            <textarea name="features_text" id="features_text" rows="4"
                                      class="form-input-modern text-sm font-mono">{{ old('features_text', $featuresFormatted) }}</textarea>
                        </div>

                        <div>
                            <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Public Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="form-input-modern text-sm">{{ old('description', $vehicle->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 5: Visibility Switches -->
            <div class="pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="flex items-center gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-slate-100/80 transition-colors">
                    <input type="checkbox" name="is_available" value="1" {{ $vehicle->is_available ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <div>
                        <div class="text-xs font-bold text-slate-800">Immediately Available for Booking</div>
                        <div class="text-[11px] text-slate-500">Vehicle will appear in the public car hire booking catalog.</div>
                    </div>
                </label>

                <label class="flex items-center gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-slate-100/80 transition-colors">
                    <input type="checkbox" name="is_featured" value="1" {{ $vehicle->is_featured ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <div>
                        <div class="text-xs font-bold text-slate-800">Feature on Car Hire Showcase</div>
                        <div class="text-[11px] text-slate-500">Highlighted on top car hire landing page rows.</div>
                    </div>
                </label>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.vehicles.index') }}" class="btn-secondary text-sm">Cancel</a>
                <button type="submit" class="btn-primary text-sm shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Vehicle Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('image_file');
    const urlInput = document.getElementById('image_url');
    const previewImg = document.getElementById('vehicle-preview-img');
    const filenameTxt = document.getElementById('vehicle-filename-txt');
    const badgeTxt = document.getElementById('preview-badge-txt');

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                const reader = new FileReader();
                reader.onload = function(evt) {
                    if (previewImg) previewImg.src = evt.target.result;
                    if (badgeTxt) badgeTxt.textContent = 'New Upload Preview';
                    if (filenameTxt) filenameTxt.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (urlInput) {
        urlInput.addEventListener('input', function() {
            if (urlInput.value && (!fileInput || !fileInput.files || fileInput.files.length === 0)) {
                if (previewImg) previewImg.src = urlInput.value;
                if (badgeTxt) badgeTxt.textContent = 'URL Link Preview';
                if (filenameTxt) filenameTxt.textContent = 'Loaded from URL';
            }
        });
    }
});
</script>
@endsection
