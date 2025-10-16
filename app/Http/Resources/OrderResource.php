<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total' => [
                'amount' => $this->total,
                'formatted' => number_format($this->total, 2) . ' $',
            ],
            'status' => $this->status,
            'order_items' => OrderItemResource::collection($this->whenLoaded('orderItems')),
            'created_at' => $this->created_at?->toDateTimeString(), 
            'updated_at' => $this->updated_at?->toDateTimeString(), 
        ];
    }
}