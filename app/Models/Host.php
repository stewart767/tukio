<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Host extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'event_type',
        'number_of_guests',
        'total_cost',
        'password',
        'is_active',
        'event_configured',
        'event_config_data',
        'event_finished_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'event_configured' => 'boolean',
            'event_config_data' => 'array',
            'total_cost' => 'decimal:2',
            'event_finished_at' => 'datetime',
        ];
    }

    /**
     * Get the events for the host.
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'host_id');
    }

    /**
     * Get the digital event website for this host's primary event.
     */
    public function eventWebsite(): ?EventWebsite
    {
        $event = $this->events()->latest()->first();
        return $event ? $event->ensureWebsite() : null;
    }

    /**
     * Get the car bookings for the host.
     */
    public function carBookings()
    {
        return $this->hasMany(CarBooking::class, 'host_id');
    }

    /**
     * Get the invoices for the host.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'host_id');
    }

    /**
     * Get the latest invoice for the host.
     */
    public function latestInvoice()
    {
        return $this->hasOne(Invoice::class, 'host_id')->latestOfMany();
    }

    /**
     * Ensure that the host has an active invoice for their event.
     */
    public function ensureInvoice(): ?Invoice
    {
        $event = $this->events()->latest()->first();
        if (!$event) {
            return null;
        }

        $invoice = $this->invoices()->where('event_id', $event->id)->first();
        
        $pricePerGuest = (float) app(\App\Services\SystemSettingsService::class)->get('payment.price_per_guest', 2000.00);
        if ($pricePerGuest <= 0) {
            $pricePerGuest = 2000.00;
        }

        $guestCount = $event->guest_count ?: ($this->number_of_guests ?: 1);
        $totalAmount = $event->total_cost ?: ($guestCount * $pricePerGuest);

        if (!$invoice) {
            $invoice = Invoice::create([
                'host_id' => $this->id,
                'event_id' => $event->id,
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'guest_count' => $guestCount,
                'amount_per_guest' => $pricePerGuest,
                'total_amount' => $totalAmount,
                'amount_paid' => 0.00,
                'status' => 'pending',
                'due_date' => $event->date ?: now()->addDays(14),
                'payment_method' => 'selcom_pesa',
            ]);
        } else {
            // Keep guest count and total amount in sync if pending
            if ($invoice->status === 'pending') {
                $invoice->update([
                    'guest_count' => $guestCount,
                    'amount_per_guest' => $pricePerGuest,
                    'total_amount' => $totalAmount,
                ]);
            }
        }

        return $invoice;
    }

    /**
     * Check if host has configured their event
     */
    public function hasConfiguredEvent()
    {
        if ($this->event_configured) {
            return true;
        }

        // If an event already exists for this host, mark event_configured as true so future checks are fast
        if ($this->events()->exists()) {
            $this->update(['event_configured' => true]);
            return true;
        }

        return false;
    }

    /**
     * Determine if the host's event has been marked as finished.
     */
    public function hasFinishedEvent(): bool
    {
        return !is_null($this->event_finished_at);
    }

    /**
     * Get the event type configuration
     */
    public function getEventTypeConfig()
    {
        return EventTypeConfig::getFieldsForType($this->event_type);
    }
}
