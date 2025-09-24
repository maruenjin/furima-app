<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.locale' => 'ja']); 
    }

    private const MSG_EMAIL_REQUIRED    = 'メールアドレスを入力してください';
    private const MSG_PASSWORD_REQUIRED = 'パスワードを入力してください';
    private const MSG_LOGIN_FAILED      = 'ログイン情報が登録されていません'; 

    /** メール未入力でエラー */
    public function test_メール未入力で日本語メッセージ(): void
    {
        $res = $this->from('/login')->post(route('login.attempt'), [
            'email' => '',
            'password' => 'secret123',
        ]);

        $res->assertRedirect('/login')->assertSessionHasErrors(['email']);

        $this->followRedirects($res)->assertSee(self::MSG_EMAIL_REQUIRED, false);
        $this->assertGuest();
    }

    /** パスワード未入力でエラー */
    public function test_パスワード未入力で日本語メッセージ(): void
    {
        $res = $this->from('/login')->post(route('login.attempt'), [
            'email' => 'a@example.com',
            'password' => '',
        ]);

        $res->assertRedirect('/login')->assertSessionHasErrors(['password']);

        $this->followRedirects($res)->assertSee(self::MSG_PASSWORD_REQUIRED, false);
        $this->assertGuest();
    }

    /** 入力情報が間違っている場合のエラー */
    public function test_未登録情報でエラー_日本語メッセージ(): void
    {
        $res = $this->from('/login')->post(route('login.attempt'), [
            'email' => 'noone@example.com',
            'password' => 'wrongpass',
        ]);

        
        $res->assertRedirect('/login');

        // 画面に「ログイン情報が登録されていません」が出る想定
        $this->followRedirects($res)->assertSee(self::MSG_LOGIN_FAILED, false);

        $this->assertGuest();
    }

    /** 正しい情報でログイン成功 */
    public function test_正しい情報でログイン成功(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret123'),
            'email_verified_at' => now(),   
            'profile_completed' => true,   
        ]);

        $res = $this->post(route('login.attempt'), [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        
        $res->assertRedirect(route('after-login'));
        $this->assertAuthenticatedAs($user);
    }
}


