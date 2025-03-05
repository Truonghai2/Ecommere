<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'poster',
        'file_id',
        'price',
        'sale',
        'quantity',
        'width',
        'height',
        'length',
        'weight',
        'material',
    ];


    public $timestamps = false;
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function attributes()
    {
        return $this->hasMany(ProductVariationAttribute::class);
    }

    public function attributeValue(){
        return $this->hasMany(ProductVariationAttribute::class);
    }
}

