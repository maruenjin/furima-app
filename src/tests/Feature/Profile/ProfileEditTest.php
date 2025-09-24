<?php

namespace Tests\Feature\Profile;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_編集フォームに初期値が表示される(): void
    {
        $user = User::factory()->verified()->create([
            'name'        => '太郎',
            'postal_code' => '123-4567',
            'address'     => '東京都新宿区1-2-3',
            'building'    => 'ABCビル',
        ]);
        $res = $this->actingAs($user)->get(route('profile.edit'));
        $res->assertOk()
            ->assertSee('太郎')
            ->assertSee('123-4567')
            ->assertSee('東京都新宿区1-2-3')
            ->assertSee('ABCビル');
    }
}
