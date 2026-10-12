@extends('layouts.landing')

@php
    $brandSettings = $systemSettings ?? [];
    $brandName = $brandSettings['name'] ?? 'Tukio 360';
@endphp

@section('title', 'Host Registration - ' . $brandName)

@section('content')
<div class="min-h-screen bg-slate-50 pt-10 pb-20 flex items-center justify-center">
    <div class="max-w-2xl w-full mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="text-center space-y-3 mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-500 flex items-center justify-center text-white text-2xl mx-auto shadow-lg shadow-indigo-500/20">
                ✨
            </div>
            <h1 class="text-3xl font-black font-heading text-slate-900 tracking-tight">
                Create Your Host Account
            </h1>
            <p class="text-slate-600 text-xs sm:text-sm">
                Already registered? 
                <a href="{{ route('host.login') }}" class="font-bold text-indigo-600 hover:text-indigo-700">
                    Sign in here &rarr;
                </a>
            </p>
        </div>

        <!-- Registration Card -->
        <div class="glass-card rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-2xl space-y-6">
            @if ($errors->any())
                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                    <div class="font-bold">Please fix the following:</div>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('host.register') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Full Name -->
                    <div class="space-y-1 md:col-span-2">
                        <label for="full_name" class="block text-xs font-semibold text-slate-700">
                            Full Name <span class="text-rose-500">*</span>
                        </label>
                        <input id="full_name" name="full_name" type="text" required value="{{ old('full_name') }}"
                               class="form-input-modern text-sm" placeholder="e.g. David Minja">
                        @error('full_name')
                            <p class="text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="space-y-1">
                        <label for="email" class="block text-xs font-semibold text-slate-700">
                            Email Address <span class="text-rose-500">*</span>
                        </label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}"
                               class="form-input-modern text-sm" placeholder="you@example.com">
                        @error('email')
                            <p class="text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="space-y-1">
                        <label for="phone" class="block text-xs font-semibold text-slate-700">
                            Phone / WhatsApp <span class="text-rose-500">*</span>
                        </label>
                        <input id="phone" name="phone" type="tel" required value="{{ old('phone') }}"
                               class="form-input-modern text-sm" placeholder="0712345678">
                        @error('phone')
                            <p class="text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Event Type -->
                    <div class="space-y-1">
                        <label for="event_type" class="block text-xs font-semibold text-slate-700">
                            Event Type <span class="text-rose-500">*</span>
                        </label>
                        <select id="event_type" name="event_type" required class="form-input-modern text-sm">
                            <option value="">Select event type</option>
                            @if(isset($eventTypes))
                                @foreach($eventTypes as $eventType)
                                    <option value="{{ $eventType->event_type }}" {{ old('event_type') === $eventType->event_type ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $eventType->event_type)) }}
                                    </option>
                                @endforeach
                            @else
                                <option value="wedding">Wedding</option>
                                <option value="birthday">Birthday</option>
                                <option value="corporate">Corporate</option>
                                <option value="send_off">Send-Off</option>
                                <option value="kitchen_party">Kitchen Party</option>
                            @endif
                        </select>
                        @error('event_type')
                            <p class="text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Number of Guests -->
                    <div class="space-y-1">
                        <label for="number_of_guests" class="block text-xs font-semibold text-slate-700">
                            Estimated Number of Guests <span class="text-rose-500">*</span>
                        </label>
                        <input id="number_of_guests" name="number_of_guests" type="number" min="1" max="1000" required
                               value="{{ old('number_of_guests', 100) }}"
                               class="form-input-modern text-sm" placeholder="e.g. 200">
                        @error('number_of_guests')
                            <p class="text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Live Total Calculation Card -->
                    <div id="total-amount-display" class="md:col-span-2 p-5 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl border border-indigo-200 shadow-sm flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold">
                                💳
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-900">Total Event Package</p>
                                <p class="text-[11px] text-slate-500">TZS 2,000 per guest (Includes WhatsApp + QR Pass)</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-extrabold text-indigo-700" id="total-amount">TZS 0</p>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-1">
                        <label for="password" class="block text-xs font-semibold text-slate-700">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <input id="password" name="password" type="password" required
                               class="form-input-modern text-sm" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
                        @error('password')
                            <p class="text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-1">
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700">
                            Confirm Password <span class="text-rose-500">*</span>
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="form-input-modern text-sm" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn-primary w-full text-center text-sm py-3.5 shadow-lg">
                        <span>Complete Registration</span>
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>

                <p class="text-center text-[11px] text-slate-400 pt-2">
                    By registering, you agree to {{ $brandName }}'s 
                    <a href="{{ route('page.show', 'terms-of-use') }}" class="underline text-slate-600">Terms of Service</a> &amp; 
                    <a href="{{ route('page.show', 'privacy-policy') }}" class="underline text-slate-600">Privacy Policy</a>.
                </p>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const guestsInput = document.getElementById('number_of_guests');
    const amountDisplay = document.getElementById('total-amount-display');
    const totalAmountElement = document.getElementById('total-amount');
    
    function updateCalc() {
        const numberOfGuests = parseInt(guestsInput.value) || 0;
        const costPerGuest = 2000;
        const totalAmount = numberOfGuests * costPerGuest;
        
        if (numberOfGuests > 0) {
            totalAmountElement.textContent = 'TZS ' + totalAmount.toLocaleString();
            amountDisplay.style.display = 'flex';
        } else {
            amountDisplay.style.display = 'none';
        }
    }
    
    if (guestsInput) {
        guestsInput.addEventListener('input', updateCalc);
        updateCalc();
    }
});
</script>
@endpush
