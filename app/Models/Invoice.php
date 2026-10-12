<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'host_id',
        'event_id',
        'invoice_number',
        'guest_count',
        'amount_per_guest',
        'total_amount',
        'amount_paid',
        'status',
        'due_date',
        'paid_at',
        'payment_method',
        'transaction_reference',
        'payment_receipt',
        'payer_name',
        'payer_phone',
        'admin_notes',
        'verified_by',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'amount_per_guest' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'due_date' => 'date',
            'paid_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Get the host that owns the invoice.
     */
    public function host()
    {
        return $this->belongsTo(Host::class);
    }

    /**
     * Get the user that owns the invoice (if applicable).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event for the invoice.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the admin user who verified the payment.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Check if invoice is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if payment proof has been submitted and is under review.
     */
    public function isUnderReview(): bool
    {
        return $this->status === 'under_review';
    }

    /**
     * Check if invoice is pending payment.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Generate unique invoice number.
     */
    public static function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $prefix = "INV-{$year}-";
        
        $latest = self::where('invoice_number', 'LIKE', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($latest) {
            $lastNumber = (int) str_replace($prefix, '', $latest->invoice_number);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }

        return $prefix . $newNumber;
    }

    /**
     * Get badge color class for status.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-200 ring-emerald-500/20',
            'under_review' => 'bg-blue-100 text-blue-800 border-blue-200 ring-blue-500/20',
            'pending' => 'bg-amber-100 text-amber-800 border-amber-200 ring-amber-500/20',
            'cancelled', 'rejected' => 'bg-rose-100 text-rose-800 border-rose-200 ring-rose-500/20',
            default => 'bg-slate-100 text-slate-800 border-slate-200 ring-slate-500/20',
        };
    }

    /**
     * Get formatted readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'Paid & Verified',
            'under_review' => 'Under Review',
            'pending' => 'Payment Required',
            'cancelled' => 'Cancelled',
            'rejected' => 'Rejected',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }
}
