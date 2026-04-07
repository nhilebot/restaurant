<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

protected $fillable = [
    'user_id', 
    'table_number', 
    'total_price', 
    'status',
    'name',        // Tâm thêm vào
    'phone',       // Tâm thêm vào
    'address'      // Tâm thêm vào
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
