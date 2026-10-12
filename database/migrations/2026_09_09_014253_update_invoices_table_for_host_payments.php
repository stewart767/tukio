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
        Schema::table('invoices', function (Blueprint $table) {
            // Drop foreign key on user_id if needed, make user_id nullable
            $table->unsignedBigInteger('user_id')->nullable()->change();
            
            // Add host relationship & payment verification fields
            if (!Schema::hasColumn('invoices', 'host_id')) {
                $table->foreignId('host_id')->nullable()->after('user_id')->constrained('hosts')->onDelete('cascade');
            }
            if (!Schema::hasColumn('invoices', 'amount_paid')) {
                $table->decimal('amount_paid', 10, 2)->default(0.00)->after('total_amount');
            }
            if (!Schema::hasColumn('invoices', 'transaction_reference')) {
                $table->string('transaction_reference')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('invoices', 'payment_receipt')) {
                $table->string('payment_receipt')->nullable()->after('transaction_reference');
            }
            if (!Schema::hasColumn('invoices', 'payer_name')) {
                $table->string('payer_name')->nullable()->after('payment_receipt');
            }
            if (!Schema::hasColumn('invoices', 'payer_phone')) {
                $table->string('payer_phone')->nullable()->after('payer_name');
            }
            if (!Schema::hasColumn('invoices', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('payer_phone');
            }
            if (!Schema::hasColumn('invoices', 'verified_by')) {
                $table->foreignId('verified_by')->nullable()->after('admin_notes')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('invoices', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verified_by');
            }
            if (!Schema::hasColumn('invoices', 'due_date')) {
                $table->date('due_date')->nullable()->after('total_amount');
            }
        });

        // Change status column to string so it supports 'under_review', 'paid', 'pending', 'cancelled'
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('status', 30)->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'host_id')) {
                $table->dropForeign(['host_id']);
                $table->dropColumn('host_id');
            }
            if (Schema::hasColumn('invoices', 'verified_by')) {
                $table->dropForeign(['verified_by']);
                $table->dropColumn('verified_by');
            }
            $table->dropColumn([
                'amount_paid',
                'transaction_reference',
                'payment_receipt',
                'payer_name',
                'payer_phone',
                'admin_notes',
                'verified_at',
            ]);
        });
    }
};
