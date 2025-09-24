<?php

namespace Tests\Feature\Product;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\User;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_全商品が表示される(): void
    {
        $products = Product::factory()->count(3)->create();

        $res = $this->get(route('products.index'));
        $res->assertOk();

        foreach ($products as $p) {
            $res->assertSee($p->name, false);
        }
    }

    public function test_購入済みはSoldバッジが出る(): void
    {
        $sold = Product::factory()->sold()->create();

        $res = $this->get(route('products.index'));
        $res->assertOk()->assertSee('Sold', false)
            ->assertSee($sold->name, false);
    }

    public function test_自分の出品は一覧に出ない(): void
    {
        $me = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

        $mine  = Product::factory()->for($me, 'user')->create(['name' => '自分の出品']);
        $other = Product::factory()->create(['name' => '他人の出品']);

        $res = $this->actingAs($me)->get(route('products.index'));
        $res->assertOk()
            ->assertSee('他人の出品', false)
            ->assertDontSee('自分の出品', false);
    }

    public function test_商品名で部分一致検索できる(): void
    {
        $a = Product::factory()->create(['name' => 'ナイキ エア']);
        $b = Product::factory()->create(['name' => 'アディダス スタンスミス']);

        $res = $this->get(route('products.index', ['q' => 'ナイキ']));
        $res->assertOk()
            ->assertSee('ナイキ エア', false)
            ->assertDontSee('アディダス スタンスミス', false);
    }

 public function test_検索語がマイリストでも保持される(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

        $res = $this->actingAs($user)
            ->get(route('products.index', ['tab' => 'mylist', 'q' => 'バッグ']));

        $res->assertOk()
            ->assertSee('name="q"', false)
            ->assertSee('value="バッグ"', false);
    }
}


