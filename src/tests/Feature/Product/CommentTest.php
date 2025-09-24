<?php

namespace Tests\Feature\Product;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** ゲストはコメント投稿できない */
    public function test_ゲストはコメント投稿できない(): void
    {
        $seller  = User::factory()->create();
        $product = Product::factory()->for($seller, 'user')->create();

        $res = $this->post(route('products.comments.store', $product), [
            'body' => 'ゲストコメント',
        ]);

        $res->assertRedirect(route('login'));
    }

    /** ログイン済みならコメント投稿できる */
    public function test_ログイン済みはコメント投稿できる(): void
    {
        $seller = User::factory()->create();
        $user   = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);
        $product = Product::factory()->for($seller, 'user')->create();

        $res = $this->actingAs($user)->post(route('products.comments.store', $product), [
            'body' => 'とても素敵ですね！',
        ]);

        $res->assertRedirect(); 
        $this->assertDatabaseHas('product_comments', [
            'product_id' => $product->id,
            'user_id'    => $user->id,
            'body'       => 'とても素敵ですね！',
        ]);
    }

    /** バリデーション：未入力/255超はエラー */
    public function test_バリデーション_未入力や255超はエラー(): void
    {
        $seller = User::factory()->create();
        $user   = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);
        $product = Product::factory()->for($seller, 'user')->create();

        // 未入力
        $res1 = $this->actingAs($user)->from(route('products.show', $product))
            ->post(route('products.comments.store', $product), ['body' => '']);
        $res1->assertRedirect(route('products.show', $product))
             ->assertSessionHasErrors(['body']);

        // 256文字
        $tooLong = str_repeat('あ', 256);
        $res2 = $this->actingAs($user)->from(route('products.show', $product))
            ->post(route('products.comments.store', $product), ['body' => $tooLong]);
        $res2->assertRedirect(route('products.show', $product))
             ->assertSessionHasErrors(['body']);
    }
}


