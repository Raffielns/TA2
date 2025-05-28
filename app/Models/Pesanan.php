<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'stock',
        'unit',
        'price',
        'supplier',
        'minimum_stock',
        'image'
    ];

    // Accessor untuk format harga
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // Scope untuk bahan dengan stok rendah
    public function scopeLowStock($query)
    {
        // return $query->where('stock', '<=', \DB::raw('minimum_stock'));
    }
}
