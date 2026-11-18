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
        Schema::create('security_vulnerabilities', function (Blueprint $table) {
            $table->id();
            $table->string('cve_id', 50)->nullable()->index();
            $table->string('title', 255);
            $table->string('package_name', 150)->index();
            $table->string('installed_version', 50)->nullable();
            $table->string('fixed_version', 50)->nullable();
            $table->enum('severity', ['critical', 'high', 'medium', 'low', 'info'])->default('medium')->index();
            $table->decimal('cvss_score', 3, 1)->nullable();
            $table->enum('status', ['open', 'investigating', 'mitigated', 'patched', 'false_positive'])->default('open')->index();
            $table->string('source', 50)->default('composer_audit'); // composer_audit, wazuh, manual
            $table->text('remediation_guidance')->nullable();
            $table->json('advisory_details')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('detected_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_vulnerabilities');
    }
};

