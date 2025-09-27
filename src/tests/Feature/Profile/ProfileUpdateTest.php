<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedUser(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ], $overrides));
    }

    
    public function test_avatar_is_saved_into_public_storage_and_old_is_deleted()
    {
        Storage::fake('public');

        $user = $this->verifiedUser(['avatar_path' => 'avatars/old.png']);
        Storage::disk('public')->put('avatars/old.png', 'old');

       $png1x1 = base64_decode(
    'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGNgYAAAAAMAASsJTYQAAAAASUVORK5CYII='
    );
    $file = UploadedFile::fake()->createWithContent('new.png', $png1x1);

        
        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name'        => '新しい名前',
                'postal_code' => '123-4567',
                'address'     => '東京都千代田区',
                'building'    => '',
                'avatar'      => $file,
            ])
            ->assertRedirect();

        $user->refresh();
        $this->assertNotNull($user->avatar_path);
        $this->assertStringStartsWith('avatars/', $user->avatar_path);
        Storage::disk('public')->assertExists($user->avatar_path);
        Storage::disk('public')->assertMissing('avatars/old.png');
    }
}
