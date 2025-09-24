<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\User;


class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'name'        => $this->faker->words(2, true),
            'brand'       => $this->faker->company(),
            'price'       => $this->faker->numberBetween(500, 50000),
            'description' => $this->faker->sentence(),
            'condition'   => '新品未使用',
            'image_path'  => null,
            'categories'  => ['レディース'],
            'buyer_id'    => null,
        ];
    }

    public function sold(): self
    {
        return $this->state(fn () => ['buyer_id' => User::factory()]);
    }
}

