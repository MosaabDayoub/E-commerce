<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;

class ProductController extends Controller
{
    /**
     * get products with filltering
     */
    public function index(ProductRequest $request)
    {
        //build the main query
        $query = Product::with([
            'category:id,name',
            'colors:id,name',
            'sizes:id,name'
        ])->select([ 
            'id', 
            'name', 
            'price', 
            'description', 
            'category_id',
        ]);

        //filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        //filter by price
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        //filter by color
        if ($request->filled('colors')) {
            $query->whereHas('colors', function($q) use ($request) {
                $q->whereIn('colors.id', $request->colors);
            });
        }

        //filter by size
        if ($request->filled('sizes')) {
            $query->whereHas('sizes', function($q) use ($request) {
                $q->whereIn('sizes.id', $request->sizes);
            });
        }
        $products = $query->get(); 
        return response()->json([
            'success' => true,
            'message' => 'data retrived successfuly',
            'data' => $products
        ],200); 
        
    }
    
    /**
     * Store a newly product.
     */
    public function store(ProductRequest $request)
    {
        $validated = $request->validated();

        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'], 
            'category_id' => $validated['category_id'],
        ]);

        // add colors
        if ($request->has('colors')) {
            $product->colors()->attach($validated['colors']);
        }

        // add sizes
        if ($request->has('sizes')) {
            $product->sizes()->attach($validated['sizes']);
        }

        return response()->json([
            'success' => true,
            'message' => 'product created successfuly',
            'data' => $product
        ],200);
    }

    /**
     * Display products of specified user.
     */
    public function show(Product $product)
    {         
        return response()->json([
            'success' => true,
            'message' => 'data retrived successfuly',
            'data' => $product
        ],200); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        $product->update([
        'name' => $validated['name'],
        'description' => $validated['description'],
        'price' => $validated['price'], 
        'category_id' => $validated['category_id'],
        ]);

        if ($request->has('colors')) {
            $product->colors()->sync($validated['colors']);
        }

        if ($request->has('sizes')) {
            $product->sizes()->sync($validated['sizes']);
        }

        return response()->json([
            'success' => true,
            'message' => 'product updated successfuly',
            'data' => $product->load(['colors', 'sizes'])
        ],200);
        
    }

    /**
     * Remove product.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'product deleted successfully'], 200);
    }

   // search about specified resource
    public function search(ProductRequest $request){

        $validated = $request->validated();
        $product = Product::where('name','like',$validated['search'] . '%')->get();

         return response()->json($product, 200);
    }

    // add color to product 
    public function addColorsToProduct(ProductRequest $request, $productId)
    {
        $validated = $request->validated();

        $product = Product::findOrFail($productId);
        $product->colors()->syncWithoutDetaching($validated['colors']);

        return response()->json([
            'message' => 'Colors added successfuly',
            'colors' => $product->colors
        ]);
    }
    // remove color of product
    public function removeColorsFromProduct(ProductRequest $request, $productId)
    {
        $validated = $request->validated();

        $product = Product::findOrFail($productId);
        $product->colors()->detach($validated['colors']);

        return response()->json([
            'message' => 'Product colors have been removed',
            'colors' => $product->colors
        ]);
    }

    // add size to product's sizes
    public function addSizesToProduct(ProductRequest $request, $productId)
    {
        $validated = $request->validated();

        $product = Product::findOrFail($productId);
        $product->sizes()->syncWithoutDetaching($validated['sizes']);
        return response()->json([
            'message' => 'Product sizes have been updated',
            'sizes' => $product-> sizes
        ]);
    }

    // remove size to product's sizes
    public function removeSizesFromProduct(ProductRequest $request, $productId)
    {
        $validated = $request->validated();

        $product = Product::findOrFail($productId);
        $product->sizes()->detach($validated['sizes']);
        
        return response()->json([
            'message' => 'Product sizes have been removed',
            'sizes' => $product-> sizes
        ]);
    }

}
