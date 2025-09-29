<?php

namespace App\Http\Controllers\User;

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
     * get the specified product.
     */
    public function show($productId){ 
        $product = Product::select([
            'id', 'name', 'price', 'description','category_id'
        ])->with([
            'sizes:id,name',
            'colors:id,name',
            'category:id,name'
        ])->findOrFail($productId);

        return response()->json([
            'success' => true,
            'message' => 'data retrived successfuly',
            'data' => $product
        ],200);
    }

    // search about specified resource
    public function search(Request $request){
          $request->validate([
             'search' => 'required|string|max:100'
             ]);
            $product = Product::where('name','like',$request->search . '%')
            ->limit(50)
            ->get();
            return response()->json($product, 200);
}

}
