<?php
// app/Http/Resources/CategoryResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            
            'id' => $this->id,
            'name' => $this->name??null,
            'description' => $this->description??null,
            'image_url' => $this->getFirstMediaUrl('main')??null,
            'created_at' => $this->created_at?->toDateTimeString()??null,
            'updated_at' => $this->updated_at?->toDateTimeString()??null,
            'products_count' => $this->whenCounted('products')??null
        ];
    }
}