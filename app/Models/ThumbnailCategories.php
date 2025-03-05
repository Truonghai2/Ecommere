<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThumbnailCategories extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'thumbnail',
        'file_id',
        'title',
        'description',
        'type'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
