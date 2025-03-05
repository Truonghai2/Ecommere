<?php

namespace App\Jobs;

use App\Models\user_log;
use App\Services\ProductService;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class CallAiAndCacheResults implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;

    /**
     * Tạo mới một instance job.
     *
     * @param  int  $userId
     * @return void
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Xử lý công việc.
     *
     * @return void
     */
    public function handle()
    {

        // php D:\Users\HomeEcommerce\artisan schedule:run

        try {
            Cache::remember(
                "user_{$this->userId}_recommendations",
                now()->addMinutes(3),
                function () {
                    return $this->Recommendation_personalization($this->userId);
                }
            );
            
            self::dispatch($this->userId)->delay(now()->addMinutes(1));

        } catch (\Exception $e) {
            Log::error("Failed to get recommendations for user {$this->userId}: " . $e->getMessage());
        }
    }

    /**
     * get data Recommendation_personalization
     * 
     * @param int $userId
     * @return Collection|null
     */
    public function Recommendation_personalization(int $userId): ?Collection
    {
        $user = \App\Models\User::find($userId);

        if (!$user) {
            Log::error("User not found with ID: {$userId}");
            return null;
        }

        $product_logs = user_log::where('user_id', $user->id)
                ->with('category', 'user', 'product.variations')
                ->get();
        

        $age_groups = [
            '3-17' => [3, 17],
            '18-35' => [18, 35],
            '36-55' => [36, 55],
            '55+' => [56, PHP_INT_MAX]
        ];

        $user_age_group = '';
        foreach ($age_groups as $group => $range) {
            if ($user->age >= $range[0] && $user->age <= $range[1]) {
                $user_age_group = $group;
                break;
            }
        }


        if ($product_logs->isEmpty()) {
            $product_logs = user_log::with(['category', 'user', 'product.variations'])->get();
            $product_logs = $product_logs->filter(function ($log) use ($user_age_group, $age_groups) {
                $user_age = $log->user->age;
                $range = $age_groups[$user_age_group] ?? null;
                return $range && $user_age >= $range[0] && $user_age <= $range[1];
            });

            if($product_logs->isEmpty()){
                
            }
            else{
                $product_logs = $product_logs->values();
            }
        }

        $product_data = $product_logs->map(function ($log) use ($age_groups) {
            $age_group = '';

            foreach ($age_groups as $group => $range) {
                if ($log->user->age >= $range[0] && $log->user->age <= $range[1]) {
                    $age_group = $group;
                    break;
                }
            }
            
            return [
                'user_id' => $log['user_id'],
                'product_id' => $log['product_id'],
                'product_name' => $log['product_name'],
                'price' => (int)$log['price'],
                'sale' => (int)$log['sale'],
                'action' => $log['actions'],
                'category' => $log->category->slug,
                'age' => $age_group
            ];
        })->toArray();

        $filePathUserLog = public_path('dataAI/user_log_' . $user->id . '.json');

        try {
            File::put($filePathUserLog, json_encode($product_data, JSON_PRETTY_PRINT));
            Artisan::call('recommendation:run', ['user_id' => $user->id]);

            $outputFilePath = public_path('dataAI/output_' . $user->id . '.json');

            if (File::exists($outputFilePath)) {
                $outputData = json_decode(File::get($outputFilePath), true);
                $products = Product::select('id', 'title', 'poster', 'price', 'sale', 'option_type')
                    ->whereIn('id', $outputData)
                    ->get();

                // Cache::put("recommendation_{$user->id}", $products, 2);
                return $products;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to write user log: ' . $e->getMessage()], 500);
        }
    }

    
}
