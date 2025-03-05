<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\GhnService;
use App\Services\NotificationService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    protected $orderService;
    protected $ghnService;
    protected $notification;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OrderService $orderService, GhnService $ghnService, NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->orderService = $orderService;
        $this->ghnService = $ghnService;
        $this->notification = $notificationService;
    }

    /**
     *  function get item order of user
     * @param Request $request
     * @return object
     */
    public function getOrder(Request $request): object
    {
        $order = Order::where('status_order', 1)->whereNotNull('order_code')->with('list_item_orders')->paginate(16, ['*'], 'page', $request->page);

        return response()->json([
            'orders' => $order->items(),
            'last_page' => $order->lastPage(),
            'total' => $order->total(),
        ]);

    }

    /**
     *
     */
    public function PrintBillOrderTOA5(Request $request): object
    {

        $token = $this->ghnService->printBillA5($request->order_code);

        return response()->json(['url' => 'https://online-gateway.ghn.vn/a5/public-api/printA5?token=' . $token]);
    }

    /**
     * admin cancel order product user
     *
     * @param Request $request
     * @return boolean
     */
    public function cancelOrderProduct(Request $request): bool
    {

        $check = Order::where('order_code', $request->order_code)->select('status_ship')->first();

        if($check['status_ship'] == 'cancel'){
            return false;
        }

        $data = $this->ghnService->cancelOrder($request->order_code);
        $reason = $request->reason;
        if($data['data'][0]['result']){
            $content = 'Quản lý ' . auth()->user()->first_name . " ". auth()->user()->last_name.'#'. auth()->id() . ' đã hủy đơn hàng, mã đơn ' . $request->order_code . ". Lý do: " . $reason;
            $this->notification->addNotificaion($content, 'user', [$request->user_id], $request->order_code);
            $this->orderService->updateStatusOrderShip($request->order_code, 'cancel');
        }
        return true;
    }

}
