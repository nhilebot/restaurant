<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id', 'reservation_date', 'reservation_time', 
        'table_id', 'full_name', 'phone', 'notes', 'status', 'cart_data'
    ];

    protected $casts = [
        'cart_data' => 'array', // Cực kỳ quan trọng để lưu được mảng món ăn
    ];
}