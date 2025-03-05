<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationProductCategory extends Model
{
    use HasFactory;

    protected $table = 'relation_product_category';

    protected $fillable = [
        'category_id',
        'product_id',
    ];



    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
