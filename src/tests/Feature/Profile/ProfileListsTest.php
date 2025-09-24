<?php

namespace Tests\Feature\Profile;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileListsTest extends TestCase
{
    use RefreshDatabase;

    public function test_出品した商品と購入した商品が表示される(): void
    {
        $user = User::factory()->verified()->create();
        $sell = Product::factory()->for($user, 'user')->create(['name' => '私の出品']);
        $buyP = Product::factory()->create(['name' => '私の購入']);
        Order::factory()->create(['user_id' => $user->id, 'product_id' => $buyP->id]);

        $res = $this->actingAs($user)->get(route('mypage.purchases', ['tab' => 'sell']));
        $res->assertOk()->assertSee('私の出品');

        $res = $this->actingAs($user)->get(route('mypage.purchases', ['tab' => 'buy']));
        $res->assertOk()->assertSee('私の購入');
    }
}
