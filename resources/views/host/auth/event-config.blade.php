@extends('layouts.host')

@section('title', 'Configure Your Event')
@section('page-title', 'Event Configuration')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 animate-fade-in">
    
    <!-- Hero Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 p-8 sm:p-10 text-white shadow-xl shadow-indigo-500/20">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-xs font-semibold uppercase tracking-wider text-indigo-100">
                    <span>
                        @if(str_contains(strtolower($host->event_type ?? ''), 'wed'))
                            💍 Wedding
                        @elseif(str_contains(strtolower($host->event_type ?? ''), 'birth'))
                            🎂 Birthday
                        @elseif(str_contains(strtolower($host->event_type ?? ''), 'corp'))
                            💼 Corporate
                        @else
                            ✨ Event
                        @endif
                    </span>
                    <span>&bull;</span>
                    <span>Configuration</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black font-heading tracking-tight text-white">
                    Configure Your {{ ucfirst(str_replace('_', ' ', $host->event_type)) }}
                </h1>
                <p class="text-indigo-100/90 text-sm sm:text-base max-w-xl">
                    Fill in your event details below to personalize your digital cards, invitations, and guest check-in portal.
                </p>
            </div>

            <div class="hidden md:flex shrink-0">
                <div class="w-20 h-20 rounded-2xl bg-white/15 backdrop-blur-md border border-white/20 flex items-center justify-center text-white shadow-inner">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Decorative Ambient Light -->
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Event Summary Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        
        <!-- Stat: Event Type -->
        <div class="host-stat-card p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Event Type</p>
                <p class="text-lg font-bold text-slate-900 mt-0.5">
                    {{ ucfirst(str_replace('_', ' ', $host->event_type)) }}
                </p>
            </div>
        </div>

        <!-- Stat: Guests -->
        <div class="host-stat-card p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Guest Allocation</p>
                <p class="text-lg font-bold text-slate-900 mt-0.5">
                    {{ number_format($host->number_of_guests) }} Invited Guests
                </p>
            </div>
        </div>

        <!-- Stat: Pricing -->
        <div class="host-stat-card p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Package</p>
                <p class="text-lg font-bold text-slate-900 mt-0.5" id="display-total-cost">
                    TZS {{ number_format($host->number_of_guests * 2000) }}
                </p>
                <span class="text-[11px] text-slate-400 font-medium">TZS 2,000 / guest</span>
            </div>
        </div>

    </div>

    <!-- Configuration Form Container -->
    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/90 shadow-xl shadow-slate-200/40">
        
        <div class="flex items-center gap-3 pb-6 mb-8 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold font-heading text-slate-900">Event Details & Customization</h2>
                <p class="text-xs text-slate-500">Provide the specific details for your event invitations and cards.</p>
            </div>
        </div>

        <form action="{{ route('host.configure-event') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <!-- Event Configuration Dynamic Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="event-config-fields">
                @if(!empty($eventTypeConfig['fields']))
                    @foreach($eventTypeConfig['fields'] as $field)
                        @php
                            $fieldName = $field['name'];
                            $fieldVal = old($fieldName, $host->event_config_data[$fieldName] ?? '');
                        @endphp
                        <div class="{{ $field['type'] === 'file' ? 'md:col-span-2' : '' }} space-y-1.5">
                            
                            <label for="{{ $fieldName }}" class="modern-label">
                                {{ $field['label'] }}
                                @if(!empty($field['required']))
                                    <span class="text-rose-500">*</span>
                                @endif
                            </label>
                            
                            @if($field['type'] === 'file')
                                <div class="space-y-4">
                                    <!-- Interactive Upload Dropzone Box -->
                                    <div class="modern-file-dropzone" onclick="document.getElementById('{{ $fieldName }}').click()">
                                        <input type="file" name="{{ $fieldName }}" id="{{ $fieldName }}" 
                                               {{ (!empty($field['required']) && empty($host->event_config_data[$fieldName])) ? 'required' : '' }}
                                               {{ isset($field['accept']) ? 'accept=' . $field['accept'] : 'accept=image/*' }}
                                               class="hidden" onchange="previewImage(this, '{{ $fieldName }}_preview')">
                                        
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-800">
                                                    Click to upload <span class="font-normal text-slate-500">or drag & drop</span>
                                                </p>
                                                <p class="text-xs text-slate-400 mt-0.5">PNG, JPG, JPEG, WEBP (Max 5MB)</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Live Image Preview Container -->
                                    <div id="{{ $fieldName }}_preview" class="hidden">
                                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200 inline-flex items-center gap-4">
                                            <div class="relative">
                                                <img id="{{ $fieldName }}_img" src="" alt="Preview" 
                                                     class="w-24 h-24 object-cover rounded-xl border border-slate-200 shadow-sm">
                                                <button type="button" onclick="removeImage('{{ $fieldName }}')" 
                                                        class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold hover:bg-rose-600 shadow transition-transform hover:scale-110">
                                                    &times;
                                                </button>
                                            </div>
                                            <div class="text-left">
                                                <p class="text-xs font-bold text-slate-800" id="{{ $fieldName }}_filename">Selected Photo</p>
                                                <p class="text-[11px] text-emerald-600 font-semibold mt-0.5">&check; Ready to upload</p>
                                                <button type="button" onclick="removeImage('{{ $fieldName }}')" class="text-xs text-rose-500 hover:text-rose-700 font-semibold mt-1">
                                                    Remove file
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Existing Image Display -->
                                    @if(isset($host->event_config_data[$fieldName]) && !empty($host->event_config_data[$fieldName]))
                                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200 inline-flex items-center gap-4 mt-2">
                                            <img src="{{ storage_url('event_uploads/' . $host->event_config_data[$fieldName]) }}" 
                                                 alt="Current {{ $field['label'] }}" 
                                                 class="w-20 h-20 object-cover rounded-xl border border-slate-200 shadow-sm"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <div class="hidden w-20 h-20 bg-slate-100 rounded-xl border border-slate-200 flex items-center justify-center">
                                                <p class="text-[10px] text-slate-400 text-center px-1">No Image</p>
                                            </div>
                                            <div class="text-left">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-700 uppercase">Current Photo</span>
                                                <p class="text-xs font-semibold text-slate-800 mt-1">{{ $field['label'] }}</p>
                                                <p class="text-[10px] text-slate-400">Upload a new photo above to replace this one.</p>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            @elseif($field['type'] === 'date')
                                <input type="date" name="{{ $fieldName }}" id="{{ $fieldName }}" 
                                       value="{{ $fieldVal }}"
                                       {{ !empty($field['required']) ? 'required' : '' }}
                                       class="modern-input">
                            @elseif($field['type'] === 'textarea')
                                <textarea name="{{ $fieldName }}" id="{{ $fieldName }}" rows="3"
                                          {{ !empty($field['required']) ? 'required' : '' }}
                                          placeholder="{{ $field['placeholder'] ?? 'Enter ' . strtolower($field['label']) }}"
                                          class="modern-textarea">{{ $fieldVal }}</textarea>
                            @else
                                <input type="{{ $field['type'] ?? 'text' }}" name="{{ $fieldName }}" id="{{ $fieldName }}" 
                                       value="{{ $fieldVal }}"
                                       {{ !empty($field['required']) ? 'required' : '' }}
                                       placeholder="{{ $field['placeholder'] ?? 'Enter ' . strtolower($field['label']) }}"
                                       class="modern-input">
                            @endif

                            @error($fieldName)
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Action Bar -->
            <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-xs text-slate-500 text-center sm:text-left">
                    <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>You can update and fine-tune these details at any time from your host settings.</span>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <a href="{{ route('host.dashboard') }}" class="modern-button modern-button-secondary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </a>

                    <button type="submit" class="modern-button modern-button-primary shadow-lg shadow-indigo-500/25">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Save &amp; Configure Event</span>
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>

<!-- Scripts for Image Preview -->
<script>
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            const img = document.getElementById(input.name + '_img');
            const filenameEl = document.getElementById(input.name + '_filename');
            
            if (img) img.src = e.target.result;
            if (filenameEl) filenameEl.textContent = file.name;
            if (preview) preview.classList.remove('hidden');
        };
        
        reader.readAsDataURL(file);
    }
}

function removeImage(fieldName) {
    const input = document.getElementById(fieldName);
    const preview = document.getElementById(fieldName + '_preview');
    
    if (input) input.value = '';
    if (preview) preview.classList.add('hidden');
}
</script>
@endsection
