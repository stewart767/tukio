@extends('layouts.host')

@section('title', 'Billing & Payments')
@section('page-title', 'Billing & Event Payment')

@section('content')
<div class="space-y-8 animate-fade-in max-w-7xl mx-auto">

    <!-- Payment Status Alert Banner -->
    @if($invoice->isPaid())
        <div class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 p-6 text-white shadow-lg shadow-emerald-500/20 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shrink-0">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-xl font-bold font-heading">Event Payment Verified</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-white text-emerald-800 uppercase tracking-wider">Paid</span>
                    </div>
                    <p class="text-emerald-100 text-sm mt-1">Invoice <strong>#{{ $invoice->invoice_number }}</strong> has been fully settled. Your event is active and all digital invitation features are unlocked.</p>
                </div>
            </div>
            <a href="{{ route('host.payments.invoice') }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-emerald-700 hover:bg-emerald-50 font-bold rounded-xl text-sm transition-all shadow-md shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                View / Print Receipt
            </a>
        </div>
    @elseif($invoice->isUnderReview())
        <div class="rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 p-6 text-white shadow-lg shadow-blue-500/20 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shrink-0 animate-pulse">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-xl font-bold font-heading">Payment Verification In Progress</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/25 text-white uppercase tracking-wider">Under Review</span>
                    </div>
                    <p class="text-blue-100 text-sm mt-1">Transaction Reference: <strong class="font-mono bg-white/20 px-2 py-0.5 rounded text-white">{{ $invoice->transaction_reference }}</strong>. Our billing desk is confirming the receipt. Thank you for your patience.</p>
                </div>
            </div>
            <a href="#submit-proof" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 border border-white/30 text-white font-semibold rounded-xl text-sm transition-all shrink-0">
                Update Payment Reference
            </a>
        </div>
    @else
        <div class="rounded-2xl bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 p-6 text-white shadow-lg shadow-amber-500/20 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shrink-0">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-xl font-bold font-heading">Action Required: Event Fee Payment</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-white text-amber-900 uppercase tracking-wider">Payment Pending</span>
                    </div>
                    <p class="text-amber-100 text-sm mt-1">Total Due: <strong class="text-white text-base">{{ number_format($invoice->total_amount) }} TZS</strong>. Scan the Selcom QR code or pay to Lipa number <strong>{{ $paymentSettings['selcom_number'] }}</strong> below.</p>
                </div>
            </div>
            <a href="#selcom-card" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-amber-700 hover:bg-amber-50 font-bold rounded-xl text-sm transition-all shadow-md shrink-0 transform hover:scale-105">
                <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Pay With Selcom Now
            </a>
        </div>
    @endif

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Invoice Details & Payment Guides (7 Cols) -->
        <div class="lg:col-span-7 space-y-8">
            
            <!-- Invoice Summary Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 md:p-8">
                <div class="flex items-center justify-between border-b border-slate-100 pb-6 mb-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-primary-600 bg-primary-50 px-3 py-1 rounded-full">Invoice Breakdown</span>
                        <h2 class="text-2xl font-bold text-slate-900 font-heading mt-2">Invoice #{{ $invoice->invoice_number }}</h2>
                        <p class="text-sm text-slate-500">Issued for: <strong class="text-slate-700">{{ $event->title ?? $host->full_name . ' Event' }}</strong></p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $invoice->status_badge_class }} border">
                            {{ $invoice->status_label }}
                        </span>
                        <p class="text-xs text-slate-400 mt-1">Date: {{ $invoice->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <!-- Event Specs -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6 bg-slate-50/80 rounded-xl p-4 border border-slate-100 text-sm">
                    <div>
                        <span class="text-xs text-slate-400 block font-medium">Event Type</span>
                        <span class="font-semibold text-slate-800">{{ ucfirst(str_replace('_', ' ', $host->event_type)) }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block font-medium">Guest Quota</span>
                        <span class="font-semibold text-slate-800">{{ $invoice->guest_count }} Guests</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block font-medium">Event Date</span>
                        <span class="font-semibold text-slate-800">{{ $event->date ? $event->date->format('M d, Y') : 'TBD' }}</span>
                    </div>
                </div>

                <!-- Line Items Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-400">
                                <th class="pb-3">Service Description</th>
                                <th class="pb-3 text-center">Qty / Guests</th>
                                <th class="pb-3 text-right">Unit Rate</th>
                                <th class="pb-3 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="py-4">
                                    <div class="font-semibold text-slate-800">Event Invitation & Guest System</div>
                                    <div class="text-xs text-slate-500">Includes WhatsApp delivery, QR guest check-in, RSVP tracking</div>
                                </td>
                                <td class="py-4 text-center font-mono font-medium text-slate-700">{{ $invoice->guest_count }}</td>
                                <td class="py-4 text-right font-mono text-slate-700">{{ number_format($invoice->amount_per_guest) }} TZS</td>
                                <td class="py-4 text-right font-mono font-bold text-slate-900">{{ number_format($invoice->total_amount) }} TZS</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-slate-200">
                                <td colspan="3" class="pt-4 text-right font-bold text-slate-700 text-base">Total Due:</td>
                                <td class="pt-4 text-right font-mono font-black text-slate-900 text-xl text-primary-600">{{ number_format($invoice->total_amount) }} TZS</td>
                            </tr>
                            @if($invoice->amount_paid > 0)
                            <tr class="text-emerald-700">
                                <td colspan="3" class="pt-2 text-right font-semibold text-sm">Amount Paid:</td>
                                <td class="pt-2 text-right font-mono font-bold text-sm">{{ number_format($invoice->amount_paid) }} TZS</td>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                    <a href="{{ route('host.payments.invoice') }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download Official Invoice (PDF / Print)
                    </a>
                    <span class="text-xs text-slate-400">Payment currency: Tanzanian Shilling (TZS)</span>
                </div>
            </div>

            <!-- How to Pay Guide (Accordion / Tabs) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">How to Pay via Selcom Pesa</h3>
                        <p class="text-xs text-slate-500">Pay from any mobile network or banking application in Tanzania</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <!-- Option 1: Selcom / M-Pesa / Tigo / Airtel -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-primary-600 text-white text-xs flex items-center justify-center font-bold">1</span>
                            Via Mobile Money (M-Pesa, Tigo, Airtel)
                        </div>
                        <ol class="list-decimal list-inside space-y-1.5 text-xs text-slate-600 leading-relaxed">
                            <li>Open your SIM Toolkit / Mobile Money menu (e.g. *150*00#).</li>
                            <li>Choose <strong>Lipa kwa Simu / Lipa Namba</strong> (or Pay Merchant / Selcom).</li>
                            <li>Enter Lipa Number: <strong class="font-mono text-primary-700 select-all font-bold">{{ $paymentSettings['selcom_number'] }}</strong></li>
                            <li>Enter Exact Amount: <strong>{{ number_format($invoice->total_amount) }} TZS</strong></li>
                            <li>Confirm Beneficiary Name: <strong>{{ $paymentSettings['selcom_beneficiary'] }}</strong></li>
                            <li>Enter PIN to complete payment.</li>
                        </ol>
                    </div>

                    <!-- Option 2: Selcom Pesa QR Scan -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-primary-600 text-white text-xs flex items-center justify-center font-bold">2</span>
                            Via Selcom Pesa App / Bank App
                        </div>
                        <ol class="list-decimal list-inside space-y-1.5 text-xs text-slate-600 leading-relaxed">
                            <li>Open your <strong>Selcom Pesa</strong> app, CRDB SimBanking, or NMB Mkononi.</li>
                            <li>Select <strong>Scan QR Code</strong>.</li>
                            <li>Scan the QR Code displayed on the right.</li>
                            <li>Confirm amount: <strong>{{ number_format($invoice->total_amount) }} TZS</strong>.</li>
                            <li>Confirm beneficiary: <strong>{{ $paymentSettings['selcom_beneficiary'] }}</strong>.</li>
                            <li>Save the SMS or screenshot confirmation code.</li>
                        </ol>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Selcom QR Code & Proof Submission (5 Cols) -->
        <div class="lg:col-span-5 space-y-8">
            
            <!-- Selcom Payment Card (Dark & Premium Glass Design) -->
            <div id="selcom-card" class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white rounded-3xl p-6 md:p-8 shadow-xl border border-slate-700/60 relative overflow-hidden">
                <!-- Background Accent Glow -->
                <div class="absolute -top-24 -right-24 w-60 h-60 bg-primary-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-60 h-60 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Header -->
                <div class="flex items-center justify-between mb-6 relative z-10">
                    <div class="flex items-center gap-2">
                        <div class="px-3 py-1 rounded-lg bg-white/10 backdrop-blur-md border border-white/20 text-xs font-bold uppercase tracking-wider text-emerald-400">
                            Selcom Pesa
                        </div>
                    </div>
                    <span class="text-xs text-slate-400 font-mono font-medium">Lipa Hapa</span>
                </div>

                <!-- QR Code Box -->
                <div class="bg-white rounded-2xl p-4 text-center mx-auto max-w-[260px] shadow-2xl relative group cursor-pointer transition-transform duration-300 hover:scale-[1.02]">
                    <img src="{{ asset($paymentSettings['selcom_qr_code']) }}" 
                         alt="Selcom Pesa QR Code" 
                         class="w-full h-auto aspect-square object-contain mx-auto rounded-xl"
                         onerror="this.onerror=null; this.src='{{ asset('images/payments/selcom-qr.jpg') }}';">
                    
                    <div class="mt-2 text-[11px] font-semibold text-slate-600">
                        Scan to add <strong class="text-slate-900">{{ $paymentSettings['selcom_beneficiary'] }}</strong>
                    </div>
                </div>

                <!-- Lipa Number & Beneficiary Info -->
                <div class="mt-6 space-y-4 relative z-10">
                    
                    <!-- Copyable Lipa Number -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10 flex items-center justify-between gap-3">
                        <div>
                            <span class="text-[11px] uppercase tracking-wider font-bold text-slate-300 block">Lipa / Selcom Number</span>
                            <span id="selcom-number-text" class="text-2xl font-black font-mono tracking-wider text-white select-all">{{ $paymentSettings['selcom_number'] }}</span>
                        </div>
                        <button type="button" 
                                onclick="copySelcomNumber('{{ $paymentSettings['selcom_number'] }}')" 
                                id="copy-btn"
                                class="px-3.5 py-2 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-bold text-xs flex items-center gap-1.5 transition-all shadow-md shrink-0 active:scale-95">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span id="copy-text">Copy</span>
                        </button>
                    </div>

                    <!-- Beneficiary Details -->
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="bg-white/5 rounded-xl p-3 border border-white/5">
                            <span class="text-slate-400 block text-[10px] uppercase tracking-wider font-medium">Beneficiary Name</span>
                            <span class="font-bold text-slate-200 mt-0.5 block truncate">{{ $paymentSettings['selcom_beneficiary'] }}</span>
                        </div>
                        <div class="bg-white/5 rounded-xl p-3 border border-white/5">
                            <span class="text-slate-400 block text-[10px] uppercase tracking-wider font-medium">Amount to Pay</span>
                            <span class="font-black text-emerald-400 font-mono text-sm mt-0.5 block">{{ number_format($invoice->total_amount) }} TZS</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Payment Proof Form Card -->
            <div id="submit-proof" class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Submit Payment Verification</h3>
                        <p class="text-xs text-slate-500">Already paid? Submit your transaction reference code for instant verification</p>
                    </div>
                </div>

                <form action="{{ route('host.payments.submit-proof') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label for="transaction_reference" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Transaction Reference / Code <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="transaction_reference" 
                               name="transaction_reference" 
                               value="{{ old('transaction_reference', $invoice->transaction_reference) }}" 
                               required 
                               placeholder="e.g. 9AK382109 or MP260908.1234"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono text-sm tracking-wider uppercase bg-slate-50/50">
                        <p class="text-[11px] text-slate-400 mt-1">Enter the transaction ID received in your Selcom / M-Pesa / Tigo SMS</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="payment_method" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Payment Method <span class="text-rose-500">*</span>
                            </label>
                            <select id="payment_method" name="payment_method" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm bg-slate-50/50">
                                <option value="selcom_pesa" @selected(old('payment_method', $invoice->payment_method) === 'selcom_pesa')>Selcom Pesa / QR</option>
                                <option value="mpesa" @selected(old('payment_method', $invoice->payment_method) === 'mpesa')>Vodacom M-Pesa</option>
                                <option value="tigo_pesa" @selected(old('payment_method', $invoice->payment_method) === 'tigo_pesa')>Tigo Pesa</option>
                                <option value="airtel_money" @selected(old('payment_method', $invoice->payment_method) === 'airtel_money')>Airtel Money</option>
                                <option value="halopesa" @selected(old('payment_method', $invoice->payment_method) === 'halopesa')>HaloPesa</option>
                                <option value="bank_transfer" @selected(old('payment_method', $invoice->payment_method) === 'bank_transfer')>Bank Transfer</option>
                            </select>
                        </div>

                        <div>
                            <label for="payer_phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Payer Phone Number
                            </label>
                            <input type="text" 
                                   id="payer_phone" 
                                   name="payer_phone" 
                                   value="{{ old('payer_phone', $invoice->payer_phone ?? $host->phone) }}" 
                                   placeholder="0750 000 000"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm bg-slate-50/50">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="payer_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Payer Full Name
                            </label>
                            <input type="text" 
                                   id="payer_name" 
                                   name="payer_name" 
                                   value="{{ old('payer_name', $invoice->payer_name ?? $host->full_name) }}" 
                                   placeholder="Name on mobile money"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm bg-slate-50/50">
                        </div>

                        <div>
                            <label for="amount_paid" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Amount Paid (TZS)
                            </label>
                            <input type="number" 
                                   id="amount_paid" 
                                   name="amount_paid" 
                                   value="{{ old('amount_paid', $invoice->amount_paid ?: $invoice->total_amount) }}" 
                                   step="100"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono text-sm bg-slate-50/50">
                        </div>
                    </div>

                    <div>
                        <label for="receipt_image" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Upload Receipt / Screenshot (Optional)
                        </label>
                        <input type="file" 
                               id="receipt_image" 
                               name="receipt_image" 
                               accept="image/*"
                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                        @if($invoice->payment_receipt)
                            <div class="mt-2 text-xs text-slate-500 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Receipt uploaded: <a href="{{ asset('storage/' . $invoice->payment_receipt) }}" target="_blank" class="text-primary-600 underline font-medium">View File</a></span>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-bold text-sm shadow-lg shadow-primary-500/25 transition-all transform hover:-translate-y-0.5 mt-2 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Submit Payment Verification
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>

@push('scripts')
<script>
function copySelcomNumber(number) {
    navigator.clipboard.writeText(number).then(function() {
        const copyText = document.getElementById('copy-text');
        const copyBtn = document.getElementById('copy-btn');
        if (copyText) {
            copyText.textContent = 'Copied!';
            copyBtn.classList.remove('bg-primary-600', 'hover:bg-primary-500');
            copyBtn.classList.add('bg-emerald-600', 'hover:bg-emerald-500');
            setTimeout(() => {
                copyText.textContent = 'Copy';
                copyBtn.classList.remove('bg-emerald-600', 'hover:bg-emerald-500');
                copyBtn.classList.add('bg-primary-600', 'hover:bg-primary-500');
            }, 2500);
        }
    }).catch(function(err) {
        alert('Lipa Number: ' + number);
    });
}
</script>
@endpush

@endsection
