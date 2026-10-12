@extends('layouts.dashboard')

@section('title', 'Edit Host - ' . $host->full_name)
@section('page-title', 'Edit Host Account')

@section('content')
<div class="space-y-8 pb-12 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 rounded-full border border-amber-200/60 bg-amber-50/80 px-3 py-1 text-xs font-semibold text-amber-700">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                Host Profile Update
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Edit Host: {{ $host->full_name }}</h1>
            <p class="text-sm text-slate-500">Modify organizer contacts, event allocations, and portal accessibility.</p>
        </div>
        <div class="flex items-center gap-2">
            @if(!$host->hasFinishedEvent())
                <a href="{{ route('admin.hosts.assign-staff', $host) }}" class="btn-primary text-xs sm:text-sm">
                    Assign Staff
                </a>
            @endif
            <a href="{{ route('admin.hosts.index') }}" class="btn-secondary text-xs sm:text-sm">
                Back to Hosts
            </a>
        </div>
    </div>

    <!-- Edit Host Form Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md overflow-hidden">
        <div class="border-b border-slate-100 bg-slate-50/70 px-8 py-5 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900 font-heading">Organizer Profile &amp; Event Scope</h2>
                <p class="text-xs text-slate-500 mt-0.5">Manage budget limits and active login credentials</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $host->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ $host->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                {{ $host->is_active ? 'Active Portal' : 'Inactive' }}
            </span>
        </div>

        <form method="POST" action="{{ route('admin.hosts.update', $host) }}" class="p-8 space-y-8">
            @csrf
            @method('PUT')

            <!-- Section 1: Personal Details -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">1. Organizer Contact Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="full_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Full Name <span class="text-rose-500">*</span></label>
                        <input id="full_name" name="full_name" type="text" required value="{{ old('full_name', $host->full_name) }}"
                               class="form-input-modern text-sm @error('full_name') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('full_name')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Email Address <span class="text-rose-500">*</span></label>
                        <input id="email" name="email" type="email" required value="{{ old('email', $host->email) }}"
                               class="form-input-modern text-sm @error('email') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('email')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Phone Number <span class="text-rose-500">*</span></label>
                        <input id="phone" name="phone" type="tel" required value="{{ old('phone', $host->phone) }}"
                               class="form-input-modern text-sm @error('phone') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('phone')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Event Scope & Cost -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">2. Event Scope &amp; Budget</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="event_type" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Event Type <span class="text-rose-500">*</span></label>
                        <select id="event_type" name="event_type" required
                                class="form-input-modern text-sm @error('event_type') border-rose-300 ring-1 ring-rose-300 @enderror">
                            @foreach ($eventTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('event_type', $host->event_type) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('event_type')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="number_of_guests" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Guest Target <span class="text-rose-500">*</span></label>
                        <input id="number_of_guests" name="number_of_guests" type="number" min="1" required value="{{ old('number_of_guests', $host->number_of_guests) }}"
                               class="form-input-modern text-sm @error('number_of_guests') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('number_of_guests')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="total_cost" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Budget Cost (TZS) <span class="text-rose-500">*</span></label>
                        <input id="total_cost" name="total_cost" type="number" min="0" step="1" required value="{{ old('total_cost', $host->total_cost) }}"
                               class="form-input-modern text-sm font-mono @error('total_cost') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('total_cost')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Password Update (Optional) -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">3. Security Reset (Leave blank to preserve current password)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">New Password</label>
                        <input id="password" name="password" type="password" placeholder="Leave empty to keep current"
                               class="form-input-modern text-sm @error('password') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('password')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Confirm New Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Repeat new password"
                               class="form-input-modern text-sm">
                    </div>
                </div>
            </div>

            <!-- Section 4: Access Toggles -->
            <div class="pt-4 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="flex items-center gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-slate-100/80 transition-colors">
                    <input type="hidden" name="is_active" value="0">
                    <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $host->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <div>
                        <div class="text-xs font-bold text-slate-800">Active Host Portal</div>
                        <div class="text-[11px] text-slate-500">Active hosts can sign in and manage live events.</div>
                    </div>
                </label>

                <label class="flex items-center gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200 cursor-pointer hover:bg-slate-100/80 transition-colors">
                    <input type="hidden" name="event_configured" value="0">
                    <input id="event_configured" name="event_configured" type="checkbox" value="1" {{ old('event_configured', $host->event_configured) ? 'checked' : '' }}
                           class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <div>
                        <div class="text-xs font-bold text-slate-800">Event Configuration Completed</div>
                        <div class="text-[11px] text-slate-500">Mark onboarding setup wizard as finished.</div>
                    </div>
                </label>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.hosts.index') }}" class="btn-secondary text-sm">Cancel</a>
                <button type="submit" class="btn-primary text-sm shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Host Changes
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
