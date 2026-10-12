@extends('layouts.host')

@section('title', 'Edit Guest')
@section('page-title', 'Edit Guest Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-fade-in">
    
    <!-- Top Bar -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xl shadow-slate-200/30 flex items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-semibold text-indigo-700">
                <span>Code: {{ $guest->invitation_code ?? 'N/A' }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black font-heading text-slate-900 tracking-tight mt-1">Edit {{ $guest->full_name }}</h1>
            <p class="text-xs sm:text-sm text-slate-500">Update guest name, pass type, or contact phone number.</p>
        </div>

        <a href="{{ route('host.guests.index') }}" class="modern-button modern-button-ghost">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Back to Guests</span>
        </a>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/90 shadow-xl shadow-slate-200/30 space-y-6">
        <form action="{{ route('host.guests.update', $guest->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Full Name -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="full_name" class="modern-label">
                        Guest Full Name <span class="text-rose-500">*</span>
                    </label>
                    <input id="full_name" name="full_name" type="text" required
                           value="{{ old('full_name', $guest->full_name) }}"
                           class="modern-input @error('full_name') border-rose-300 focus:ring-rose-500 @enderror"
                           placeholder="Enter guest's full name">
                    @error('full_name')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Guest Type -->
                <div class="space-y-1.5">
                    <label for="guest_type" class="modern-label">
                        Pass / Ticket Type <span class="text-rose-500">*</span>
                    </label>
                    <select id="guest_type" name="guest_type" required
                            class="modern-select @error('guest_type') border-rose-300 focus:ring-rose-500 @enderror">
                        <option value="single" {{ old('guest_type', $guest->guest_type ?? 'single') == 'single' ? 'selected' : '' }}>Single (1 Pass)</option>
                        <option value="double" {{ old('guest_type', $guest->guest_type ?? 'single') == 'double' ? 'selected' : '' }}>Double (2 Passes)</option>
                    </select>
                    @error('guest_type')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone / WhatsApp -->
                <div class="space-y-1.5">
                    <label for="phone" class="modern-label">
                        Phone / WhatsApp Number <span class="text-rose-500">*</span>
                    </label>
                    <input id="phone" name="phone" type="tel" required
                           value="{{ old('phone', $guest->phone) }}"
                           class="modern-input @error('phone') border-rose-300 focus:ring-rose-500 @enderror"
                           placeholder="0712345678 or +255 712 345 678">
                    @error('phone')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Bar -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('host.guests.index') }}" class="modern-button modern-button-ghost">
                    Cancel
                </a>
                <button type="submit" class="modern-button modern-button-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Save Changes</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
