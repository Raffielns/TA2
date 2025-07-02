<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'receipt_number',
        'file_path',
        'issued_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
