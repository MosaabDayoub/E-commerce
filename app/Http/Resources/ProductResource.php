<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name??null,
            'description' => $this->description??null,
            'price' => [
                'amount' => $this->price??null,
                'formatted' =>isset($this->price) ? number_format($this->price, 2) . ' $':null,
            ],
            'category' => $this->whenLoaded('category', function() {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ];
            }),
            'colors' => $this->whenLoaded('colors', function() {
                return $this->colors->map(function($color) {
                    return [
                        'id' => $color->id,
                        'name' => $color->name,
                    ];
                });
            }),
            'sizes' => $this->whenLoaded('sizes', function() {
                return $this->sizes->map(function($size) {
                    return [
                        'id' => $size->id,
                        'name' => $size->name,
                    ];
                });
            }),
            'main_image' => $this->getFirstMediaUrl('main')?? null,
            
            'gallery_images' => $this->getMedia('gallery')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                ];
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
    
}