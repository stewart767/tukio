@extends('layouts.dashboard')

@section('title', 'Invoice Details #' . $invoice->invoice_number)
@section('page-title', 'Invoice Details')

@section('content')
<div class="space-y-8 animate-fade-in max-w-5xl mx-auto">

    <!-- Top Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Payments List
        </a>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $invoice->status_badge_class }} border">
                {{ $invoice->status_label }}
            </span>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left 2 Cols: Details & Receipt Viewer -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Invoice Details Card -->
            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-slate-200/80">
                <div class="border-b border-slate-100 pb-6 mb-6">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">Invoice #{{ $invoice->invoice_number }}</span>
                    <h2 class="text-2xl font-black text-slate-900 font-heading mt-3">{{ $invoice->event?->title ?? 'Host Event' }}</h2>
                    <p class="text-xs text-slate-500 mt-1">Host: <strong class="text-slate-800">{{ $invoice->host?->full_name }}</strong> ({{ $invoice->host?->email }} | {{ $invoice->host?->phone }})</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm bg-slate-50 rounded-xl p-4 border border-slate-100 mb-6">
                    <div>
                        <span class="text-xs text-slate-400 block font-medium">Guest Quota</span>
                        <span class="font-bold text-slate-800">{{ $invoice->guest_count }} Guests</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block font-medium">Rate / Guest</span>
                        <span class="font-bold text-slate-800">{{ number_format($invoice->amount_per_guest) }} TZS</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block font-medium">Total Amount</span>
                        <span class="font-black text-indigo-600 font-mono">{{ number_format($invoice->total_amount) }} TZS</span>
                    </div>
                </div>

                <!-- Transaction Details -->
                <div class="space-y-3 text-sm">
                    <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider text-slate-400">Transaction Submission Info</h4>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Reference ID</span>
                            <span class="font-mono font-bold text-slate-900 text-sm">{{ $invoice->transaction_reference ?? 'Not submitted' }}</span>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Payment Method</span>
                            <span class="font-bold text-slate-900 text-sm">{{ ucfirst(str_replace('_', ' ', $invoice->payment_method ?? 'Selcom Pesa')) }}</span>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Payer Phone</span>
                            <span class="font-mono font-bold text-slate-900 text-sm">{{ $invoice->payer_phone ?? $invoice->host?->phone }}</span>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Amount Paid</span>
                            <span class="font-mono font-bold text-emerald-600 text-sm">{{ number_format($invoice->amount_paid ?: $invoice->total_amount) }} TZS</span>
                        </div>
                    </div>

                    @if($invoice->admin_notes)
                    <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 mt-3">
                        <strong class="block font-bold">Admin Note:</strong>
                        <p class="mt-0.5">{{ $invoice->admin_notes }}</p>
                    </div>
                    @endif

                    @if($invoice->verified_by)
                    <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 mt-3 flex items-center justify-between">
                        <div>
                            <span class="font-bold">Verified by:</span> {{ $invoice->verifiedBy?->name ?? 'Admin #' . $invoice->verified_by }}
                        </div>
                        <div class="font-mono text-[11px] text-emerald-700">
                            {{ $invoice->verified_at?->format('M d, Y H:i') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Receipt Screenshot Viewer -->
            @if($invoice->payment_receipt)
            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-slate-200/80">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-slate-900 font-heading">Payment Receipt Attachment</h3>
                    <a href="{{ asset('storage/' . $invoice->payment_receipt) }}" target="_blank" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 underline">Open Full Size</a>
                </div>
                <div class="rounded-xl overflow-hidden border border-slate-200 bg-slate-50 max-h-96 flex items-center justify-center p-2">
                    <img src="{{ asset('storage/' . $invoice->payment_receipt) }}" alt="Receipt" class="max-h-80 object-contain rounded-lg shadow-sm">
                </div>
            </div>
            @endif

        </div>

        <!-- Right 1 Col: Verification Actions & Selcom Details -->
        <div class="space-y-6">
            
            <!-- Verify Action Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 space-y-4">
                <h3 class="font-bold text-slate-900 font-heading">Payment Verification</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Confirm that the host has transferred the required event fee to Selcom account <strong>{{ $paymentSettings['selcom_number'] }}</strong>.</p>

                @if(!$invoice->isPaid())
                <form action="{{ route('admin.payments.verify', $invoice->id) }}" method="POST" class="space-y-3 pt-2">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-700 mb-1">Confirmed Amount (TZS)</label>
                        <input type="number" name="amount_paid" value="{{ $invoice->amount_paid ?: $invoice->total_amount }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono font-bold">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-700 mb-1">Verification Note (Optional)</label>
                        <textarea name="admin_notes" rows="2" placeholder="e.g. Verified on Selcom portal" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">{{ $invoice->admin_notes }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Approve &amp; Mark as PAID
                    </button>
                </form>

                <form action="{{ route('admin.payments.reject', $invoice->id) }}" method="POST" class="space-y-2 pt-4 border-t border-slate-100">
                    @csrf
                    <label class="block text-[11px] font-bold uppercase text-slate-700">Reject / Request Re-submission</label>
                    <input type="text" name="admin_notes" required placeholder="Reason for rejection (e.g. invalid reference)" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs mb-2">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition-all">
                        Reject Submission
                    </button>
                </form>
                @else
                <div class="rounded-xl bg-emerald-50 p-4 border border-emerald-200 text-center">
                    <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="font-bold text-emerald-800 text-sm block">Payment Verified</span>
                    <span class="text-xs text-emerald-600">Settled on {{ $invoice->paid_at?->format('M d, Y') }}</span>
                </div>
                @endif
            </div>

            <!-- Selcom Details Quick Box -->
            <div class="bg-slate-900 text-white rounded-2xl p-6 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 block">Selcom Pesa Receiver</span>
                <div class="text-xl font-black font-mono tracking-wider">{{ $paymentSettings['selcom_number'] }}</div>
                <div class="text-xs text-slate-300">Beneficiary: <strong>{{ $paymentSettings['selcom_beneficiary'] }}</strong></div>
            </div>

        </div>

    </div>

</div>
@endsection
