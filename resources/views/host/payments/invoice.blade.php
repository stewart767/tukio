<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number }} - {{ config('app.name', 'Tukio 360') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; color: #000 !important; }
            .print-shadow-none { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800 py-10 min-h-screen">
    <div class="max-w-3xl mx-auto">
        <!-- Action Bar -->
        <div class="no-print mb-6 flex items-center justify-between">
            <a href="{{ route('host.payments.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Billing
            </a>
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="inline-flex items-center gap-2 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 px-5 py-2.5 rounded-xl shadow-md transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print / Save PDF
                </button>
            </div>
        </div>

        <!-- Invoice Paper -->
        <div class="bg-white rounded-3xl p-8 md:p-12 shadow-xl border border-slate-200 print-shadow-none">
            
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-100 pb-8 gap-6">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-600 text-white flex items-center justify-center font-black text-xl shadow-md">
                            T
                        </div>
                        <span class="text-2xl font-black text-slate-900 font-heading">Tukio<span class="text-primary-600">360</span></span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Digital Event Invitations & Guest Management</p>
                    <p class="text-xs text-slate-500">{{ $publicSettings['phone'] ?? '+255 662 199 071' }} | {{ $publicSettings['email'] ?? 'info@mycard.co.tz' }}</p>
                </div>
                <div class="sm:text-right">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Official Invoice</span>
                    <h1 class="text-2xl font-black font-mono text-slate-900 mt-0.5">#{{ $invoice->invoice_number }}</h1>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $invoice->status_badge_class }} border">
                            {{ $invoice->status_label }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Billed To / Dates -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 py-8 border-b border-slate-100 text-sm">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-2">Billed To (Host)</span>
                    <h3 class="font-bold text-slate-900 text-base">{{ $host->full_name }}</h3>
                    <p class="text-slate-600">{{ $host->email }}</p>
                    <p class="text-slate-600">{{ $host->phone }}</p>
                    <p class="text-slate-500 text-xs mt-1">Event: <strong class="text-slate-700">{{ $event->title ?? $host->full_name . ' Event' }}</strong> ({{ ucfirst(str_replace('_', ' ', $host->event_type)) }})</p>
                </div>
                <div class="sm:text-right space-y-1">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-2">Invoice Details</span>
                    <p class="text-slate-600 text-xs">Invoice Date: <strong class="text-slate-800">{{ $invoice->created_at->format('M d, Y') }}</strong></p>
                    <p class="text-slate-600 text-xs">Event Date: <strong class="text-slate-800">{{ $event->date ? $event->date->format('M d, Y') : 'TBD' }}</strong></p>
                    @if($invoice->transaction_reference)
                    <p class="text-slate-600 text-xs">Payment Ref: <strong class="font-mono text-primary-600">{{ $invoice->transaction_reference }}</strong></p>
                    @endif
                    @if($invoice->paid_at)
                    <p class="text-emerald-700 text-xs font-bold">Paid On: {{ $invoice->paid_at->format('M d, Y H:i') }}</p>
                    @endif
                </div>
            </div>

            <!-- Table of Items -->
            <div class="py-8">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-400">
                            <th class="pb-3">Item / Service</th>
                            <th class="pb-3 text-center">Guest Quota</th>
                            <th class="pb-3 text-right">Rate</th>
                            <th class="pb-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="py-4">
                                <span class="font-bold text-slate-800 block">Digital Invitation & Host Platform Access</span>
                                <span class="text-xs text-slate-500">Includes QR attendance passes, automated WhatsApp invitations, guest dashboard</span>
                            </td>
                            <td class="py-4 text-center font-mono text-slate-700">{{ $invoice->guest_count }}</td>
                            <td class="py-4 text-right font-mono text-slate-700">{{ number_format($invoice->amount_per_guest) }} TZS</td>
                            <td class="py-4 text-right font-mono font-bold text-slate-900">{{ number_format($invoice->total_amount) }} TZS</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-slate-200">
                            <td colspan="3" class="pt-4 text-right font-bold text-slate-700 text-base">Grand Total:</td>
                            <td class="pt-4 text-right font-mono font-black text-primary-600 text-xl">{{ number_format($invoice->total_amount) }} TZS</td>
                        </tr>
                        @if($invoice->isPaid())
                        <tr class="text-emerald-700">
                            <td colspan="3" class="pt-1 text-right font-bold text-sm">Total Paid:</td>
                            <td class="pt-1 text-right font-mono font-bold text-sm">{{ number_format($invoice->amount_paid ?: $invoice->total_amount) }} TZS</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="pt-1 text-right font-bold text-slate-500 text-sm">Balance Due:</td>
                            <td class="pt-1 text-right font-mono font-bold text-slate-800 text-sm">0.00 TZS</td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>

            <!-- Payment Instructions Box -->
            <div class="mt-6 p-6 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-6 text-xs">
                <div class="space-y-1">
                    <span class="font-bold uppercase tracking-wider text-slate-500 block">Payment Channel</span>
                    <p class="font-bold text-slate-800 text-sm">Selcom Pesa / Lipa Namba</p>
                    <p class="text-slate-600">Lipa Number: <strong class="font-mono text-slate-900 font-bold text-base">{{ $paymentSettings['selcom_number'] }}</strong></p>
                    <p class="text-slate-600">Beneficiary: <strong class="text-slate-900">{{ $paymentSettings['selcom_beneficiary'] }}</strong></p>
                </div>
                <div class="text-center shrink-0">
                    <img src="{{ asset($paymentSettings['selcom_qr_code']) }}" 
                         alt="Selcom QR" 
                         class="w-24 h-24 object-contain rounded-xl border border-slate-200 bg-white p-1 mx-auto shadow-sm"
                         onerror="this.onerror=null; this.src='{{ asset('images/payments/selcom-qr.jpg') }}';">
                    <span class="text-[10px] text-slate-400 block mt-1">Scan to Pay</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs text-slate-400">
                <p>Thank you for choosing {{ config('app.name', 'Tukio 360') }} for your special occasion!</p>
                <p class="mt-1">Need assistance? Contact support at {{ $publicSettings['phone'] ?? '+255 662 199 071' }} or WhatsApp {{ $publicSettings['whatsapp'] ?? '+255 662 199 071' }}</p>
            </div>

        </div>
    </div>
</body>
</html>
