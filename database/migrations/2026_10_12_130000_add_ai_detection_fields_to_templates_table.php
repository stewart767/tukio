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
        Schema::table('templates', function (Blueprint $table) {
            if (!Schema::hasColumn('templates', 'artwork_path')) {
                $table->string('artwork_path')->nullable()->after('preview_image');
            }
            if (!Schema::hasColumn('templates', 'artwork_width')) {
                $table->unsignedInteger('artwork_width')->nullable()->after('artwork_path');
            }
            if (!Schema::hasColumn('templates', 'artwork_height')) {
                $table->unsignedInteger('artwork_height')->nullable()->after('artwork_width');
            }
            if (!Schema::hasColumn('templates', 'analysis_status')) {
                $table->string('analysis_status', 30)->default('completed')->after('artwork_height');
            }
            if (!Schema::hasColumn('templates', 'approval_status')) {
                $table->string('approval_status', 30)->default('approved')->after('analysis_status');
            }
            if (!Schema::hasColumn('templates', 'analysis_metadata')) {
                $table->json('analysis_metadata')->nullable()->after('approval_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn([
                'artwork_path',
                'artwork_width',
                'artwork_height',
                'analysis_status',
                'approval_status',
                'analysis_metadata',
            ]);
        });
    }
};
