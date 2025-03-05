<?php

namespace App\Services;

use App\Client\config;
use App\Models\Address;
use App\Models\ListItemOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\user_log;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OrderService{

    protected $ghnService;
    protected $config;

    public function __contruct(GhnService $ghnService, config $config){
        $this->ghnService = $ghnService;
        $this->config = $config;
    }

    /**
     * create order
     *
     * @param integer $type_payment
     * @return void
     */
    public function createOrder(int $type_payment): void
    {
        $products = session('products');
        $total_price = session('total_price');
        $price_ship = session('price_ship');
        $items = session('items');
        $total_weight = session('total_weight');

        // Ensure session variables are not null
        if (!$products || !$total_price || !$price_ship) {
            throw new \Exception('Required session data is missing.');
        }

        $userId = auth()->id();
        if (!$userId) {
            throw new \Exception('User not authenticated.');
        }


        $address = Address::where('user_id', $userId)->where('active', 1)->first();

        if($total_weight >= 30000){
            $body = $this->createbody(auth()->user()->phone_number, $this->stringAddress($address->home_number, $address->ward_name, $address->district_name, $address->provinces_name), (string)$address->ward_id, $address->district_id, "test", $total_weight, 15, 15, 15, $total_price, 5, $items);
        }
        else{
            $body = $this->createbody(auth()->user()->phone_number, $this->stringAddress($address->home_number, $address->ward_name, $address->district_name, $address->provinces_name), (string)$address->ward_id, $address->district_id, "test", 15, 15, 15, 15, $total_price, 2, $items);
        }

        // dd($body);
        $order = Order::create([
            'user_id' => $userId,
            'payment_id' => $type_payment,
            'to_ward_name' => $address->ward_name,
            'to_district_name' => $address->district_name,
            'to_province_name' => $address->provinces_name,
            'to_user_name' => auth()->user()->first_name . " " . auth()->user()->last_name,
            'to_phone_number' => auth()->user()->phone_number,
            'price_old' => $total_price,
            'price_save' =>  $this->convertPrice($price_ship),
            'price_ship' =>  $this->convertPrice($price_ship),
            'price_new' => $total_price,
            'status_order' => 0,
            'content' => "test",
            'body' => json_encode($body),
            'created_at' => now(),
        ]);

        $array_logs = [];


        $listItemOrders = [];
        
        foreach ($products as $product) {
            if (count($product) == 1) {
                foreach ($product as $item) {
                    $array_logs[] = [
                        'user_id' => auth()->id(),
                        'product_id' => $item['variation']->product_id,
                        'actions' => 'purchased'
                    ];

                    $listItemOrders[] = [
                        'order_id' => $order->id,
                        'product_id' => $item['variation']->product_id,
                        'option_id' => $item['variation']->id,
                        'name' => $item['product_title'],
                        'name_option' => $item['attribute_value'],
                        'price' => $item['variation']->price,
                        'sale' => $item['variation']->sale,
                        'quantity' => $item['quantity'],
                        'poster' => $item['variation']->poster,
                    ];
                }
            } else {
                $array_logs[] = [
                    'user_id' => auth()->id(),
                    'product_id' => $product['id'],
                    'actions' => 'purchased'
                ];

                $listItemOrders[] = [
                    'order_id' => $order->id,
                    'product_id' => $product['id'],
                    'name' => $product['title'],
                    'price' => $product['price'],
                    'sale' => $product['sale'],
                    'quantity' => $product['cart_quantity'],
                    'poster' => $product['poster'],
                ];
            }
        }

        user_log::insert($array_logs);

        ListItemOrder::insert($listItemOrders);

        session()->forget(['products', 'price_ship', 'total_weight']);
        session(['order_id' => $order->id]);
    }


    /**
     * function convert price string to price int
     *
     * @param string $price
     * @return integer
     */
    function convertPrice(string $price): int
    {
        $price = str_replace('đ', '', $price);

        $price = str_replace('.', '', $price);

        $price = (int) $price;

        return $price;
    }


    /**
     * Undocumented function
     *
     * @param string $home_number
     * @param string $ward_name
     * @param string $district_name
     * @param string $provice_name
     * @return string
     */
    private function stringAddress(string $home_number, string $ward_name, string $district_name, string $provice_name): string
    {
        return $home_number .", ". $ward_name .", ". $district_name .", ". $provice_name. ", Vietnam";
    }


    /**
     * function update table order by order id
     *
     * @param string $status
     * @return array
     */
    public function updateStatusOrder(string $status): ?array
    {
        $order_id = session('order_id');

        $order = Order::where('id', $order_id)->first();
        if($order){
            $body = json_decode($order->body, true);

            $dataItem = $order->list_item_orders;


            if($dataItem){
                $this->updateQuantityProduct($dataItem);
            }
            
            
            $order_code_response = $this->createShippingOrder($body);

            dd($order_code_response);
            $content = auth()->user()->first_name . " ". auth()->user()->last_name.'#'. auth()->id() . ' đã đặt đơn hàng, mã đơn ' . $order_code_response['data']['order_code'];

            if (isset($order_code_response['data']['order_code'])) {
                $order->order_code = $order_code_response['data']['order_code'];
            }

            $order->status_order = $status;
            $order->save();

            
            session()->forget(['order_id', 'total_price', 'items']);


            return [
                'content' => $content,
                'order_code' => $order_code_response['data']['order_code'],
            ];
        }

        session()->forget(['order_id', 'total_price', 'items']);


        return null;
    }


    /**
     * count item in queue payment
     *
     * @return integer
     */
    public function countQueue(): int
    {

        $order = Order::where('user_id', auth()->id())->where('status_order', 0)->count();
        return $order;
    }


    /**
     * get item status queue pick
     *
     * @param integer $page
     * @return void
     */
    public function getItemQueue(int $page)
    {
        $order = Order::where('user_id', auth()->id())->where('status_order', 0)->with('list_item_orders')->orderByDesc('created_at')->paginate(15, ['*'], 'page', $page);
        return $order;
    }

    /**
     * get item status picking
     *
     * @param integer $page
     * @return void
     */
    public function getOrderPicking(int $page){
        return Order::where('user_id', auth()->id())->where('status_order', 1)->where('status_ship', 'picking')->with('list_item_orders')->orderByDesc('created_at')->paginate(15, ['*'], 'page', $page);
    }


    /**
     * create array body
     *
     * @param string $to_phone_number
     * @param string $to_address
     * @param string $ward_name
     * @param string $to_district_name
     * @param string $to_province_name
     * @param string $content
     * @param integer $weight
     * @param integer $height
     * @param integer $width
     * @param integer $length
     * @param integer $total_price
     * @param integer $type_service
     * @param array $items
     * @return array
     */
    public function createbody(
        string $to_phone_number,
        string $to_address,
        string $to_ward_code,
        int $to_district_id,
        string $content,
        int $weight,
        int $height,
        int $width,
        int $length,
        int $total_price,
        int $type_service,
        array $items
    ): array {
        return [
            "payment_type_id" => 1,
            "note" => "Hàng dễ vỡ vui lòng nhẹ tay.",
            "required_note" => "CHOXEMHANGKHONGTHU",
            "return_phone" => $this->convertPhoneNumber(auth()->user()->phone_number),
            "return_address" => null,
            "return_district_id" => null,
            "return_ward_code" => "",
            "client_order_code" => "",
            "from_name" => null,
            "from_phone" => null,
            "from_address" => null,
            "from_ward_name" => null,
            "from_district_name" => null,
            "from_province_name" => null,
            "to_name" => auth()->user()->first_name . " " . auth()->user()->last_name,
            "to_phone" => $this->convertPhoneNumber($to_phone_number),
            "to_address" => $to_address,
            "to_ward_code" => $to_ward_code,
            "to_district_id" => $to_district_id,
            "cod_amount" => 0,
            "content" => $content,
            "weight" => $weight,
            "length" => $length,
            "width" => $width,
            "height" => $height,
            "cod_failed_amount" => 0,
            "deliver_station_id" => null,
            "insurance_value" => 0,
            "service_type_id" => $type_service,
            "coupon" => null,
            "pickup_time" => null,
            "pick_shift" => null,
            "items" => $items
        ];
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
     * function create order shipping in ghn api
     *
     * @param array $body
     * @return array
     */
    public function createShippingOrder(array $body)
    {
        try {

            if (isset($body['pick_station_id']) && is_string($body['pick_station_id'])) {
                $body['pick_station_id'] = (int) $body['pick_station_id'];
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'shopid' => env('GHN_SHOP_ID'),
                'token' => (string)env('GHN_API_TOKEN'),
            ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/preview', $body);

            if ($response->successful()) {
                return $response->json();
            }

        } catch (\Exception $e) {
            return (object) ['error' => $e->getMessage()];
        }
    }

    /**
     * function get status order ship
     *
     * @param string $order_code
     * @param string $status
     * @return void
     */
    public function updateStatusOrderShip(string $order_code, string $status): void
    {

        $order = Order::where('order_code', $order_code)->first();
        if ($order) {
            if ($order->status_ship != $status) {
                $order->status_ship = $status;
                $order->save();
            }
        }
    }


    /**
     * handle quantity product 
     *
     * @param Collection $data
     * @return void
     */
    public function updateQuantityProduct(Collection $data): void
    {
        $productIds = [];
        $variationIds = [];

        // Step 1: Collect product and variation IDs
        foreach ($data as $item) {
            $productIds[] = $item->product_id;

            if (!is_null($item->option_id)) {
                $variationIds[] = $item->option_id;
            }
        }

        $productIds = array_unique($productIds);
        $variationIds = array_unique($variationIds);

        // Step 2: Fetch products and variations in a single query
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $productVariations = ProductVariation::whereIn('id', $variationIds)->get()->keyBy('id');

        // Step 3: Update quantities in memory
        foreach ($data as $item) {
            $product = $products[$item->product_id];
            $product->quantity_saled += $item->quantity;
            
            if ($product->option_type == 0) {
               
                $variation = $productVariations[$item->option_id];
                $variation->quantity -=  $item->quantity;
            } else {
                $product->quantity -= $item->quantity;
            }
        }

        $productUpdates = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'quantity_saled' => $product->quantity_saled,
                'quantity' => $product->quantity,
            ];
        })->values()->toArray();

        $variationUpdates = $productVariations->map(function ($variation) {
            return [
                'id' => $variation->id,
                'quantity' => $variation->quantity,
            ];
        })->values()->toArray();

        
        DB::transaction(function () use ($productUpdates, $variationUpdates) {

            foreach ($productUpdates as $update) {
                Product::where('id', $update['id'])
                    ->update([
                        'quantity' => $update['quantity'],
                        'quantity_saled' => $update['quantity_saled'],
                    ]);
            }
    
            foreach ($variationUpdates as $update) {
                // dd($update);
                ProductVariation::where('id', $update['id'])
                    ->update([
                        'quantity' => $update['quantity'],
                    ]);
            }
        });
    }

}
