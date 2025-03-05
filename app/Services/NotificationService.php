<?php
namespace App\Services;

use App\Client\config;
use App\Jobs\NotificationJobs;
use App\Models\Notification;
use App\Models\User;
use App\Models\ViewNotification;
use App\Reponsitories\DeviceReponsitory;
use App\Reponsitories\NotificationReponsitory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\select;
use function PHPUnit\Framework\isEmpty;

class NotificationService{

    protected $notification;
    protected $deviceReponsitory;
    protected $config;
    public function __construct(NotificationReponsitory $notificationReponsitory, DeviceReponsitory $deviceReponsitory, config $config)
    {
        $this->notification = $notificationReponsitory;
        $this->deviceReponsitory = $deviceReponsitory;
        $this->config = $config;
    }

    /**
     * handle notification
     *
     * @param string $content
     * @param string $type
     * @param array $array
     * @return boolean
     */
    public function addNotificaion(string $content, string $type, array $array, string $order_code = null,
    bool $product = false, int $product_id = null, string $poster = null): bool
    {
        $url = env('APP_URL');
        if($type == 'all'){
            $this->notification->addAllNotification($content);
            $subscription_id = $this->deviceReponsitory->getAllSubscription()->pluck('subcription_id')->toArray();
            $notifications = array_map(function($subscription_id) use($content, $url) {
                return [
                    'content' => $content,
                    'subcription_id' => $subscription_id,
                    'url' => $url,
                ];
            }, $subscription_id);

            foreach ($notifications as $notification) {
                NotificationJobs::dispatch($notification);
            }
        }
        else if($type == 'user'){

            $notifications = array_map(function($item) use ($content, $type, $url, $product, $product_id, $poster) {


                $subscription_id = $this->deviceReponsitory->getSubscriptionID($item);
                if ($subscription_id != null) {
                    $data = [
                        'content' => $content,
                        'subcription_id' => $subscription_id->subcription_id,
                        'url' => ($product) ?  $url . '/product/' . $product_id : $url,
                    ];

                    // Dispatch notification job
                    NotificationJobs::dispatch($data);

                }

                if($product){

                    return [
                        'content' => $content,
                        'type' => 1,
                        'user_id' => $item,
                        'product_id' => $product_id,
                        'poster' => $poster,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                else{
                    return [
                        'content' => $content,
                        'type' => 1,
                        'user_id' => $item,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }




            }, $array);

            // Loại bỏ các giá trị null từ mảng $notifications
            $notifications = array_filter($notifications, function($notification) {
                return $notification !== null;
            });

            // Chèn vào cơ sở dữ liệu
            Notification::insert($notifications);

        }
        else if($type == 'admin'){
            $adminIds = User::where('role', 1)->pluck('id');


            // Khởi tạo một mảng trống để lưu trữ thông tin thông báo
            $notifications = [];

            foreach ($adminIds as $adminId) {
                $subscription = $this->deviceReponsitory->getSubscriptionID($adminId);

                if ($subscription && $subscription->subcription_id != null) {
                    $data = [
                        'content' => $content,
                        'subcription_id' => $subscription->subcription_id,
                        'url' => $url . '/detail/order/'. $order_code,
                    ];

                    NotificationJobs::dispatch($data);
                }

                $notifications[] = [
                    'content' => $content,
                    'type' => 2,
                    'user_id' => $adminId,
                    'order_code' => $order_code,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Chèn các thông báo vào database
            Notification::insert($notifications);
        }

        return true;
    }

    /**
     * send notification event message to the user device
     *
     * @param integer $user_id
     * @param string $content
     * @return void
     */
    public function notificaitonMessage(int $from_id, int $to_id, string $content): void
    {
        $url =  env('APP_URL') .'/chatify/'. $from_id;
        $user = $this->deviceReponsitory->getSubscriptionID($to_id);

        if($user){
            $this->sendNotification($content, $user->subcription_id, $url);
        }
    }

    /**
     * send notifications to the user's device
     *
     * @param string $content
     * @param array $subscription_ids
     * @param string $url
     * @return boolean
     */
    public function sendNotification(string $content, string $subscription_ids, string $url): bool
    {
        $content = $content;
        $subsription_id = $subscription_ids;
        try{
            $response = Http::withHeaders([
                'Authorization' => 'Basic ZGRlNjQ1M2UtOGRjMS00MjcwLTllZGItMTYzZDI5OTg2ZWNh',
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ])->post('https://onesignal.com/api/v1/notifications',[
                'app_id' => 'f41bdea2-508a-4082-9951-e77411fa9f53',
                'include_player_ids' => [$subsription_id],
                'contents' => ['en' =>$content],
                'url' => $url,
            ]);

            return $response->body();
        }catch(\Exception $e){
            report($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * get notification use pagination
     *
     * @param [type] $page
     * @return void
     */
    public function getNotification($page){
        $notification = $this->notification->getNotification($page);

        // dd($notification);
        $html = array_map(function($index, $item) {
            return $this->config->getNotification("notification", $index + 1, $item);
        },array_keys($notification->items()), $notification->items());


        return response()->json([
            'html' => $html,
            'total' => $notification->total(),
            'last_page' => $notification->lastPage(),
        ]);
    }


    /**
     * handle get notification all or not see
     *
     * @param integer $type
     * @param integer $page
     * @return object
     */
    public function handleNotificationUser(int $type, int $page): object
    {
        if($type == 1){
            $notification = Notification::where(function($query) {
                $query->where('type', 0)
                      ->orWhere(function($query) {
                          $query->where('type', 1)
                                ->where('user_id', auth()->id());
                      });
            })->whereDoesntHave('checkSee', function ($query) {
                $query->where('user_id', auth()->id())
                      ->where('see', 1);
            })->orderByDesc('created_at')->paginate(16, ['*'], 'page', $page);
        }
        else{
            $notification = Notification::where(function($query) {
                $query->where('type', 0)
                      ->orWhere(function($query) {
                          $query->where('type', 1)
                                ->where('user_id', auth()->id());
                      });
            })->orderByDesc('created_at')->paginate(16, ['*'], 'page', $page);
        }

        $notification->getCollection()->transform(function ($item) {
            $item->seen = $item->checkSee()
                ->where('user_id', auth()->id())
                ->where('see', 1)
                ->exists();
            return $item;
        });

        $html = $notification->map(function($item){
            return $this->config->ItemNotification("item-notification", $item);
        });
        return response()->json([
            'html' => $html,
            'total' => $notification->total(),
            'last_page' => $notification->lastPage(),
        ]);
    }


    /**
     * function mark as read notification
     *
     * @param integer $notificationId
     * @return void
     */
    public function markAsRead(int $notificationId): void
    {
        $userId = auth()->id();

        ViewNotification::updateOrCreate(
            [
                'notification_id' => $notificationId,
                'user_id' => $userId,
            ],
            [
                'see' => 1,
            ]
        );
    }


    /**
     * count notification not see 
     *
     * @return object
     */
    public function countNotification(): object
    {
        $user = auth()->user();

        
        $notification = Notification::where(function($query) {
            $query->where('type', 0)
                  ->orWhere(function($query) {
                      $query->where('type', 1)
                            ->where('user_id', auth()->id());
                  });
        })->whereDoesntHave('checkSee', function ($query) {
            $query->where('user_id', auth()->id())
                  ->where('see', 1);
        })->count();


        return response()->json([
            'quantity' => $notification,
        ]);
    }
}

