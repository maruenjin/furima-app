<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductListingTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedUser(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ], $overrides));
    }

    public function test_recommended_excludes_my_own_products_when_logged_in(): void
    {
        $me    = $this->verifiedUser();
        $other = $this->verifiedUser();

        Product::factory()->create(['user_id' => $me->id, 'name' => '自分の出品']);
        Product::factory()->create(['user_id' => $other->id, 'name' => '他人の出品']);

        $this->actingAs($me)
            ->get(route('products.index', ['tab' => 'recommended']))
            ->assertOk()
            ->assertDontSee('自分の出品')
            ->assertSee('他人の出品');
    }

    public function test_mylist_shows_only_liked_products_and_respects_search_query(): void
    {
        $user = $this->verifiedUser();

        $p1 = Product::factory()->create(['name' => '赤いバッグ']);
        $p2 = Product::factory()->create(['name' => '青いバッグ']);

        DB::table('product_likes')->insert([
            'user_id' => $user->id,
            'product_id' => $p1->id,
        ]);

        $this->actingAs($user)
            ->get(route('products.index', ['tab' => 'mylist', 'q' => 'バッグ']))
            ->assertOk()
            ->assertSee('赤いバッグ')
            ->assertDontSee('青いバッグ');
    }

    public function test_search_query_is_kept_across_tabs_and_pagination(): void
    {
        $user = $this->verifiedUser();

        Product::factory()->count(15)->sequence(
            ['name' => '赤いバッグ 1'],
            ['name' => '赤いバッグ 2']
        )->create();

        $res = $this->actingAs($user)
            ->get(route('products.index', ['tab' => 'recommended', 'q' => '赤いバッグ']))
            ->assertOk();

        $res->assertSee('?q=' . urlencode('赤いバッグ'));

        $this->actingAs($user)
            ->get(route('products.index', ['tab' => 'mylist', 'q' => '赤いバッグ']))
            ->assertOk();
    }
}

