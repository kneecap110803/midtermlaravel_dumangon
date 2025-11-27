<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $preset = [
            ['name' => 'Appetizers', 'description' => 'Start your meal right.'],
            ['name' => 'Main Courses', 'description' => 'Hearty and filling.'],
            ['name' => 'Desserts', 'description' => 'Sweet finishes.'],
            ['name' => 'Beverages', 'description' => 'Hot and cold drinks.'],
            ['name' => 'Specials', 'description' => 'Limited-time offerings.'],
        ];

        foreach ($preset as $c) {
            Category::firstOrCreate(['name' => $c['name']], ['description' => $c['description']]);
        }
    }
}

