<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class NotificationJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notification;

    /**
     * Create a new job instance.
     *
     * @param array $notification
     */
    public function __construct(array $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $content = $this->notification['content'];
        $subscriptionId = $this->notification['subcription_id'];
        $url = $this->notification['url'];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ZGRlNjQ1M2UtOGRjMS00MjcwLTllZGItMTYzZDI5OTg2ZWNh', // Thay YOUR_ONESIGNAL_REST_API_KEY bằng khóa API của bạn
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ])->post('https://onesignal.com/api/v1/notifications', [
                'app_id' => 'f41bdea2-508a-4082-9951-e77411fa9f53', // Thay YOUR_ONESIGNAL_APP_ID bằng ID ứng dụng của bạn
                'include_player_ids' => [$subscriptionId],
                'contents' => ['en' => $content],
                'url' => $url,
            ]);

            // Xử lý phản hồi nếu cần thiết
            return $response->body();
        } catch (\Exception $e) {
            // Báo cáo lỗi nếu có
            report($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
