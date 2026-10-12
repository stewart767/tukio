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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('invitation_type', ['sms', 'whatsapp', 'both'])->default('whatsapp');
            $table->enum('status', ['pending', 'sent', 'delivered', 'opened', 'attended'])->default('pending');
            $table->boolean('attended')->default(false);
            $table->timestamp('attended_at')->nullable();
            $table->string('qr_code')->unique()->nullable();
            $table->string('invitation_code')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
