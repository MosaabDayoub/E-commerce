<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * get products with filltering
     */
    public function index(Request $request)
    {
       $request->validate([
        'colors' => 'sometimes|array',
        'colors.*' => 'integer|exists:colors,id',
        'sizes' => 'sometimes|array', 
        'sizes.*' => 'integer|exists:sizes,id',
        'min_price' => 'sometimes|numeric|min:0',
        'max_price' => 'sometimes|numeric|min:0',
        'category_id' => 'sometimes|integer|exists:categories,id',
        ]);

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
     * Store a newly \product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'bail|required|unique:products,name',
            'description' =>'required|nullable|string|max:1000',
            'price' => 'required|numeric|min:0'
        ]);  
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price, 
            'category_id' => $request->category_id,
    ]);

    // add colors
    if ($request->has('colors')) {
        $product->colors()->attach($request->colors);
    }

    // add sizes
    if ($request->has('sizes')) {
        $product->sizes()->attach($request->sizes);
    }

        return response()->json(['message' => 'product created successfully'], 201);
    }

    /**
     * Display products of specified user.
     */
    public function show(Product $product)
    {         
        return response()->json($product, 200); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
         $request->validate([
            'name'=> 'bail|required|Unique:products,name,',
            'description' =>'required|nullable|string|max:1000',
            'price' => 'required|numeric|min:0'
        ]);

        $data = $request->all();

        $product->update($data);

        if ($request->has('colors')) {
            $product->colors()->sync($request->colors);
        }

        if ($request->has('sizes')) {
            $product->sizes()->sync($request->sizes);
        }

        return response()->json($product->load(['colors', 'sizes']));
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
    public function search(Request $request){
        
        $product = Product::where('name','like',$request->search . '%')->get();

         return response()->json($product, 200);
    }

    // add color to product's colors
    public function attachColors(Request $request, $productId)
    {
        $request->validate([
            'colors' => 'required|array',
            'colors.*' => 'exists:colors,id'
        ]);

        $product = Product::findOrFail($productId);
        $product->colors()->attach($request->colors);

        return response()->json([
            'message' => 'Colors added successfuly',
            'colors' => $product->colors
        ]);
    }
    // remove color of product's colors
    public function detachColors(Request $request, $productId)
    {
        $request->validate([
            'colors' => 'required|array',
            'colors.*' => 'exists:colors,id'
        ]);

        $product = Product::findOrFail($productId);
        $product->colors()->detach($request->colors);

        return response()->json([
            'message' => 'Product colors have been removed',
            'colors' => $product->colors
        ]);
    }

    public function syncColors(Request $request, $productId)
    {
        $request->validate([
            'colors' => 'required|array',
            'colors.*' => 'exists:colors,id'
        ]);

        $product = Product::findOrFail($productId);
        $product->colors()->sync($request->colors);

        return response()->json([
            'message' => 'Product colors have been updated',
            'colors' => $product->colors
        ]);
    }

    // add size to product's sizes
    public function attachSizes(Request $request, $productId)
    {
        $request->validate([
            'sizes' => 'required|array',
            'sizes.*' => 'exists:sizes,id'
        ]);

        $product = Product::findOrFail($productId);
        $product->sizes()->attach($request->sizes);
        return response()->json([
            'message' => 'Product sizes have been updated',
            'colors' => $product-> sizes
        ]);
    }

    // remove size to product's sizes
    public function detachSizes(Request $request, $productId)
    {
        $request->validate([
            'sizes' => 'required|array',
            'sizes.*' => 'exists:sizes,id'
        ]);

        $product = Product::findOrFail($productId);
        $product->sizes()->detach($request->sizes);
        return response()->json([
            'message' => 'Product sizes have been removed',
            'colors' => $product-> sizes
        ]);
    }

    public function syncsizes(Request $request, $productId)
    {
        $request->validate([
            'sizes' => 'required|array',
            'sizes.*' => 'exists:sizes,id'
        ]);

        $product = Product::findOrFail($productId);
        $product->sizes()->sync($request->sizes);

        return response()->json([
            'message' => 'Product sizes have been updated',
            'colors' => $product->sizes
        ]);
    }
}
