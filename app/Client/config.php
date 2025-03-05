<?php 
namespace App\Client;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class config{

    public static function handleTime($created_at){
        $startDate = Carbon::parse($created_at); // Sử dụng parse để chuyển đổi ngày tháng từ chuỗi hoặc timestamp
        $endDate = Carbon::now(); // Ngày đích, hiện tại
        $diff = $startDate->diffForHumans($endDate);

        return $diff;
    }


    public static function handlePrice($price, $sale)
    {
        $finalPrice = ceil((int)($price - ($price * $sale / 100)));
        return number_format($finalPrice, 0, ',', '.') . 'đ';
    }

    public static function formatPrice($price){
        return number_format(ceil($price), 0, ',', '.') . 'đ';
    }


    /**
     * function calculate fee ship weight < 30kg
     *
     * @param integer $to_district_id
     * @param integer $to_ward_code
     * @param array $array
     * @param integer $total_price
     * @return void
     */
    public static function HandlePriceShip(int $to_district_id, int $to_ward_code, array $array, int $total_price){
        $client = new Client();

        $url = 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee';
        $headers = [
            'Content-Type' => 'application/json',
            'token' =>  (string)env('GHN_API_TOKEN'),
            'shopid' => env('GHN_SHOP_ID'),
        ];

        $body = [
            "service_type_id" => 2,
            'to_district_id' => $to_district_id,
            'to_ward_code' => (string)$to_ward_code,
            'weight' => 500,
            'insurance_value' => $total_price,
            'coupon' => null,
            'items' => $array,
        ];
        
        try {
            $response = $client->get($url, [
                'headers' => $headers,
                'json' => $body,
            ]);

            $data = json_decode($response->getBody(), true);
            return self::formatPrice($data['data']['total']);
           

        } catch (\Exception $e) {
            Log::error("message", [$e->getMessage()]);
            return $e->getMessage();
        }

    }

    /**
     * function calculate fee ship weight >= 30kg
     *
     * @param integer $to_district_id
     * @param integer $to_ward_code
     * @param array $array
     * @param integer $total_price
     * @return string
     */    
    public static function HandlePriceShipListProduct(int $to_district_id, int $to_ward_code, array $array, int $total_price, int $total_weight): string
    {
        $client = new Client();
        $url = 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee';
        $headers = [
            'Content-Type' => 'application/json',
            'token' =>  (string)env('GHN_API_TOKEN'),
            'shopid' => env('GHN_SHOP_ID'),
        ];

        $body = [
            "service_type_id" => 2,
            'to_district_id' => $to_district_id,
            'to_ward_code' => (string)$to_ward_code,
            'weight' => $total_weight,
            'insurance_value' => $total_price,
            'coupon' => null,
            'items' => $array,
        ];

        try {
            $response = $client->get($url, [
                'headers' => $headers,
                'json' => $body,
            ]);

            $data = json_decode($response->getBody(), true);
            return self::formatPrice($data['data']['total']);
           

        } catch (\Exception $e) {
            Log::error("message", [$e->getMessage()]);
            return $e->getMessage();
        }

        
    }
    /**
     * function calculate time ship 
     *
     * @param integer $to_district_id
     * @param integer $to_ward_code
     * @return string
     */
    public static function calculateTimeship(int $to_district_id, int $to_ward_code): string
    {
        $response = Http::withHeaders([
            'token' =>  env('GHN_API_TOKEN'),
            'ShopId' => env('GHN_SHOP_ID'),
        ])->get('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/leadtime', [
            "to_district_id" => $to_district_id,
            "to_ward_code" => (string)$to_ward_code,
            "service_id" => 100039,
            "shop_id" => env('GHN_SHOP_ID'),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['data']['leadtime'])) {
                $leadtime = Carbon::createFromTimestamp($data['data']['leadtime'])->toDateString(); 
                return "$leadtime\n";
            } else {
                return "Leadtime not found in the response.\n";
            }
        } else {
            return "Failed to fetch leadtime.\n";
        }
    }


    /**
     * function get service of api ghn
     *
     * @return void
     */
    public static function getService(){
        $response = Http::withHeaders([
            'token' =>  env('GHN_API_TOKEN'),
        ])->get('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/available-services',[
            "from_district" =>  1542,
            "to_district" =>  3287,
            "shop_id" => env('GHN_SHOP_ID'),
        ]);

        if($response->successful()){
            dd($response->json());
            return $response->json();
        }
    }

    /**
     * function create payment 
     *
     * @param integer $price
     * @return void
     */
    public static function createPayment(int $price)
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
        return $vnp_Url;
    }



    public function getThumbnail($item){

        return view('layout.thumbnail',[
            'id' => $item['id'],
            'type' => $item['type'],
            'title' => $item['title'],
            'image' => $item['image'],
            'content' => $item['content'],
        ])->render();
    }

    public function getUserTable($type, $item){
        
        return view('layout.ItemTable',[
            'type' => $type,
            'item' => $item,
        ])->render();
    }


    public function getCategory($type, $item, $time){
        return view('layout.ItemTable',[
            'type' => $type,
            'item' => $item,
            'time' => $time,
        ])->render();
    }


    public function getProduct($type, $item){
        return view('layout.ItemTable',[
            'type' => $type,
            'item' => $item,
        ])->render();
    }

    public function getNotification($type, $index, $item){
        return view('layout.ItemTable',[
            'type' => $type,
            'index' => $index,
            'item' => $item,
        ])->render();
    }


    public function itemCart($type, $item){
        return view('layout.ItemTable',[
            'type' => $type,
            'item' => $item,
        ])->render();
    }

    public function MenuOption($type, $product, $attribute, $option_id){
        return view('layout.ItemTable',[
            'type' => $type,
            'product' => $product,
            'attribute' => $attribute,
            'option_id' => $option_id, 
        ])->render();
    }

    /**
     * Undocumented function
     *
     * @param array $body
     * @return object
     */
    public function createShippingOrder(array $body): object
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'ShopId' => env('GHN_SHOP_ID'),
            'Token' => env('GHN_API_TOKEN'),
        ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/create', $body);

        // Handle the response
        if ($response->successful()) {
            return $response->json(); // or handle success as needed
        } else {
            // Handle error
            return response()->json(['error' => 'Failed to create shipping order'], 400);
        }
    }


    /**
     * convert phone number
     *
     * @param string $phone
     * @return string
     */
    public function convertToInternationalFormat(string $phone): string
    {
        // Xóa các ký tự không phải là số
        $phone = preg_replace('/\D/', '', $phone);

        // Nếu số điện thoại bắt đầu với số 0, thay thế bằng +84
        if (substr($phone, 0, 1) === '0') {
            $phone = '+84' . substr($phone, 1);
        }

        return $phone;
    }


    public function ItemNotification($type, $item){
        return view('components.blockNotification',[
            'type' => $type,
            'item' => $item,
        ])->render();
    }
}