<?php

use App\Console\Commands\AutoCompleteBookings;
use App\Console\Commands\UpdateContractorRanking;
use App\Console\Commands\WeeklyTaskStatusUpdate;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();
Schedule::call( function () {
    logger()->info('test it');
})->everySecond();

Schedule::command(WeeklyTaskStatusUpdate::class)->everyFourHours();