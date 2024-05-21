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
        // $schedule->command('inspire')->hourly();
        //$schedule->command('app:user-monthly-performance')->daily();
        $schedule->command('app:calculate-team-totals')->hourly();
        $schedule->command('app:user-monthly-performance')->hourly();
        $schedule->command('app:calculate-monthly-sales')->hourly();
        $schedule->command('app:update-incentive-data')->everyMinute();
        $schedule->command('app:customer-by-pharmacy')->everySixHours();
        $schedule->command('app:customer-by-clinic')->everySixHours();
        //$schedule->command('app:cache-monthly-rep-items')->hourly();



    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    protected $commands = [
        Commands\CalculateTeamTotals::class,
        Commands\CalculateMonthlySales::class,
        Commands\userMonthlyPerformance::class,
        Commands\UpdateIncentiveData::class,
        Commands\CacheMonthlyRepItems::class,
    ];

}
