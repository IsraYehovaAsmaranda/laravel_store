<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            "size" => "numeric"
        ]);

        $size = $request->size ?: 10;
        return response()->json([
            "status" => "success",
            "message" => "Product categories fetched successfully",
            "data" => ProductCategory::paginate($size)
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductCategoryRequest $request)
    {
        $productCategory = ProductCategory::create($request->all());
        return response()->json([
            "status" => "success",
            "message" => "Product category created successfully",
            "data" => $productCategory
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $productCategory = ProductCategory::findOrFail($id);
            return response()->json([
                "status" => "success",
                "message" => "Product category fetched successfully",
                "data" => $productCategory
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Product category not found"
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductCategoryRequest $request, $id)
    {
        try {
            $productCategory = ProductCategory::findOrFail($id);
            $productCategory->name = $request->name;
            $productCategory->save();

            return response()->json([
                "status" => "success",
                "message" => "Product category updated successfully",
                "data" => $productCategory
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Product category not found"
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $productCategory = ProductCategory::findOrFail($id);
            $productCategory->delete();

            return response()->json([
                "status" => "success",
                "message" => "Product category deleted successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Product category not found"
            ], 404);
        }
    }
}
