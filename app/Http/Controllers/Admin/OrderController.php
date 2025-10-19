<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\Admin\OrderRequest;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Helpers\ResponseHelper;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{

    // get Orders.
    public function index()
    {
        $orders = Order::with([
        'orderItems.product.colors',
        'orderItems.product.sizes',
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return ResponseHelper::success(OrderResource::collection($orders));          
    }

    // get user orders.
    public function show($user_id)
    {
        $orders = Order::with([
        'orderItems.product.colors',
        'orderItems.product.sizes',
        ])
        ->where('user_id',$user_id)
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        return ResponseHelper::success(OrderResource::collection($orders));
    }
}
