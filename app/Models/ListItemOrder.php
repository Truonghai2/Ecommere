<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListItemOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'option_id',
        'name',
        'price',
        'sale',
        'quantity',
        'name_option',
        'poster'
    ];

    protected $dates =[
        'created_at',
        'updated_at',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function option(){
        return $this->belongsTo(ProductVariation::class);
    }
}
