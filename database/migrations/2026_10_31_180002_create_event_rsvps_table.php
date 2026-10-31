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
        Schema::create('event_rsvps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_website_id')->constrained('event_websites')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('guest_id')->nullable()->constrained('guests')->onDelete('set null');
            $table->string('full_name');
            $table->string('phone', 30)->index();
            $table->string('email')->nullable();
            $table->enum('attendance_status', ['attending', 'declined', 'tentative'])->default('attending');
            $table->integer('plus_one_count')->default(0);
            $table->json('accompanying_guest_names')->nullable();
            $table->string('dietary_requirements', 80)->nullable();
            $table->text('dietary_details')->nullable();
            $table->text('arrival_departure_info')->nullable();
            $table->text('special_requests')->nullable();
            $table->json('custom_answers')->nullable();
            $table->string('confirmation_code', 20)->unique();
            $table->string('qr_code_path')->nullable();
            $table->boolean('is_checked_in')->default(false);
            $table->dateTime('checked_in_at')->nullable();
            $table->dateTime('invitation_sent_at')->nullable();
            $table->string('invitation_channel', 30)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['event_id', 'phone']);
            $table->index(['event_website_id', 'attendance_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_rsvps');
    }
};

