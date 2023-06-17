<?php

namespace App\Console;

use App\Jobs\AccessTokenDeactivatorJob;
use App\Jobs\AtaaPrizeActivatorJob;
use App\Jobs\AtaaPrizeDeactivatorJob;
use App\Jobs\UserBanActivatorJob;
use App\Jobs\UserBanDeactivatorJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new UserBanActivatorJob())->daily();
        $schedule->job(new UserBanDeactivatorJob())->daily();
        $schedule->job(new AtaaPrizeActivatorJob())->daily();
        $schedule->job(new AtaaPrizeDeactivatorJob())->daily();
        $schedule->job(new AccessTokenDeactivatorJob())->daily();
        // $schedule->job(new UserBanActivatorJob())->everyMinute();
        // $schedule->job(new UserBanDeactivatorJob())->everyMinute();
        // $schedule->job(new AtaaPrizeActivatorJob())->everyMinute();
        // $schedule->job(new AtaaPrizeDeactivatorJob())->everyMinute();
        // $schedule->job(new AccessTokenDeactivatorJob())->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
