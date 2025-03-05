<?php

namespace App\Http\Controllers;

use App\Services\VnPayService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $vnPayService;
    public function __construct(VnPayService $vnPayService)
    {
        $this->vnPayService = $vnPayService;
    }
    public function testPay(Request $request){
        session(['cost_id' => $request->id]);
        session(['url_prev' => url()->previous()]);
        $vnp_TmnCode = (string)env('VNPAY_TMN'); //Mã website tại VNPAY 
        $vnp_HashSecret = (string)env('VNPAY_SECRET'); //Chuỗi bí mật
        // dd($vnp_HashSecret, $vnp_TmnCode);
        $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = (string)route('vnpay.response');
        $vnp_TxnRef = date("YmdHis"); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "Thanh toán hóa đơn sản phẩm";
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = 10000000 * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.0.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . $key . "=" . $value;
            } else {
                $hashdata .= $key . "=" . $value;
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        return redirect($vnp_Url);
    }

    public function response(Request $request){
        $vnp_SecureHash = $request->input('vnp_SecureHash');
        $vnp_HashSecret = (string)env('VNPAY_SECRET'); // Đảm bảo bạn đã cấu hình vnp_HashSecret trong file config

        $inputData = $request->only(array_filter(array_keys($request->all()), function ($key) {
            return substr($key, 0, 4) == 'vnp_';
        }));

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $hashData = urldecode(http_build_query($inputData));

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $vnp_PayDate = Carbon::createFromFormat('YmdHis', $request->input('vnp_PayDate'))->format('Y-m-d H:i:s');
        
        return view('Vnpay.Return', [
            'vnp_TxnRef' => $request->input('vnp_TxnRef'),
            'vnp_Amount' => $request->input('vnp_Amount'),
            'vnp_OrderInfo' => $request->input('vnp_OrderInfo'),
            'vnp_ResponseCode' => $request->input('vnp_ResponseCode'),
            'vnp_TransactionNo' => $request->input('vnp_TransactionNo'),
            'vnp_BankCode' => $request->input('vnp_BankCode'),
            'vnp_PayDate' => $vnp_PayDate,
            'secureHash' => $secureHash,
            'vnp_SecureHash' => $vnp_SecureHash
        ]);
    }



    public function testToken(){
        return $this->vnPayService->getAccessToken();
    }
}
