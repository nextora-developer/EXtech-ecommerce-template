<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'icon' => 'categories/accessories.jpg', // 你到时放 storage 或 public/images 里
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Home & Living',
                'slug' => 'home-living',
                'icon' => 'categories/home.jpg',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Beauty & Personal Care',
                'slug' => 'beauty-personal-care',
                'icon' => 'categories/beauty.jpg',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Gadgets',
                'slug' => 'gadgets',
                'icon' => 'categories/gadgets.jpg',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Sports & Outdoors',
                'slug' => 'sports-outdoors',
                'icon' => 'categories/sports.jpg',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $data) {
            Category::create($data);
        }
    }
}
