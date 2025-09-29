<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $hidden = ['pivot'];
    
    public function products(){
        return $this->belongsToMany(Product::Class,'product_id');
    }

    public function cartItems(){
        return $this->hasMany(CartItem::class,'color_id');
    }

    public function orderItem(){
        return $this->hasMany(OrderItem::class,'color_id');
    }
}
