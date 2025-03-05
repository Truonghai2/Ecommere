<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class user_log extends Model
{
    use HasFactory;
    protected $table = 'logs';

    protected $fillable = [

        'user_id',
        'product_id',
        'actions',

    ];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);

    }

    public function category()
    {
        return $this->belongsTo(RelationProductCategory::class, 'product_id')->with("category");
    }

}
