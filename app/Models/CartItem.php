<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id','product_id','color_id','size_id','quantity'];
    
    public function cart(){
        return $this->belongsTo(Cart::class,'cart_id');
    }

     public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function color(){
        return $this->belongsTo(Color::class,'color_id');
    }

    public function size(){
        return $this->belongsTo(Size::class,'size_id');
    }
}
