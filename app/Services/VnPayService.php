<?php 
namespace App\Services;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
class VnPayService{

    protected $accesstoken;

    
    public function __construct()
    {
        
    }
    public function getAccessToken(){
        $data = [
            'clientId' => (string)env("VNPAY_TMN"),
            "username" => "truonghai16122002@gmail.com",
            "password" => "Truonghai2002",
        ];

        // dd($data);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://sandbox.vnpayment.vn/isp-svc/oauth/authenticate', $data);

        if($response->successful()){
            return $response;
        }
    }

    public function register()
    {
        $this->app->singleton(VnPayService::class, function ($app) {
            return new VnPayService();
        });
    }

    public function createPayment(int $price)
    {
        // session(['cost_id' => $request->id]);
        session(['url_prev' => url()->previous()]);
        $vnp_TmnCode = (string)env('VNPAY_TMN'); //Mã website tại VNPAY 
        $vnp_HashSecret = (string)env('VNPAY_SECRET'); //Chuỗi bí mật
        $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = (string)route('vnpay.response');
        $vnp_TxnRef = date("YmdHis"); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "Thanh toán hóa đơn sản phẩm";
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $price * 100;
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



}