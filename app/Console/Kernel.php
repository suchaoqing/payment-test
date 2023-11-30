<?php

namespace App\Console;

use App\Console\Commands\ActiveLocation;
use App\Console\Commands\DatabaseCreateCommand;
use App\Console\Commands\EventsMonthly;
use App\Console\Commands\FixedMonthly;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        DatabaseCreateCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
    }
}
