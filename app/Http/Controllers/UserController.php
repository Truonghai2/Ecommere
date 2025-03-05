<?php

namespace App\Http\Controllers;

use App\Client\config;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\user_log;
use App\Services\GhnService;
use App\Services\InfobipService;
use App\Services\NotificationService;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\ProductService;
use App\Services\SelectItemService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Colors\Rgb\Channels\Red;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */

    protected $user;
    protected $productService;
    protected $config;
    protected $ghnService;
    protected $infobip;
    protected $orderService;

    protected $SelectItem;
    protected $notification;

    /**
     * dependency injection service
     *
     * @param UserService $userService
     * @param ProductService $productService
     * @param config $config
     * @param GhnService $ghnService
     * @param InfobipService $infobipService
     * @param OrderService $orderService
     * @param SelectItemService $selectItemService
     */
    public function __construct(UserService $userService, ProductService $productService,
    config $config, GhnService $ghnService,
    InfobipService $infobipService, OrderService $orderService, SelectItemService $selectItemService, NotificationService $notificationService)
    {
        $this->middleware('auth');

        
        $this->user = $userService;
        $this->productService = $productService;
        $this->config = $config;
        $this->ghnService = $ghnService;
        $this->infobip = $infobipService;
        $this->orderService = $orderService;
        $this->SelectItem = $selectItemService;
        $this->notification = $notificationService;
    }

    /**
     * view manager user
     *
     * @return void
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * get item in cart by user
     *
     * @return void
     */
    public function cart() {
        $numerCart = Cart::count();

        // Fetch the paginated cart with related option attributes, attribute values, and product titles
        $cart = Cart::with(['option.attributes.attributeValue', 'option.product'])->orderByDesc('created_at')->paginate(15);


        // Flatten and group the unique attributes including the product title and quantity
        $uniqueAttributes = $cart->flatMap(function ($cartItem) {
            $variation = $cartItem->option;

            // Check if attributes and product are loaded correctly
            if (!isset($variation->attributes) || !isset($variation->product)) {
                $cartItem->load('product');
                // dd($cartItem);
                return collect([
                    [
                        'cart_id' => $cartItem->id,
                        'quantity' => $cartItem->quantity,
                        'product_title' => $cartItem->product->title,
                        'option' => ($cartItem->option_id != null) ? true : false,
                        'variation' => $cartItem->product,
                    ]
                ]);
            }

            return $variation->attributes->map(function ($attribute) use ($variation, $cartItem) {
                // Check if attribute and attributeValue are loaded correctly
                if (!isset($attribute->attribute) || !isset($attribute->attributeValue)) {
                    return collect();
                }

                return [
                    'cart_id' => $cartItem->id,
                    'quantity' => $cartItem->quantity,
                    'option' => ($cartItem->option_id != null) ? true : false,
                    'attribute_name' => $attribute->attribute->name,
                    'attribute_value' => $attribute->attributeValue->value,
                    'product_title' => $variation->product->title,
                    'variation' => $variation,
                ];
            });
        })->filter();

        // dd($uniqueAttributes);
        // Pass the necessary data to the view
        return view('layout.Card', [
            'check' => $numerCart,
            'product' => $uniqueAttributes,
            'total' => $cart->total(),
            'last_page' => $cart->lastPage(),
        ]);
    }

    /**
     * update cart use
     *
     * @param Request $request
     * @return boolean
     */
    public function updateCart(Request $request): bool
    {
        return $this->productService->updateCart($request->id, $request->quantity);
    }


    public function informationUser(){

        $previousUrl = session('previous_url');
        // dd($previousUrl);


        return view('layout.Information',[
            'url_old' => $previousUrl,
            'queueVerify' => $this->orderService->countQueue(),
        ]);
    }

    /**
     * search user
     *
     * @param Request $request
     * @return void
     */
    public function SearchUser(Request $request){
        $data = $request->data;
        return $this->user->search_user($data);
    }


    /**
     * get user
     *
     * @param Request $request
     * @return void
     */
    public function getUser(Request $request){
        $page = $request->page;

        return $this->user->getUser($page);
    }


    public function selectUser(Request $request){
        return $this->user->selectUser($request->page);
    }


    /**
     * function add data address 
     *
     * @param Request $request
     * @return void
     */
    public function addAddress(Request $request){
        $request->validate([
            'home_number' => 'required|string',
            'provinces' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
        ]);
        $this->user->handleAddress($request->only('home_number', 'provinces', 'district', 'ward'));
        
        // Quay lại trang trước
        return back();
    }

    /**
     * get total price all product selected
     *
     * @param Request $request
     * @return object
     */
    public function getPrice(Request $request):object
    {
        $array = json_decode($request->array, true);

        $totalPrice = 0;
        $totalSavePrice = 0;

        $listPrice = array_map(function($item) use (&$totalPrice, &$totalSavePrice) {
            $price = 0;
            $savePrice = 0;

            if ($item['option_id'] != null) {
                $option = ProductVariation::select('price', 'sale')->find($item['option_id']);
                if ($option) {
                    $price = $this->calculatePrice($option->price, $option->sale);
                    $savePrice = $this->calculateSavePrice($price, $option->price);
                }
            } else {
                $product = Product::select('price', 'sale')->find($item['product_id']);
                if ($product) {
                    $price = $this->calculatePrice($product->price, $product->sale);
                    $savePrice = $this->calculateSavePrice($price, $product->price);
                }
            }

            $totalPrice += $price * intval($item['quantity']);
            $totalSavePrice += $savePrice * intval($item['quantity']);
            return [
                'price' => $price,
                'savePrice' => $savePrice,
            ];
        }, $array);

        // dd($totalPrice, $totalSavePrice);
        return response()->json([
            'totalPrice' => $totalPrice,
            'totalSavePrice' => $totalSavePrice,
        ]);
    }
    /**
     * calculate price
     *
     * @param integer $price
     * @param integer $sale
     * @return integer
     */
    protected function calculatePrice(int $price, int $sale): int
    {
        return (int)($price - ($price * $sale / 100));
    }


    /**
     * calculate save money
     *
     * @param integer $price_new
     * @param integer $price_old
     * @return integer
     */
    protected function calculateSavePrice(int $price_new, int $price_old): int
    {
        return (int)($price_old - $price_new);
    }

    /**
     * handle trans option item cart
     *
     * @param Request $request
     * @return object
     */
    public function transCartProduct(Request $request): object
    {
        $cart_old = Cart::where('user_id', auth()->id())
                        ->where('product_id', $request->product_id)
                        ->where('option_id', $request->option_id_old)
                        ->first();

        if ($cart_old) {
            if($request->quantity < $cart_old->quantity){
                $cart_old->quantity -= $request->quantity;
            }
            else{
                $cart_old->delete();
            }

        }

        $cart_new = Cart::where('user_id', auth()->id())
                        ->where('product_id', $request->product_id)
                        ->where('option_id', $request->option_id_new)
                        ->first();

        if ($cart_new) {
            $cart_new->quantity += $request->quantity;
            $cart_new->save();
        } else {
            $cart_new = Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'option_id' => $request->option_id_new,
                'quantity' => $request->quantity,
            ]);
        }

        $cart_new->load('option.attributes.attributeValue', 'option.product');

        $uniqueAttributes = collect([$cart_new])->flatMap(function ($cartItem) {
            $variation = $cartItem->option;

            if (!isset($variation->attributes) || !isset($variation->product)) {
                return collect();
            }

            return $variation->attributes->map(function ($attribute) use ($variation, $cartItem) {
                if (!isset($attribute->attribute) || !isset($attribute->attributeValue)) {
                    return collect();
                }

                return [
                    'cart_id' => $cartItem->id,
                    'quantity' => $cartItem->quantity,
                    'attribute_name' => $attribute->attribute->name,
                    'attribute_value' => $attribute->attributeValue->value,
                    'product_title' => $variation->product->title,
                    'variation' => $variation,
                ];
            });
        })->filter();

        $html = $uniqueAttributes->map(function($item){
            return $this->config->itemCart('itemCart', $item);
        });

        return response()->json([
            'cart_old_id' => $cart_old ? $cart_old->id : null,
            'cart_new_id' => $cart_new->id,
            'html' => $html,
        ]);
    }

    /**
     * view payment page 
     *
     * @param Request $request
     * @return void
     */
    public function viewPay(Request $request)
    {
        $checkbox = $request->input('checkbox-product');
        $total_price = 0;
        $products = [];
        $items = [];
        $numberItem = 0;
        $total_weight = 0;


        $address = Address::where('user_id', auth()->id())->where('active', 1)->first();
        if ($checkbox) {
            foreach ($checkbox as $value) {
                // Fetch cart data
                $cartData = $this->productService->getProductCart($value);

                // Ensure $cartData is an array
                if (is_array($cartData)) {
                    // Check if necessary keys are present in $cartData
                    if (isset($cartData['product'], $cartData['total_price'], $cartData['quantity'])) {
                        // Process product and total price
                        $products[] = $cartData['product'];
                        $total_price += $cartData['total_price'];

                        // Process items if 'type' is true
                        if ($cartData['type']) {
                            foreach ($cartData['product'] as $product) {
                                $items[] = $this->extractItemData($product, $cartData['quantity']);
                            }
                        }
                        else{
                            $items[] = $this->extractItemData2($cartData['product'], $cartData['quantity']);
                        }
                        $total_weight += $cartData['weight'];

                        $numberItem += $cartData['quantity'];
                    }
                }
            }
        }
        if(count(auth()->user()->getAddress) > 0){

            if($total_weight >= 30000){
                $price_ship = $this->config->HandlePriceShipListProduct($address->district_id, $address->ward_id, $items, $total_price, $total_weight);
            }else{
                $price_ship = $this->config->HandlePriceShip($address->district_id, $address->ward_id, $items, $total_price);
            }
            $calculateTimeship = $this->config->calculateTimeship($address->district_id, $address->ward_id);
        }

        
        session([
            'products' => $products,
            'total_price' => $total_price,
            'price_ship' => $price_ship ?? 0,
            'items' => $items,
            'total_weight' => $total_weight,
        ]);

        return view('layout.Pay', [
            'provines' => Cache::remember('provinces', 525600, function() {
                return $this->ghnService->getProvinces();
            }),
            'type' => true,
            'product' => $products,
            'total_price' => $total_price,
            'items' => $items,
            'numberItem' => $numberItem,
            'price_ship' => $price_ship ?? 0,
            'calculateTimeship' => $calculateTimeship ?? 0,
        ]);
    }



    /**
     * Extract item data from product.
     *
     * @param array $product
     * @return array
     */
    private function extractItemData(array $product, int $quantity): array
    {


        return [
            'name' => $product['product_title'],
            'quantity' => $quantity,
            'height' => $product['variation']->height ?? 0,
            'weight' => $product['variation']->weight ?? 0,
            'length' => $product['variation']->length ?? 0,
            'width' => $product['variation']->width ?? 0,
        ];
    }

    private function extractItemData2(array $product, int $quantity): array
    {
        return [
            'name' => $product['title'],
            'quantity' => $quantity,
            'height' => $product['height'] ?? 0,
            'weight' => $product['weight'] ?? 0,
            'length' => $product['length'] ?? 0,
            'width' => $product['width'] ?? 0,
        ];
    }
    /**
     * function get address user by user id
     *
     * @param Request $request
     * @return object
     */
    public function getAddress(Request $request): object
    {
        return response()->json([
            'success' => true,
            'address' => Address::where('user_id', auth()->id())->get(),
        ]);
    }

    /**
     * controller add phone number and send code verify to user
     *
     * @param Request $request
     * 
     */
    public function addNumberPhone(Request $request)
    {
        $numberPhone = $request->phone;
        $numberPhone = $this->convertToInternationalFormat($numberPhone);
        // dd($numberPhone);
        $user = auth()->user();

        $user->phone_number = $numberPhone;
        $user->save();

        $this->infobip->sendVerificationCode($numberPhone);

        return true;
    }

    public function addPhoneNumber(Request $request){
        $numberPhone = $request->phone;
        $numberPhone = $this->convertToInternationalFormat($numberPhone);
        // dd($numberPhone);
        $user = auth()->user();

        $user->phone_number = $numberPhone;
        $user->save();

        return view('layout.VerifyPhoneNumber');
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

    /**
     * verify phone number
     *
     * @param Request $request
     * @return object
     */
    public function verifyPhoneNumber(Request $request): object
    {
        $code = $request->code;

        $check =  $this->infobip->verifyCode(auth()->user()->phone_number, $code);
        if($check){
            $user = auth()->user();
            $user->verify_number = 1;
            $user->save();
            return response()->json(['success' => true]);
        }
        else{
            return response()->json(['success' => false]);
        }
    }

    /**
     * view page verify number phone
     */
    public function showVerificationForm(){
        $this->infobip->sendVerificationCode(auth()->user()->phone_number);
        return view('layout.VerifyPhoneNumber');

    }


    /**
     *
     */
    public function verifyPhoneNumberForm(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string',
        ]);

        $user = auth()->user();
        $verificationCode = $request->input('verification_code');

        $isVerified = $this->infobip->verifyCode(auth()->user()->phone_number, $verificationCode);

        if ($isVerified) {
            $user->verify_number = 1;
            $user->save();

            return redirect()->intended();
        } else {
            return redirect()->route('verify.phone')->withErrors([
                'error' => 'Invalid verification code. Please try again.'
            ]);
        }
    }

    /**
     * create order
     *
     * @param Request $request
     * @return object
     */
    public function createOrder(Request $request): object
    {
        
        $type_payment = $request->type_payment;

        auth()->user()->type_payment = $type_payment;
        auth()->user()->save();

        $this->orderService->createOrder($type_payment);

        $total_price = session('total_price');
        return response()->json(['url' => $this->config->createPayment($total_price)]);
    }

    /**
     * api vpn respone data
     *
     * @param Request $request
     * @return void
     */
    public function response(Request $request){
        $vnp_SecureHash = $request->input('vnp_SecureHash');
        $vnp_HashSecret = (string)env('VNPAY_SECRET');

        $inputData = $request->only(array_filter(array_keys($request->all()), function ($key) {
            return substr($key, 0, 4) == 'vnp_';
        }));

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $hashData = urldecode(http_build_query($inputData));

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $vnp_PayDate = Carbon::createFromFormat('YmdHis', $request->input('vnp_PayDate'))->format('Y-m-d H:i:s');

        if($request->input('vnp_ResponseCode') == "00" && session('order_id') != null){

            $content = $this->orderService->updateStatusOrder(1);
            // $this->productService->updateQuantity();
            $this->notification->addNotificaion($content['content'], 'admin', [], $content['order_code']);
        }

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


    /**
     * function remake payment product
     *
     * @param Request $request
     * @return object
     */
    public function rePayment(Request $request): object
    {
        $order = Order::find($request->id);

        if ($order) {
            session(['order_id' => $order->id]);
            $paymentUrl = $this->config->createPayment($order->price_new);
            return response()->json(['url' => $paymentUrl]);
        }
    }

    /**
     * function edit information user
     *
     * @param Request $request
     * @return object
     */
    public function editInformation(Request $request): object
    {
        return $this->user->editInformation($request->data);
    }


    public function viewPageFavourite(Request $request){
        return view('user.favourite');
    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @return object
     */
    public function getProductFavourite(Request $request):object
    {
        return $this->SelectItem->getFavourite($request->page);
    }


    public function getStatusOrder(Request $request) {
        $order_codes = Order::where('user_id', auth()->id())
                            ->whereNotNull('order_code')
                            ->pluck('order_code');



        $order_codes->map(function($item){
            $data = $this->ghnService->getDetailOrder($item);
            $log = $data['data']['log'];
            $this->orderService->updateStatusOrderShip($item, $log[0]['status']);
        });

        return true;
    }

    /**
     * function cancel order product in ghn
     *
     * @param Request $request
     * @return boolean
     */
    public function cancelOrderProduct(Request $request): bool
    {
        $data = $this->ghnService->cancelOrder($request->order_code);
        if($data['data'][0]['result']){
            $content = auth()->user()->first_name . " ". auth()->user()->last_name.'#'. auth()->id() . ' đã hủy đơn hàng, mã đơn ' . $request->order_code;
            $this->notification->addNotificaion($content, 'admin', [], $request->order_code);
            $this->orderService->updateStatusOrderShip($request->order_code, 'cancel');
        }
        return true;
    }


    public function viewPhoneNumber(){
        return view('layout.AddPhoneNumber');
    }


    public function getCartUser(Request $request){
        $number = Cart::where('user_id', auth()->id())->count();

        return response()->json(['quantity' => $number]);
    }

}
