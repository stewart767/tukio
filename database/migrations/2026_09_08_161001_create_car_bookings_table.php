<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('car_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference')->unique();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('host_id')->nullable()->constrained('hosts')->nullOnDelete();
            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->string('event_type')->default('Wedding'); // Wedding, Send Off, Kitchen Party, Corporate, VIP Convoy
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('days_count')->default(1);
            $table->string('pickup_location');
            $table->string('dropoff_location')->nullable();
            $table->string('pickup_time')->nullable()->default('08:00 AM');
            $table->integer('guest_count')->nullable();
            $table->boolean('need_decoration')->default(true);
            $table->boolean('need_guest_coasters')->default(false);
            $table->integer('guest_coasters_count')->default(0);
            $table->text('special_requests')->nullable();
            $table->decimal('total_price', 12, 2)->default(0);
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_bookings');
    }
};
