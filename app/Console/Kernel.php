<?php

namespace App\Console;

use App\Disaster;
use App\Jobs\CheckUsersSubscriptions;
use App\Jobs\InitNotificationUsersJob;
use App\Jobs\ParseDisasterApi;
use App\Services\DisasterHandler\HiszRsoeApiHandler\HiszRsoeApiHandler;
use App\User;
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
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(
            new ParseDisasterApi(new HiszRsoeApiHandler(), Disaster::class)
        )->hourly();

        $schedule->job(
            new CheckUsersSubscriptions()
        )->twiceDaily(1, 13);

        $schedule->job(
            new InitNotificationUsersJob(User::class)
        )->twiceDaily(2, 14);

        $schedule->command('horizon:snapshot')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
