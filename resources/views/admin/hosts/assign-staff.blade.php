@extends('layouts.dashboard')

@section('title', 'Assign Staff to Host Events')
@section('page-title', 'Staff Operations Assignment')

@section('content')
<div class="space-y-8 pb-12 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 rounded-full border border-blue-200/60 bg-blue-50/80 px-3 py-1 text-xs font-semibold text-blue-700">
                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                Staff Deployment
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 font-heading">Assign Staff: {{ $host->full_name }}</h1>
            <p class="text-sm text-slate-500">Delegate check-in scanning and guest desk management to active platform staff.</p>
        </div>
        <a href="{{ route('admin.hosts.index') }}" class="btn-secondary text-xs sm:text-sm">
            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Hosts
        </a>
    </div>

    <!-- Host Information Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 sm:p-8 shadow-xl backdrop-blur-md">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Host Overview</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="text-xs text-slate-500">Host Organizer</div>
                <div class="text-sm font-bold text-slate-900 mt-1">{{ $host->full_name }}</div>
                <div class="text-[11px] text-slate-400 font-mono">{{ $host->email }}</div>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="text-xs text-slate-500">Event Category</div>
                <div class="text-sm font-bold text-slate-900 mt-1">{{ ucfirst(str_replace('_', ' ', $host->event_type)) }}</div>
                <div class="text-[11px] text-slate-400">{{ number_format($host->number_of_guests) }} Expected Guests</div>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="text-xs text-slate-500">Active Events</div>
                <div class="text-sm font-bold text-slate-900 mt-1">{{ $events->count() }} Event(s) Created</div>
                <div class="text-[11px] text-indigo-600 font-medium">Ready for deployment</div>
            </div>
        </div>
    </div>

    <!-- Assignment Form Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 shadow-xl backdrop-blur-md overflow-hidden">
        <div class="border-b border-slate-100 bg-slate-50/70 px-8 py-5">
            <h2 class="text-base font-bold text-slate-900 font-heading">Event &amp; Staff Selection</h2>
            <p class="text-xs text-slate-500 mt-0.5">Select the target event and one or more staff members</p>
        </div>

        @if($events->count() > 0)
            <form action="{{ route('admin.hosts.assign-staff.store', $host) }}" method="POST" class="p-8 space-y-6">
                @csrf
                
                <!-- Event Selection -->
                <div>
                    <label for="event_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Select Target Event <span class="text-rose-500">*</span></label>
                    <select name="event_id" id="event_id" required class="form-input-modern text-sm @error('event_id') border-rose-300 ring-1 ring-rose-300 @enderror">
                        <option value="">-- Choose Host Event --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->title }} ({{ $event->date ? \Carbon\Carbon::parse($event->date)->format('M d, Y') : 'Date TBD' }})
                                @php
                                    $assignedStaff = $event->staffMembers->pluck('name')->toArray();
                                    if ($event->staff && !in_array($event->staff->name, $assignedStaff)) {
                                        $assignedStaff[] = $event->staff->name;
                                    }
                                @endphp
                                @if(count($assignedStaff) > 0)
                                    [Assigned: {{ implode(', ', $assignedStaff) }}]
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('event_id')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Staff Selection -->
                <div>
                    <label for="staff_ids" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Select Field Staff Members <span class="text-rose-500">*</span></label>
                    <select name="staff_ids[]" id="staff_ids" multiple required 
                            class="form-input-modern text-sm min-h-[160px] @error('staff_ids') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @foreach($staffMembers as $staff)
                            <option value="{{ $staff->id }}" {{ in_array($staff->id, old('staff_ids', [])) ? 'selected' : '' }} class="p-2 border-b border-slate-100 last:border-0">
                                🛡️ {{ $staff->name }} — {{ $staff->email }} {{ $staff->phone ? '('.$staff->phone.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs text-slate-500">
                        <span class="font-bold">Tip:</span> Hold <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-300 rounded-md text-[10px] font-mono">Ctrl</kbd> / <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-300 rounded-md text-[10px] font-mono">Cmd</kbd> to multi-select staff coordinators.
                    </p>
                    @error('staff_ids')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                    @if($staffMembers->count() == 0)
                        <div class="mt-3 p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800">
                            No active staff accounts found. Please <a href="{{ route('admin.users.create') }}" class="font-bold underline text-amber-900">create a staff account</a> first.
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.hosts.index') }}" class="btn-secondary text-sm">Cancel</a>
                    <button type="submit" class="btn-primary text-sm shadow-md" {{ $staffMembers->count() == 0 ? 'disabled' : '' }}>
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Confirm Staff Assignment
                    </button>
                </div>
            </form>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl mx-auto flex items-center justify-center text-2xl text-slate-400 mb-3">
                    📅
                </div>
                <h3 class="text-base font-bold text-slate-900">No Events Configured Yet</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">This host has not yet finalized event details in the portal. Staff can be assigned once the event record is initialized.</p>
                <a href="{{ route('admin.hosts.index') }}" class="btn-secondary text-xs mt-4 inline-flex">
                    Return to Hosts List
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
