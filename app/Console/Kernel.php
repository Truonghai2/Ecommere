<?php

namespace App\Console;

use App\Jobs\CallAiAndCacheResults;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        
        $schedule->call(function () {
            $users = User::all();  
        
            $users->chunk(100)->each(function ($userBatch) {
                foreach ($userBatch as $user) {
                    CallAiAndCacheResults::dispatch($user->id);
                }
            });
        })->everyTwoMinutes();
        
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
