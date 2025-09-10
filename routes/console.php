<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



// Run expiry alerts daily at 09:00 London time
Schedule::command('alerts:expiries --days=90,60,30 --tz=Europe/London')
    ->dailyAt('09:00')
    ->timezone('Europe/London')
    ->withoutOverlapping()
    ->onOneServer();
