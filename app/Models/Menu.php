<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'image', 'description', 'stock', 'category'];
    
    protected $hidden = ['created_at', 'updated_at'];
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
