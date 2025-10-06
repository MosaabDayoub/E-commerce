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

        return ResponseHelper::success(new ProductResource($product));
    }

    // search about specified resource
    public function search(ProductRequest $request){
            
            $products = Product::where('name','like',$request->search . '%')
            ->limit(50)
            ->paginate(10);
            return ResponseHelper::success(ProductResource::collection($products)); 
}

}
