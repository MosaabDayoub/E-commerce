<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProductRequest;
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
            'search' => $request->search ?? null,
        ])
        ->paginate(10);

    return ResponseHelper::success(ProductResource::collection($products));      
}

    /**
     * get the specified product.
     */
    public function show($productId){ 
        $product = Product::with([
            'sizes',
            'colors',
            'category'
        ])->findOrFail($productId);

        return ResponseHelper::success(new ProductResource($product));
    }
}