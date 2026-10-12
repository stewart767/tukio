@extends('layouts.host')

@section('title', 'Send Invitations')
@section('page-title', 'Dispatch Invitations')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 animate-fade-in">
    
    <!-- Top Header Bar -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xl shadow-slate-200/30 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-semibold text-indigo-700 mb-2">
                <span>📱 WhatsApp Automated Dispatch</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black font-heading text-slate-900 tracking-tight">Send Digital Invitations</h1>
            <p class="text-xs sm:text-sm text-slate-500">Configure message content, select recipient guests, and pick a custom card template.</p>
        </div>

        <a href="{{ route('host.invitations.index') }}" class="modern-button modern-button-ghost shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Back to History</span>
        </a>
    </div>

    <!-- Main Configuration Form -->
    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/90 shadow-xl shadow-slate-200/30 space-y-8">
        <form action="{{ route('host.invitations.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Step 1: Sending Type -->
            <div class="space-y-3">
                <label class="modern-label text-sm font-bold text-slate-900">
                    Step 1: Choose Recipient Mode <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <label class="relative cursor-pointer">
                        <input type="radio" name="sending_type" value="bulk" id="bulk" class="sr-only" onchange="toggleGuestSelection()" checked>
                        <div class="p-5 rounded-2xl border-2 border-indigo-500 bg-indigo-50/40 shadow-sm transition-all duration-200 flex items-center gap-4" id="bulk-card">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-indigo-500/20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">Bulk Dispatch (All Guests)</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Send personalized cards to all {{ $guests->count() }} registered guests.</p>
                            </div>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input type="radio" name="sending_type" value="individual" id="individual" class="sr-only" onchange="toggleGuestSelection()">
                        <div class="p-5 rounded-2xl border-2 border-slate-200 hover:border-slate-300 transition-all duration-200 flex items-center gap-4" id="individual-card">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">Individual Guest</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Send to one specific guest only.</p>
                            </div>
                        </div>
                    </label>

                </div>
                @error('sending_type')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Guest Picker (Individual Only) -->
            <div id="guest-selection" class="hidden space-y-2">
                <label for="guest_id" class="modern-label text-xs font-bold text-slate-700">
                    Select Recipient Guest <span class="text-rose-500">*</span>
                </label>
                <select name="guest_id" id="guest_id" class="modern-select">
                    <option value="">Choose a guest from your list...</option>
                    @foreach($guests as $guest)
                        <option value="{{ $guest->id }}">{{ $guest->full_name }} ({{ $guest->phone }}) &bull; Code: {{ $guest->invitation_code }}</option>
                    @endforeach
                </select>
                @error('guest_id')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Hidden Guest Selection (Bulk) -->
            <div id="bulk-guest-selection" class="hidden">
                @foreach($guests as $guest)
                    <input type="hidden" name="guest_ids[]" value="{{ $guest->id }}">
                @endforeach
            </div>

            <!-- Step 2: Channel Method -->
            <div class="space-y-3">
                <label class="modern-label text-sm font-bold text-slate-900">
                    Step 2: Delivery Channel <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <label class="relative cursor-pointer">
                        <input type="radio" name="sending_method" value="whatsapp" id="whatsapp" class="sr-only" checked>
                        <div class="p-4 rounded-2xl border-2 border-emerald-500 bg-emerald-50/40 transition-all flex items-center gap-3.5" id="whatsapp-card">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.23-.382a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">WhatsApp</h4>
                                <p class="text-[11px] text-slate-500">Image Card + Link</p>
                            </div>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input type="radio" name="sending_method" value="sms" id="sms" class="sr-only">
                        <div class="p-4 rounded-2xl border-2 border-slate-200 hover:border-slate-300 transition-all flex items-center gap-3.5" id="sms-card">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">SMS Gateway</h4>
                                <p class="text-[11px] text-slate-500">Direct text &amp; code</p>
                            </div>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input type="radio" name="sending_method" value="both" id="both" class="sr-only">
                        <div class="p-4 rounded-2xl border-2 border-slate-200 hover:border-slate-300 transition-all flex items-center gap-3.5" id="both-card">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">Both (Hybrid)</h4>
                                <p class="text-[11px] text-slate-500">WhatsApp + SMS</p>
                            </div>
                        </div>
                    </label>

                </div>
            </div>

            <!-- Step 3: Message Text -->
            <div class="space-y-2">
                <label for="description" class="modern-label text-sm font-bold text-slate-900">
                    Step 3: Invitation Text Message
                </label>
                <textarea name="description" id="description" rows="3" 
                          class="modern-textarea" 
                          placeholder="You are cordially invited to celebrate with us...">{{ old('description', 'Habari! You are cordially invited to our special celebration. Click below to view your digital card and invitation pass.') }}</textarea>
                <p class="text-xs text-slate-400">Personalized greeting and invitation pass QR code will be automatically attached.</p>
            </div>

            <!-- Step 4: Card Template Gallery -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Step 4: Pick Invitation Design Template</h3>
                        <p class="text-xs text-slate-500">Select the card style that will be generated for each guest.</p>
                    </div>
                </div>

                @if($templates->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                        @foreach($templates as $template)
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="template_id" value="{{ $template->id }}" id="template_{{ $template->id }}" class="sr-only" {{ $loop->first ? 'checked' : '' }}>
                                <div class="p-4 rounded-3xl border-2 border-slate-200 group-hover:border-indigo-400 transition-all template-card bg-white shadow-sm hover:shadow-lg flex flex-col justify-between" id="template-card-{{ $template->id }}" onclick="selectTemplate({{ $template->id }})">
                                    <div>
                                        <div class="relative overflow-hidden rounded-2xl mb-3 h-48 bg-slate-900 flex items-center justify-center">
                                            @if($template->preview_image)
                                                <img src="{{ storage_url($template->preview_image) }}" alt="{{ $template->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="text-center p-4 text-white">
                                                    <span class="text-3xl mb-1 block">🎨</span>
                                                    <span class="text-xs font-bold font-heading">{{ $template->name }}</span>
                                                    <span class="text-[10px] text-indigo-300 block font-mono">v{{ $template->version ?? '1.0' }}</span>
                                                </div>
                                            @endif
                                            
                                            <!-- Category Badge -->
                                            <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-white/95 backdrop-blur text-slate-800 uppercase shadow-xs">
                                                {{ ucfirst(str_replace('_', ' ', $template->category)) }}
                                            </span>

                                            <!-- Price Badge -->
                                            <span class="absolute top-2.5 right-2.5 px-2.5 py-0.5 rounded-full text-[10px] font-black shadow-xs backdrop-blur {{ $template->isPaid() ? 'bg-amber-400 text-slate-950' : 'bg-emerald-500 text-white' }}">
                                                {{ $template->formatted_price }}
                                            </span>
                                        </div>

                                        <div class="space-y-1">
                                            <div class="flex items-center justify-between">
                                                <h4 class="font-bold text-sm text-slate-900 group-hover:text-indigo-600">{{ $template->name }}</h4>
                                                <span class="text-[10px] font-mono text-slate-400">v{{ $template->version ?? '1.0' }}</span>
                                            </div>
                                            @if($template->description)
                                                <p class="text-xs text-slate-500 line-clamp-2">{{ $template->description }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                        <span class="text-[11px] font-semibold text-indigo-600 flex items-center gap-1">
                                            <span>Select Style</span>
                                            <span>&rarr;</span>
                                        </span>

                                        <a href="{{ route('admin.templates.preview', $template) }}" target="_blank" onclick="event.stopPropagation();" class="text-[11px] font-bold text-slate-500 hover:text-purple-600 hover:underline inline-flex items-center gap-1">
                                            <span>Preview</span>
                                            <span>↗</span>
                                        </a>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center rounded-2xl bg-slate-50 border border-slate-200">
                        <p class="text-sm text-slate-500">No active templates available for this category.</p>
                    </div>
                @endif
            </div>

            <!-- Action Bar -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('host.invitations.index') }}" class="modern-button modern-button-ghost">
                    Cancel
                </a>
                <button type="submit" class="modern-button modern-button-primary shadow-lg shadow-indigo-500/25">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <span>Dispatch Invitations</span>
                </button>
            </div>

        </form>
    </div>

