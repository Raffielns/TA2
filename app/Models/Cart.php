<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }
}
