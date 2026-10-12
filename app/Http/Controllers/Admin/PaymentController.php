<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\SystemSettingsService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        protected SystemSettingsService $settings
    ) {}

    /**
     * Display a listing of host invoices and payments.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search');

        $query = Invoice::with(['host', 'event', 'verifiedBy'])->latest();

        if ($status !== 'all' && !empty($status)) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('transaction_reference', 'like', "%{$search}%")
                  ->orWhere('payer_name', 'like', "%{$search}%")
                  ->orWhere('payer_phone', 'like', "%{$search}%")
                  ->orWhereHas('host', function ($hq) use ($search) {
                      $hq->where('full_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhereHas('event', function ($eq) use ($search) {
                      $eq->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->paginate(15)->withQueryString();

        // Calculate summary statistics
        $stats = [
            'total_revenue' => Invoice::where('status', 'paid')->sum('amount_paid'),
            'under_review_count' => Invoice::where('status', 'under_review')->count(),
            'pending_count' => Invoice::where('status', 'pending')->count(),
            'paid_count' => Invoice::where('status', 'paid')->count(),
            'total_invoices' => Invoice::count(),
        ];

        $paymentSettings = $this->settings->getPaymentSettings();

        return view('admin.payments.index', compact('invoices', 'stats', 'status', 'search', 'paymentSettings'));
    }

    /**
     * Display single invoice details.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['host', 'event', 'verifiedBy']);
        $paymentSettings = $this->settings->getPaymentSettings();
        $publicSettings = $this->settings->getPublicPayload();

        return view('admin.payments.show', compact('invoice', 'paymentSettings', 'publicSettings'));
    }

    /**
     * Verify and approve a payment submission.
     */
    public function verify(Request $request, Invoice $invoice)
    {
        $amountPaid = $request->input('amount_paid', $invoice->amount_paid ?: $invoice->total_amount);

        $invoice->update([
            'status' => 'paid',
            'amount_paid' => (float) $amountPaid,
            'paid_at' => now(),
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'admin_notes' => $request->input('admin_notes', $invoice->admin_notes),
        ]);

        // If the associated event is draft/pending, make it active
        if ($invoice->event && $invoice->event->status !== 'active') {
            $invoice->event->update(['status' => 'active']);
        }

        return redirect()->back()->with('success', "Invoice {$invoice->invoice_number} has been verified and marked as PAID successfully.");
    }

    /**
     * Reject or request re-submission of payment reference.
     */
    public function reject(Request $request, Invoice $invoice)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $invoice->update([
            'status' => 'pending',
            'admin_notes' => $request->input('admin_notes'),
        ]);

        return redirect()->back()->with('warning', "Payment for invoice {$invoice->invoice_number} was rejected. Host has been notified to re-submit valid payment proof.");
    }
}
