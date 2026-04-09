<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'food_id', 'quantity'];

    // Thiết lập mối quan hệ để lấy thông tin món ăn từ giỏ hàng
    public function food()
    {
        return $this->belongsTo(Menu::class, 'food_id');
    }
    // Thêm hàm này để định nghĩa mối quan hệ
    public function menu()
    {
        // belongsTo nghĩa là 1 dòng trong giỏ hàng thuộc về 1 món ăn trong Menu
        // 'food_id' là tên cột khóa ngoại bạn đã đặt trong bảng carts
        return $this->belongsTo(Menu::class, 'food_id');
    }
}