<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
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
        $data = $request->all();

        $category->update($data);

        return response()->json(['message' => 'category created successfully'], 201);
    }

    /**
     * Remove category.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    // search about specified category
        public function search(Request $request){

        $category = Category::where('name','like',$request->search . '%')->get();

         return response()->json($category, 200);
}
}
