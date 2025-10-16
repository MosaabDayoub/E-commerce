<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'price' => [
                'amount' => $this->price,
                'formatted' => number_format($this->price, 2) . ' $',
            ],
            'product' => $this->whenLoaded('product', [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
            ]),
            'color' => $this->whenLoaded('color', [
                'id' => $this->color->id,
                'name' => $this->color->name,
            ]),
            'size' => $this->whenLoaded('size', [
                'id' => $this->size->id,
                'name' => $this->size->name,
            ]),
            'created_at' => $this->created_at?->toDateTimeString(), 
            'updated_at' => $this->updated_at?->toDateTimeString(), 
        ];
    }
}