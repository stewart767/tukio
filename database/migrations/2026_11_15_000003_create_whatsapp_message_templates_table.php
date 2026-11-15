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
        Schema::create('whatsapp_message_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('whatsapp_business_account_id')->constrained('whatsapp_business_accounts')->onDelete('cascade');
            $table->foreignId('host_id')->constrained('hosts')->onDelete('cascade');
            $table->string('meta_template_id', 100)->nullable();
            $table->string('name', 100);
            $table->string('language', 20)->default('en');
            $table->string('category', 50)->default('UTILITY'); // UTILITY, MARKETING, AUTHENTICATION
            $table->string('status', 50)->default('APPROVED'); // APPROVED, PENDING, REJECTED, PAUSED, DISABLED
            $table->json('components'); // header, body, footer, buttons
            $table->text('rejected_reason')->nullable();
            $table->timestamps();

            $table->unique(['whatsapp_business_account_id', 'name', 'language'], 'waba_tpl_name_lang_unique');
            $table->index(['host_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_message_templates');
    }
};

