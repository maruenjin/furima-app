<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** 日本語メッセージを出す */
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.locale' => 'ja']);
    }

    
    private const MSG_NAME_REQUIRED      = 'お名前を入力してください';
    private const MSG_EMAIL_REQUIRED     = 'メールアドレスを入力してください';
    private const MSG_PASSWORD_REQUIRED  = 'パスワードを入力してください';
    private const MSG_PASSWORD_MIN       = 'パスワードは8文字以上で入力してください';
    private const MSG_PASSWORD_CONFIRMED = 'パスワードと一致しません';

    /** 名前が未入力でエラー */
    public function test_名前未入力で日本語メッセージ(): void
    {
        $res = $this->from('/register')->post('/register', [
            'name' => '',
            'email' => 'a@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $res->assertRedirect('/register')
            ->assertSessionHasErrors(['name']);

        $this->followRedirects($res)
             ->assertSee(self::MSG_NAME_REQUIRED, false);
    }

    /** メールが未入力でエラー */
    public function test_メール未入力で日本語メッセージ(): void
    {
        $res = $this->from('/register')->post('/register', [
            'name' => '山田',
            'email' => '',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $res->assertRedirect('/register')
            ->assertSessionHasErrors(['email']);

        $this->followRedirects($res)
             ->assertSee(self::MSG_EMAIL_REQUIRED, false);
    }

    /** パスワードが未入力でエラー */
    public function test_パスワード未入力で日本語メッセージ(): void
    {
        $res = $this->from('/register')->post('/register', [
            'name' => '山田',
            'email' => 'a@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $res->assertRedirect('/register')
            ->assertSessionHasErrors(['password']);

        $this->followRedirects($res)
             ->assertSee(self::MSG_PASSWORD_REQUIRED, false);
    }

    /** パスワードが7文字以下でエラー */
    public function test_パスワード7文字以下で日本語メッセージ(): void
    {
        $seven = 'secret7'; 
        $res = $this->from('/register')->post('/register', [
            'name' => '山田',
            'email' => 'a@example.com',
            'password' => $seven,
            'password_confirmation' => $seven,
        ]);

        $res->assertRedirect('/register')
            ->assertSessionHasErrors(['password']);

        $this->followRedirects($res)
             ->assertSee(self::MSG_PASSWORD_MIN, false);
    }

    /** 確認用と不一致でエラー */
    public function test_パスワード確認不一致で日本語メッセージ(): void
    {
        $res = $this->from('/register')->post('/register', [
            'name' => '山田',
            'email' => 'a@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'different',
        ]);

        $res->assertRedirect('/register')
            ->assertSessionHasErrors(['password']);

        $this->followRedirects($res)
             ->assertSee(self::MSG_PASSWORD_CONFIRMED, false);
    }

    /** 正常系：会員情報が登録され after-login に遷移 */
   public function test_全項目OKで登録成功_メール認証誘導へ(): void
{
    $res = $this->post('/register', [
        'name' => '山田',
        'email' => 'a@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]);

    // ← ここを verification.notice に
    $res->assertRedirect(route('verification.notice'));

    $this->assertDatabaseHas('users', [
        'email' => 'a@example.com',
        'name'  => '山田',
    ]);
    }
}





