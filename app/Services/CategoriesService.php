<?php 
namespace App\Services;

use App\Client\config;
use App\Reponsitories\CategoriesReponsitory;
use Carbon\Carbon;

use function PHPUnit\Framework\isEmpty;

class CategoriesService{
    protected $category;
    protected $config; 

    /**
     * Dependency injection register service
     *
     * @param CategoriesReponsitory $categoriesReponsitory
     * @param config $config
     */
    public function __construct(CategoriesReponsitory $categoriesReponsitory, config $config)
    {
        $this->category = $categoriesReponsitory;

        $this->config = $config;
    }

    /**
     * get value tabel category
     *
     * @return void
     */
    public function getCategory()
    {
        $categories = $this->category->get();
        $sortedCategories = $categories->sortByDesc(function ($category) {
            return $category->created_at;
        });
    
        return $sortedCategories->values()->all();
    }


    /**
     * create a category
     *
     * @param string $image
     * @param string $file_id
     * @param string $name
     * @return object
     */
    public function addCategories($image, $file_id, $name){
        $category = $this->category->create($image, $file_id,$name);


        $diff = $this->config->handleTime($category->created_at);

        $html = $this->config->getCategory('category', $category, $diff);

        return response()->json(['success' => true, 'html' => $html]);
    }


    /**
     * create a thumbnail category
     *
     * @param string $thumbnail
     * @param string $file_id
     * @param object $request
     * @return object
     */
    public function CreateThumbnailCategories($thumbnail, $file_id,$request){
        $this->category->ThumbnailCategories($request['category_id'], $thumbnail, $file_id, $request['title'], $request['description'], $request['type']);

        return response()->json(['success' => true]);
    }

}