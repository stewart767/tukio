<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'host_id',
        'staff_id',
        'title',
        'description',
        'category',
        'date',
        'time',
        'venue',
        'venue_address',
        'template_id',
        'status',
        'event_password',
        'guest_count',
        'total_cost',
        'custom_data',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'total_cost' => 'decimal:2',
            'custom_data' => 'array',
        ];
    }

    /**
     * Get the user that owns the event.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the host that owns the event.
     */
    public function host()
    {
        return $this->belongsTo(Host::class);
    }

    /**
     * Get the staff assigned to the event (single staff - legacy).
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Get all staff members assigned to the event (many-to-many).
     */
    public function staffMembers()
    {
        return $this->belongsToMany(User::class, 'event_staff', 'event_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Get the template for the event.
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Get the guests for the event.
     */
    public function guests()
    {
        return $this->hasMany(Guest::class);
    }

    /**
     * Get the invitations for the event.
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * Get the invoice for the event.
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Get the car bookings for the event.
     */
    public function carBookings()
    {
        return $this->hasMany(CarBooking::class);
    }

    /**
     * Ensure this event has an invoice created.
     */
    public function ensureInvoice(): Invoice
    {
        $invoice = $this->invoice;
        $pricePerGuest = (float) app(\App\Services\SystemSettingsService::class)->get('payment.price_per_guest', 2000.00);
        if ($pricePerGuest <= 0) {
            $pricePerGuest = 2000.00;
        }

        $guestCount = $this->guest_count ?: ($this->host?->number_of_guests ?: 1);
        $totalAmount = $this->total_cost ?: ($guestCount * $pricePerGuest);

        if (!$invoice) {
            $invoice = Invoice::create([
                'host_id' => $this->host_id,
                'user_id' => $this->user_id,
                'event_id' => $this->id,
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'guest_count' => $guestCount,
                'amount_per_guest' => $pricePerGuest,
                'total_amount' => $totalAmount,
                'amount_paid' => 0.00,
                'status' => 'pending',
                'due_date' => $this->date ?: now()->addDays(14),
                'payment_method' => 'selcom_pesa',
            ]);
        }

        return $invoice;
    }
}
