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
            'category:id,name',
            'colors:id,name',
            'sizes:id,name'
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
        
        // add colors
        if ($request->has('colors')) {
            $product->colors()->attach($validated['colors']);
        }

        // add sizes
        if ($request->has('sizes')) {
            $product->sizes()->attach($validated['sizes']);
        }

        return ResponseHelper::success(new ProductResource($product),'Product created successfully');
    }

    // get specified product.
    public function show(Product $product)
    {       
        return ResponseHelper::success(new ProductResource($product));
    }

    // Update product.
    public function update(ProductRequest $request, Product $product)
    {
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

        $product->save();

        if ($request->has('colors')) {
            $product->colors()->sync($validated['colors']);
        }

        if ($request->has('sizes')) {
            $product->sizes()->sync($validated['sizes']);
        }
        
        return ResponseHelper::success(new ProductResource($product),'Product updated successfully');
        
    }

    // Remove product.
    public function destroy(Product $product)
    {
        $product->delete();
        return ResponseHelper::successMessage('product deleted successfully'); 
    }

    // search about specified resource
    public function search(ProductRequest $request){

        $validated = $request->validated();
        $products = Product::where('name','like',$validated['search'] . '%')->paginate(10);;

        return ResponseHelper::success(ProductResource::collection($products));
    }

    // add colors to product 
    public function addColorsToProduct(ProductRequest $request, $productId)
    {
        $validated = $request->validated();

        $product = Product::findOrFail($productId);
        $product->colors()->syncWithoutDetaching($validated['colors']);

        return ResponseHelper::success(new ProductResource($product),'Colors added successfully');;
    }

    // remove color of product
    public function removeColorsFromProduct(ProductRequest $request, $productId)
    {
        $validated = $request->validated();

        $product = Product::findOrFail($productId);
        $product->colors()->detach($validated['colors']);

        return ResponseHelper::success(new ProductResource($product),'Colors removed successfully');
    }

    // add size to product's sizes
    public function addSizesToProduct(ProductRequest $request, $productId)
    {
        $validated = $request->validated();

        $product = Product::findOrFail($productId);
        $product->sizes()->syncWithoutDetaching($validated['sizes']);

        return ResponseHelper::success(new ProductResource($product),'Sizes added successfully');
    }

    // remove size to product's sizes
    public function removeSizesFromProduct(ProductRequest $request, $productId)
    {
        $validated = $request->validated();

        $product = Product::findOrFail($productId);
        $product->sizes()->detach($validated['sizes']);
        
        return ResponseHelper::success(new ProductResource($product),'Sizes removed successfully');
    }

}