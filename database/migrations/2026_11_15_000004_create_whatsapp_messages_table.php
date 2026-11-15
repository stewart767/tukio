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
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_id')->constrained('hosts')->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('set null');
            $table->foreignId('guest_id')->nullable()->constrained('guests')->onDelete('set null');
            $table->foreignId('whatsapp_phone_number_id')->nullable()->constrained('whatsapp_phone_numbers')->onDelete('set null');
            $table->string('wamid', 191)->nullable()->unique();
            $table->enum('direction', ['outbound', 'inbound'])->default('outbound');
            $table->string('recipient_phone', 30)->index();
            $table->string('sender_phone', 30)->nullable();
            $table->enum('type', ['template', 'text', 'image', 'document', 'interactive'])->default('template');
            $table->string('template_name', 100)->nullable();
            $table->text('content')->nullable();
            $table->enum('status', ['queued', 'sent', 'delivered', 'read', 'failed'])->default('queued');
            $table->string('error_code', 50)->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['host_id', 'status']);
            $table->index(['event_id', 'recipient_phone']);
            $table->index(['guest_id', 'direction']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};

