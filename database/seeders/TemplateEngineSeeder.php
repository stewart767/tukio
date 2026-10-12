<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\User;
use App\Services\Invitations\TemplateEngine;
use Illuminate\Database\Seeder;

class TemplateEngineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $adminId = $admin?->id ?? 1;

        $templateEngine = app(TemplateEngine::class);

        // 1. Sync file-based templates
        $templateEngine->syncTemplatesWithDatabase($adminId);

        // 2. Ensure existing legacy templates have valid slugs and view paths
        $legacyTemplates = Template::whereNull('slug')->orWhere('slug', '')->get();
        foreach ($legacyTemplates as $t) {
            $slug = \Illuminate\Support\Str::slug($t->name);
            $baseSlug = $slug;
            $counter = 1;
            while (Template::where('slug', $slug)->where('id', '!=', $t->id)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }
            $t->update([
                'slug' => $slug,
                'template_type' => 'standard',
                'price' => 0.00,
                'currency' => 'TZS',
                'is_paid' => false,
                'version' => '1.0',
            ]);
        }
    }
}
