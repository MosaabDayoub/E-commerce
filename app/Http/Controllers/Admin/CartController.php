<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;

class CartController extends Controller
{
    /**
     * get carts.
     */
    public function index()
    {
        $carts = Cart::with([
        'cartItems.product.colors',
        'cartItems.product.sizes',
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'data retrived successfuly',
            'data' => $carts
        ],200); 
    }

    /**
     * get user cart.
     */
    public function show(Cart $cart)
    {
        $cart = Cart::with([
        'cartItems.product.colors',
        'cartItems.product.sizes',
        ]);
        where('user_id',$user_id)->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'data retrived successfuly',
            'data' => $cart
        ],200); 
    }

}
