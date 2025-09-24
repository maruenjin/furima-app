<?php

namespace Tests\Feature\Product;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    /** 未ログインはログイン画面へ */
    public function test_未ログインはログインへリダイレクト(): void
    {
        $seller  = User::factory()->create();
        $product = Product::factory()->for($seller, 'user')->create();

        $res = $this->post(route('products.like', $product));
        $res->assertRedirect(route('login')); 
        $this->assertDatabaseMissing('product_likes', [
            'product_id' => $product->id,
        ]);
    }

    /** いいね 解除 */
    public function test_いいねと解除がトグルできる(): void
    {
        $seller = User::factory()->create();
        $user   = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);
        $product = Product::factory()->for($seller, 'user')->create();

        // 1回目：いいね
        $res1 = $this->actingAs($user)->post(route('products.like', $product));
        $res1->assertRedirect(); 
        $this->assertDatabaseHas('product_likes', [
            'product_id' => $product->id,
            'user_id'    => $user->id,
        ]);

        // 2回目：解除
        $res2 = $this->actingAs($user)->post(route('products.like', $product));
        $res2->assertRedirect();
        $this->assertDatabaseMissing('product_likes', [
            'product_id' => $product->id,
            'user_id'    => $user->id,
        ]);
    }

   
    public function test_同じ商品を連打しても二重登録されない(): void
    {
        $seller = User::factory()->create();
        $user   = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);
        $product = Product::factory()->for($seller, 'user')->create();

        $this->actingAs($user)->post(route('products.like', $product));
        $this->assertDatabaseCount('product_likes', 1);

        // もう一度押すと解除
        $this->actingAs($user)->post(route('products.like', $product));
        $this->assertDatabaseCount('product_likes', 0);
    }
}

