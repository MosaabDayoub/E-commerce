<?php

namespace App\Http\Controllers\User;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\OrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Helpers\ResponseHelper;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    // Get user's orders
    public function index(OrderRequest $request)
    {
        $validated = $request->validated();

        $orders = Order::with(['orderItems.product', 'orderItems.color', 'orderItems.size'])
            ->where('user_id', $validated['user_id'])
            ->orderBy('created_at', 'desc')
            ->paginate(10); 

        return ResponseHelper::success(OrderResource::collection($orders));
    }

    // Create new order
    public function store(OrderRequest $request)
    {
        $validated = $request->validated();

        $cart = Cart::with('cartItems.product')->where('user_id', $validated['user_id'])->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return ResponseHelper::error('The cart is empty, cannot create order');
        }

        // Calculate the total amount
        $totalAmount = $cart->cartItems->sum(function ($cartItem) {
            return $cartItem->quantity * $cartItem->product->price;
        });

        $order = Order::create([
            'user_id' => $validated['user_id'],
            'total' => $totalAmount,
        ]);

        // Copy cart items to the order
        foreach ($cart->cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'color_id' => $cartItem->color_id,
                'size_id' => $cartItem->size_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price
            ]);
        }

        $order->load(['orderItems.product', 'orderItems.color', 'orderItems.size']);
        $cart->delete();
        event(new OrderCreated($order));
        return ResponseHelper::success(new OrderResource($order),'Order Created successfully');
    }

    // Get order's items
    public function show($order_id)
    {
        $order = Order::with([
            'orderItems.product:id,name,description,category_id,price',
            'orderItems.color:id,name',
            'orderItems.size:id,name'
        ])
        ->where('id', $order_id)
        ->firstOrFail();

        return ResponseHelper::success(new OrderResource($order));
    }

    /**
     * Update order
     */
    public function update(OrderRequest $request, $orderId)
    {
        $validated = $request->validated();

        $order = Order::with('orderItems')->where('id', $orderId)->firstOrFail();
        
        $orderStatus = $order->status;
        
        if($orderStatus == 'pending') {
            $order->orderItems()->update($validated);
            
            if (isset($validated['quantity'])) {
                $this->updateOrderTotal($order);
            }

            $order->load(['orderItems.product', 'orderItems.color', 'orderItems.size']);

            return ResponseHelper::success(new OrderResource($order),'Order updated successfully');
        } else {
            return ResponseHelper::error('Cannot update order. Current status: ' . $orderStatus);
        }
    }

    /**
     * Update order total amount
     */
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
     * Cancel order
     */
    public function destroy($orderId)
    {
        $order = Order::where('id', $orderId)->firstOrFail();
        
        if($order->status == 'pending') {
            $order->delete();
            return ResponseHelper::successMessage('Order canceled successfully');
        } else {
            return ResponseHelper::error('Cannot cancel order. Current status: ' . $order->status);
        } 
    }

    /**
     * Delete order's item
     */
    public function removeItem(Order $order, OrderRequest $request)
    {
        $validated = $request->validated();

        $orderItem = $order->orderItems()->where('id', $validated['orderItem_id'])->first();

        if (!$orderItem) {  
            return ResponseHelper::error('Order item not found in this order');
        }

        if ($order->status != 'pending') {
            return ResponseHelper::error('Cannot remove item from order. Current status: ' . $order->status);
        }

        $orderItem->delete();
        $this->updateOrderTotal($order);
        
        $order->load(['orderItems.product', 'orderItems.color', 'orderItems.size']);

        return ResponseHelper::success(new OrderResource($order),'Order item removed successfully');
    }
}