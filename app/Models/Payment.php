<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'bank_name',
        'amount',
        'proof_image',
        'status'
    ];

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
