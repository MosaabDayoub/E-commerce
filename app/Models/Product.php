<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Product extends Model implements TranslatableContract, HasMedia
{
    
    use Translatable, InteractsWithMedia;

    public $translatedAttributes = ['name', 'description'];
    
    protected $fillable = ['price','category_id'];

    public function category(){
        return $this->belongsTo(Product::class);
    }

    public function colors(){
        return $this->belongsToMany(Color::class,'color_product');
    }

     public function sizes(){
        return $this->belongsToMany(Size::class);
    }

    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }

     public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }

    public function registerMediaCollections(): void
    {
        // Main Photo
        $this->addMediaCollection('main')
            ->singleFile()->useDisk('product');

        // gallery
        $this->addMediaCollection('gallery')->useDisk('product');
    }
    
    /**
     * Scope for filter by category
     */
    public function scopeFilterByCategory(Builder $query, $categoryId)
    {
        return $query->when($categoryId, function($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        });
    }

    /**
     * Scope for filter by price
     */
    public function scopeFilterByPrice(Builder $query, $minPrice = null, $maxPrice = null)
    {
        return $query->when($minPrice, function($q) use ($minPrice) {
            $q->where('price', '>=', $minPrice);
        })->when($maxPrice, function($q) use ($maxPrice) {
            $q->where('price', '<=', $maxPrice);
        });
    }

    /**
     * Scope for filter by color
     */
    public function scopeFilterByColors(Builder $query, $colors)
    {
        return $query->when($colors, function($q) use ($colors) {
            $q->whereHas('colors', function($colorQuery) use ($colors) {
                $colorQuery->whereIn('colors.id', (array)$colors);
            });
        });
    }

    /**
     * Scope for filter by size
     */
    public function scopeFilterBySizes(Builder $query, $sizes)
    {
        return $query->when($sizes, function($q) use ($sizes) {
            $q->whereHas('sizes', function($sizeQuery) use ($sizes) {
                $sizeQuery->whereIn('sizes.id', (array)$sizes);
            });
        });
    }

    /**
     * Scope for all filters
     */
    public function scopeApplyFilters(Builder $query, array $filters)
    {
        return $query
            ->when(isset($filters['search']), function($q) use ($filters) {
                $q->where('name->' . app()->getLocale(), 'like', $filters['search'] . '%');
            })
            ->filterByCategory($filters['category_id'] ?? null)
            ->filterByPrice($filters['min_price'] ?? null, $filters['max_price'] ?? null)
            ->filterByColors($filters['colors'] ?? null)
            ->filterBySizes($filters['sizes'] ?? null);
    }
    
}
