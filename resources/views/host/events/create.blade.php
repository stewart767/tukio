@extends('layouts.host')

@section('title', 'Create Event')
@section('page-title', 'Create New Event')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary-900 via-primary-800 to-indigo-950 p-6 md:p-8 text-white shadow-xl">
        <div class="absolute -right-12 -bottom-12 w-56 h-56 rounded-full bg-primary-500/20 blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 backdrop-blur-md border border-white/15 text-primary-200 uppercase tracking-wider mb-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    New Celebration Setup
                </span>
                <h1 class="text-2xl md:text-3xl font-bold font-heading">Create New Event</h1>
                <p class="text-sm text-slate-300 mt-1 max-w-xl">Set up your event details, guest quota, and custom invitation preferences.</p>
            </div>
            <a href="{{ route('host.events.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-medium text-xs backdrop-blur-md transition-all shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Events
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="modern-card p-6 md:p-8">
        <form action="{{ route('host.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="space-y-4">
                <h3 class="text-base font-bold font-heading text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-3">
                    <span class="w-6 h-6 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center text-xs font-bold">1</span>
                    Basic Event Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Event Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="modern-label">
                            Event Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               class="modern-input @error('title') border-red-400 @enderror"
                               placeholder="e.g., Sarah & John's Wedding Celebration">
                        @error('title')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="modern-label">
                            Event Category <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" id="category" required
                                class="modern-select @error('category') border-red-400 @enderror">
                            <option value="">Select Category</option>
                            @foreach($eventTypes as $eventType)
                                <option value="{{ $eventType->event_type }}" {{ old('category') === $eventType->event_type ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $eventType->event_type)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Template -->
                    <div>
                        <label for="template_id" class="modern-label">
                            Invitation Template
                        </label>
                        <select name="template_id" id="template_id"
                                class="modern-select @error('template_id') border-red-400 @enderror">
                            <option value="">Select template (optional)</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" {{ old('template_id') == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }} ({{ ucfirst(str_replace('_', ' ', $template->category)) }})
                                </option>
                            @endforeach
                        </select>
                        @error('template_id')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="modern-label">Description / Invitation Notes</label>
                        <textarea name="description" id="description" rows="3"
                                  class="modern-textarea @error('description') border-red-400 @enderror"
                                  placeholder="Provide any additional welcome notes or instructions for your guests...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Dynamic Event Type Fields -->
            <div id="dynamic-fields" class="space-y-4" style="display: none;">
                <h3 class="text-base font-bold font-heading text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-3">
                    <span class="w-6 h-6 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center text-xs font-bold">2</span>
                    Event Specific Details
                </h3>
                <div id="dynamic-fields-container" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Dynamic fields will be loaded here -->
                </div>
            </div>

            <!-- Date, Time & Venue -->
            <div class="space-y-4">
                <h3 class="text-base font-bold font-heading text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-3">
                    <span class="w-6 h-6 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center text-xs font-bold">3</span>
                    Schedule &amp; Location
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Date -->
                    <div>
                        <label for="date" class="modern-label">
                            Event Date <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="date" id="date" value="{{ old('date') }}" required
                               class="modern-input @error('date') border-red-400 @enderror">
                        @error('date')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Time -->
                    <div>
                        <label for="time" class="modern-label">
                            Event Time <span class="text-rose-500">*</span>
                        </label>
                        <input type="time" name="time" id="time" value="{{ old('time') }}" required
                               class="modern-input @error('time') border-red-400 @enderror">
                        @error('time')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Venue -->
                    <div>
                        <label for="venue" class="modern-label">
                            Venue Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="venue" id="venue" value="{{ old('venue') }}" required
                               class="modern-input @error('venue') border-red-400 @enderror"
                               placeholder="e.g., Serena Hotel Grand Ballroom">
                        @error('venue')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Venue Address -->
                    <div>
                        <label for="venue_address" class="modern-label">Venue Address / Landmarks</label>
                        <input type="text" name="venue_address" id="venue_address" value="{{ old('venue_address') }}"
                               class="modern-input @error('venue_address') border-red-400 @enderror"
                               placeholder="e.g., Ohio Street, Dar es Salaam">
                        @error('venue_address')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing Notice -->
            <div class="rounded-2xl bg-gradient-to-r from-primary-50 to-indigo-50/50 border border-primary-100 p-4 flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-primary-600 text-white flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-primary-900">Gharama &amp; Orodha ya Wageni</h4>
                    <p class="text-xs text-slate-600 mt-0.5">
                        Kila mwaliko unajumuisha kadi ya WhatsApp na QR code ya mlangoni. Unaweza kusimamia orodha ya wageni wako moja kwa moja hapa.
                    </p>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('host.events.index') }}" class="modern-button modern-button-secondary">
                    Cancel
                </a>
                <button type="submit" class="modern-button modern-button-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Create Event</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category');
    const dynamicFields = document.getElementById('dynamic-fields');
    const dynamicFieldsContainer = document.getElementById('dynamic-fields-container');
    
    // Event type configurations
    const eventTypeConfigs = @json($eventTypes->keyBy('event_type'));
    
    categorySelect.addEventListener('change', function() {
        const selectedCategory = this.value;
        
        if (selectedCategory && eventTypeConfigs[selectedCategory]) {
            const config = eventTypeConfigs[selectedCategory].fields_config;
            renderDynamicFields(config.fields || []);
            dynamicFields.style.display = 'block';
        } else {
            dynamicFields.style.display = 'none';
        }
    });
    
    function renderDynamicFields(fields) {
        dynamicFieldsContainer.innerHTML = '';
        
        fields.forEach(field => {
            const fieldDiv = document.createElement('div');
            fieldDiv.className = field.type === 'file' ? 'md:col-span-2' : '';
            
            let fieldHTML = '';
            
            if (field.type === 'file') {
                fieldHTML = `
                    <label for="${field.name}" class="modern-label">
                        ${field.label}
                        ${field.required ? '<span class="text-rose-500">*</span>' : ''}
                    </label>
                    <input type="file" name="${field.name}" id="${field.name}" 
                           ${field.required ? 'required' : ''}
                           ${field.accept ? `accept="${field.accept}"` : ''}
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                `;
            } else if (field.type === 'date') {
                fieldHTML = `
                    <label for="${field.name}" class="modern-label">
                        ${field.label}
                        ${field.required ? '<span class="text-rose-500">*</span>' : ''}
                    </label>
                    <input type="date" name="${field.name}" id="${field.name}" 
                           ${field.required ? 'required' : ''}
                           class="modern-input">
                `;
            } else {
                fieldHTML = `
                    <label for="${field.name}" class="modern-label">
                        ${field.label}
                        ${field.required ? '<span class="text-rose-500">*</span>' : ''}
                    </label>
                    <input type="${field.type}" name="${field.name}" id="${field.name}" 
                           ${field.required ? 'required' : ''}
                           ${field.placeholder ? `placeholder="${field.placeholder}"` : ''}
                           class="modern-input">
                `;
            }
            
            fieldDiv.innerHTML = fieldHTML;
            dynamicFieldsContainer.appendChild(fieldDiv);
        });
    }
    
    // Trigger change event if category is already selected
    if (categorySelect.value) {
        categorySelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection
