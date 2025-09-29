<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;


class OrderController extends Controller
{
    // get user's orders.
    public function index(Request $request)
    {
         $validated = $request->validate([
        'user_id' => 'required|integer|exists:users,id'
        ]);

        $orders = Order::with('orderItems.product') 
            ->where('user_id', $validated['user_id'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $orders,
            'orders_count' => $orders->count(),
            'message' => 'Orders retrieved successfully'
        ], 200);
    }
    /**
     * create new order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);
    
        $cart = Cart::with('cartItems.product')->where('user_id', $validated['user_id'])->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return response()->json([
                'message' => 'The cart is empty, cannot create order'
            ], 404);
        }

        // calculate the total amount
        $totalAmount = $cart->cartItems->sum(function ($cartItem) {
            return $cartItem->quantity * $cartItem->product->price;
        });

        
        $order = Order::create([
            'user_id' => $validated['user_id'],
            'total' => $totalAmount, 
        ]);
        
        // copy cartitems to the order
        foreach ($cart->cartItems as $cartItem) {
           $orderItems = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'color_id' => $cartItem->color_id,
                'size_id' => $cartItem->size_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price
            ]);
        }
        $cart->delete();
        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order->load('orderItems'),
            'total_amount' => $totalAmount
        ], 201);
        }

        /**
         * get order's items.
         */
    public function show($order_id)
    {
        $order=Order::with([
            'orderItems.product:id,name,description,category_id,price',
            'orderItems.color:id,name',
            'orderItem.size'
        ])
        ->where('order_id',$order_id)
        ->firstOrFail();
        return response()->json([
            'success' => true,
            'order' => $order,
            'order_items' => $order->orderItems->count()
        ], 200);
    }

    /**
     * Update order.
     */
    public function update(Request $request,$orderId)
    {
      $validated = $request->validate([
        'product_id' => 'sometimes|exists:products,id',
        'color_id' => 'sometimes|exists:colors,id',
        'size_id' => 'sometimes|exists:sizes,id',
        'quantity' => 'sometimes|integer|min:1|max:100'
        ]);

        $order = Order::where('id', $orderId)->firstOrFail();
        
        $orderStatus = $order->status;
        
        if($orderStatus == 'pending') {
            $order->orderItems()->update($validated);
            
        if (isset($validated['quantity'])) {
            $this->updateOrderTotal($order);
        }
        return response()->json([
            'success' => true,
            'message' => 'Update completed successfully',
            'order' => $order->fresh(['orderItems.product', 'orderItems.color', 'orderItems.size'])
        ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update order. Current status: ' . $orderStatus
            ], 422);
        }
    }
    // update order total amount
    private function updateOrderTotal(Order $order)
    {
        $totalAmount = $order->orderItems()->with('product')
            ->get()
            ->sum(function ($orderItem) {
                return $orderItem->quantity * $orderItem->product->price;
            });
        
        $order->update(['total' => $totalAmount]);
    }

    /**
     * cancel order.
     */
    public function destroy($orderId)
    {
       $order = Order::where('id',$orderId)->firstOrFail();
       if($order->status == 'pending') {
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order canceled successfully'
            ], 200);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel order. Current status: ' . $order->status 
            ], 422);
        } 
    }
// Delete order's item
public function removeItem(Order $order, Request $request)
{
    $validated = $request->validate([
        'orderItem_id' => 'required|exists:orderItems,id'
    ]);

    $orderItem = $order->orderItems()->where('id', $validated['orderItem_id'])->first();

    if (!$orderItem) {
        return response()->json([
            'success' => false,
            'message' => 'Order item not found in this order'
        ], 404);
    }

    if ($order->status == 'pending') {
        return response()->json([
            'success' => false,
            'message' => 'Cannot remove item from order. Current status: ' . $order->status
        ], 422);
    }

    $orderItem->delete();

    $this->updateOrderTotal($order);

    return response()->json([
        'success' => true,
        'message' => 'Order item removed successfully',
        'order' => $order->fresh(['orderItems.product'])
    ], 200);
}
}
