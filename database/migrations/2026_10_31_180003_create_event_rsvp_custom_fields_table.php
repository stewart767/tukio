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
        Schema::create('event_rsvp_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_website_id')->constrained('event_websites')->onDelete('cascade');
            $table->string('field_key', 60);
            $table->string('field_label');
            $table->string('field_type', 30)->default('text'); // text, textarea, select, radio, checkbox
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_enabled')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['event_website_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_rsvp_custom_fields');
    }
};
