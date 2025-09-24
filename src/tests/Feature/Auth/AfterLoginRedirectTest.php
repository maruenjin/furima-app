<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class AfterLoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_認証済みでプロフィール未完了ならプロフィール編集へ(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => false,
        ]);

        $res = $this->actingAs($user)->get(route('after-login'));
        $res->assertRedirect(route('profile.edit'));
    }

    public function test_認証済みでプロフィール完了なら商品一覧へ(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

        $res = $this->actingAs($user)->get(route('after-login'));
        $res->assertRedirect(route('products.index'));
    }
}

