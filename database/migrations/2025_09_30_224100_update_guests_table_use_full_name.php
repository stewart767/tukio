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
        Schema::table('guests', function (Blueprint $table) {
            // Rename name column to full_name
            $table->renameColumn('name', 'full_name');
            // Remove email column as we only need full_name and phone
            $table->dropColumn('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            // Reverse the changes
            $table->renameColumn('full_name', 'name');
            $table->string('email')->nullable();
        });
    }
};
