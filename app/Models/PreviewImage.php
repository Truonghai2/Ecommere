<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreviewImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'rating_id',
        'image',
        'file_id',
        'width',
        'height',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }
}
