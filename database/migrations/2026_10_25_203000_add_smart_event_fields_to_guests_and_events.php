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
        // Add smart fields to guests table
        Schema::table('guests', function (Blueprint $table) {
            if (!Schema::hasColumn('guests', 'pin_code')) {
                $table->string('pin_code', 10)->nullable()->index()->after('invitation_code');
            }
            if (!Schema::hasColumn('guests', 'table_number')) {
                $table->string('table_number', 50)->nullable()->after('pin_code');
            }
            if (!Schema::hasColumn('guests', 'pax')) {
                $table->integer('pax')->default(1)->after('table_number');
            }
            if (!Schema::hasColumn('guests', 'pax_checked_in')) {
                $table->integer('pax_checked_in')->default(0)->after('pax');
            }
            if (!Schema::hasColumn('guests', 'amount_pledged')) {
                $table->decimal('amount_pledged', 12, 2)->default(0.00)->after('pax_checked_in');
            }
            if (!Schema::hasColumn('guests', 'amount_paid')) {
                $table->decimal('amount_paid', 12, 2)->default(0.00)->after('amount_pledged');
            }
            if (!Schema::hasColumn('guests', 'payment_method')) {
                $table->string('payment_method', 50)->nullable()->after('amount_paid'); // cash, wakala, lipa_namba, bank
            }
            if (!Schema::hasColumn('guests', 'payment_status')) {
                $table->string('payment_status', 30)->default('pending')->after('payment_method'); // pending, partial, paid
            }
            if (!Schema::hasColumn('guests', 'payment_reference')) {
                $table->string('payment_reference', 100)->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('guests', 'rsvp_status')) {
                $table->string('rsvp_status', 30)->default('pending')->after('payment_reference'); // pending, confirmed, declined
            }
            if (!Schema::hasColumn('guests', 'category')) {
                $table->string('category', 50)->nullable()->after('rsvp_status'); // family, friends, work, vip, committee
            }
            if (!Schema::hasColumn('guests', 'checked_in_by')) {
                $table->unsignedBigInteger('checked_in_by')->nullable()->after('attended_at');
            }
        });

        // Add payment & gate info to events table
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'lipa_namba')) {
                $table->string('lipa_namba', 50)->nullable()->after('venue_address');
            }
            if (!Schema::hasColumn('events', 'account_name')) {
                $table->string('account_name', 100)->nullable()->after('lipa_namba');
            }
            if (!Schema::hasColumn('events', 'treasurer_phone')) {
                $table->string('treasurer_phone', 50)->nullable()->after('account_name');
            }
            if (!Schema::hasColumn('events', 'bank_name')) {
                $table->string('bank_name', 50)->nullable()->after('treasurer_phone');
            }
            if (!Schema::hasColumn('events', 'bank_account_number')) {
                $table->string('bank_account_number', 50)->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('events', 'gate_pin')) {
                $table->string('gate_pin', 10)->nullable()->after('event_password');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropIndex(['pin_code']);
            $table->dropColumn([
                'pin_code',
                'table_number',
                'pax',
                'pax_checked_in',
                'amount_pledged',
                'amount_paid',
                'payment_method',
                'payment_status',
                'payment_reference',
                'rsvp_status',
                'category',
                'checked_in_by',
            ]);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'lipa_namba',
                'account_name',
                'treasurer_phone',
                'bank_name',
                'bank_account_number',
                'gate_pin',
            ]);
        });
    }
};
