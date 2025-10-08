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

        $category = new Category();

        // add translations to arabic
        $category->translateOrNew('ar')->name = $request->name_ar;
        $category->translateOrNew('ar')->description = $request->description_ar;
        
        // add translations to english
        $category->translateOrNew('en')->name = $request->name_en;
        $category->translateOrNew('en')->description = $request->description_en;
  
        
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
        if ($request->has('name_ar')) {
            $category->translateOrNew('ar')->name = $request->name_ar;
        }
     
        if ($request->has('name_en')) {
            $category->translateOrNew('en')->name = $request->name_en;
        }

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
