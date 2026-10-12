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
        Schema::create('hosts', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->enum('event_type', ['wedding', 'birthday', 'kitchen_party', 'send_off', 'corporate']);
            $table->integer('number_of_guests');
            $table->decimal('total_cost', 10, 2);
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->boolean('event_configured')->default(false);
            $table->json('event_config_data')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hosts');
    }
};
