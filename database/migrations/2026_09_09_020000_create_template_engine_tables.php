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
        // 1. Enhance templates table
        Schema::table('templates', function (Blueprint $table) {
            $table->string('category', 100)->change();
            
            if (!Schema::hasColumn('templates', 'slug')) {
                $table->string('slug', 100)->nullable()->unique()->after('name');
            }
            if (!Schema::hasColumn('templates', 'template_type')) {
                $table->enum('template_type', ['standard', 'premium', 'custom'])->default('standard')->after('category');
            }
            if (!Schema::hasColumn('templates', 'price')) {
                $table->decimal('price', 12, 2)->default(0.00)->after('template_type');
            }
            if (!Schema::hasColumn('templates', 'currency')) {
                $table->string('currency', 10)->default('TZS')->after('price');
            }
            if (!Schema::hasColumn('templates', 'is_paid')) {
                $table->boolean('is_paid')->default(false)->after('currency');
            }
            if (!Schema::hasColumn('templates', 'version')) {
                $table->string('version', 20)->default('1.0')->after('is_paid');
            }
            if (!Schema::hasColumn('templates', 'features')) {
                $table->json('features')->nullable()->after('version');
            }
            if (!Schema::hasColumn('templates', 'view_path')) {
                $table->string('view_path')->nullable()->after('css_content');
            }
            if (!Schema::hasColumn('templates', 'assets_path')) {
                $table->string('assets_path')->nullable()->after('view_path');
            }
            if (!Schema::hasColumn('templates', 'host_id')) {
                $table->foreignId('host_id')->nullable()->after('created_by')->constrained('hosts')->nullOnDelete();
            }
        });

        // 2. Create template_fields table
        if (!Schema::hasTable('template_fields')) {
            Schema::create('template_fields', function (Blueprint $table) {
                $table->id();
                $table->foreignId('template_id')->constrained('templates')->onDelete('cascade');
                $table->string('field_key', 100);
                $table->string('field_label', 150);
                $table->string('field_type', 50)->default('text'); // text, textarea, date, time, datetime, image, gallery, color, font, url, qr, countdown, rsvp, map, guest_name
                $table->boolean('is_required')->default(false);
                $table->text('default_value')->nullable();
                $table->integer('sort_order')->default(0);
                $table->json('configuration')->nullable();
                $table->timestamps();

                $table->unique(['template_id', 'field_key']);
            });
        }

        // 3. Enhance invitations table
        Schema::table('invitations', function (Blueprint $table) {
            if (!Schema::hasColumn('invitations', 'template_version')) {
                $table->string('template_version', 20)->nullable()->after('template_id');
            }
            if (!Schema::hasColumn('invitations', 'rsvp_status')) {
                $table->enum('rsvp_status', ['pending', 'attending', 'declined', 'tentative'])->default('pending')->after('status');
            }
            if (!Schema::hasColumn('invitations', 'rsvp_at')) {
                $table->timestamp('rsvp_at')->nullable()->after('rsvp_status');
            }
            if (!Schema::hasColumn('invitations', 'rsvp_notes')) {
                $table->text('rsvp_notes')->nullable()->after('rsvp_at');
            }
            if (!Schema::hasColumn('invitations', 'plus_one_count')) {
                $table->unsignedSmallInteger('plus_one_count')->default(0)->after('rsvp_notes');
            }
            if (!Schema::hasColumn('invitations', 'custom_data')) {
                $table->json('custom_data')->nullable()->after('plus_one_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_fields');

        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('invitations', function (Blueprint $table) {
                $table->dropColumn([
                    'template_version',
                    'rsvp_status',
                    'rsvp_at',
                    'rsvp_notes',
                    'plus_one_count',
                    'custom_data',
                ]);
            });

            Schema::table('templates', function (Blueprint $table) {
                $table->dropForeign(['host_id']);
                $table->dropUnique(['slug']);
                $table->dropColumn([
                    'slug',
                    'template_type',
                    'price',
                    'currency',
                    'is_paid',
                    'version',
                    'features',
                    'view_path',
                    'assets_path',
                    'host_id',
                ]);
            });
        }
    }
};