</div>

<script>
function toggleGuestSelection() {
    const individual = document.getElementById('individual');
    const guestSelection = document.getElementById('guest-selection');
    const bulkGuestSelection = document.getElementById('bulk-guest-selection');
    const individualCard = document.getElementById('individual-card');
    const bulkCard = document.getElementById('bulk-card');
    
    if (individual && individual.checked) {
        if (guestSelection) guestSelection.classList.remove('hidden');
        if (bulkGuestSelection) bulkGuestSelection.classList.add('hidden');
        if (individualCard) {
            individualCard.classList.add('border-indigo-500', 'bg-indigo-50/40');
            individualCard.classList.remove('border-slate-200');
        }
        if (bulkCard) {
            bulkCard.classList.add('border-slate-200');
            bulkCard.classList.remove('border-indigo-500', 'bg-indigo-50/40');
        }
    } else {
        if (guestSelection) guestSelection.classList.add('hidden');
        if (bulkGuestSelection) bulkGuestSelection.classList.remove('hidden');
        if (bulkCard) {
            bulkCard.classList.add('border-indigo-500', 'bg-indigo-50/40');
            bulkCard.classList.remove('border-slate-200');
        }
        if (individualCard) {
            individualCard.classList.add('border-slate-200');
            individualCard.classList.remove('border-indigo-500', 'bg-indigo-50/40');
        }
    }
}

