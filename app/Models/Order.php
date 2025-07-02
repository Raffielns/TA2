<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'payment_method',
        'shipping_address',
        'payment_proof',
        'bank_name',
        'notes',
        'telephone',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function receipt()
    {
        return $this->hasOne(Receipt::class);
    }
}
