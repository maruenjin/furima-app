@extends('layouts.auth')
@section('title','ログイン')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="register-container">
  <h1 class="auth-title">ログイン</h1>

 

  <form method="POST" action="{{ route('login') }}" class="auth-form" novalidate>
    @csrf

    <div class="form-group">
      <label for="email" class="form-label">メールアドレス</label>
      <input id="email" type="email" name="email" value="{{ old('email') }}" class="input" autocomplete="email" autofocus>
      @error('email') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label for="password" class="form-label">パスワード</label>
      <input id="password" type="password" name="password" class="input" autocomplete="current-password">
      @error('password') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn-primary btn-auth">ログインする</button>

    <p class="auth-switch">
      <a href="{{ route('register') }}" class="link">会員登録はこちら</a>
    </p>
  </form>
</div>
@endsection

