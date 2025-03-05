<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = [
        'thumbnail',
        'file_id',
        'name',
        'slug'
    ];


    public function relation(){

        return $this->HasMany(RelationProductCategory::class);
    }


    public function products()
    {
        return $this->belongsToMany(Product::class, 'relation_product_category', 'category_id', 'product_id');
    }

    public function takeProduct()
    {
        return $this->belongsToMany(Product::class, 'relation_product_category', 'category_id', 'product_id')
                    ->select('products.id', 'products.title', 'products.poster', 'products.quantity_saled', 'products.price', 'products.sale', 'products.option_type');
    }

    public function Thumbnail(){
        return $this->hasMany(ThumbnailCategories::class);
    }

    
    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}
