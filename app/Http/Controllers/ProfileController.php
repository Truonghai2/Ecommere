<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use App\Services\OrderService;
use PDO;

class ProfileController extends Controller
{

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->middleware('auth');
        $this->orderService = $orderService;
    }

    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        auth()->user()->update($request->all());

        return back()->withStatus(__('Profile successfully updated.'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withPasswordStatus(__('Password successfully updated.'));
    }


    public function viewQueueVerify(){
        $orderPicking = $this->orderService->getItemQueue(1);
        $data = collect($orderPicking->items());

        // Nếu cần lấy danh sách product_id, có thể lấy sau khi gọi hàm items
        $productIds = $data->pluck('list_item_orders.*.product_id')->flatten();

        return view('user.QueueVerify', [
            'products' => $this->orderService->getItemQueue(1)->items(),
            'total' => $this->orderService->getItemQueue(1)->total(),
            'last_page' => $this->orderService->getItemQueue(1)->lastPage(),
            'productIds' => $productIds,
        ]);
    }

    public function viewSettingAccount(){

        return view('user.SettingAccount');
    }


    public function viewQueuePickOrder()
    {
        $orderPicking = $this->orderService->getOrderPicking(1);
        $data = collect($orderPicking->items());

        // Nếu cần lấy danh sách product_id, có thể lấy sau khi gọi hàm items
        $productIds = $data->pluck('list_item_orders.*.product_id')->flatten();

        return view('user.QueueProduct', [
            'products' => $data,
            'total' => $orderPicking->total(),
            'last_page' => $orderPicking->lastPage(),
            'productIds' => $productIds,
        ]);
    }


    public function viewQueueShipping(){
        return view('user.QueueShipping');
    }

    public function viewHistoryOrder(){
        return view('user.historyOrder');
    }


    public function viewRatingProduct(){
        return view('user.QueueRating');
    }
}
