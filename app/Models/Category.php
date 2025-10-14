<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Category extends Model implements TranslatableContract,HasMedia
{
    use Translatable, InteractsWithMedia;
    public $translatedAttributes = ['name', 'description'];

    public function registerMediaCollections(): void
    {
        // Main Photo
        $this->addMediaCollection('main')
            ->singleFile()->useDisk('category');
    }

    public function products(){
        return $this->hasMany(Product::class);
    }
}
