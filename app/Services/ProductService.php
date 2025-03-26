<?php
namespace App\Services;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function getAll(Request $request)
    {
        $size = $request->query("size") ?: 10;
        return Product::with("productCategory")->paginate($size);
    }
    public function create(StoreProductRequest $request)
    {
        $path = "product-images";
        $image = $request->file("image")->store($path);
        $product= Product::create([
            "product_category_id" => $request->product_category_id,
            "name" => $request->name,
            "price" => $request->price,
            "image" => str_replace($path . "/", "", $image),
        ]);
        return $product->load("productCategory");
    }
    public function getById($id)
    {
        return Product::with("productCategory")->findOrFail($id)->first();
    }
    public function update(UpdateProductRequest $request, $id)
    {
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

        return $product;
    }
    public function delete($id)
    {
        $product = Product::findOrFail($id);
        if (Storage::exists("product-images/" . $product->image)) {
            Storage::delete("product-images/" . $product->image);
        }

        $product->delete();
    }
}