<?php

namespace App\Console\Commands;

use App\Jobs\CallAiAndCacheResults;
use Illuminate\Console\Command;

class StartRecommendationJobCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:StartRecommendationJobCommand';
    protected $description = 'Start the recommendation job every 2 minutes';

    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Dispatch job lần đầu tiên
        CallAiAndCacheResults::dispatch()->delay(now()->addMinutes(2)); 
    }
}
