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
        Schema::create('whatsapp_business_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_id')->constrained('hosts')->onDelete('cascade');
            $table->string('waba_id', 100)->unique();
            $table->string('account_name', 255)->nullable();
            $table->string('currency', 10)->nullable();
            $table->string('timezone_id', 50)->nullable();
            $table->string('message_template_namespace', 255)->nullable();
            $table->text('access_token'); // Encrypted at model level
            $table->string('token_type', 50)->default('bearer');
            $table->timestamp('token_expires_at')->nullable();
            $table->enum('status', ['active', 'disconnected', 'expired', 'suspended'])->default('active');
            $table->boolean('is_webhook_subscribed')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['host_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_business_accounts');
    }
};

