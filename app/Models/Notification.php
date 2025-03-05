<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'product_id',
        'category_id',
        'content',
        'type',
        'type_notification',
        'image',
        'file_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'first_name', 'last_name');
    }

    public function checkSee(){
        return $this->hasMany(ViewNotification::class);
    }

}
