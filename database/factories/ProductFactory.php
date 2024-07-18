<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $name=$this->faker->name(),
            'slug' => Str::slug($name),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'discount' => $this->faker->randomFloat(2, 0, 100),
            'thumbnail' => $this->faker->imageUrl(),
            'status' => $this->faker->randomElement(['publish', 'unpublish']),
        ];
    }
}
