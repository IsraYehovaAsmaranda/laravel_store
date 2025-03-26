<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $size = $request->query("size") ?: 10;
        $products = Product::with("productCategory")->paginate($size);
        return response()->json([
            "status" => "success",
            "message" => "Products fetched successfully",
            "data" => $products
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

        $path = "product-images";
        $image = $request->file("image")->store($path);
        $product = Product::create([
            "product_category_id" => $request->product_category_id,
            "name" => $request->name,
            "price" => $request->price,
            "image" => str_replace($path . "/", "", $image),
        ]);

        return response()->json([
            "status" => "success",
            "message" => "Product created successfully",
            "data" => $product->with("productCategory")
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id)->with("productCategory")->first();
            return response()->json([
                "status" => "success",
                "message" => "Product fetched successfully",
                "data" => $product
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => $th->getMessage(),
            ]);
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
            $product = Product::with("productCategory")->findOrFail($id);
            if (Storage::exists("product-images/" . $product->image)) {
                Storage::delete("product-images/" . $product->image);
            }

            $path = "product-images";
            $image = $request->file("image")->store($path);

            $product->name = $request->name;
            $product->price = $request->price;
            $product->image = str_replace("product-images/", "", $image);
            $product->save();

            return response()->json([
                "status" => "success",
                "message" => "Product updated successfully",
                "data" => $product
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => $th->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            if (Storage::exists("product-images/" . $product->image)) {
                Storage::delete("product-images/" . $product->image);
            }

            $product->delete();
            return response()->json([
                "status" => "success",
                "message" => "Product deleted successfully",
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => $th->getMessage(),
            ]);
        }
    }
}
