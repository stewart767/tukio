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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category'); // luxury_bride_groom, guest_coaster_bus, vip_suv_convoy, host_logistics
            $table->string('brand')->nullable(); // Mercedes-Benz, Range Rover, Toyota, etc.
            $table->string('model_year')->nullable(); // 2023, 2024
            $table->integer('seating_capacity')->default(4);
            $table->string('luggage_capacity')->nullable()->default('2-4 Bags');
            $table->string('transmission')->default('Automatic');
            $table->string('fuel_type')->default('Petrol');
            $table->string('color')->nullable()->default('White');
            $table->decimal('price_per_day', 12, 2)->default(0);
            $table->decimal('price_per_hour', 12, 2)->nullable();
            $table->boolean('has_driver')->default(true);
            $table->boolean('has_fuel_included')->default(true);
            $table->boolean('has_ribbon_decoration')->default(true);
            $table->json('features')->nullable(); // ['Chauffeur Driven', 'Air Conditioning', 'Leather Seats', 'Wedding Ribbons', 'Drinks Cooler']
            $table->string('image_url')->nullable();
            $table->json('gallery')->nullable();
            $table->text('description')->nullable();
            $table->string('location')->default('Dar es Salaam, Tanzania');
            $table->boolean('is_available')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
