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
        Schema::create('event_website_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_website_id')->constrained('event_websites')->onDelete('cascade');
            $table->string('section_key', 50);
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('content')->nullable();
            $table->timestamps();

            $table->index(['event_website_id', 'section_key']);
            $table->index(['event_website_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_website_sections');
    }
};
