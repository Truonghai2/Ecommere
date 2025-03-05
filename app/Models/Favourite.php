<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)->select('id', 'title', 'price', 'sale', 'poster', 'option_type', 'quantity');
    }


    public function user(){
        return $this->belongsTo(User::class);
    }
}
