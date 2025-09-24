<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    
    protected function makeVerifiedUser(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'email_verified_at' => now(),
            'profile_completed' => true,
            'name'             => 'テスト次郎',
            'postal_code'      => '123-4567',
            'address'          => '東京都新宿区1-1-2',
            'building'         => 'テストビル201',
        ], $overrides));
    }

}
