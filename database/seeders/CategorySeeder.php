<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Gadgets and electronic devices'],
            ['name' => 'Fashion', 'description' => 'Clothing and accessories'],
            ['name' => 'Home & Kitchen', 'description' => 'Everything for your home'],
            ['name' => 'Sports', 'description' => 'Sports and outdoor gear'],
        ];

        foreach ($categories as $i => $category) {
            Category::firstOrCreate(
                ['slug' => Str::slug($category['name'])],
                $category + ['is_active' => true, 'sort_order' => $i + 1]
            );
        }
    }
}
