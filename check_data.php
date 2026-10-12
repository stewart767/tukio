<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== MILESTONES ===\n";
foreach (\App\Models\Milestone::all() as $m) {
    echo "ID: {$m->id}, Slug: {$m->slug}, Title: {$m->title}, Category: {$m->category}\n";
}

echo "\n=== EVENT TYPE CONFIGS ===\n";
foreach (\App\Models\EventTypeConfig::all() as $etc) {
    echo "ID: {$etc->id}, EventType: {$etc->event_type}\n";
}

echo "\n=== TEMPLATES ===\n";
foreach (\App\Models\Template::all() as $t) {
    echo "ID: {$t->id}, Slug: {$t->slug}, Name: {$t->name}, Category: {$t->category}, View: {$t->view_path}, Image: {$t->preview_image}\n";
}

echo "\n=== EVENTS ===\n";
foreach (\App\Models\Event::all() as $e) {
    echo "ID: {$e->id}, Title: {$e->title}, Category: {$e->category}, Template ID: {$e->template_id}\n";
}
