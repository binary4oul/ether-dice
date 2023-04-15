<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Cron\CronExpression;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            app('App\Http\Controllers\EtherscanController')->getSuccessfulTransactions(request());
        })->everyMinute();
        $schedule->command('dice:biggestwins')->hourly();
        $schedule->command('fetch:successful-transactions')
        ->everyFiveMinutes();

    $schedule->command('fetch:successful-bsc-transactions')
        ->everyFiveMinutes();

    }
    
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
