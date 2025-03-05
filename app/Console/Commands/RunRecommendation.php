<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RunRecommendation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recommendation:run {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run product recommendations for a user';

    /**
     * Execute the console command.
     */
    public function handle(): void 
    {
        $userId = $this->argument('user_id');
        $scriptPath = public_path('train.py');
        // Log::debug("message", ["python $scriptPath $userId"]);
        shell_exec("python $scriptPath $userId");
    }

}
