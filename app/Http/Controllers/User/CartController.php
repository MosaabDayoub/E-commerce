<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;

class CartController extends Controller
{
 
    /**
     * add new item to the cart
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'product_id' => 'sometimes|exists:products,id',
        'color_id' => 'sometimes|exists:colors,id',
        'size_id' => 'sometimes|exists:sizes,id',
        'quantity' => 'sometimes|integer|min:1|max:100'
        ]);
        
        $cart = Cart::firstOrCreate(['user_id' => $request->user_id]);

        $item = $cart->cartItems()->create([
            'product_id' => $request->product_id,
            'color_id' => $request->color_id,
            'size_id' => $request->size_id,
            'quantity' => $request->quantity
        ]);
        return response()->json([
            'success' => true,
            'message' => 'item is added successfuly',
            'data' => $item->load(['product', 'color', 'size'])
        ], 201);     
    }

    /**
     * get cart's items.
     */
    public function show($user_id)
    {
         $cart = Cart::with([
        'cartItems.product:id,name,description,category_id,price',
        'cartItems.color:name', 
        'cartItems.size:id,name'
        ])
        ->where('user_id', $user_id)
        ->firstOrFail();
        return response()->json([
            'success' => true,
            'cart' => $cart,
            'total_items' => $cart->cartItems->count()
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateItem(Request $request,CartItem $cartItem){
        $validated = $request->validate([
        'product_id' => 'sometimes|exists:products,id',
        'color_id' => 'sometimes|exists:colors,id',
        'size_id' => 'sometimes|exists:sizes,id',
        'quantity' => 'sometimes|integer|min:1|max:100'
        ]);
        
        $cartItem->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'update completed',
            'item' => $cartItem->fresh()
        ]);
    }

    /**
     * Remove the specified cart.
     */
    public function destroy(Cart $cart)
    {   
        $cart->delete();
         return response()->json([
        'success' => true,
        'message' => 'Cart deleted successfully'
        ], 200);    
    }

    //remove the specified item from the cart
    public function removeItem($cartItemId){
        CartItem::where('id',$cartItemId)->delete();
        return response()->json([
        'success' => true,
        'message' => 'Item deleted successfully'
    ], 200); 
    }

    //get the cost of user's cart
    public function getCartCost(Request $request)
    {
        $taxRate = 0.15;
        $discountThreshold = 100;
        $discountRate = 0.10;
        

        $cart = Cart::with('cartItems.product')->where('user_id', $request->user_id)->first();

        $subtotal = $cart->cartItems->sum(function($cartItem) {
            return $cartItem->quantity * $cartItem->product->price;
        });

        $tax = $subtotal * $taxRate;

        $discount = 0;
        if ($subtotal >= $discountThreshold) {
            $discount = $subtotal*$discountRate;  
        }

        $total = $subtotal - $discount + $tax;

        return response()->json([
            'success' => true,
            'summary' => [
                'subtotal' => number_format($subtotal, 2),
                'discount' => number_format($discount, 2),
                'tax' => number_format($tax, 2),
                'total' => number_format($total, 2),
                'items_count' => $cart->cartItems->count(),
                'currency' => '$'
            ]
        ]);
    }
}
