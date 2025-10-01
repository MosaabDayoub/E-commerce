<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Requests\Admin\CategoryRequest;
use Illuminate\Http\Request\Admin;
use App\Http\Controllers\Controller;


class CategoryController extends Controller
{
    /**
     * get categories.
     */
    public function index()
    {
        $categorys = Category::all();
        
        return response()->json($categorys, 200);
    }

    /**
     * add new category.
     */
    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();

        Category::create([
            'name' => $request->name,
            'description' => $request->email,
        ]);
        
        return response()->json(null, 200);
    }
    /**
     * get specified category.
     */
    public function show(Category $category)
    {
        return response()->json($category, 200);
    }

    /**
     * Update specified category.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json(['message' => 'category created successfully'], 201);
    }

    /**
     * Remove category.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'category deleted successfully'], 200);
    }

    // search about specified category
        public function search(CategoryRequest $request){
        
        $validated = $request->validated();

        $category = Category::where('name','like',$validated['search'] . '%')->get();

         return response()->json($category, 200);
}
}
