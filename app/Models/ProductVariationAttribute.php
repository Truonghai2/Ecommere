<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariationAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['product_variation_id', 'attribute_id', 'attribute_value_id'];

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function attributeValue(){
        return $this->belongsTo(AttributeValue::class);
    }

}
