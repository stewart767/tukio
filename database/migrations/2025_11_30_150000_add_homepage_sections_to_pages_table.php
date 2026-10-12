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
        Schema::table('pages', function (Blueprint $table) {
            // Add columns for homepage sections
            $table->string('welcome_title')->nullable()->after('sort_order');
            $table->text('welcome_content')->nullable()->after('welcome_title');
            $table->string('benefits_title')->nullable()->after('welcome_content');
            $table->string('benefits_subtitle')->nullable()->after('benefits_title');
            $table->text('benefits_content')->nullable()->after('benefits_subtitle');
            $table->string('faq_title')->nullable()->after('benefits_content');
            $table->string('faq_subtitle')->nullable()->after('faq_title');
            $table->text('faq_content')->nullable()->after('faq_subtitle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'welcome_title',
                'welcome_content',
                'benefits_title',
                'benefits_subtitle',
                'benefits_content',
                'faq_title',
                'faq_subtitle',
                'faq_content',
            ]);
        });
    }
};