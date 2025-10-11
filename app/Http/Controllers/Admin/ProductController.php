<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Helpers\ResponseHelper;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * get products with filltering
     */
    public function index(ProductRequest $request)
    {   
        //build the main query
        $products = Product::with([
            'category',
            'colors',
            'sizes'
        ])
        ->applyFilters([
            'category_id' => $request->category_id,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'colors' => $request->colors,
            'sizes' => $request->sizes,
        ])
        ->paginate(10);
        
        return ResponseHelper::success(ProductResource::collection($products));      
    }
    
    // Store a newly product.
    public function store(ProductRequest $request)
    {
        try {
            $validated = $request->validated();

            $product = Product::create([
                'price' => $validated['price'], 
                'category_id' => $validated['category_id'],
            ]);

            // add translations to arabic
            $product->translateOrNew('ar')->name = $request->name_ar;
            $product->translateOrNew('ar')->description = $request->description_ar;
            
            // add translations to english
            $product->translateOrNew('en')->name = $request->name_en;
            $product->translateOrNew('en')->description = $request->description_en;

            $product->save();
            
            // add main image
            if ($request->hasFile('main_image')) {
                $product->addMedia($request->file('main_image'))
                    ->toMediaCollection('main');
            }

            // add images to gallery
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $product->addMedia($image)
                        ->toMediaCollection('gallery');
                }
            }
            
            // add colors
            if ($request->has('colors')) {
                $product->colors()->attach($validated['colors']);
            }

            // add sizes
            if ($request->has('sizes')) {
                $product->sizes()->attach($validated['sizes']);
            }

            return ResponseHelper::success(new ProductResource($product),'Product created successfully');

        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to create product: ' . $e->getMessage());
        }
    }

    // get specified product.
    public function show(Product $product)
    {       
        return ResponseHelper::success(new ProductResource($product));
    }

    // Update product.
    public function update(ProductRequest $request, Product $product)
    {
        try {
            $validated = $request->validated();

            $product->update([
                'price' => $validated['price'], 
                'category_id' => $validated['category_id'],
            ]);

            if($request->has('name_ar')) {
                $product->translateOrNew('ar')->name = $request->name_ar;
            }
            
            if($request->has('name_en')) {
                $product->translateOrNew('en')->name = $request->name_en;
            }

            if($request->has('description_ar')) {
                $product->translateOrNew('ar')->description = $request->description_ar;
            }
            
            if($request->has('description_en')) {
                $product->translateOrNew('en')->description = $request->description_en;
            }

            $product->save();

            // update main image
            if ($request->hasFile('main_image')) {
                $product->clearMediaCollection('main');
                $product->addMedia($request->file('main_image'))
                    ->toMediaCollection('main');
            }

            // add images to gallery
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $product->addMedia($image)
                        ->toMediaCollection('gallery');
                }
            }

            if ($request->has('colors')) {
                $product->colors()->sync($validated['colors']);
            }

            if ($request->has('sizes')) {
                $product->sizes()->sync($validated['sizes']);
            }
            
            return ResponseHelper::success(new ProductResource($product),'Product updated successfully');

        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to update product: ' . $e->getMessage());
        }
    }

    // Remove product.
    public function destroy(Product $product)
    {
        try {
            $product->clearMediaCollection('main');
            $product->clearMediaCollection('gallery');
            
            $product->delete();
            return ResponseHelper::successMessage('Product deleted successfully');

        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to delete product: ' . $e->getMessage());
        } 
    }

    // search about specified resource
    public function search(ProductRequest $request)
    {
        try {
            $validated = $request->validated();
            $products = Product::where('name','like',$validated['search'] . '%')->paginate(10);

            return ResponseHelper::success(ProductResource::collection($products));

        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to search products: ' . $e->getMessage());
        }
    }

    // add colors to product 
    public function addColorsToProduct(ProductRequest $request, $productId)
    {
        try {
            $validated = $request->validated();

            $product = Product::findOrFail($productId);
            $product->colors()->syncWithoutDetaching($validated['colors']);

            return ResponseHelper::success(new ProductResource($product),'Colors added successfully');

        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to add colors: ' . $e->getMessage());
        }
    }

    // remove color of product
    public function removeColorsFromProduct(ProductRequest $request, $productId)
    {
        try {
            $validated = $request->validated();

            $product = Product::findOrFail($productId);
            $product->colors()->detach($validated['colors']);

            return ResponseHelper::success(new ProductResource($product),'Colors removed successfully');

        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to remove colors: ' . $e->getMessage());
        }
    }

    // add size to product's sizes
    public function addSizesToProduct(ProductRequest $request, $productId)
    {
        try {
            $validated = $request->validated();

            $product = Product::findOrFail($productId);
            $product->sizes()->syncWithoutDetaching($validated['sizes']);

            return ResponseHelper::success(new ProductResource($product),'Sizes added successfully');

        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to add sizes: ' . $e->getMessage());
        }
    }

    // remove size to product's sizes
    public function removeSizesFromProduct(ProductRequest $request, $productId)
    {
        try {
            $validated = $request->validated();

            $product = Product::findOrFail($productId);
            $product->sizes()->detach($validated['sizes']);
            
            return ResponseHelper::success(new ProductResource($product),'Sizes removed successfully');

        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to remove sizes: ' . $e->getMessage());
        }
    }

    /**
     * dekete main image
     */
    public function deleteMainImage(Product $product)
    {
        try {
            $product->clearMediaCollection('main');
            return ResponseHelper::successMessage('Main image deleted successfully');

        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to delete main image: ' . $e->getMessage());
        }
    }

    /**
     * delete 
     */
    public function deleteGalleryImage(Product $product, $mediaId)
    {
        try {
            $product->deleteMedia($mediaId);
            return ResponseHelper::successMessage('Gallery image deleted successfully');

        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to delete gallery image: ' . $e->getMessage());
        }
    }

    /**
     * delete all images
     */
    public function clearGallery(Product $product)
    {
        try {
            $product->clearMediaCollection('gallery');
            return ResponseHelper::successMessage('Gallery cleared successfully');

        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to clear gallery: ' . $e->getMessage());
        }
    }
}