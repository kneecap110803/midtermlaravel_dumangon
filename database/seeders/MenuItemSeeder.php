<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        $items = [
            ['name' => 'Caesar Salad', 'price' => 199.00, 'description' => 'Crisp romaine, creamy dressing.', 'category' => 'Appetizers', 'is_available' => true],
            ['name' => 'Grilled Chicken', 'price' => 349.00, 'description' => 'Served with seasonal veggies.', 'category' => 'Main Courses', 'is_available' => true],
            ['name' => 'Chocolate Lava Cake', 'price' => 249.00, 'description' => 'Warm chocolate center.', 'category' => 'Desserts', 'is_available' => false],
            ['name' => 'Iced Latte', 'price' => 149.00, 'description' => null, 'category' => 'Beverages', 'is_available' => true],
            ['name' => 'Chef’s Special Pasta', 'price' => 399.00, 'description' => 'Daily special.', 'category' => 'Specials', 'is_available' => true],
        ];

        foreach ($items as $i) {
            $catId = optional($categories->firstWhere('name', $i['category']))->id;
            MenuItem::firstOrCreate(
                ['name' => $i['name']],
                [
                    'price' => $i['price'],
                    'description' => $i['description'],
                    'category_id' => $catId,
                    'is_available' => $i['is_available'],
                ]
            );
        }
    }
}
