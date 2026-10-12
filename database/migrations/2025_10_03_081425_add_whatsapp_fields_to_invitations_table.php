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
        Schema::table('invitations', function (Blueprint $table) {
            $table->string('whatsapp_message_id')->nullable()->after('whatsapp_message');
            $table->string('whatsapp_media_id')->nullable()->after('whatsapp_message_id');
            $table->timestamp('whatsapp_sent_at')->nullable()->after('sent_at');
            $table->timestamp('whatsapp_delivered_at')->nullable()->after('delivered_at');
            $table->timestamp('whatsapp_read_at')->nullable()->after('opened_at');
            $table->text('whatsapp_error_message')->nullable()->after('qr_code_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp_message_id',
                'whatsapp_media_id',
                'whatsapp_sent_at',
                'whatsapp_delivered_at',
                'whatsapp_read_at',
                'whatsapp_error_message'
            ]);
        });
    }
};