function selectTemplate(templateId) {
    document.querySelectorAll('input[name="template_id"]').forEach(r => r.checked = false);
    const radio = document.getElementById('template_' + templateId);
    if (radio) radio.checked = true;

    document.querySelectorAll('.template-card').forEach(card => {
        card.classList.remove('border-indigo-500', 'bg-indigo-50/30', 'ring-2', 'ring-indigo-500/20');
        card.classList.add('border-slate-200');
    });

    const selectedCard = document.getElementById('template-card-' + templateId);
    if (selectedCard) {
        selectedCard.classList.add('border-indigo-500', 'bg-indigo-50/30', 'ring-2', 'ring-indigo-500/20');
        selectedCard.classList.remove('border-slate-200');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleGuestSelection();

    // Setup channel radio cards
    const methods = ['whatsapp', 'sms', 'both'];
    methods.forEach(method => {
        const radio = document.getElementById(method);
        if (radio) {
            radio.addEventListener('change', function() {
                methods.forEach(m => {
                    const card = document.getElementById(m + '-card');
                    if (card) {
                        card.classList.remove('border-indigo-500', 'border-emerald-500', 'bg-indigo-50/40', 'bg-emerald-50/40');
                        card.classList.add('border-slate-200');
                    }
                });
                const activeCard = document.getElementById(this.value + '-card');
                if (activeCard) {
                    activeCard.classList.remove('border-slate-200');
                    activeCard.classList.add(this.value === 'whatsapp' ? 'border-emerald-500' : 'border-indigo-500');
                    activeCard.classList.add(this.value === 'whatsapp' ? 'bg-emerald-50/40' : 'bg-indigo-50/40');
                }
            });
        }
    });

    // Initial template selection styling
    const checkedTemplate = document.querySelector('input[name="template_id"]:checked');
    if (checkedTemplate) {
        selectTemplate(checkedTemplate.value);
    }
});
</script>
@endsection
