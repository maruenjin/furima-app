<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'user_id'           => User::factory(),
            'product_id'        => Product::factory(),
            'amount'            => $this->faker->numberBetween(500, 50000),
            'payment_method'    => $this->faker->randomElement(['card', 'convenience']),
            'shipping_postcode' => '123-4567',
            'shipping_address'  => '東京都新宿区1-1-2',
            'shipping_building' => 'テストビル201',
        ];
    }

    public function forBuyer(User $user)
    {
        return $this->state(function () use ($user) {
            return ['user_id' => $user->id];
        });
    }

    public function forProduct(Product $product)
    {
        return $this->state(function () use ($product) {
            return [
                'product_id' => $product->id,
                'amount'     => $product->price,
            ];
        });
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            
            $order->product()->update(['buyer_id' => $order->user_id]);
        });
    }
}

