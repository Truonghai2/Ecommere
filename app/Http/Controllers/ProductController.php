<?php

namespace App\Http\Controllers;

use App\Client\config;
use App\Jobs\ProcessProductsJob;
use App\Jobs\UploadImageJobs;
use App\Jobs\UploadPosterJob;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Favourite;
use App\Models\PreviewImage;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationAttribute;
use App\Models\user_log;
use App\Services\CategoriesService;
use App\Services\FavouriteService;
use App\Services\GhnService;
use App\Services\GoogleDriveService;
use App\Services\NotificationService;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    protected $googleDriveService;
    protected $productService;
    protected $category;
    protected $ghnService;
    protected $favourite;
    protected $userService;
    protected $config;
    protected $orderService;
    protected $notificationService;

    /**
     * denpendency injection
     *
     * @param GoogleDriveService $googleDriveService
     * @param ProductService $productService
     * @param CategoriesService $categoriesService
     * @param GhnService $ghnService
     */
    public function __construct(
        GoogleDriveService $googleDriveService,
        ProductService $productService,
        CategoriesService $categoriesService,
        GhnService $ghnService,
        FavouriteService $favouriteService,
        UserService $userService,
        config $config,
        OrderService $order,
        NotificationService $notificationService,
    ) {
        $this->googleDriveService = $googleDriveService;

        $this->productService = $productService;

        $this->category = $categoriesService;

        $this->ghnService = $ghnService;

        $this->favourite = $favouriteService;

        $this->userService = $userService;

        $this->config = $config;

        $this->orderService = $order;

        $this->notificationService = $notificationService;
    }


    /**
     * get view page
     *
     * @param Request $request
     * @return void
     */
    public function viewNewProduct(Request $request)
    {

        



        return view('admin.pages.NewProduct', [
            'category' => $this->category->getCategory(),
            'attribute' => Attribute::all(),
        ]);
    }

    public function viewPageProduct(Request $request){
        return view('admin.pages.Products',[
            'categories' => $this->category->getCategory(),
            'attribute' => Attribute::all(),
        ]);
    }


    /**
     * add many product use Jobs
     *
     * @param Request $request
     * @return void
     */
    public function addProduct(Request $request)
    {


        $array = $request->products;

        array_map(function ($item) {

            ProcessProductsJob::dispatch($item);
        }, $array);

        return response()->json(['success' => true]);
    }

    public function getProduct(Request $request)
    {
        $page = $request->page ?: 1;

        // Retrieve paginated products with their categories and previewImages
        $products = Product::with([
            'categories',
            'previewImages',
            'variations.attributes.attribute',
            'variations.attributes.attributeValue'
        ])
        ->orderByDesc('created_at')
        ->paginate(16, ['*'], 'page', $page);

        // Xử lý sản phẩm để lấy thuộc tính của biến thể nếu type_option là 0
        $products->getCollection()->transform(function($product) {
            if ($product->option_type == 0) {
                $product->load('variations.attributes.attribute', 'variations.attributes.attributeValue');
                $variations = $product->variations;

                // Lấy thuộc tính độc nhất
                $uniqueAttributes = $variations->flatMap(function ($variation) {
                    return $variation->attributes->map(function ($attribute) use ($variation) {
                        // Kiểm tra nếu attribute và attributeValue không phải là null
                        $attributeId = $attribute->attribute->id;
                        $attributeName = $attribute->attribute->name ?? 'N/A';
                        $attributeValue = $attribute->attributeValue->value ?? 'N/A';
                        return [
                            'attribute_id' => $attributeId,
                            'attribute_name' => $attributeName,
                            'attribute_value' => $attributeValue,
                            'variation' => $variation,
                        ];
                    });
                })->groupBy('attribute_name');

                $product->uniqueAttributes = $uniqueAttributes;


                // Get min and max prices and sales
                $prices = $variations->pluck('price')->filter();
                $sales = $variations->pluck('sale')->filter();

                $product->min_price = $prices->min();
                $product->max_price = $prices->max();
                $product->min_sale = $sales->min();
                $product->max_sale = $sales->max();
            } else {
                $product->uniqueAttributes = null;

                $product->uniqueAttributes = null;
                $product->min_price = null;
                $product->max_price = null;
                $product->min_sale = null;
                $product->max_sale = null;
            }

            return $product;
        });


        return response()->json([

            'products'=> $products->items(),
            'total' => $products->total(),
            'last_page' => $products->lastPage(),
        ]);
    }


    /**
     * search product
     *
     * @param Request $request
     * @return void
     */
    public function searchPerpage(Request $request)
    {
        $data = $request->data;

        $page = $request->page;

        return $this->productService->PerPageSearch($data, $page);
    }

    /**
     * function get detail product where id
     *
     * @param int $id
     * @return void
     */
    public function detailProduct($id)
    {

        user_log::create([
            'user_id' => auth()->id(),
            'product_id' => $id,
            'actions' => 'view'
        ]);


        $product = Product::with(['categories', 'previewImages'])->find($id);
        
        if ($product->option_type == 0) {
            $product->load('variations.attributes.attributeValue');
            $variation = $product->variations;
           

            $uniqueAttributes = $variation->flatMap(function ($variation){
                return $variation->attributes->map(function ($attribute) use ($variation) {
                    return [
                        'attribute_name' => $attribute->attribute->name,
                        'attribute_value' => $attribute->attributeValue->value,
                        'variation' => $variation,
                    ];
                });
            })->groupBy('attribute_name');
        }

        $provinces = Cache::remember('provinces', 525600, function() {
            return $this->ghnService->getProvinces();
        });

        

        if (!$product) {
            abort(404); 
        }

        // dd($product);
        return view('product', [
            'product' => $product,
            'provines' => $provinces,
            'attribute' => $uniqueAttributes ?? 0,
        ]);
    }

    /**
     * function get price ship product
     *
     * @param Request $request
     * @return void
     */
    public function calculateShippingFee(Request $request)
    {
        $data = [
            "service_type_id" => $request->input('service_type_id'),
            "from_district_id" => $request->input('from_district_id'),
            "to_district_id" => $request->input('to_district_id'),
            "to_ward_code" => $request->input('to_ward_code'),
            "height" => $request->input('height'),
            "length" => $request->input('length'),
            "weight" => $request->input('weight'),
            "width" => $request->input('width'),
            "insurance_value" => $request->input('insurance_value'),
            "coupon" => $request->input('coupon'),
            "items" => $request->input('items'),
        ];

        $response = $this->ghnService->HandlePriceShip();

        return response()->json($response);
    }


    /**
     * function get item product hot
     *
     * @param Request $request
     * @return void
     */
    public function hotTrend(Request $request)
    {
        return $this->productService->getHotProduct($request->page, $request->limit);
    }

    /**
     * function handle recomendation product
     *
     * @param int $id
     * @param Request $request
     * @return void
     */
    public function RecomendationProduct($id, Request $request)
    {
        return $this->productService->handleRecommendation($id, $request->page);
    }


    public function ManyRecomendationProduct(Request $request): object
    {
        return $this->productService->handleRecommendationMany($request->ids, $request->page);
    }

    /**
     * function filter product
     *
     * @param Request $request
     * @return object
     */
    public function FilterProduct(Request $request): object
    {
        $this->userService->updateUserFilter($request->min, $request->max, $request->sort_price, $request->sort_favourite, $request->sort_sale);
        return $this->productService->filterProduct($request->page, $request->slug, $request->min, $request->max, $request->material ?? [], $request->sort_price, $request->sort_favourite, $request->sort_sale);
    }



    /**
     * handle add to card
     *
     * @param Request $request
     * @return object
     */
    public function addCart(Request $request): object
    {

        user_log::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'actions' => 'addcart'
        ]);
        return $this->productService->addCart($request->product_id, $request->option_id ?? null, $request->quantity);
    }


    // public function getItemCart(Request $request): object
    // {
    //     return $this->productService->getItemCart($page);
    // }


    /**
     * Controller get Option Product 
     *
     * @param Request $request
     * @return object
     */
    public function getOptionProduct(Request $request): object
    {
        $product = Product::with(['categories', 'previewImages'])->find($request->product_id);

        if ($product->option_type == 0) {
            $product->load('variations.attributes.attribute', 'variations.attributes.attributeValue');
            $variation = $product->variations;


            $uniqueAttributes = $variation->flatMap(function ($variation) {
                return $variation->attributes->map(function ($attribute) use ($variation) {
                    return [
                        'attribute_name' => $attribute->attribute->name,
                        'attribute_value' => $attribute->attributeValue->value,
                        'variation' => $variation,
                    ];
                });
            })->groupBy('attribute_name');
        }

        $html = $this->config->MenuOption('layout-select-option', $product, $uniqueAttributes, $request->option_id);

        // dd($html);
        return response()->json([
            'html' => $html,
        ]);
    }

    /**
     * add favourite product in db
     *
     * @param Request $request
     * @return object
     */
    public function addFavouriteProduct(Request $request): object
    {

        user_log::create([
            'user_id' => auth()->id(),
            'product_id' => $request->id,
            'actions' => 'like',
        ]);

        
        $id = $request->id;
        $userId = auth()->id();

        // Kiểm tra sự tồn tại của sản phẩm trong danh sách yêu thích
        $favourite = Favourite::where('product_id', $id)->where('user_id', $userId)->first();

        if ($favourite) {
            // Nếu sản phẩm đã tồn tại trong danh sách yêu thích, xóa nó
            $favourite->delete();
            return response()->json(['success' => true, 'status' => 'like']);
        } else {
            // Nếu sản phẩm chưa tồn tại trong danh sách yêu thích, thêm nó
            Favourite::create([
                'product_id' => $id,
                'user_id' => $userId,
            ]);
            return response()->json(['success' => true, 'message' => 'liked']);
        }
    }



    public function getFavouriteProduct(Request $request)
    {
        $userId = auth()->id();


        if ($request->type == "one") {
            $product_id = $request->id;

            $check = Favourite::where('product_id', $product_id)->where('user_id', $userId)->first();

            if ($check) {
                return response()->json(['success' => true, 'status' => 'like']);
            } else {
                return response()->json(['success' => true, 'status' => 'liked']);
            }
        } elseif ($request->type == "all") {
            $item = Favourite::where('user_id', $userId)->paginate(15, ['*'], 'page', $request->page);
        }
    }

    /**
     *  @param string @order_code
     *  @return
     */
    public function getDetailOrderProduct(string $order_code)
    {

        return view('user.DetailOrder');
    }


    /**
     * Controller handle event delete Image
     *
     * @param Request $request
     * @return object
     */
    public function deleteImage(Request $request): object
    {
        $check = $this->googleDriveService->deleteFile($request->file_id);
        if($check){
            PreviewImage::where('file_id', $request->file_id)->where('product_id', $request->id)->delete();
        }

        return response()->json(['success'=> true]);

    }

    public function addImagePreview(Request $request){

        if ($request->hasFile('images')) {
            $request->validate([
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            foreach ($request->file('images') as $file) {
                // Save the file temporarily
                $path = $file->store('temp');

                // Dispatch the job with the file path
                UploadImageJobs::dispatch($path, $request->product_id);
            }

            return response()->json(['message' => 'Upload in progress']);
        }
    }

    
    /**
     * Undocumented function
     *
     * @param Request $request
     * @return boolean
     */
    public function updateOption(Request $request): bool
    {
        $data = $request->updatedVariations;
        $productId = $request->product_id;
        $userIds = $this->favourite->getAllUserFavouriteProduct($productId);
    
        if (count($userIds) > 0) {
            $poster = Product::find($productId)->select('poster')->first();
            $this->notificationService->addNotificaion(
                "Sản phẩm mà bạn quan tâm đã có cập nhật mới. Hãy xem thử!",
                "user",
                $userIds,
                null,
                true,
                $productId,
                $poster['poster']
            );
        }
    
        $variationIds = array_column($data, 'id');
        $variations = ProductVariation::whereIn('id', $variationIds)->get()->keyBy('id');
        $relationIds = ProductVariationAttribute::whereIn('product_variation_id', $variationIds)
                        ->with('attributeValue')
                        ->get()
                        ->keyBy('product_variation_id');
    
        DB::beginTransaction(); // Start transaction
        try {
            $batchUpdates = [];
            $attributeValueUpdates = [];
    
            foreach ($data as $item) {
                $id = $item['id'];
                $changes = $item['changes'];
    
                if (isset($variations[$id])) {
                    $variation = $variations[$id];
                    $updateFields = [];
    
                    foreach ($changes as $key => $value) {
                        if ($key == 'poster') {
                            UploadPosterJob::dispatch($variation, $value);
                        } else if ($key == 'attribute_id' && isset($relationIds[$id])) {
                            $relation = $relationIds[$id];
                            $updateFields['attribute_id'] = $value;
    
                            if (isset($relation->attributeValue)) {
                                $relation->attributeValue->attribute_id = $value;
                                $attributeValueUpdates[] = $relation->attributeValue;
                            }
                        } else if ($key == 'attribute_value' && isset($relationIds[$id])) {
                            $relation = $relationIds[$id];
                            if (isset($relation->attributeValue)) {
                                $relation->attributeValue->value = $value;
                                $attributeValueUpdates[] = $relation->attributeValue;
                            }
                        } else if (property_exists($variation, $key) && $key != 'poster' && $key != 'attribute_id' && $key != 'attribute_name') {
                            $updateFields[$key] = $value;
                        }
                    }
    
                    if (!empty($updateFields)) {
                        $variation->update($updateFields);
                    }
                }
            }
    
            // Bulk update for attribute values
            foreach ($attributeValueUpdates as $attributeValue) {
                $attributeValue->save();
            }
    
            DB::commit(); // Commit transaction
    
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            // Optionally log the error or rethrow it
            return false;
        }
    
        return true;
    }
    


    /**
     * update quantity product on db
     *
     * @param Request $request
     * @return boolean
     */
    public function updateQuantity(Request $request):bool
    {
        $data = $request->changes;
        $productId = $request->productId;

        $product = Product::find($productId);

        if(!$product){
            return false;
        }

        if ($product->option_type == 0) {
            $product->load('variations');

            $changesByOptionId = collect($data)->keyBy('optionId');
            
            foreach ($product->variations as $variation) {
                $optionId = $variation->id;
                if (isset($changesByOptionId[$optionId])) {
                    
                    $variation->quantity = (int)$changesByOptionId[$optionId]['quantity'];
                    $variation->save();
                }
            }
        } else {
            if (!empty($data)) {
                $product->quantity = $data[0]['quantity'];
                $product->save();
            }
        }
        return true;    
    }

}
