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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('host_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('category', ['wedding', 'birthday', 'kitchen_party', 'send_off', 'corporate']);
            $table->date('date');
            $table->time('time');
            $table->string('venue');
            $table->text('venue_address')->nullable();
            $table->foreignId('template_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->integer('guest_count')->default(0);
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->json('custom_data')->nullable(); // Store event type specific data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
