<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
    'user_id',
    'reservation_date',
    'reservation_time',
    'table_id',
    'full_name',
    'phone',
    'notes',
    'cart_data',
    'status'
];

    protected $casts = [
        'cart_data' => 'array', // Cực kỳ quan trọng để lưu được mảng món ăn
    ];
}