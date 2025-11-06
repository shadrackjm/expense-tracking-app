<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('expenses:generate-recurring-expense')
    ->dailyAt('00:00')
    ->withoutOverlapping()
    ->onSuccess(function(){
        Log::info('Reccurring expense generated successfully.');
    })
    ->onFailure( function(){
        Log::info('Failed to generate recurring expenses');
    });
