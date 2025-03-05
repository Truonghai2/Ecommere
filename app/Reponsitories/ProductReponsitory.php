<?php 
namespace App\Reponsitories;

use App\Models\Product;
use App\Models\RelationProductCategory;

class ProductReponsitory{


    protected $product;
    protected $perPage = 16;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }


    public function getPerpage($page){
        return Product::paginate($this->perPage, ['*'], 'page', $page);
    }


    public function getNewProduct(){
        return Product::orderBy('created_at', 'desc')->select('id', 'title', 'sale', 'price', 'quantity_saled', 'poster', 'option_type')->take(8)->get();
    }

    public function getCategoryProduct(){

    }


    public function getPerpageSelect($page){
        return Product::select('id', 'poster', 'title')->paginate($this->perPage, ['*'], 'page', $page);
    }

    public function cntProduct(){
        
    }

    public function searchNameProduct($data){
        return Product::where('title', 'like', "%{$data}%")->get();
    }

    public function searchNameProductPerpage($data, $page){
        return Product::where('title', 'like', "%{$data}%")->select('id', 'poster', 'title','sale','price')->paginate($this->perPage, ['*'], 'page', $page);
    }


    /**
     * query get item product relation 
     *
     * @param int $data
     * @param int $page
     * @return void
     */
    public function getProductCategories($data, $page){
        return RelationProductCategory::where('category_id', $data)->with('product:id,title,poster,price,sale,quantity_saled,total_rate,option_type')->paginate($this->perPage, ['*'], 'page', $page);
    }

    public function allProductCategories($data){
        return RelationProductCategory::where('category_id', $data)->with('product:id,title,poster,price,sale,quantity_saled,total_rate,option_type')->get();
    }

    public function filterCategory($data){
        return RelationProductCategory::where('category_id', $data)->with('product')->get();
    }
}