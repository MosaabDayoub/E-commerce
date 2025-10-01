<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\Admin\OrderRequest;
use App\Http\Controllers\Controller;
use App\Models\Order;


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
        return response()->json(data: [
            'success' => true,
            'message' => 'data retrived successfuly',
            'data' => $orders
        ],status: 200); 
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
    public function update(OrderRequest $request, $order_id)
    {
        $validated = $request->validated();

        $order = Order::findOrFail($order_id);
        
        $order->update([
        'status' => $validated['status']
        ]);

        return response()->json(data: [
            'success' => true,
            'message' => 'Order status updated successfully',
            'data' => $order
        ], status: 200);
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
