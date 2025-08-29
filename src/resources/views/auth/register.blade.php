<!-- resources/views/auth/register.blade.php -->
@extends('layouts.app')

@section('title', '会員登録')

@section('content')
<div class="register-container">
    <h2>会員登録</h2>

     @if ($errors->any())
        <div style="color:#b00020; margin:0 0 12px;">
            {{ $errors->first() }}
        </div>
    @endif

    
    @if (session('status'))
        <div style="color:#2e7d32; margin:0 0 12px;">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST" novalidate>
        @csrf
        <div>
            <label for="name">ユーザー名</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" >
             @error('name')
        <div style="color:#b00020;">{{ $message }}</div>
    @enderror
        </div>

        <div>
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" >
            @error('email')
        <div style="color:#b00020;">{{ $message }}</div>
    @enderror
        </div>

        <div>
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" >
             @error('password')
        <div style="color:#b00020;">{{ $message }}</div>
    @enderror
        </div>

        <div>
            <label for="password_confirmation">確認用パスワード</label>
            <input type="password" id="password_confirmation" name="password_confirmation" >
             @error('password_confirmation')
        <div style="color:#b00020;">{{ $message }}</div>
    @enderror
        </div>

        <button type="submit"formnovalidate>登録する</button>
    </form>

    <p><a href="{{ route('login') }}">ログインはこちら</a></p>
</div>
@endsection
