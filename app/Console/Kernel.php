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
        $schedule->command('subscriptions:renew')->everyMinute();

        // Run the backup daily at 1 AM
        $schedule->command('backup:run')->dailyAt('01:00')
            ->onSuccess(function () {
                // Log successful backup
                \Log::info('Daily backup completed successfully');
            })
            ->onFailure(function () {
                // Log backup failure
                \Log::error('Daily backup failed');
            });

        // Run backup cleanup weekly to remove old backups
        $schedule->command('backup:clean')->weekly()
            ->onSuccess(function () {
                \Log::info('Old backups cleaned successfully');
            });

        // Clear and optimize cache weekly
        $schedule->command('cache:manage optimize --force')->weekly()
            ->sundays()
            ->at('03:00')
            ->onSuccess(function () {
                \Log::info('Cache optimization completed successfully');
            });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Get the commands to register.
     *
     * @return array
     */
    protected $commands = [
        \App\Console\Commands\BackupManager::class,
        \App\Console\Commands\CacheManager::class,
    ];
}
