<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

protected $fillable = ['user_id', 'total_price', 'status', 'table_number', 'name', 
'phone', 'address', 'payment_method','notes', 'menu_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function items()
{
    return $this->hasMany(\App\Models\OrderItem::class);
}
}
