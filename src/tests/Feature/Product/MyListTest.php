<?php

namespace Tests\Feature\Product;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;

class MyListTest extends TestCase
{
    use RefreshDatabase;

   
    public function test_ログイン時_いいねした商品だけが表示される(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

        
        $seller = User::factory()->create();
        $liked   = Product::factory()->for($seller, 'user')->create(['name' => 'いいねした商品']);
        $unliked = Product::factory()->for($seller, 'user')->create(['name' => 'いいねしてない商品']);

        
        $liked->likes()->attach($user->id);

        $res = $this->actingAs($user)
            ->get(route('products.index', ['tab' => 'mylist']));

        $res->assertOk()
            ->assertSee('いいねした商品', false)
            ->assertDontSee('いいねしてない商品', false);
    }

   
    public function test_ログイン時_購入済み商品はSold表示(): void
    {
        $user   = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);
        $seller = User::factory()->create();

        
        $sold = Product::factory()->for($seller, 'user')->create([
            'name'     => '購入済み商品',
            'buyer_id' => $user->id,
        ]);
        $sold->likes()->attach($user->id);

        $res = $this->actingAs($user)
            ->get(route('products.index', ['tab' => 'mylist']));

        $res->assertOk()
            ->assertSee('購入済み商品', false)
            ->assertSee('Sold', false); 
    }

    
    public function test_未認証は何も表示されない(): void
    {
       
        $seller = User::factory()->create();
        $p1 = Product::factory()->for($seller, 'user')->create(['name' => '商品A']);
        $p2 = Product::factory()->for($seller, 'user')->create(['name' => '商品B']);

        $res = $this->get(route('products.index', ['tab' => 'mylist']));
        $res->assertOk()
            ->assertDontSee('商品A', false)
            ->assertDontSee('商品B', false);
    }
}
