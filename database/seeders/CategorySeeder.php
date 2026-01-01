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
                'icon' => 'categories/accessories.jpg',
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
            [
                'name' => 'Kitchen & Dining',
                'slug' => 'kitchen-dining',
                'icon' => 'categories/kitchen.jpg',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Pet Supplies',
                'slug' => 'pet-supplies',
                'icon' => 'categories/pets.jpg',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Toys & Games',
                'slug' => 'toys-games',
                'icon' => 'categories/toys.jpg',
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Stationery & Office',
                'slug' => 'stationery-office',
                'icon' => 'categories/stationery.jpg',
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Automotive',
                'slug' => 'automotive',
                'icon' => 'categories/automotive.jpg',
                'is_active' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'Baby & Kids',
                'slug' => 'baby-kids',
                'icon' => 'categories/kids.jpg',
                'is_active' => true,
                'sort_order' => 11,
            ],
            [
                'name' => 'Health & Wellness',
                'slug' => 'health-wellness',
                'icon' => 'categories/health.jpg',
                'is_active' => true,
                'sort_order' => 12,
            ],
            [
                'name' => 'Fashion & Apparel',
                'slug' => 'fashion-apparel',
                'icon' => 'categories/fashion.jpg',
                'is_active' => true,
                'sort_order' => 13,
            ],
            [
                'name' => 'Travel & Luggage',
                'slug' => 'travel-luggage',
                'icon' => 'categories/travel.jpg',
                'is_active' => true,
                'sort_order' => 14,
            ],
            [
                'name' => 'Groceries & Food',
                'slug' => 'groceries-food',
                'icon' => 'categories/food.jpg',
                'is_active' => true,
                'sort_order' => 15,
            ],

        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(
                ['slug' => $data['slug']], // 唯一条件（判断已存在就更新）
                [
                    'name'       => $data['name'],
                    'icon'       => $data['icon'],
                    'is_active'  => $data['is_active'],
                    'sort_order' => $data['sort_order'],
                ]
            );
        }
    }
}
