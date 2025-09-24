<?php

namespace Tests\Feature\Order;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentMethodReflectTest extends TestCase
{
    use RefreshDatabase;

    public function test_old入力の支払い方法ラベルが表示される(): void
    {
        $user    = User::factory()->verified()->create();
        $product = Product::factory()->create(['price' => 3000]);

        $res = $this->actingAs($user)
            ->withSession(['_old_input' => ['payment_method' => 'card']])
            ->get(route('orders.create', $product));

        $res->assertOk()->assertSee('カード支払い');
    }
}
