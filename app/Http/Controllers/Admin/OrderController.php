<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;

class OrderController extends Controller
{
    /**
     * get Orders.
     */
    public function index()
    {
        $orders = Order::with([
        'orderItems.product.colors',
        'orderItems.product.sizes',
        ])
        ->orderBy('created_at', 'desc')
        ->get();  
        return response()->json([
            'success' => true,
            'message' => 'data retrived successfuly',
            'data' => $orders
        ],200); 
    }

    /**
     * get user orders.
     */
    public function show($user_id)
    {
        $order = Order::with([
        'orderItems.product.colors',
        'orderItems.product.sizes',
        ])
        ->where('user_id',$user_id)
        ->orderBy('created_at', 'desc')
        ->get();
        return response()->json([
            'success' => true,
            'message' => 'data retrived successfuly',
            'data' => $order
        ],200);
    }

    /**
     * update order status.
     */
    public function update(Request $request, $order_id)
    {
         $request->validate([
        'status' => 'sometimes|string|in:pending,approved,completed,cancelled',
         ]);

        $order = Order::findOrFail($order_id);
        
        $order->update([
        'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'data' => $order
        ], 200);
    }

    /**
     * Remove order.
     */
    public function destroy(Order $order)
    {
        $order ->delete();
        return response()->json([
                'success' => true,
                'message' => 'Order has removed successfuly'
            ], 200);
    }
}
