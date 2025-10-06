<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'cart_items' => CartItemResource::collection($this->whenLoaded('cartItems')),
            'summary' => $this->whenLoaded('cartItems', function() {
                $subtotal = $this->cartItems->sum(function($item) {
                    return $item->quantity * $item->product->price;
                });    
                return [
                    'items_count' => $this->cartItems->count(),
                    'total_quantity' => $this->cartItems->sum('quantity'),
                    'subtotal' => [
                        'amount' => $subtotal,
                        'formatted' => number_format($subtotal, 2) . ' $',
                    ],
                ];
            }),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}