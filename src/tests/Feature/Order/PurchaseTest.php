<?php

namespace Tests\Feature\Order;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

   
    public function test_購入ボタンで購入完了(): void
    {
        $this->withoutExceptionHandling();
        $seller = User::factory()->create();
        $buyer  = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
            'address'           => '東京都品川区1-2-3',
            'postal_code'       => '123-4567',
        ]);
        $product = Product::factory()->for($seller, 'user')->create([
            'price'    => 1200,
            'buyer_id' => null,
        ]);

        $res = $this->actingAs($buyer)->post(route('orders.store', $product), [
            'payment_method' => 'card',
        ]);

        $res->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('orders', [
            'user_id'        => $buyer->id,
            'product_id'     => $product->id,
            'amount'         => 1200,
            'payment_method' => 'card',
        ]);

        $this->assertEquals($buyer->id, $product->fresh()->buyer_id);
    }

    
    public function test_購入後_一覧でSold表示される(): void
    {
         $this->withoutExceptionHandling();
        $seller  = User::factory()->create();
        $buyer   = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);
        $product = Product::factory()->for($seller, 'user')->create([
            'name'     => '購入テスト商品',
            'price'    => 1000,
            'buyer_id' => null,
        ]);

        $this->actingAs($buyer)->post(route('orders.store', $product), [
            'payment_method' => 'card',
        ])->assertRedirect();

        $this->get(route('products.index', ['q' => '購入テスト商品']))
             ->assertOk()
             ->assertSee('Sold', false);
    }

    
    public function test_購入後_マイページ購入一覧に表示される(): void
    {
        $seller  = User::factory()->create();
        $buyer   = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);
        $product = Product::factory()->for($seller, 'user')->create([
            'name'     => 'プロフィール購入商品',
            'price'    => 2000,
            'buyer_id' => null,
        ]);

        $this->actingAs($buyer)->post(route('orders.store', $product), [
            'payment_method' => 'card',
        ])->assertRedirect();

        $this->actingAs($buyer)
             ->get(route('mypage.purchases', ['tab' => 'buy']))
             ->assertOk()
             ->assertSee('プロフィール購入商品', false);
    }
}


