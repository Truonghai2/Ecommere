<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_id',
        'payment_status',
        'to_ward_name',
        'to_district_name',
        'to_province_name',
        'to_user_name',
        'to_phone_number',
        'price_old',
        'price_save',
        'price_ship',
        'price_new',
        'status_order',
        'content',
        'body',
        'order_code',
        'status_ship'
    ];


    // Define the date fields
    protected $dates = [
        'created_at',
        'updated_at',
    ];

     public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function list_item_orders(){
        return $this->hasMany(ListItemOrder::class);
    }
    
}
