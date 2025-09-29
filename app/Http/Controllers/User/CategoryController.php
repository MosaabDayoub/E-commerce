<?php

namespace App\Http\Controllers\User;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorys = Category::all();
        
        return response()->json($categorys, 200);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json($category, 200);
    }

    // search about specified resource
    public function search(Request $request){

    $category = Category::where('name','like',$request->search . '%')->get();

        return response()->json($category, 200);
    }
}
