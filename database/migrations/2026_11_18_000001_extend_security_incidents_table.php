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
        Schema::table('security_incidents', function (Blueprint $table) {
            $table->string('title', 255)->nullable()->after('incident_uuid');
            $table->foreignId('assigned_to')->nullable()->after('host_id')->constrained('users')->onDelete('set null');
            $table->json('evidence')->nullable()->after('payload_sample');
            $table->text('remediation_notes')->nullable()->after('investigation_notes');
            $table->timestamp('closed_at')->nullable()->after('resolved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('security_incidents', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropColumn(['title', 'assigned_to', 'evidence', 'remediation_notes', 'closed_at']);
        });
    }
};

