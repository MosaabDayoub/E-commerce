<?php

namespace App\Http\Controllers\User;

use App\Models\Category;
use App\Http\Requests\User\CategoryRequest;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    // get categories
    public function index()
    {
        $categories = Category::withCount('products')->get();
        
        return ResponseHelper::success(CategoryResource::collection($categories));
    }
    
    // get specified category.
    public function show(Category $category)
    {
        $category->loadCount('products');
        
        return ResponseHelper::success(new CategoryResource($category));
    }

    // search about specified category
    public function search(CategoryRequest $request){

    $categories = Category::where('name','like',$request->search . '%')->get();

    return ResponseHelper::success(CategoryResource::collection($categories));
    
    }
}
