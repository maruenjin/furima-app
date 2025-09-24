<?php

namespace Tests\Feature\Order;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;

class PurchaseFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_購入すると注文が作成され一覧へリダイレクト(): void
    {
        $seller = User::factory()->create();
        $buyer  = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
            'postal_code' => '123-4567',
            'address' => '東京都新宿区1-1',
            'building' => 'テスト101',
        ]);

        $product = Product::factory()->for($seller, 'user')->create([
            'price' => 3000,
            'buyer_id' => null,
        ]);

        $res = $this->actingAs($buyer)
            ->post(route('orders.store', $product), ['payment_method' => 'card']);

        $res->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('orders', [
            'user_id' => $buyer->id,
            'product_id' => $product->id,
            'amount' => 3000,
            'payment_method' => 'card',
            'shipping_postcode' => '123-4567',
            'shipping_address' => '東京都新宿区1-1',
            'shipping_building' => 'テスト101',
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'buyer_id' => $buyer->id,
        ]);
    }

    
 

    public function test_売り切れ商品は購入できない(): void
    {
        $seller   = User::factory()->create();
        $buyer    = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);
        $another  = User::factory()->create();

        $product = Product::factory()->for($seller, 'user')->create([
            'buyer_id' => $another->id,
        ]);

        $res = $this->actingAs($buyer)
            ->post(route('orders.store', $product), ['payment_method' => 'card']);

        $res->assertRedirect(route('products.show', $product));
        $res->assertSessionHasErrors();
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_購入後は一覧でSold表示になる(): void
    {
        
        $seller = User::factory()->create();
        $buyer  = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
            'postal_code' => '123-4567',
            'address' => '東京都新宿区1-1',
        ]);
        $product = Product::factory()->for($seller, 'user')->create([
            'name' => 'テストスニーカー',
            'price' => 1000,
        ]);

        $this->actingAs($buyer)
            ->post(route('orders.store', $product), ['payment_method' => 'card'])
            ->assertRedirect(route('products.index'));

        $this->get(route('products.index'))
            ->assertSee('テストスニーカー')
            ->assertSee('Sold');
    }

    public function test_購入後はマイページ購入した商品に出る(): void
    {
        $seller = User::factory()->create();
        $buyer  = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
            'postal_code' => '123-4567',
            'address' => '東京都新宿区1-1',
        ]);
        $product = Product::factory()->for($seller, 'user')->create([
            'name' => '購入済商品',
            'price' => 2000,
        ]);

        $this->actingAs($buyer)
            ->post(route('orders.store', $product), ['payment_method' => 'card'])
            ->assertRedirect(route('products.index'));

        $this->get(route('mypage.purchases', ['tab' => 'buy']))
            ->assertSee('購入済商品');
    }
}

