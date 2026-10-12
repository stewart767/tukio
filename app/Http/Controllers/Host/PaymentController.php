<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\SystemSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function __construct(
        protected SystemSettingsService $settings
    ) {}

    /**
     * Show the host payment / billing overview.
     */
    public function index()
    {
        $host = Auth::guard('host')->user();

        if (!$host->hasConfiguredEvent()) {
            return redirect()->route('host.event-config')->with('error', 'Please configure your event first.');
        }

        $event = $host->events()->latest()->first();
        $invoice = $host->ensureInvoice();
        $paymentSettings = $this->settings->getPaymentSettings();

        return view('host.payments.index', compact('host', 'event', 'invoice', 'paymentSettings'));
    }

    /**
     * Submit payment transaction reference or receipt for verification.
     */
    public function submitProof(Request $request)
    {
        $host = Auth::guard('host')->user();
        $invoice = $host->ensureInvoice();

        if (!$invoice) {
            return redirect()->route('host.dashboard')->with('error', 'No active invoice found.');
        }

        $request->validate([
            'transaction_reference' => 'required|string|max:100',
            'payer_phone' => 'nullable|string|max:30',
            'payer_name' => 'nullable|string|max:100',
            'payment_method' => 'required|string|max:50',
            'amount_paid' => 'nullable|numeric|min:1',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $receiptPath = $invoice->payment_receipt;

        if ($request->hasFile('receipt_image')) {
            $file = $request->file('receipt_image');
            $filename = 'receipt_' . time() . '_' . $host->id . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/payment_receipts', $filename);
            $receiptPath = 'payment_receipts/' . $filename;
        }

        $amountPaid = $request->amount_paid ? (float) $request->amount_paid : (float) $invoice->total_amount;

        $invoice->update([
            'transaction_reference' => strtoupper(trim($request->transaction_reference)),
            'payer_phone' => $request->payer_phone ?: $host->phone,
            'payer_name' => $request->payer_name ?: $host->full_name,
            'payment_method' => $request->payment_method,
            'amount_paid' => $amountPaid,
            'payment_receipt' => $receiptPath,
            'status' => 'under_review',
        ]);

        return redirect()->route('host.payments.index')
            ->with('success', 'Your Selcom payment reference (' . $invoice->transaction_reference . ') has been submitted successfully. Our team will verify and confirm your event payment shortly.');
    }

    /**
     * View or print invoice document.
     */
    public function invoice()
    {
        $host = Auth::guard('host')->user();
        $event = $host->events()->latest()->first();
        $invoice = $host->ensureInvoice();
        $paymentSettings = $this->settings->getPaymentSettings();
        $publicSettings = $this->settings->getPublicPayload();

        return view('host.payments.invoice', compact('host', 'event', 'invoice', 'paymentSettings', 'publicSettings'));
    }
}
