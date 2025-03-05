<?php

namespace App\Http\Controllers;

use App\Client\config;
use App\Services\CategoriesService;
use App\Services\GhnService;
use App\Services\HomeService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laracasts\Flash\Flash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $home;
    protected $ghnService;
    protected $categories;
    protected $product;
    public function __construct(HomeService $homeService, GhnService $ghnService, CategoriesService $categoriesService, ProductService $productService)
    {
        $this->middleware('auth');
        $this->home = $homeService;
        $this->ghnService = $ghnService;
        $this->categories = $categoriesService;
        $this->product = $productService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * function get thumbnail home and return view home
     *
     * @param Request $request
     * @return void
     */
    public function getThumbnail(Request $request){
        

        $index = $this->home->getALL();
        $config = new config();

        $html = array_map(function($item) use($config) {
            return $config->getThumbnail($item);
        }, $index);

        $CategoriesProduct = $this->product->getproductCategories();


        $userId = auth()->id();
        $Recommendations = [];
        Log::info("Checking cache key: user_{$userId}_recommendations");
        if (Cache::has("user_{$userId}_recommendations")) {
            Log::info("Cache found for key: user_{$userId}_recommendations");
            $Recommendations = Cache::get("user_{$userId}_recommendations");
        } else {
            Log::warning("No cache found for key: user_{$userId}_recommendations");
        }


        // $this->product->Recommendation_personalization($userId);

        return view('home',[
            'html' => $html,
            'categories' => $this->categories->getCategory(),
            'category_product' => $CategoriesProduct,
            'recommendation' => $Recommendations,
        ]);
    }

    /**
     * get provinces where api ghn
     *
     * @return void
     */
    public function getProvinces()
    {
        $provinces = $this->ghnService->getProvinces();

        if ($provinces) {
            return response()->json($provinces);
        }

        return response()->json(['message' => 'Unable to fetch provinces'], 500);
    }

    /**
     * get district where api ghn
     *
     * @param Request $request
     * @return void
     */
    public function getDistrict(Request $request){
        $district = $this->ghnService->getDistrict($request->provinces_id);

        if($district){
            return $district;
        }

        return false;
    }


    /**
     * get ward where api ghn
     *
     * @param Request $request
     * @return void
     */
    public function getWard(Request $request){
        $ward = $this->ghnService->getWard($request->district_id);

        if($ward){
            return response()->json($ward);
        }
        return false;
    }

    public function flashNotification(Request $request){
        Flash::success('Thông báo của bạn đã được xử lý thành công!');


        return redirect()->back();
    }
}
