@extends('layouts.host')

@section('title', 'Add Guest')
@section('page-title', 'Add New Guest')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-fade-in">
    
    <!-- Top Bar -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xl shadow-slate-200/30 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            @php
                $host = auth('host')->user();
                $event = \App\Models\Event::where('host_id', $host->id)->first();
                $currentGuestCount = $event ? \App\Models\Guest::where('event_id', $event->id)->count() : 0;
                $allowedGuests = $host->number_of_guests;
                $remainingGuests = $allowedGuests - $currentGuestCount;
            @endphp
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-semibold text-indigo-700">
                <span>Guest Allocation: {{ $currentGuestCount }} / {{ $allowedGuests }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black font-heading text-slate-900 tracking-tight">Add Invitee</h1>
            <p class="text-xs sm:text-sm text-slate-500">Add an individual guest or import your guest list in bulk.</p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <button type="button" onclick="openBulkImportModal()" class="modern-button modern-button-secondary {{ $remainingGuests <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                    @if($remainingGuests <= 0) onclick="return false;" title="Guest limit reached" @endif>
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                </svg>
                <span>Bulk Excel Import</span>
            </button>
            <a href="{{ route('host.guests.index') }}" class="modern-button modern-button-ghost">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back</span>
            </a>
        </div>
    </div>

    <!-- Add Guest Form Card -->
    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/90 shadow-xl shadow-slate-200/30 space-y-6">
        <div class="flex items-center gap-3 pb-6 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold font-heading text-slate-900">Guest Information</h2>
                <p class="text-xs text-slate-500">Provide the guest's contact number for digital WhatsApp card delivery.</p>
            </div>
        </div>

        <form action="{{ route('host.guests.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Full Name -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="full_name" class="modern-label">
                        Guest Full Name <span class="text-rose-500">*</span>
                    </label>
                    <input id="full_name" name="full_name" type="text" required
                           value="{{ old('full_name') }}"
                           class="modern-input @error('full_name') border-rose-300 focus:ring-rose-500 @enderror"
                           placeholder="e.g. Dr. John &amp; Mary Smith">
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
                        <option value="single" {{ old('guest_type') == 'single' ? 'selected' : '' }}>Single (1 Guest Pass)</option>
                        <option value="double" {{ old('guest_type') == 'double' ? 'selected' : '' }}>Double (2 Guests / Couple Pass)</option>
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
                           value="{{ old('phone') }}"
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
                <button type="submit" class="modern-button modern-button-primary {{ $remainingGuests <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}" 
                        @if($remainingGuests <= 0) disabled title="Guest limit reached" @endif>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Save &amp; Add Guest</span>
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Bulk Import Modal -->
<div id="bulkImportModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-lg w-full bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-200">
        
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold font-heading text-slate-900">Bulk Import Guests</h3>
                    <p class="text-xs text-slate-500">Upload an Excel (.xlsx, .xls) or .csv file</p>
                </div>
            </div>
            <button type="button" onclick="closeBulkImportModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Form -->
        <form action="{{ route('host.guests.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6 pt-6">
            @csrf
            <div class="space-y-3">
                <label for="modal_excel_file" class="modern-label">Upload Spreadsheet</label>
                <div class="modern-file-dropzone" onclick="document.getElementById('modal_excel_file').click()">
                    <input id="modal_excel_file" name="excel_file" type="file" accept=".xlsx,.xls,.csv" required class="hidden"
                           onchange="document.getElementById('selected-file-name').textContent = this.files[0]?.name || ''">
                    <div class="flex flex-col items-center justify-center gap-2">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-slate-800">Click to choose spreadsheet</p>
                        <p class="text-xs text-indigo-600 font-semibold" id="selected-file-name"></p>
                    </div>
                </div>

                <div class="p-3.5 rounded-2xl bg-indigo-50/60 border border-indigo-100 flex items-center justify-between text-xs">
                    <span class="text-slate-600">Need the correct column format?</span>
                    <a href="{{ route('host.guests.template') }}" class="font-bold text-indigo-600 hover:text-indigo-800 underline">
                        Download Sample Template &darr;
                    </a>
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeBulkImportModal()" class="modern-button modern-button-ghost">
                    Cancel
                </button>
                <button type="submit" class="modern-button modern-button-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    <span>Import All Guests</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openBulkImportModal() {
    const modal = document.getElementById('bulkImportModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeBulkImportModal() {
    const modal = document.getElementById('bulkImportModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}
</script>
@endsection
