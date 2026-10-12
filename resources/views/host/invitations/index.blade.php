@extends('layouts.host')

@section('title', 'Invitation Management')
@section('page-title', 'Digital Invitations')

@section('content')
<div class="space-y-8 animate-fade-in">
    
    <!-- Header Banner -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xl shadow-slate-200/30 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1.5">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-xs font-semibold text-emerald-700">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>WhatsApp Gateway Active</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black font-heading text-slate-900 tracking-tight">Digital Invitations &amp; Cards</h1>
            <p class="text-xs sm:text-sm text-slate-500">Dispatch customized event invitation cards with QR codes directly to guests' WhatsApp.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <button type="button" onclick="testWhatsAppConnection()" class="modern-button modern-button-secondary" title="Test Gateway">
                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.23-.382a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                <span>Test WhatsApp</span>
            </button>
            <a href="{{ route('host.invitations.create') }}" class="modern-button modern-button-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                <span>Send Invitations</span>
            </a>
        </div>
    </div>

    <!-- Dispatch Statistics Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        <div class="host-stat-card p-5">
            <div class="flex items-center justify-between">
                <div class="w-11 h-11 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-slate-400 uppercase">Dispatched</span>
            </div>
            <p class="text-2xl sm:text-3xl font-black font-heading text-slate-900 mt-3">{{ $stats['total_sent'] }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Total sent</p>
        </div>

        <div class="host-stat-card p-5">
            <div class="flex items-center justify-between">
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-emerald-600 uppercase bg-emerald-50 px-2 py-0.5 rounded-full">Delivered</span>
            </div>
            <p class="text-2xl sm:text-3xl font-black font-heading text-slate-900 mt-3">{{ $stats['delivered'] }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Received on device</p>
        </div>

        <div class="host-stat-card p-5">
            <div class="flex items-center justify-between">
                <div class="w-11 h-11 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-blue-600 uppercase bg-blue-50 px-2 py-0.5 rounded-full">Opened</span>
            </div>
            <p class="text-2xl sm:text-3xl font-black font-heading text-slate-900 mt-3">{{ $stats['opened'] }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Card viewed by guest</p>
        </div>

        <div class="host-stat-card p-5">
            <div class="flex items-center justify-between">
                <div class="w-11 h-11 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-rose-600 uppercase bg-rose-50 px-2 py-0.5 rounded-full">Failed</span>
            </div>
            <p class="text-2xl sm:text-3xl font-black font-heading text-slate-900 mt-3">{{ $stats['failed'] }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Undelivered attempts</p>
        </div>

    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-xl shadow-slate-200/30">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <div class="flex flex-1 flex-col sm:flex-row items-center gap-3">
                <div class="relative w-full sm:w-80">
                    <input type="text" id="invitationSearchInput" placeholder="Search by recipient, phone..." 
                           class="modern-input pl-10" onkeyup="filterInvitationTable()">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <select id="invitationStatusFilter" class="modern-select w-full sm:w-44" onchange="filterInvitationTable()">
                    <option value="">All Statuses</option>
                    <option value="delivered">Delivered</option>
                    <option value="opened">Opened</option>
                    <option value="failed">Failed</option>
                </select>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('host.invitations.create') }}" class="modern-button modern-button-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <span>Dispatch to More Guests</span>
                </a>
            </div>

        </div>
    </div>

    <!-- Invitations Table -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl shadow-slate-200/30 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-bold font-heading text-slate-900">Dispatch Log &amp; History</h3>
            <span class="text-xs font-semibold text-slate-400">{{ $invitations->count() }} Total Dispatches</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100" id="invitationsTable">
                <thead class="bg-slate-50/75">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Recipient Guest</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Channel</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Delivery Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Sent Time</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Delivered Time</th>
                        <th class="px-6 py-3.5 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($invitations as $invitation)
                        <tr class="hover:bg-slate-50/60 transition-colors invitation-row" data-status="{{ strtolower($invitation->status ?? 'sent') }}">
                            
                            <!-- Guest -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-50 to-teal-50 border border-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($invitation->guest->full_name ?? 'G', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900 invitation-guest">{{ $invitation->guest->full_name ?? 'Unknown Guest' }}</div>
                                        <div class="text-[11px] text-slate-400 font-mono invitation-phone">{{ $invitation->guest->phone ?? '' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Channel -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                                    @if($invitation->type === 'whatsapp') bg-emerald-50 text-emerald-700 border border-emerald-200/60
                                    @else bg-blue-50 text-blue-700 border border-blue-200/60
                                    @endif">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    <span>{{ ucfirst($invitation->type) }}</span>
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold
                                    @if($invitation->status === 'delivered') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($invitation->status === 'opened') bg-blue-50 text-blue-700 border border-blue-200
                                    @elseif($invitation->status === 'failed') bg-rose-50 text-rose-700 border border-rose-200
                                    @else bg-amber-50 text-amber-700 border border-amber-200
                                    @endif">
                                    {{ ucfirst($invitation->status) }}
                                </span>
                            </td>

                            <!-- Sent Time -->
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-600 font-medium">
                                {{ $invitation->sent_at ? $invitation->sent_at->format('M d, Y H:i') : '&mdash;' }}
                            </td>

                            <!-- Delivered Time -->
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-600 font-medium">
                                {{ $invitation->delivered_at ? $invitation->delivered_at->format('M d, Y H:i') : '&mdash;' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('host.invitations.download', $invitation) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Download Card Image">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('host.invitations.preview', $invitation) }}" target="_blank" class="p-1.5 rounded-lg text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Preview Digital Web Card">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    @if($invitation->status === 'failed')
                                        <form method="POST" action="{{ route('host.invitations.resend', $invitation) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-50 transition-colors" title="Resend via WhatsApp">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <button type="button" onclick="confirmDelete({{ $invitation->id }}, '{{ $invitation->guest->full_name ?? 'Guest' }}', '{{ route('host.invitations.destroy', $invitation) }}')" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Delete Log">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-500 flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.902l-5.4 2.7a2.25 2.25 0 01-2.13 0l-5.4-2.7a2.25 2.25 0 01-1.07-1.902V6.75m19.5 0H4.5" />
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-900 mb-1">No Invitations Sent Yet</h3>
                                    <p class="text-xs text-slate-500 mb-6">Dispatch your first batch of digital WhatsApp cards with custom QR codes.</p>
                                    <a href="{{ route('host.invitations.create') }}" class="modern-button modern-button-primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                        <span>Dispatch First Invitations</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-md w-full bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-200 text-center">
        <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>
        <h3 class="text-lg font-bold font-heading text-slate-900 mb-2">Delete Invitation Record</h3>
        <p class="text-xs text-slate-500 mb-6">
            Are you sure you want to delete the dispatch log for <strong id="guestName" class="text-slate-800"></strong>? This record will be permanently removed.
        </p>
        <form id="deleteForm" method="POST" class="flex items-center justify-center gap-3">
            @csrf
            @method('DELETE')
            <button id="deleteCancel" type="button" class="modern-button modern-button-ghost">
                Cancel
            </button>
            <button type="submit" class="modern-button modern-button-danger">
                Delete Log
            </button>
        </form>
    </div>
</div>

<script>
function filterInvitationTable() {
    const searchVal = (document.getElementById('invitationSearchInput')?.value || '').toLowerCase();
    const statusVal = (document.getElementById('invitationStatusFilter')?.value || '').toLowerCase();
    const rows = document.querySelectorAll('.invitation-row');

    rows.forEach(row => {
        const guest = (row.querySelector('.invitation-guest')?.textContent || '').toLowerCase();
        const phone = (row.querySelector('.invitation-phone')?.textContent || '').toLowerCase();
        const status = (row.getAttribute('data-status') || '').toLowerCase();

        const matchesSearch = !searchVal || guest.includes(searchVal) || phone.includes(searchVal);
        const matchesStatus = !statusVal || status === statusVal;

        if (matchesSearch && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function confirmDelete(invitationId, guestName, deleteUrl) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    const nameSpan = document.getElementById('guestName');
    
    if (nameSpan) nameSpan.textContent = guestName;
    if (form) form.action = deleteUrl;
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function hideDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteModal');
    const cancelBtn = document.getElementById('deleteCancel');
    if (cancelBtn) cancelBtn.addEventListener('click', hideDeleteModal);
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) hideDeleteModal();
        });
    }
});

function testWhatsAppConnection() {
    fetch('{{ route("host.whatsapp.test") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message || 'WhatsApp Gateway status check completed!');
    })
    .catch(error => {
        alert('Could not connect to WhatsApp gateway. Please check host settings.');
    });
}
</script>
@endsection
