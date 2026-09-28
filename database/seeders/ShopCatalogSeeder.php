<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class ShopCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $trees = [
            'Mobile' => ['iPhone', 'Samsung', 'Xiaomi', 'Oppo', 'Vivo'],
            'Accessories' => ['Charger', 'Cable', 'Earphones', 'Covers', 'Glass Protector'],
        ];

        foreach ($trees as $categoryName => $children) {
            $category = Category::query()->firstOrCreate(
                ['name' => $categoryName],
                ['is_active' => true]
            );
            foreach ($children as $name) {
                Subcategory::query()->firstOrCreate(
                    ['category_id' => $category->id, 'name' => $name],
                    ['is_active' => true]
                );
            }
        }
    }
}
