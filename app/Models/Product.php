<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'title',
        'poster',
        'file_id',
        'width',
        'height',
        'length',
        'name_product',
        'description',
        'price',
        'sale',
        'quantity',
        'brand',
        'option_type',
        'material',
        'weight',
        'guarantee',
        'country',
        'quantity_saled',
        'total_rate'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'relation_product_category', 'product_id', 'category_id');
    }

    public function previewImages()
    {
        return $this->hasMany(PreviewImage::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }


    public function scopeLimited($query, $limit = 8)
    {
        return $query->take($limit);
    }


    public function relatedProducts()
    {
        
        $nameParts = explode(' ', $this->title);
        // Query for related products
        $relatedProducts = Product::search($this->title)
                                ->get();
        return $relatedProducts;
    }


    public static function removeAllFromSearch()
    {
        self::disableSearchSyncing();
        self::query()->orderBy('id')->unsearchable();
        self::enableSearchSyncing();
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }


    public function relation(){
        return $this->hasMany(ProductVariationAttribute::class);
    }    

}
