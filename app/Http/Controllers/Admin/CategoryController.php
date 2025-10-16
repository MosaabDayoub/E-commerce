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
        try {
            $category = new Category();

            $category->save();

            if ($request->hasFile('image')) {
                $category->addMedia($request->file('image'))
                    ->toMediaCollection('main');
            }

            // add translations to arabic
            $category->translateOrNew('ar')->name = $request->name_ar;
            $category->translateOrNew('ar')->description = $request->description_ar ?? null;
            
            // add translations to english
            $category->translateOrNew('en')->name = $request->name_en;
            $category->translateOrNew('en')->description = $request->description_en ?? null;
            
            $category->save();

            return ResponseHelper::success(
                new CategoryResource($category), 
                'Category created successfully'
            ); 

        } catch (\Exception $e) {
            return ResponseHelper::error(
                'Failed to create category: ' . $e->getMessage()
            );
        }
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
        try {
            
            if ($request->has('name_ar')) {
                $category->translateOrNew('ar')->name = $request->name_ar;
            }
            
            if ($request->has('description_ar')) {
                $category->translateOrNew('ar')->description = $request->description_ar;
            }
         
            if ($request->has('name_en')) {
                $category->translateOrNew('en')->name = $request->name_en;
            }
            
            if ($request->has('description_en')) {
                $category->translateOrNew('en')->description = $request->description_en;
            }
            
            if ($request->hasFile('image')) {
                $category->clearMediaCollection('main');
                $category->addMedia($request->file('image'))
                        ->toMediaCollection('main');
            }

            $category->save();

            return ResponseHelper::success(
                new CategoryResource($category), 
                'Category updated successfully'
            );

        } catch (\Exception $e) {
            return ResponseHelper::error(
                'Failed to update category: ' . $e->getMessage()
            );
        }
    }

    /**
     * Remove category.
     */
    public function destroy(Category $category)
    {
        try {
            $category->clearMediaCollection('main');
            $category->delete();

            return ResponseHelper::successMessage('Category deleted successfully');

        } catch (\Exception $e) {
            return ResponseHelper::error(
                'Failed to delete category: ' . $e->getMessage()
            );
        }
    }

    // search about specified category
    public function search(CategoryRequest $request)
    {
        try {
            $validated = $request->validated();

            $categories = Category::whereHas('translations', function($q) use ($validated) {
                $q->where('name->' . app()->getLocale(), 'like', $validated['search'] . '%');
            })->get();

            return ResponseHelper::success(CategoryResource::collection($categories));

        } catch (\Exception $e) {
            return ResponseHelper::error(
                'Failed to search categories: ' . $e->getMessage()
            );
        }
    }
}