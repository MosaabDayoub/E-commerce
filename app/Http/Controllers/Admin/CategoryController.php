<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use App\Http\Resources\CategoryResource;


class CategoryController extends Controller
{
    
    // get categories.
    public function index()
    {
        $categories = Category::withCount('products')->get();
        
        return ResponseHelper::success(CategoryResource::collection($categories)); 
    }

    // add new category.
    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();

        $category = Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);
        
        return ResponseHelper::success(new CategoryResource($category),'Category created successfully'); 
    }

    // get specified category.
    public function show(Category $category)
    {
        $category->loadCount('products');
        return ResponseHelper::success(new CategoryResource($category)); 
    }

    // Update specified category.
    public function update(CategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return ResponseHelper::success(new CategoryResource($category,'Category updated successfully'));
    }

    /**
     * Remove category.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return ResponseHelper::successMessage('category deleted successfully');
    }

    // search about specified category
        public function search(CategoryRequest $request){
        
        $validated = $request->validated();

        $categories = Category::where('name','like',$validated['search'] . '%')->get();

        return ResponseHelper::success(CategoryResource::collection($categories));
}
}
