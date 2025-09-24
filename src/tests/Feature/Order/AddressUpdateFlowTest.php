<?php

namespace Tests\Feature\Order;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;

class AddressUpdateFlowTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_送付先変更が購入画面に反映される(): void
    {
        $seller = User::factory()->create();
        $buyer  = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
            'postal_code' => '111-1111',
            'address'     => '旧住所',
            'building'    => '旧ビル',
        ]);
        $product = Product::factory()->for($seller, 'user')->create();

    
        $this->actingAs($buyer)->post(route('profile.address.update', $product), [
            'postal_code' => '1234567',
            'address'  => '東京都渋谷区テスト1-2-3',
            'building' => 'テストビル101',
        ])->assertRedirect(route('orders.create', $product));

        
        $this->actingAs($buyer)->get(route('orders.create', $product))
             ->assertOk()
             ->assertSee('〒123-4567', false)
             ->assertSee('東京都渋谷区テスト1-2-3', false)
             ->assertSee('テストビル101', false);
    }

   
    public function test_購入時に送付先が注文に保存される(): void
    {
        $seller = User::factory()->create();
        $buyer  = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
            'postal_code' => '987-6543',
            'address'     => '東京都品川区1-2-3',
            'building'    => 'ABCビル2F',
        ]);
        $product = Product::factory()->for($seller, 'user')->create([
            'price' => 3000,
        ]);

        $this->actingAs($buyer)->post(route('orders.store', $product), [
            'payment_method' => 'card',
        ])->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('orders', [
            'user_id'            => $buyer->id,
            'product_id'         => $product->id,
            'amount'             => 3000,
            'payment_method'     => 'card',
            'shipping_postcode'  => '987-6543',
            'shipping_address'   => '東京都品川区1-2-3',
            'shipping_building'  => 'ABCビル2F',
        ]);
    }
}
