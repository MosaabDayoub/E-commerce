<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
     protected $fillable = [
        'name',
        'description',
        "price",
        "category_id"
    ];

    public function category(){
        return $this->belongsTo(Product::class);
    }

    public function colors(){
        return $this->belongsToMany(Color::class,'color_product');
    }

     public function sizes(){
        return $this->belongsToMany(Size::class);
    }

    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }

     public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
    
}
