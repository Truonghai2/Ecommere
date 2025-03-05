<?php

namespace App\Http\Controllers;

use App\Reponsitories\DeviceReponsitory;
use App\Services\DeviceService;
use Illuminate\Http\Request;

class DeviceController extends Controller{
    protected $device;

    /**
     * denpendency injection
     *
     * @param DeviceService $deviceReponsitory
     */
    public function __construct(DeviceService $deviceReponsitory)
    {
        $this->middleware('auth');
        $this->device = $deviceReponsitory;
    }

    /**
     * save subcription id on database
     *
     * @param Request $request
     * @return void
     */
    public function Subcription_Id(Request $request){
        return $this->device->handleAdd($request->user_id ?? null, $request->subscription_id);
    }
}
