<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductCategory::factory()->create([
            "name" => "Electronics"
        ]);
        ProductCategory::factory()->create([
            "name" => "Clothing"
        ]);
        ProductCategory::factory()->create([
            "name" => "Furniture"
        ]);
        ProductCategory::factory()->create([
            "name" => "Books"
        ]);
    }
}
