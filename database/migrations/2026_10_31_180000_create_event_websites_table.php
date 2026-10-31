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
        Schema::create('event_websites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('category')->default('birthday');
            $table->text('tagline')->nullable();
            $table->string('host_names')->nullable();
            $table->date('event_date')->nullable();
            $table->string('event_time')->nullable();
            $table->string('venue_name')->nullable();
            $table->text('venue_address')->nullable();
            $table->text('google_maps_url')->nullable();
            $table->text('google_maps_embed')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('cover_video_url')->nullable();
            $table->string('music_url')->nullable();
            $table->string('music_title')->nullable();
            $table->string('theme')->default('luxury_gold');
            $table->string('primary_color', 30)->nullable();
            $table->string('secondary_color', 30)->nullable();
            $table->string('accent_color', 30)->nullable();
            $table->string('background_color', 30)->nullable();
            $table->string('text_color', 30)->nullable();
            $table->string('font_heading', 80)->default('Playfair Display');
            $table->string('font_body', 80)->default('Inter');
            $table->string('countdown_style', 40)->default('cards');
            $table->dateTime('rsvp_deadline')->nullable();
            $table->integer('max_plus_ones')->default(1);
            $table->boolean('allow_guest_updates')->default(true);
            $table->boolean('require_phone')->default(true);
            $table->boolean('require_email')->default(false);
            $table->boolean('is_published')->default(false);
            $table->text('meta_description')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index(['event_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_websites');
    }
};

