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
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('badge')->nullable(); // e.g. "Most Popular", "Corporate"
            $table->string('tagline')->nullable(); // e.g. "Royal Celebrations"
            $table->string('icon')->nullable(); // icon class or symbol
            $table->text('description')->nullable();
            $table->json('tags')->nullable(); // e.g. ["Digital Cards", "RSVP Seating", "Pledge Tracker"]
            $table->string('image_url')->nullable();
            $table->string('button_text')->nullable()->default('Explore Features');
            $table->string('button_url')->nullable();
            $table->string('theme_color')->nullable()->default('indigo'); // purple, blue, pink, teal, amber, violet, indigo
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
        Schema::dropIfExists('milestones');
    }
};
