@extends('layouts.auth')

@section('title', '会員登録')

@push('styles')
  
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="register-container">
  <h2 class="register-title">会員登録</h2>

  
 

  @if (session('status'))
    <div class="flash-success">{{ session('status') }}</div>
  @endif

  <form action="{{ route('register') }}" method="POST" class="auth-form"
  novalidate>
    @csrf

    <div class="form-group">
      <label for="name" class="form-label">ユーザー名 </label>
      <input type="text" id="name" name="name" value="{{ old('name') }}" class="input">
      @error('name') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label for="email" class="form-label">メールアドレス </label>
      <input type="email" id="email" name="email" value="{{ old('email') }}" class="input">
      @error('email') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label for="password" class="form-label">パスワード </label>
      <input type="password" id="password" name="password" class="input">
      @error('password') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label for="password_confirmation" class="form-label">確認用パスワード </label>
      <input type="password" id="password_confirmation" name="password_confirmation" class="input">
      
      @error('password_confirmation') <div class="form-error">{{ $message }}</div> @enderror
    </div>

   
    <button type="submit" class="btn-primary btn-auth">登録する</button>

    <p class="auth-switch">
    
      <a href="{{ route('login') }}" class="link">ログインはこちら</a>
    </p>
  </form>
</div>
@endsection

