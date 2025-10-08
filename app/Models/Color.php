<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


class Color extends Model implements TranslatableContract
{
    use Translatable;

    protected $hidden = ['pivot'];
    public $translatedAttributes = ['name'];
    protected $fillable = ['price','category_id'];
    
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
