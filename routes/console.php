<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:send-holiday')->everyMinute();
Schedule::command('app:clear-storage-command')->dailyAt('6:00');
Schedule::command('app:parse-all-holidays')->dailyAt('6:00');
Schedule::command('app:send-names-command')->dailyAt('16:00');
Schedule::command('app:send-birthdays-command')->dailyAt('17:00');
Schedule::command('app:send-events-command')->dailyAt('18:00');

