@extends('layouts.host')

@section('title', 'WhatsApp & SMS Broadcast - ' . $website->title)
@section('page-title', 'Invitation Distribution')

@section('content')
<div class="space-y-6">

    <!-- Top Navigation Breadcrumbs -->
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('host.website.index') }}" class="hover:text-indigo-600 transition-colors font-semibold">&larr; Back to Website Hub</a>
            <span>/</span>
            <span class="text-slate-800 font-bold">WhatsApp &amp; SMS Broadcast</span>
        </div>
        <a href="{{ $website->public_url }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-xs font-bold transition-all border border-indigo-200/60">
            <span>View Public Website</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
    </div>

    <!-- Alert status -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('host.website.distribution.send') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left column: Message Compose & Channel -->
            <div class="lg:col-span-5 space-y-6">
                
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-5">
                    <h3 class="font-heading font-black text-lg text-slate-900 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">1</span>
                        <span>Select Channel &amp; Compose</span>
                    </h3>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Delivery Channel</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex flex-col p-3 rounded-2xl border-2 border-indigo-600 bg-indigo-50/50 cursor-pointer">
                                <div class="flex items-center gap-2 font-bold text-slate-900 text-sm">
                                    <input type="radio" name="channel" value="whatsapp_direct" checked class="text-indigo-600 focus:ring-indigo-500">
                                    <span>WhatsApp Direct</span>
                                </div>
                                <span class="text-[11px] text-slate-500 mt-1">One-click wa.me send &amp; status sharing</span>
                            </label>
                            <label class="flex flex-col p-3 rounded-2xl border border-slate-200 hover:border-indigo-300 cursor-pointer">
                                <div class="flex items-center gap-2 font-bold text-slate-900 text-sm">
                                    <input type="radio" name="channel" value="sms" class="text-indigo-600 focus:ring-indigo-500">
                                    <span>Beem SMS</span>
                                </div>
                                <span class="text-[11px] text-slate-500 mt-1">Direct SMS via Beem Gateway</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Personalized Message</label>
                            <span class="text-[11px] text-indigo-600 font-semibold">Available tags: {name}, {title}, {url}</span>
                        </div>
                        <textarea name="message_template" id="message-template" rows="7" required class="w-full rounded-2xl border border-slate-200 p-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $defaultMessage }}</textarea>
                    </div>

                    <!-- Live Preview Box -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Live Guest Preview (Example)</div>
                        <div class="text-xs text-slate-700 whitespace-pre-line leading-relaxed font-mono bg-white p-3 rounded-xl border border-slate-100" id="live-preview-box">
Hello Sarah,

You are warmly invited to celebrate {{ $website->title }}!

Please visit our official event website to view celebration details, schedule, location, and confirm your attendance:

{{ $website->public_url }}

We look forward to celebrating with you!
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-lg shadow-indigo-600/20 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span>Send Broadcast to Selected Guests</span>
                    </button>
                </div>

            </div>

            <!-- Right column: Recipient Selection Table -->
            <div class="lg:col-span-7 space-y-4">
                
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-heading font-black text-lg text-slate-900 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">2</span>
                                <span>Select Recipients ({{ $guests->count() }} Total)</span>
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Select all or filter guests who have not responded.</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button" onclick="selectAll(true)" class="px-2.5 py-1 rounded-lg text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors">Select All</button>
                            <button type="button" onclick="selectAll(false)" class="px-2.5 py-1 rounded-lg text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Clear</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto max-h-[550px] custom-scrollbar">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-500 sticky top-0 uppercase font-bold text-[10px] tracking-wider">
                                <tr>
                                    <th class="p-3 w-10">
                                        <input type="checkbox" id="header-select-all" onclick="selectAll(this.checked)" class="rounded text-indigo-600">
                                    </th>
                                    <th class="p-3">Guest Name</th>
                                    <th class="p-3">Phone</th>
                                    <th class="p-3">Current RSVP</th>
                                    <th class="p-3">Direct Send</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($guests as $guest)
                                    @php
                                        $guestRsvp = $rsvps->get($guest->phone);
                                        $waDirectUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $guest->phone) . "?text=" . urlencode(str_replace(['{name}', '{title}', '{url}'], [$guest->full_name, $website->title, $website->public_url], $defaultMessage));
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="p-3">
                                            <input type="checkbox" name="recipients[]" value="{{ $guest->id }}" class="guest-checkbox rounded text-indigo-600">
                                        </td>
                                        <td class="p-3 font-semibold text-slate-900">{{ $guest->full_name }}</td>
                                        <td class="p-3 text-slate-600 font-mono">{{ $guest->phone }}</td>
                                        <td class="p-3">
                                            @if($guestRsvp)
                                                @if($guestRsvp->attendance_status === 'attending')
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Attending (+{{ $guestRsvp->plus_one_count }})</span>
                                                @elseif($guestRsvp->attendance_status === 'declined')
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Declined</span>
                                                @else
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Tentative</span>
                                                @endif
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">Pending Response</span>
                                            @endif
                                        </td>
                                        <td class="p-3">
                                            <a href="{{ $waDirectUrl }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-[11px] transition-colors">
                                                <span>WhatsApp</span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-slate-400">
                                            No guests found for this event. Please add guests in Guest Management first.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>

<script>
    function selectAll(checked) {
        document.querySelectorAll('.guest-checkbox').forEach(cb => cb.checked = checked);
        const header = document.getElementById('header-select-all');
        if (header) header.checked = checked;
    }

    const templateInput = document.getElementById('message-template');
    const previewBox = document.getElementById('live-preview-box');
    if (templateInput && previewBox) {
        templateInput.addEventListener('input', function() {
            let val = templateInput.value;
            val = val.replace('{name}', 'Sarah');
            val = val.replace('{title}', @json($website->title));
            val = val.replace('{url}', @json($website->public_url));
            previewBox.textContent = val;
        });
    }
</script>
@endsection
