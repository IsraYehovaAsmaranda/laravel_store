<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\Services\ProductCategoryService;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    private ProductCategoryService $productCategoryService;

    public function __construct(ProductCategoryService $productCategoryService)
    {
        $this->productCategoryService = $productCategoryService;
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
            "message" => "Product categories fetched successfully",
            "data" => $this->productCategoryService->getAll($request)
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductCategoryRequest $request)
    {

        return response()->json([
            "status" => "success",
            "message" => "Product category created successfully",
            "data" => $this->productCategoryService->create($request)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            return response()->json([
                "status" => "success",
                "message" => "Product category fetched successfully",
                "data" => $this->productCategoryService->getById($id)
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
            return response()->json([
                "status" => "success",
                "message" => "Product category updated successfully",
                "data" => $this->productCategoryService->update($request, $id)
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
            $this->productCategoryService->delete($id);
            return response()->json([
                "status" => "success",
                "message" => "Product category deleted successfully"
            ]);
        } catch (\Throwable $th) {
            $message = isset($th->errorInfo) ? "Cannot delete this product category. This product category is used in a product" : "Product not found";
            $errorCode = isset($th->errorInfo) ? 409 : 404;
            return response()->json([
                "status" => "error",
                "message" => $message
            ], $errorCode);
        }
    }
}
