<?php

namespace Tests\Feature\Product;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_必要項目で保存できる(): void
    {
        $user = User::factory()->verified()->create([
            'postal_code' => '123-4567', 'address' => '東京都', 'building' => 'A',
        ]);

        $res = $this->actingAs($user)->post(route('products.store'), [
            'name'        => '出品A',
            'brand'       => 'BR',
            'price'       => 2000,
            'description' => '説明',
            'condition'   => '新品未使用',
            'categories'  => ['レディース','トップス'],
            
        ]);

        $res->assertRedirect(); 
        $this->assertDatabaseHas('products', [
            'name' => '出品A', 'brand' => 'BR', 'price' => 2000,
        ]);
    }
}


