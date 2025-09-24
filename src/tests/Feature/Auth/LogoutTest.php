<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログアウトできる(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

        $this->actingAs($user);

        $res = $this->post(route('logout'));

        // Fortify のデフォルトは '/' へ
        $res->assertRedirect('/');

        $this->assertGuest();
    }
}
