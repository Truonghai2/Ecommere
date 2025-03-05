<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GhnService{
    protected $token;
    protected $shopId;


    public function __construct()
    {
        $this->token = env('GHN_API_TOKEN'); // Lấy token từ file .env
        $this->shopId = env('GHN_SHOP_ID');
    }

    /**
     * get provines by api ghn
     *
     * @return void
     */
    public function getProvinces()
    {
        $response = Http::withHeaders([
            'token' => $this->token,
        ])->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/province');

        if ($response->successful()) {
            $data = $response->json();

            return $this->sortByName($data['data']);
        }

        return null; // Xử lý lỗi ở đây
    }

    /**
     * get district by provinces id
     *
     * @param integer $provinces_id
     * @return object
     */
    public function getDistrict(int $provinces_id): object
    {
        $response =  Http::withHeaders([
            'token' => $this->token,
        ])->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/district');

        if($response->successful()){

            $data = $response->json();
            $filteredData = $this->filterByProvinceId($data['data'], $provinces_id);

            return response()->json(['data' => $this->sortByNameDistrict($filteredData)]);

            // dd($filteredData);
            // return response()->json(['data' => $filteredData]);
        }

        return false;
    }

    /**
     * get ward by district id
     *
     * @param integer $district_id
     * @return object
     */
    public function getWard(int $district_id)
    {
        $response = Http::withHeaders([
            'token' => $this->token,
        ])->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/ward', [
            'district_id' => $district_id
        ]);

        if($response->successful()){
            return $response->json();
        }

        return null; // Handle error cases if needed
    }

    /**
     * get service by api ghn
     *
     * @return object
     */
    public function getService(): object
    {
        $response = Http::withHeaders([
            'token' => $this->token,
            'shop_id' => env('GHN_SHOP_ID'),
        ])->get('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/available-services',[
            "from_district" =>  1447,
            "to_district" =>  1442
        ]);

        if($response->successful()){
            return $response->json();
        }


    }

    public function getShippingFee($data)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'token' => $this->token,
            'ShopId' => $this->shopId,
        ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee', $data);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'success' => false,
            'message' => 'Unable to retrieve shipping fee',
        ];
    }


    public function HandlePriceShip(){
        $client = new Client();
        // $url = 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee';
        // $headers = [
        //     'Content-Type' => 'application/json',
        //     'token' =>  $this->token,

        // ];


        $url = 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee';
        $headers = [
            'Content-Type' => 'application/json',
            'Token' =>  $this->token,
            'ShopId' => $this->shopId,
        ];

        $body = [
            "service_id" => 53321,
            "insurance_value" => 500000,
            "coupon" => null,
            // "from_district_id" => 1542,
            "to_district_id" => 1444,
            "to_ward_code" => "20314",
            "height" => 30,
            "length" => 100,
            "weight" => 150000,
            "width" => 30
        ];

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $body,
            ]);

            $data = json_decode($response->getBody(), true);
            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to calculate shipping fee',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    private function filterByProvinceId($data, $provinces_id) {
        return array_filter($data, function($item) use ($provinces_id) {
            return $item['ProvinceID'] == $provinces_id;
        });
    }


    private function sortByName($data) {
        usort($data, function($a, $b) {
            return strcmp($a['ProvinceName'], $b['ProvinceName']);
        });
        return $data;
    }


    private function sortByNameDistrict($data) {
        usort($data, function($a, $b) {
            return strcmp($a['DistrictName'], $b['DistrictName']);
        });
        return $data;
    }


    public function calculateTimeship(int $to_district_id, int $to_ward_code){

    }

    /**
     * function create order ship
     *
     * @param array $body

     * @return object
     */
    public function createShippingOrder(array $body): object
    {
        try{
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'shopid' => env('GHN_SHOP_ID'),
                'token' => env('GHN_API_TOKEN'),
            ])->get('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/create', $body);

            // Handle the response
            if ($response->successful()) {
                return $response->json(); // or handle success as needed
            }
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    /**
     * function convert phone number (+84) to (0)
     * @param string $phoneNumber
     * @return string
     */
    public function convertPhoneNumber(string $phoneNumber): string
    {
        if (substr($phoneNumber, 0, 3) === '+84') {
            return '0' . substr($phoneNumber, 3);
        }
        return $phoneNumber;
    }


    /**
     * get detail order in ghn
     *
     * @param string $order_code
     * @return array
     */
    public function getDetailOrder(string $order_code):array
    {
        try{
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'shopid' => env('GHN_SHOP_ID'),
                'token' => env('GHN_API_TOKEN'),
            ])->get('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail', ['order_code' => $order_code]);

            if ($response->successful()) {
                return $response->json();
            }
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * function cancel order in ghn
     *
     * @param string $order_code
     * @return array
     */
    public function cancelOrder(string $order_code)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'shopid' => env('GHN_SHOP_ID'),
                'token' => env('GHN_API_TOKEN'),
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/switch-status/cancel', [
                'order_codes' => [$order_code]
            ]);

            if ($response->successful()) {

                return $response->json();
            }
        }catch (\Exception $e) {

            return $e->getMessage();
        }
    }


    public function printBillA5(string $order_code): string
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'shopid' => env('GHN_SHOP_ID'),
                'token' => env('GHN_API_TOKEN'),
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/a5/gen-token', [
                'order_codes' => [$order_code]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['data']['token'];
            }
        }catch (\Exception $e) {

            return $e->getMessage();
        }
    }
}
