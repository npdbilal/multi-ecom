<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Wireless Headphones',
                'category' => 'electronics',
                'price' => 79.99,
                'compare_price' => 99.99,
                'stock' => 50,
                'short_description' => 'Premium noise-cancelling wireless headphones.',
                'is_featured' => true,
            ],
            [
                'name' => 'Smart Watch',
                'category' => 'electronics',
                'price' => 149.99,
                'compare_price' => null,
                'stock' => 30,
                'short_description' => 'Track fitness, calls and notifications.',
                'is_featured' => true,
            ],
            [
                'name' => 'Cotton T-Shirt',
                'category' => 'fashion',
                'price' => 19.99,
                'compare_price' => 29.99,
                'stock' => 200,
                'short_description' => 'Comfortable 100% cotton t-shirt.',
                'is_featured' => true,
            ],
            [
                'name' => 'Denim Jacket',
                'category' => 'fashion',
                'price' => 59.99,
                'compare_price' => null,
                'stock' => 40,
                'short_description' => 'Classic denim jacket, all seasons.',
                'is_featured' => false,
            ],
            [
                'name' => 'Coffee Maker',
                'category' => 'home-kitchen',
                'price' => 89.99,
                'compare_price' => 119.99,
                'stock' => 25,
                'short_description' => 'Brew barista-quality coffee at home.',
                'is_featured' => true,
            ],
            [
                'name' => 'Yoga Mat',
                'category' => 'sports',
                'price' => 24.99,
                'compare_price' => null,
                'stock' => 100,
                'short_description' => 'Non-slip exercise yoga mat.',
                'is_featured' => false,
            ],
        ];

        foreach ($products as $item) {
            $category = Category::where('slug', $item['category'])->first();

            Product::firstOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'category_id' => $category?->id,
                    'name' => $item['name'],
                    'description' => $item['short_description'].' Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'short_description' => $item['short_description'],
                    'price' => $item['price'],
                    'compare_price' => $item['compare_price'],
                    'sku' => 'SKU-'.strtoupper(Str::random(8)),
                    'stock' => $item['stock'],
                    'is_active' => true,
                    'is_featured' => $item['is_featured'],
                ]
            );
        }
    }
}
