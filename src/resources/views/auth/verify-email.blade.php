@extends('layouts.app')
@section('title','メール認証')

@section('content')
  <h1 style="text-align:center;font-size:20px;font-weight:bold;margin-bottom:16px;">メール認証のお願い</h1>
  <p>登録したメールアドレスに認証リンクを送信しました。メールをご確認ください。</p>

  @if (session('status') == 'verification-link-sent')
    <div style="background:#eef;color:#114;padding:12px 16px;margin:12px 0;">
      認証メールを再送しました！
    </div>
  @endif

  <form method="POST" action="{{ route('verification.send') }}" style="margin:12px 0;">
    @csrf
    <button style="padding:10px;background:#000;color:#fff;border:none;cursor:pointer;">認証メール再送</button>
  </form>

  <p><a href="{{ route('login') }}">認証はこちらから（ログイン画面へ）</a></p>

  <form method="POST" action="{{ route('logout') }}" style="margin-top:12px;">
    @csrf
    <button class="link" style="background:none;border:none;color:#06c;cursor:pointer;">ログアウト</button>
  </form>
@endsection


