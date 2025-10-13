<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id??null,
            'name' => $this->name??null,
            'email' => $this->email??null,
            'avatar' => $this->getFirstMediaUrl('avatar'),
            'email_verified' => !is_null($this->email_verified_at)??null,
            'email_verified_at' => $this->email_verified_at?->toDateTimeString()??null,
            'created_at' => $this->created_at->toDateTimeString()??null,
            'updated_at' => $this->updated_at->toDateTimeString()??null,
            'orders_count' => $this->whenCounted('orders')??null
        ];
    }
}