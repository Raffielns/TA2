<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected static function booted()
    {
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }
}
