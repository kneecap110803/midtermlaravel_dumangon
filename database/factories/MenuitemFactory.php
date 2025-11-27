<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'price' => $this->faker->randomFloat(2, 50, 1000),
            'description' => $this->faker->optional()->paragraph(),
            'category_id' => $this->faker->optional()->randomElement(Category::pluck('id')->toArray()),
            'is_available' => $this->faker->boolean(80),
        ];
    }
}
