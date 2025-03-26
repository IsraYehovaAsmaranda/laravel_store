<?php
namespace App\Services;

use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryService
{
    public function getAll(Request $request)
    {
        $size = $request->size ?: 10;
        return ProductCategory::paginate($size);
    }

    public function create(StoreProductCategoryRequest $request)
    {
        $productCategory = ProductCategory::create([
            "name" => $request->name
        ]);

        return $productCategory;
    }
    public function getById($id)
    {
        $productCategory = ProductCategory::findOrFail($id);
        return $productCategory;
    }

    public function update(UpdateProductCategoryRequest $request, $id)
    {
        $productCategory = $this->getById($id);
        $productCategory->name = $request->name;
        $productCategory->save();

        return $productCategory;
    }
    public function delete($id) {
        $productCategory = ProductCategory::findOrFail($id);
        $productCategory->delete();
    }
}