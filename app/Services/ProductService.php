<?php
namespace App\Services;

use App\Builders\FillterProductBuilder;
use App\Client\config;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\user_log;
use App\Reponsitories\CartReponsitory;
use App\Reponsitories\ProductReponsitory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class ProductService{


    protected $productReponsitory;
    protected $config;
    protected $cart;

    /**
     * dependency injection
     *
     * @param ProductReponsitory $productReponsitory
     * @param config $config
     * @param CartReponsitory $cartReponsitory
     */
    public function __construct(ProductReponsitory $productReponsitory, config $config, CartReponsitory $cartReponsitory)
    {
        $this->productReponsitory = $productReponsitory;
        $this->config = $config;
        $this->cart = $cartReponsitory;
    }



    public function getProduct($page){

    }

    /**
     * function get item new product
     *
     * @return void
     */
    public function NewProduct()
    {
        $products = $this->productReponsitory->getNewProduct();

        return $this->HandleOptionProduct($products);
    }

    

    /**
     * function get thumbnail and takeProduct use relation categories
     *
     * @return void
     */
    public function getProductCategories()
    {
        try {
            $categories = Category::where('hidden', 0)->with(['Thumbnail', 'takeProduct'])->get();

            foreach ($categories as $category) {
                if (!$category->takeProduct->isEmpty()) {
                    $products = $category->takeProduct;

                    $products = $this->HandleOptionProduct($products);
                    $category->setRelation('takeProduct', $products);
                }
            }

            return $categories;

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * function search product, paginate and get html
     *
     * @param string $data
     * @param int $page
     * @return void
     */
    public function PerPageSearch($data, $page){
        $products = $this->productReponsitory->searchNameProductPerpage($data, $page);

        $html = array_map(function($item) {
            // Giả sử bạn có một phương thức config để cấu hình dữ liệu sản phẩm
            return $this->config->getProduct('product', $item);
        }, $products->items());

        return response()->json([
            'html' => $html,
            'total' => $products->total(),
            'last_page' => $products->lastPage(),
        ]);
    }


    /**
     * handle logic adđ to card
     *
     * @param int $product_id
     * @param int $quantity
     * @return object
     */
    public function addCart(int $product_id, int $option_id = null, int $quantity): object
    {
        $cart = $this->cart->selectCart($product_id, $option_id);

        if($cart){
            $this->cart->updateCart($product_id, $option_id, $quantity);
        }
        else{
            $this->cart->addCart($product_id, $quantity, $option_id);
        }
        return response()->json(['success' => true]);
    }

    /**
     * function handle sort score and paginate
     *
     * @param int $page
     * @param int $limit
     * @return void
     */
    public function getHotProduct($page, $limit)
    {
        // Lấy danh sách sản phẩm từ database và tính toán điểm số
        $products = Product::orderByDesc('created_at')
            ->select('id', 'title', 'price', 'poster', 'sale', 'created_at', 'quantity_saled', 'total_rate', 'option_type')
            ->get();

        // Tính toán điểm số cho từng sản phẩm
        $products->each(function ($product) {
            if($product->option_type == 0){
                $product->load('variations');
            }
            $product->score = $this->calculateProductScore($product);
        });

        // Sử dụng paginate để chia danh sách sản phẩm thành các trang
        $perPage = $limit; // Số sản phẩm trên mỗi trang
        $currentPage = $page; // Trang hiện tại, mặc định là trang 1

        // Sử dụng Laravel's Paginator để phân trang dữ liệu
        $pagedData = $products->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedData,
            $products->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        $html = $paginator->map(function ($item) {
            return $this->config->getProduct('item-product', $item);
        });

        return response()->json([
            'items' => $html,
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
        ]);
    }


    /**
     * function handle score product
     *
     * @param object $product
     * @return float
     */
    protected function calculateProductScore($product) :float
    {
        $score = 100;

        // Tương tác của người dùng
        $score += $product->favourite * 2;
        $score += $product->total_rate * 3;

        // Thời gian đăng
        $timeDiff = now()->diffInMinutes($product->created_at);
        $score += max(0, 100 - $timeDiff);

        // Cộng điểm dựa trên rate (giả sử rate tối đa là 5)
        $score += ($product->total_rate - 3) * 10; // Mỗi điểm rate trên 3 cộng thêm 10 điểm, dưới 3 trừ đi 10 điểm

        // Cộng điểm dựa trên số lượng bán
        if ($product->quantity_saled > 100) {
            $score += 20; // Bán trên 100
        } elseif ($product->quantity_saled > 50) {
            $score += 10; // Bán trên 50
        } else {
            $score -= 10; // Bán dưới 50
        }

        return $score;
    }

    /**
     * function handle recommendation
     *
     * @param int $id
     * @param integer $page
     * @return object
     */
    public function handleRecommendation(int $id, int $page): object
    {
        // Fetch the product with its categories
        $product = Product::select('id', 'title', 'poster', 'price', 'sale', 'quantity_saled', 'option_type')
                    ->with('categories')
                    ->find($id);

        // Check if the product exists
        if (!$product) {
            abort(404);
        }

        // Extract search terms from the product title
        $searchTerms = explode(' ', $product->title);
        $termCount = count($searchTerms);

        // Get category IDs associated with the product
        $categoryIds = $product->categories->pluck('id')->toArray();

        // Fetch related products based on the categories
        $relatedProductsQuery = $this->productReponsitory->getProductCategories($categoryIds, $page);
        
        $last_page = $relatedProductsQuery->lastPage();
        $total = $relatedProductsQuery->total();

        // Calculate TF-IDF and product scores
        $relatedProducts = $relatedProductsQuery->map(function ($relatedProduct) use ($searchTerms, $termCount) {
            $relatedProduct->product->tfidfScore = $this->calculateTFIDF($relatedProduct->product->title, $searchTerms, $termCount);
            return $relatedProduct;
        });

        // Sort the related products by TF-IDF score
        $relatedProducts = $relatedProducts->sortByDesc(function ($relatedProduct) {
            return $relatedProduct->product->tfidfScore;
        })->values();

        // Generate HTML for each related product
        $html = $relatedProducts->pluck('product')->map(function ($item) {
            if ($item->option_type == 0) {
                $item->load('variations');
            }
            return $this->config->getProduct('item-product', $item);
        })->implode('');

        // Return JSON response with the HTML, last page, and total count
        return response()->json([
            'product' => $html,
            'last_page' => $last_page,
            'total' => $total,
        ]);
    }


    /**
     * handle get many item recommendation
     *
     * @param array $ids
     * @param integer $page
     * @return object
     */
    public function handleRecommendationMany(array $ids, int $page = 1): object
    {

        $ids = array_unique($ids);
        // Fetch the products with their categories
        $products = Product::select('id', 'title', 'poster', 'price', 'sale', 'quantity_saled', 'option_type')
                    ->with('categories')
                    ->whereIn('id', $ids)
                    ->get();

        // Check if products exist
        if ($products->isEmpty()) {
            abort(404);
        }

        // Extract search terms from the product titles
        $searchTerms = $products->pluck('title')->flatMap(function ($title) {
            return explode(' ', $title);
        })->unique()->toArray();
        $termCount = count($searchTerms);

        // Get unique category IDs associated with the products
        $categoryIds = $products->pluck('categories')->flatten()->pluck('id')->unique()->toArray();

        // Fetch related products based on the categories with pagination
        $relatedProductsQuery = Product::select('id', 'title', 'poster', 'price', 'sale', 'quantity_saled', 'option_type')
                            ->with('categories')
                            ->whereHas('categories', function ($query) use ($categoryIds) {
                                $query->whereIn('categories.id', $categoryIds); // Specify the table for 'id'
                            });

        // Apply pagination
        $relatedProducts = $relatedProductsQuery->paginate(16, ['*'], 'page', $page);

        // Calculate TF-IDF and product scores
        $relatedProducts->getCollection()->transform(function ($relatedProduct) use ($searchTerms, $termCount) {
            $relatedProduct->tfidfScore = $this->calculateTFIDF($relatedProduct->title, $searchTerms, $termCount);
            return $relatedProduct;
        });

        // Sort the related products by TF-IDF score
        $sortedRelatedProducts = $relatedProducts->getCollection()->sortByDesc('tfidfScore')->values();
        
        $relatedProducts->setCollection($sortedRelatedProducts);

        // Generate HTML for each related product
        $html = $relatedProducts->map(function ($item) {
            
            if ($item->option_type == 0) {
                $item->load('variations');
            }
            
            return $this->config->getProduct('item-product', $item);
        })->implode('');

        // Return JSON response with the HTML, last page, and total count
        return response()->json([
            'product' => $html,
            'last_page' => $relatedProducts->lastPage(),
            'total' => $relatedProducts->total(),
        ]);
    }






    /**
     * Calculate TF-IDF score for a product title.
     *
     * @param string $title
     * @param array $searchTerms
     * @param int $termCount
     * @return float
     */
    private function calculateTFIDF(string $title, array $searchTerms, $termCount) :float
    {
        $titleWords = explode(' ', $title);

        $termFrequency = [];
        foreach ($searchTerms as $term) {
            $termFrequency[$term] = 0;
        }

        foreach ($titleWords as $word) {
            if (isset($termFrequency[$word])) {
                $termFrequency[$word]++;
            }
        }

        $inverseDocumentFrequency = [];
        foreach ($searchTerms as $term) {
            $count = Product::where('title', 'like', '%' . $term . '%')->count();
            $inverseDocumentFrequency[$term] = $count > 0 ? log(count($titleWords) / $count) : 0;
        }

        $tfidfScore = 0;
        foreach ($searchTerms as $term) {
            $tfidfScore += ($termFrequency[$term] / count($titleWords)) * $inverseDocumentFrequency[$term];
        }

        return $tfidfScore;
    }

    /**
     * function handle filter product by builder patern
     *
     * @param integer $page
     * @param integer $slug
     * @param integer $min
     * @param integer $max
     * @param array $material
     * @param integer $sort_price
     * @param integer $sort_favourite
     * @param integer $sort_sale
     * @return object
     */
    public function filterProduct(int $page, string $slug, int $min, int $max, array $metarial, int $sort_price, int $sort_favourite, int $sort_sale): object
    {
        if ($slug == 'all-product') {
            $products = Product::select('id','title','poster','price','sale','quantity_saled','total_rate', 'option_type')->get();
        } else {
            $category = Category::where('slug', $slug)->first();
            $relation = $this->productReponsitory->allProductCategories($category->id);
            $products = $relation->pluck('product');
        }

        // dd($products);
        $builder = new FillterProductBuilder($products);

        $filteredProducts = $builder
            ->filterPrice($min, $max)
            ->sortPrice($sort_price)
            ->filterMetarial($metarial)
            ->filterSortFavourite($sort_favourite)
            ->filterSortSale($sort_sale)
            ->build();

        if ($filteredProducts != null) {
            $totalProducts = $filteredProducts->count();
            $productsPerPage = 20;
            $lastPage = ceil($totalProducts / $productsPerPage);

            // Slice the collection to get the products for the current page
            $paginatedProducts = $filteredProducts->slice(($page - 1) * $productsPerPage, $productsPerPage);

            $html = $paginatedProducts->map(function ($item) {
                return $this->config->getProduct("item-product", $item);
            })->implode('');

            return response()->json([
                'product' => $html,
                'total' => $totalProducts,
                'last_page' => $lastPage,
            ]);
        }

        return response()->json([
            'product' => null,
            'total' => 0,
            'last_page' => 1,
        ]);
    }



    /**
     * pagination item cart
     *
     * @param integer $page
     * @return object
     */
    public function getItemCart($page): object
    {
        // Fetch the paginated cart with related option attributes, attribute values, and product titles
        $cart = Cart::with(['option.attributes.attributeValue', 'option.product'])->orderByDesc('created_at')->paginate(16, ['*'], 'page', $page);

        // Flatten and group the unique attributes including the product title and quantity
        $uniqueAttributes = $cart->flatMap(function ($cartItem) {
            $variation = $cartItem->option;

            // Check if attributes and product are loaded correctly
            if (!isset($variation->attributes) || !isset($variation->product)) {
                return collect();
            }

            return $variation->attributes->map(function ($attribute) use ($variation, $cartItem) {
                // Check if attribute and attributeValue are loaded correctly
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

        $html = array_map(function($item){
            return $this->config->itemCart('itemcart', $item);
        }, $uniqueAttributes);
        return response()->json(['']);
    }

    /**
     * handle update card
     *
     * @param integer $id
     * @param integer $quantity
     * @return boolean
     */
    public function updateCart(int $id, int $quantity):bool
    {
        $cart = Cart::find($id);

        if($quantity >= 1){
            $cart->quantity = $quantity;
            $cart->save();
        }
        else{
            $cart->delete();
        }
        return true;
    }

    /**
     * Get a cart with product or option
     *
     * @param integer $id
     * @return array
     */
    public function getProductCart(int $id): array
    {
        $cart = Cart::find($id);

        if (!$cart) {
            abort(404);
        }

        if ($cart->option_id === null) {
            $cart = $cart->load('product');
            $product = $cart->product->toArray();
            $product['cart_quantity'] = $cart->quantity;
            $weight = $cart->product->weight * $cart->quantity;
            $total_price = ceil((int)($cart->product->price - ($cart->product->price * $cart->product->sale / 100))) * $cart->quantity;
        } else {
            $cart = $cart->load('option.attributes.attributeValue');
            $product = $this->handleItemCart($cart);
            $weight = $cart->option->weight * $cart->quantity;
            $total_price = ceil((int)($cart->option->price - ($cart->option->price * $cart->option->sale / 100))) * $cart->quantity;
        }


        return [
            'product' => $product,
            'type' => $cart->option_id == null ? false : true,
            'quantity' => $cart->quantity,
            'total_price' => $total_price,
            'weight' => $weight,
        ];
    }

    /**
     * Process the cart and extract unique attributes
     *
     * @param Cart $cart
     * @return array
     */
    protected function handleItemCart(Cart $cart): array
    {
        $uniqueAttributes = $cart->option->attributes->map(function ($attribute) use ($cart) {
            // Check if attribute and attributeValue are loaded correctly
            if (!isset($attribute->attributeValue)) {
                return null;
            }

            return [
                'cart_id' => $cart->id,
                'quantity' => $cart->quantity,
                'attribute_name' => $attribute->name,
                'attribute_value' => $attribute->attributeValue->value,
                'product_title' => $cart->option->product->title,
                'variation' => $cart->option,
            ];
        })->filter()->values()->all();

        return $uniqueAttributes;
    }

    public function updateQuantity(int $productId, $data): void
    {

    }


    /**
     * Handle products based on their option type
     *
     * @param Collection $products
     * @return Collection
     */
    public function HandleOptionProduct(Collection $products)
    {
        foreach ($products as $product) {
            if ($product->option_type == 0) {
                // Load 'variations' với các cột 'price', 'sale', 'product_id', 'id'
                $product->load(['variations' => function ($query) {
                    $query->select('price', 'sale', 'product_id', 'id');
                }]);

                // Tìm variation có giá trị min price
                $minPriceVariation = $product->variations->sortBy('price')->first();

                // Gán lại biến variations với dữ liệu đã xử lý
                $product->setRelation('variations', collect([$minPriceVariation]));
            }
        }

        return $products;
    }


    public function Recommendation_personalization(?int $userId): ?Collection
    {

        $user = auth()->user();

            // Truy vấn dữ liệu từ database nếu không có file JSON
        $product_logs = user_log::where('user_id', $user->id)
                ->with('category', 'user', 'product.variations')
                ->get();
        

        // Định nghĩa nhóm tuổi
        $age_groups = [
            '3-17' => [3, 17],
            '18-35' => [18, 35],
            '36-55' => [36, 55],
            '55+' => [56, PHP_INT_MAX]
        ];

        // Xác định nhóm tuổi của người dùng
        $user_age_group = '';
        foreach ($age_groups as $group => $range) {
            if ($user->age >= $range[0] && $user->age <= $range[1]) {
                $user_age_group = $group;
                break;
            }
        }


        if ($product_logs->isEmpty()) {
            $product_logs = user_log::with(['category', 'user', 'product.variations'])->get();
            $product_logs = $product_logs->filter(function ($log) use ($user_age_group, $age_groups) {
                $user_age = $log->user->age;
                $range = $age_groups[$user_age_group] ?? null;
                return $range && $user_age >= $range[0] && $user_age <= $range[1];
            });

            if($product_logs->isEmpty()){
                
            }
            else{
                $product_logs = $product_logs->values();
            }
        }
        
        // Xử lý dữ liệu
        $product_data = $product_logs->map(function ($log) use ($age_groups) {
            $age_group = '';
            
            foreach ($age_groups as $group => $range) {
                if ($log->user->age >= $range[0] && $log->user->age <= $range[1]) {
                    $age_group = $group;
                    break;
                }
            }
            

            return [
                'user_id' => $log['user_id'],
                'product_id' => $log['product_id'],
                'product_name' => $log['product_name'],
                'price' => (int)$log['price'],
                'sale' => (int)$log['sale'],
                'action' => $log['actions'],
                'category' => $log['category'],
                'age' => $age_group
            ];
        })->toArray();

        $filePathUserLog = public_path('dataAI/user_log_' . $user->id . '.json');

        try {
            File::put($filePathUserLog, json_encode($product_data, JSON_PRETTY_PRINT));
            Artisan::call('recommendation:run', ['user_id' => $user->id]);

            $outputFilePath = public_path('dataAI/output_' . $user->id . '.json');

            if (File::exists($outputFilePath)) {
                $outputData = json_decode(File::get($outputFilePath), true);
                $products = Product::select('id', 'title', 'poster', 'price', 'sale', 'option_type')
                    ->whereIn('id', $outputData)
                    ->get();

                Cache::put("recommendation_{$user->id}", $products, 2);
                return $products;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to write user log: ' . $e->getMessage()], 500);
        }
    }



}
