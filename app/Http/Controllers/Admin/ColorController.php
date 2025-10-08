<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Models\Color;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ColorRequest;
use App\Http\Resources\ColorResource;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ColorRequest $request)
    {
        $color = new Color();
        $color->translateOrNew('ar')->name = $request->name_ar;
        $color->translateOrNew('en')->name = $request->name_en;
        $color->save();
        return ResponseHelper::success(new ColorResource($color), 'Color created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ColorRequest $request, Color $color)
{
    if ($request->has('name_ar')) {
        $color->translateOrNew('ar')->name = $request->name_ar;
    }
 
    if ($request->has('name_en')) {
        $color->translateOrNew('en')->name = $request->name_en;
    }
    
    $color->save();

    return ResponseHelper::success(new ColorResource($color), 'Color updated successfully');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Color $color)
    {
        $color ->delete();
        return ResponseHelper::successMessage('size deleted successfully');
    }
}
