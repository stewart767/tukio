<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CarBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_reference',
        'vehicle_id',
        'host_id',
        'event_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'event_type',
        'start_date',
        'end_date',
        'days_count',
        'pickup_location',
        'dropoff_location',
        'pickup_time',
        'guest_count',
        'need_decoration',
        'need_guest_coasters',
        'guest_coasters_count',
        'special_requests',
        'total_price',
        'status',
        'driver_name',
        'driver_phone',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'days_count' => 'integer',
            'guest_count' => 'integer',
            'guest_coasters_count' => 'integer',
            'need_decoration' => 'boolean',
            'need_guest_coasters' => 'boolean',
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * Boot to generate booking reference automatically.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_reference)) {
                $booking->booking_reference = 'TCB-' . date('Y') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    /**
     * Related vehicle.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Related host.
     */
    public function host()
    {
        return $this->belongsTo(Host::class);
    }

    /**
     * Related event.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Status styling and label.
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'confirmed' => [
                'bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'dot' => 'bg-emerald-500',
                'label' => 'Confirmed & Reserved',
            ],
            'in_progress' => [
                'bg' => 'bg-blue-50 text-blue-700 border-blue-200',
                'dot' => 'bg-blue-500',
                'label' => 'In Progress (Active Trip)',
            ],
            'completed' => [
                'bg' => 'bg-purple-50 text-purple-700 border-purple-200',
                'dot' => 'bg-purple-500',
                'label' => 'Completed',
            ],
            'cancelled' => [
                'bg' => 'bg-rose-50 text-rose-700 border-rose-200',
                'dot' => 'bg-rose-500',
                'label' => 'Cancelled',
            ],
            default => [
                'bg' => 'bg-amber-50 text-amber-700 border-amber-200',
                'dot' => 'bg-amber-500',
                'label' => 'Pending Review',
            ],
        };
    }

    /**
     * Formatted total price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'TZS ' . number_format($this->total_price, 0, '.', ',');
    }

    /**
     * WhatsApp direct inquiry URL with message.
     */
    public function getWhatsAppUrlAttribute(): string
    {
        $phone = '255662199071';
        try {
            /** @var \App\Services\SystemSettingsService $settingsService */
            $settingsService = app(\App\Services\SystemSettingsService::class);
            $publicData = $settingsService->getPublicPayload();
            if (!empty($publicData['whatsapp_clean'])) {
                $phone = $publicData['whatsapp_clean'];
            }
        } catch (\Throwable $e) {
            // fallback
        }

        $vehicleName = $this->vehicle ? $this->vehicle->name : 'Wedding Fleet Car';
        $message = "Habari Tukio 360!\n\nNimefanya Car Hire Booking ombi langu:\n"
            . "• Ref: *{$this->booking_reference}*\n"
            . "• Gari: *{$vehicleName}*\n"
            . "• Jina: {$this->customer_name}\n"
            . "• Tarehe: {$this->start_date?->format('d/m/Y')}\n"
            . "• Tukio: {$this->event_type}\n"
            . "• Mahali: {$this->pickup_location}\n"
            . "• Bei: {$this->formatted_price}\n\n"
            . "Tafadhali thibitisha upatikanaji wa gari na maelezo ya dereva.";

        return 'https://wa.me/' . $phone . '?text=' . urlencode($message);
    }
}
