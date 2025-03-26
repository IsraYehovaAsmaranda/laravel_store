<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            "size" => "numeric"
        ]);

        return response()->json([
            "status" => "success",
            "message" => "Products fetched successfully",
            "data" => $this->productService->getAll($request)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        if (!$request->hasFile("image")) {
            return response()->json([
                "status" => "error",
                "message" => "Image is required",
            ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "Product created successfully",
            "data" => $this->productService->create($request)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            return response()->json([
                "status" => "success",
                "message" => "Product fetched successfully",
                "data" => $this->productService->getById($id)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Product Not Found",
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        if (!$request->hasFile("image")) {
            return response()->json([
                "status" => "error",
                "message" => "Image is required",
            ]);
        }

        try {
            return response()->json([
                "status" => "success",
                "message" => "Product updated successfully",
                "data" => $this->productService->update($request, $id)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Product Not Found",
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->productService->delete($id);
            return response()->json([
                "status" => "success",
                "message" => "Product deleted successfully",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Product Not Found",
            ], 404);
        }
    }
}
