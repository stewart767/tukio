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
        Schema::create('whatsapp_phone_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('whatsapp_business_account_id')->constrained('whatsapp_business_accounts')->onDelete('cascade');
            $table->foreignId('host_id')->constrained('hosts')->onDelete('cascade');
            $table->string('phone_number_id', 100)->unique();
            $table->string('display_phone_number', 50);
            $table->string('verified_name', 255)->nullable();
            $table->string('quality_rating', 50)->default('UNKNOWN');
            $table->string('code_verification_status', 50)->default('NOT_VERIFIED');
            $table->boolean('is_default')->default(true);
            $table->string('status', 50)->default('CONNECTED');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['host_id', 'is_default']);
            $table->index('display_phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_phone_numbers');
    }
};

