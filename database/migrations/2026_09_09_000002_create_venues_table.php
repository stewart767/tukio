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
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->default('Indoor Ballrooms'); // Indoor Ballrooms, Beach & Garden, Conference Centers, Luxury Suites, etc.
            $table->string('location')->default('Dar es Salaam'); // City / Region e.g. "Dar es Salaam", "Zanzibar", "Arusha"
            $table->string('capacity')->nullable(); // e.g. "500 – 2,000 Guests"
            $table->string('badge')->nullable(); // e.g. "🏛️ Indoor Ballrooms", "🏖️ Beach & Garden"
            $table->text('description')->nullable();
            $table->json('amenities')->nullable(); // e.g. ["Climate Controlled", "VIP Lounges", "Stage Lighting"]
            $table->string('image_url')->nullable();
            $table->string('price_range')->nullable(); // e.g. "From TZS 1,500,000 / day"
            $table->string('inquiry_button_text')->default('Inquire About Venue');
            $table->string('inquiry_subject')->nullable(); // e.g. "Venue Inquiry - Grand Ballroom"
            $table->boolean('is_featured')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
