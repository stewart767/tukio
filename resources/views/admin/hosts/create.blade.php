@extends('layouts.dashboard')

@section('title', 'Create Host Account')
@section('page-title', 'Register Host')

@section('content')
<div class="space-y-8 pb-12 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 rounded-full border border-amber-200/60 bg-amber-50/80 px-3 py-1 text-xs font-semibold text-amber-700">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                Host Onboarding
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Register New Host</h1>
            <p class="text-sm text-slate-500">Provision a host portal account with tailored event budgets and guest allocations.</p>
        </div>
        <a href="{{ route('admin.hosts.index') }}" class="btn-secondary text-xs sm:text-sm">
            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Hosts
        </a>
    </div>

    <!-- Create Host Form Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md overflow-hidden">
        <div class="border-b border-slate-100 bg-slate-50/70 px-8 py-5">
            <h2 class="text-base font-bold text-slate-900 font-heading">Host Organizer Profile &amp; Event Scope</h2>
            <p class="text-xs text-slate-500 mt-0.5">Fill in contact credentials and initial capacity estimates</p>
        </div>

        <form method="POST" action="{{ route('admin.hosts.store') }}" class="p-8 space-y-8">
            @csrf

            <!-- Section 1: Contact Information -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">1. Personal &amp; Contact Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="full_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Full Name <span class="text-rose-500">*</span></label>
                        <input id="full_name" name="full_name" type="text" required value="{{ old('full_name') }}" placeholder="e.g. Amani Mwamba"
                               class="form-input-modern text-sm @error('full_name') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('full_name')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Email Address <span class="text-rose-500">*</span></label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}" placeholder="amani@example.com"
                               class="form-input-modern text-sm @error('email') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('email')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Phone / WhatsApp <span class="text-rose-500">*</span></label>
                        <input id="phone" name="phone" type="tel" required value="{{ old('phone') }}" placeholder="+255 7XX XXX XXX"
                               class="form-input-modern text-sm @error('phone') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('phone')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Event Scope -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">2. Event Type &amp; Initial Capacity</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="event_type" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Event Category <span class="text-rose-500">*</span></label>
                        <select id="event_type" name="event_type" required
                                class="form-input-modern text-sm @error('event_type') border-rose-300 ring-1 ring-rose-300 @enderror">
                            <option value="">-- Select Event Type --</option>
                            @foreach ($eventTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('event_type') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('event_type')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="number_of_guests" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Target Guest Count <span class="text-rose-500">*</span></label>
                        <input id="number_of_guests" name="number_of_guests" type="number" min="1" required value="{{ old('number_of_guests', 200) }}"
                               class="form-input-modern text-sm @error('number_of_guests') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('number_of_guests')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="total_cost" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Total Package / Cost (TZS) <span class="text-rose-500">*</span></label>
                        <input id="total_cost" name="total_cost" type="number" min="0" step="1" required value="{{ old('total_cost', 400000) }}"
                               class="form-input-modern text-sm font-mono @error('total_cost') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('total_cost')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-[11px] text-slate-400">Suggested: 2,000 TZS per expected guest</p>
                    </div>
                </div>
            </div>

            <!-- Section 3: Portal Security Access -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">3. Portal Security &amp; Initial Password</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Temporary Password <span class="text-rose-500">*</span></label>
                        <input id="password" name="password" type="password" required placeholder="Minimum 8 characters"
                               class="form-input-modern text-sm @error('password') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('password')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Confirm Temporary Password <span class="text-rose-500">*</span></label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="Re-enter password"
                               class="form-input-modern text-sm">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.hosts.index') }}" class="btn-secondary text-sm">Cancel</a>
                <button type="submit" class="btn-primary text-sm shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Create &amp; Provision Host Account
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const guestsInput = document.getElementById('number_of_guests');
    const costInput = document.getElementById('total_cost');

    if (guestsInput && costInput) {
        guestsInput.addEventListener('input', function() {
            const count = parseInt(this.value, 10);
            if (!isNaN(count) && count > 0 && !costInput.dataset.manualEdited) {
                costInput.value = count * 2000;
            }
        });

        costInput.addEventListener('input', function() {
            this.dataset.manualEdited = 'true';
        });
    }
});
</script>
@endsection
