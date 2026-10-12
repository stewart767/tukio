@extends('layouts.host')

@section('title', 'Edit Event - ' . $event->title)
@section('page-title', 'Edit Event Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary-900 via-primary-800 to-indigo-950 p-6 md:p-8 text-white shadow-xl">
        <div class="absolute -right-12 -bottom-12 w-56 h-56 rounded-full bg-primary-500/20 blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 backdrop-blur-md border border-white/15 text-primary-200 uppercase tracking-wider mb-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Mode
                </span>
                <h1 class="text-2xl md:text-3xl font-bold font-heading">{{ $event->title }}</h1>
                <p class="text-sm text-slate-300 mt-1 max-w-xl">Update schedules, venue details, invitation notes, and category specifics.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('host.events.show', $event) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-medium text-xs backdrop-blur-md transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Event
                </a>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="modern-card p-6 md:p-8">
        <form action="{{ route('host.events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="space-y-4">
                <h3 class="text-base font-bold font-heading text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-3">
                    <span class="w-6 h-6 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center text-xs font-bold">1</span>
                    Basic Event Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Title -->
                    <div>
                        <label for="title" class="modern-label">
                            Event Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required
                               class="modern-input @error('title') border-red-400 @enderror">
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
                            <option value="">Select category</option>
                            @foreach($eventTypes as $eventType)
                                <option value="{{ $eventType->event_type }}" {{ old('category', $event->category) === $eventType->event_type ? 'selected' : '' }}>
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
                                <option value="{{ $template->id }}" {{ old('template_id', $event->template_id) == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }} ({{ ucfirst(str_replace('_', ' ', $template->category)) }})
                                </option>
                            @endforeach
                        </select>
                        @error('template_id')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="modern-label">
                            Event Status <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" id="status" required
                                class="modern-select @error('status') border-red-400 @enderror">
                            <option value="draft" {{ old('status', $event->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="active" {{ old('status', $event->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ old('status', $event->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $event->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="modern-label">Description / Welcome Notes</label>
                        <textarea name="description" id="description" rows="3"
                                  class="modern-textarea @error('description') border-red-400 @enderror">{{ old('description', $event->description) }}</textarea>
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

            <!-- Event Details -->
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
                        <input type="date" name="date" id="date" value="{{ old('date', $event->date?->format('Y-m-d')) }}" required
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
                        <input type="time" name="time" id="time" value="{{ old('time', $event->time) }}" required
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
                        <input type="text" name="venue" id="venue" value="{{ old('venue', $event->venue) }}" required
                               class="modern-input @error('venue') border-red-400 @enderror">
                        @error('venue')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Venue Address -->
                    <div>
                        <label for="venue_address" class="modern-label">Venue Address / Landmarks</label>
                        <textarea name="venue_address" id="venue_address" rows="2"
                                  class="modern-textarea @error('venue_address') border-red-400 @enderror">{{ old('venue_address', $event->venue_address) }}</textarea>
                        @error('venue_address')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('host.events.show', $event) }}" class="modern-button modern-button-secondary">
                    Cancel
                </a>
                <button type="submit" class="modern-button modern-button-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Update Event</span>
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
    
    // Current event data
    const currentEventData = @json($event->custom_data ?? []);
    
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
            const currentValue = currentEventData[field.name] || '';
            
            if (field.type === 'file') {
                fieldHTML = `
                    <label for="${field.name}" class="modern-label">
                        ${field.label}
                        ${field.required ? '<span class="text-rose-500">*</span>' : ''}
                    </label>
                    ${currentValue ? `
                        <div class="mt-2 mb-2 flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200">
                            <img src="/storage/event_uploads/${currentValue}" alt="${field.name}" class="w-16 h-16 object-cover rounded-lg shadow-sm">
                            <div class="text-xs text-slate-500">
                                <span class="font-semibold text-slate-700 block">Current File:</span>
                                <span class="font-mono">${currentValue}</span>
                            </div>
                        </div>
                    ` : ''}
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
                           value="${currentValue}"
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
                           value="${currentValue}"
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
