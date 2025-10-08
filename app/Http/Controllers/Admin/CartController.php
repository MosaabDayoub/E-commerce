<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Helpers\ResponseHelper;
use App\Http\Resources\CartResource;



class CartController extends Controller
{
    // get carts.
    public function index()
    {
        $carts = Cart::with([
        'cartItems.product.colors',
        'cartItems.product.sizes',
        ])->paginate(10);
        
        return ResponseHelper::success(CartResource::collection($carts)); 
    }

    // get user cart.
    public function show($userId)
    {
        $cart = Cart::where('user_id',$userId)->first();
        return ResponseHelper::success(new CartResource($cart)); 
    }

}
