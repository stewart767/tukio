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
        Schema::create('security_blocked_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->unique();
            $table->string('reason', 255);
            $table->unsignedInteger('threat_count')->default(1);
            $table->timestamp('blocked_until')->nullable()->index(); // NULL = permanent ban
            $table->boolean('is_permanent')->default(false);
            $table->foreignId('incident_id')->nullable()->constrained('security_incidents')->onDelete('set null');
            $table->string('blocked_by', 50)->default('auto_ips');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['ip_address', 'blocked_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_blocked_ips');
    }
};

