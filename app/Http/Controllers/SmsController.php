<?php

namespace App\Http\Controllers;

use App\Services\InfobipService;
use App\Services\OrderService;
use App\Services\TwilioService;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    protected $infobip;
    protected $orderService;

    public function __construct(InfobipService $infobip, OrderService $orderService)
    {
        $this->middleware('auth');
        $this->infobip = $infobip;
        $this->orderService = $orderService;
    }

    public function sendSms(Request $request)
    {
        $to = "+84344885035";
        $message = "123";


        $this->infobip->sendSms($to, $message);

        return response()->json(['message' => 'SMS sent successfully']);
    }



}
