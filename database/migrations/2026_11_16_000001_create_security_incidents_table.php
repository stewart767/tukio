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
        Schema::create('security_incidents', function (Blueprint $table) {
            $table->id();
            $table->string('incident_uuid', 64)->unique();
            $table->enum('threat_type', [
                'sqli',
                'xss',
                'path_traversal',
                'scanner_probe',
                'brute_force',
                'unauthorized_access',
                'bot_activity',
                'tampering',
                'suspicious_payload',
            ])->index();
            $table->enum('severity', ['critical', 'high', 'medium', 'low'])->default('medium')->index();
            $table->unsignedTinyInteger('confidence_score')->default(80); // 0 - 100%
            $table->string('source_ip', 45)->index();
            $table->text('user_agent')->nullable();
            $table->string('request_method', 10)->default('GET');
            $table->text('request_url');
            $table->mediumText('payload_sample')->nullable();
            $table->string('detected_rule', 100)->nullable();
            $table->string('target_guard', 20)->default('public');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('host_id')->nullable()->constrained('hosts')->onDelete('set null');
            $table->enum('containment_action', [
                'none',
                'request_blocked',
                'ip_temporary_ban',
                'session_terminated',
                'token_revoked',
            ])->default('none');
            $table->enum('status', ['active', 'contained', 'false_positive', 'resolved'])->default('active')->index();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->text('investigation_notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['source_ip', 'created_at']);
            $table->index(['severity', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_incidents');
    }
};

