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
    public function comments()
{
    return $this->hasMany(Comment::class)->where('status', 1)->orderBy('created_at', 'desc');
}

public function getAverageRatingAttribute()
{
    return $this->comments()->avg('rating') ?: 5;
}

public function getCommentsCountAttribute()
{
    return $this->comments()->count();
}
}
