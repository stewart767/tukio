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
        Schema::create('security_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('actor_type', 50)->default('system'); // 'user', 'host', 'system', 'guest'
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('action', 100)->index(); // 'ip_blocked', 'ip_unblocked', 'incident_resolved', 'login_failed', etc.
            $table->string('resource_type', 100)->nullable();
            $table->string('resource_id', 100)->nullable();
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();

            $table->index(['actor_type', 'actor_id']);
            $table->index(['action', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_audit_logs');
    }
};

