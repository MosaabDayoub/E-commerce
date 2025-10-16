<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->when($this->price, function() {
                return [
                    'amount' => $this->price,
                    'formatted' => number_format($this->price, 2) . ' $',
                ];
            }),
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
            'main_image' => $this->getFirstMediaUrl('main'),
            'gallery_images' => $this->when($this->relationLoaded('media'), function() {
                return $this->getMedia('gallery')->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'url' => $media->getUrl(),
                    ];
                });
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}