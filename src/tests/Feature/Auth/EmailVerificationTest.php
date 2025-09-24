<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail; 

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.locale' => 'ja']);
    }

    /**  会員登録後、認証メールが送信される */
    public function test_登録直後に認証メールが送信される(): void
    {
        Notification::fake();

        $this->post('/register', [
            'name' => '山田',
            'email' => 'mail@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])->assertRedirect(route('verification.notice'));

        $user = User::whereEmail('mail@example.com')->firstOrFail();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /**  認証誘導画面に「認証はこちらから」ボタンがあり、再送もできる */
    public function test_認証誘導画面の表示_再送できる(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // 誘導画面が見える
        $this->actingAs($user)
             ->get(route('verification.notice'))
             ->assertOk()
             ->assertSee('認証はこちらから', false); 

        // 再送できる（
        $this->actingAs($user)
             ->post(route('verification.send'))
             ->assertSessionHasNoErrors()
             ->assertRedirect(); 

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /**  メール内リンクで認証完了 → 商品一覧へリダイレクト*/
    public function test_検証リンクで認証完了_一覧へ遷移(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        // 署名付きURLを生成
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // 検証実行
        $this->actingAs($user)
             ->get($verificationUrl)
             ->assertRedirect(route('after-login').'?verified=1');// ← 商品一覧が '/' の場合。違うなら route('products.index') などに変更

        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}

