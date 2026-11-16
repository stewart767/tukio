<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Daily Automated Security Audit and SOC Health Report
Schedule::command('tukio:security-audit')
    ->dailyAt('06:00')
    ->runInBackground()
    ->onOneServer();

