<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity, 
            'product' => $this->whenLoaded('product', [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'description' => $this->product->description,
            ]),
            'color' => $this->whenLoaded('color', [
                'id' => $this->color->id,
                'name' => $this->color->name,
            ]),
            'size' => $this->whenLoaded('size', [
                'id' => $this->size->id,
                'name' => $this->size->name,
            ]),
            'item_total' => $this->when($this->relationLoaded('product'), function() {
                return [
                    'amount' => $this->quantity * $this->product->price,
                    'formatted' => number_format($this->quantity * $this->product->price, 2) . ' $',
                ];
            }),
            'created_at' => $this->created_at?->toDateTimeString(), 
            'updated_at' => $this->updated_at?->toDateTimeString(), 
        ];
    }
}