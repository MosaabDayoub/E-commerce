<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SizeRequest;
use App\Helpers\ResponseHelper;
use App\Http\Resources\SizeResource;
use App\Models\Size;
use App\Http\Controllers\Controller;


class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SizeRequest $request)
    {
        $size = Size::create([
            'name' => $request->name 
        ]);
    
        return ResponseHelper::success(new SizeResource($size),'size created successfully');
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
    public function update(SizeRequest $request,Size $size)
    {
        $size->update([
            'name' => $request->name 
        ]);
    
        return ResponseHelper::success(new SizeResource($size),'Color updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Size $size)
    {
        $size ->delete();
        return ResponseHelper::successMessage('size deleted successfully');
    }
}
