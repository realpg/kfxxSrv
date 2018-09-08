<?php

namespace App\Console;

use App\Components\SechduleManager;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

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
        // $schedule->command('inspire')
        //          ->hourly();
        //这里是每日生成康复计划的任务-每日早8点开始生成康复计划
        $schedule->call(function () {
            SechduleManager::autoCreateUserZXJH();
        })->dailyAt('7:00');
        //这里是每日生成康复计划的任务-每日0点开始关闭康复计划
        $schedule->call(function () {
            SechduleManager::autoFinishUserZXJH();
        })->dailyAt('1:00');
	    $schedule->call(function () {
		    Log::info("Schedule Running".date('Y/m/d h:i:sa'));
	    })->everyMinute();
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
