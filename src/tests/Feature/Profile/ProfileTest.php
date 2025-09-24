<?php

namespace Tests\Feature\Profile;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_プロフィール情報が表示される(): void
    {
        
        $user = User::factory()->create([
            'name'              => '山田太郎',
            'email_verified_at' => now(),
            'profile_completed' => true,
            'postal_code'       => '123-4567',
            'address'           => '東京都渋谷区テスト1-2-3',
            'building'          => 'テストビル101',
            'avatar_path'       => null,
        ]);

        $this->actingAs($user)
             ->get(route('mypage.purchases')) 
             ->assertOk()
             ->assertSee('山田太郎')
             ->assertSee('プロフィールを編集');
    }
}


