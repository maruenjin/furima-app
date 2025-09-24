<?php

namespace Tests\Feature\Product;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品詳細に必要情報が表示される(): void
    {
        $seller  = User::factory()->create();
        $product = Product::factory()->for($seller, 'user')->create([
            'name'        => 'テスト商品',
            'brand'       => 'テストブランド',
            'price'       => 12345,
            'description' => '説明テキスト',
            'categories'  => ['レディース','アクセサリー'],
            'condition'   => '新品未使用',
        ]);

        $res = $this->get(route('products.show', $product));
        $res->assertOk()
            ->assertSee('テスト商品')
            ->assertSee('テストブランド')
            ->assertSee('¥12,345')
            ->assertSee('説明テキスト')
            ->assertSee('新品未使用')
            ->assertSee('レディース')
            ->assertSee('アクセサリー');
    }
}


