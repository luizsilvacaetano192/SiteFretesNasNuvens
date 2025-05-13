<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
       /*  protected $commands = [
            \App\Console\Commands\cron_push_notication::class,
        ]; */

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

   /*  protected function schedule(Schedule $schedule)
    {
        $schedule->command('app:cron_push_notication')->everyMinute();
    } */

}
