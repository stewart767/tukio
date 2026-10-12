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
            // Modify the status enum to include 'active'
            $table->enum('status', ['pending', 'active', 'sent', 'delivered', 'opened', 'attended'])->default('active')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            // Revert the status enum to original values
            $table->enum('status', ['pending', 'sent', 'delivered', 'opened', 'attended'])->default('pending')->change();
        });
    }
};
