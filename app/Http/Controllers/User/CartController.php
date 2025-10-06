<?php

namespace App\Http\Controllers\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CartRequest;
use App\Models\Cart;
use App\Models\CartItem;

use App\Http\Resources\CartResource;
use App\Http\Resources\CartItemResource;

class CartController extends Controller
{
    // Add new item to the cart
    public function store(CartRequest $request)
    {
        $validated = $request->validated();
        
        $cart = Cart::firstOrCreate(['user_id' => $validated['user_id']]);

        $cartItem = $cart->cartItems()->create([
            'product_id' => $validated['product_id'],
            'color_id' => $validated['color_id'] ?? null,
            'size_id' => $validated['size_id'] ?? null,
            'quantity' => $validated['quantity']
        ]);

        $cartItem->load(['product', 'color', 'size']);

        return ResponseHelper::success(new CartItemResource($cartItem),'Item added to cart successfully');    
    }

    // Get cart's items
    public function show($user_id)
    {
        $cart = Cart::with([
            'cartItems.product:id,name,description,price',
            'cartItems.color:id,name', 
            'cartItems.size:id,name'
        ])
        ->where('user_id', $user_id)
        ->firstOrFail();

        return ResponseHelper::success(new CartResource($cart));
    }

    // Update cart item
    public function updateItem(CartRequest $request, CartItem $cartItem)
    {
        $validated = $request->validated();
        
        $cartItem->update($validated);

        $cartItem->load(['product', 'color', 'size']);
        
        return ResponseHelper::success(new CartItemResource($cartItem),'Cart item updated successfully');
    }
  
    // Remove the entire cart
    public function destroy(Cart $cart)
    {   
        $cart->delete();
        
        return ResponseHelper::successMessage('Cart removed successfully');   
    }

    // Remove specific item from cart
    public function removeItem($cartItemId)
    {
        $cartItem = CartItem::with(['product', 'color', 'size'])->findOrFail($cartItemId);
        $cartItem->delete();
        
        return ResponseHelper::success(new CartItemResource($cartItem),'Item removed from cart successfully'); 
    }

    // Get cart cost summary
    public function getCartCost(CartRequest $request)
    {
        $validated = $request->validated();
        
        $taxRate = 0.15;
        $discountThreshold = 100;
        $discountRate = 0.10;

        $cart = Cart::with('cartItems.product')
                   ->where('user_id', $validated['user_id'])
                   ->firstOrFail();

        $subtotal = $cart->cartItems->sum(function($cartItem) {
            return $cartItem->quantity * $cartItem->product->price;
        });

        $tax = $subtotal * $taxRate;
        $discount = $subtotal >= $discountThreshold ? $subtotal * $discountRate : 0;
        $total = $subtotal - $discount + $tax;

        return ResponseHelper::success([
            'summary' => [
                'subtotal' => [
                    'amount' => $subtotal,
                    'formatted' => number_format($subtotal, 2) . ' $',
                ],
                'discount' => [
                    'amount' => $discount,
                    'formatted' => number_format($discount, 2) . ' $',
                    'applied' => $subtotal >= $discountThreshold,
                ],
                'tax' => [
                    'amount' => $tax,
                    'formatted' => number_format($tax, 2) . ' $',
                    'rate' => $taxRate * 100 . '%',
                ],
                'total' => [
                    'amount' => $total,
                    'formatted' => number_format($total, 2) . ' $',
                ],
                'items_count' => $cart->cartItems->count(),
                'total_quantity' => $cart->cartItems->sum('quantity'),
                'currency' => 'USD'
            ]
        ]);
    }
}