<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'address';
    protected $fillable = [
        'user_id',
        'home_number',
        'provinces_id',
        'provinces_name',
        'district_id',
        'district_name',
        'ward_id',
        'ward_name',
        'active',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
