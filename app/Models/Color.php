<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $hidden = ['pivot'];
    
    public function products(){
        return $this->belongsToMany(Product::class);
    }

    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }

    public function orderItem(){
        return $this->hasMany(OrderItem::class);
    }
}
