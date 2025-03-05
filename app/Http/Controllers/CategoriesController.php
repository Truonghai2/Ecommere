<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Thumbnail;
use App\Models\ThumbnailCategories;
use App\Services\CategoriesService;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use App\Models\RelationProductCategory;
use App\Observers\CategoryObserver;

class CategoriesController extends Controller{

    protected $category;
    protected $google;

    //  denpendency Injection
    public function __construct(CategoriesService $categoriesService, GoogleDriveService $googleDriveService)
    {
        $this->middleware('auth');
        $this->category = $categoriesService;

        $this->google = $googleDriveService;
    }


    /**
     * get view content admin category
     *
     * @param Request $request
     * @return void
     */
    public function getCategory(Request $request){

        return view('admin.pages.Categories',[
            'categories' => $this->category->getCategory(),
        ]);
    }


    /**
     * get view content Thumbnail category
     *
     * @param Request $request
     * @return void
     */
    public function thumbailCategory(Request $request){
        $thumbnails = ThumbnailCategories::with('category')->get();

        return view('admin.pages.ThumbnailCategories',[
            'categories' => $thumbnails,
            'category' => $this->category->getCategory(),
        ]);
    }

    /**
     * create a database table
     *
     * @param Request $request
     * @return void
     */
    public function createContentThumbnailCategory(Request $request){

        $image = $this->google->uploadBase64EncodedImage($request->image);

        return $this->category->CreateThumbnailCategories($image['url'], $image['id'], $request->only('category_id', 'title', 'description','type'));
    }

    /**
     * get value category where slug
     *
     * @param string $slug
     * @return void
     */
    public function getCategoryUser($slug)
    {
        if ($slug === 'all-product') {
            $category = (object)[
                'name' => 'Tất cả sản phẩm',
                'slug' => 'all-product',
            ];
        } else {
            $category = Category::where('slug', $slug)->first();
            if (!$category) {
                // Xử lý trường hợp không tìm thấy danh mục với slug đã cho
                abort(404, 'Category not found');
            }
        }

        return view('Categories', [
            'category' => $category,
        ]);
    }



    /**
     * create a value in database
     *
     * @param Request $request
     * @return void
     */
    public function addcategories(Request $request){
        $image = $request->image;

        $name = $request->name;

        $object = $this->google->uploadBase64EncodedImage($image);

        return $this->category->addCategories($object['url'], $object['id'], $name);

    }


    public function addCategoriesProduct(Request $request): bool
    {
        $categoriesId = $request->listCategoriesId;
        $productId = $request->product_id;

        // Retrieve existing relationships for the product
        $existingRelations = RelationProductCategory::where('product_id', $productId)
            ->pluck('category_id')
            ->toArray();

        // Determine which categories need to be added
        $newCategoriesId = array_diff($categoriesId, $existingRelations);

        // Determine which categories need to be removed
        $categoriesToRemove = array_diff($existingRelations, $categoriesId);

        // Insert new category-product relationships
        if (!empty($newCategoriesId)) {
            $data = array_map(function($id) use($productId) {
                return [
                    'product_id' => $productId,
                    'category_id' => $id,
                    'created_at' => now(),
                    'updated_at' => now(), // add updated_at if your table has this column
                ];
            }, $newCategoriesId);

            RelationProductCategory::insert($data);
        }

        // Delete category-product relationships that are not in the incoming array
        if (!empty($categoriesToRemove)) {
            RelationProductCategory::where('product_id', $productId)
                ->whereIn('category_id', $categoriesToRemove)
                ->delete();
        }

        return true;
    }




    /**
     * remove a value in database
     *
     * @param Request $request
     * @return bool
     */
    public function removeCategory(Request $request): bool
    {
        $id = $request->category_id;
        

        $category = Category::find($id);

        if($category){
            $category->delete();

            return true;
        }

        return false;

    }

}
