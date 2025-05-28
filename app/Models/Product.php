<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'image_path',
        'color',
        'size'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
