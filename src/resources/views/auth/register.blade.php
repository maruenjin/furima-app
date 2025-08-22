<!-- resources/views/auth/register.blade.php -->
@extends('layouts.app')

@section('title', '会員登録')

@section('content')
<div class="register-container">
    <h2>会員登録</h2>

    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div>
            <label for="name">ユーザー名</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div>
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div>
            <label for="password_confirmation">確認用パスワード</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit">登録する</button>
    </form>

    <p><a href="{{ route('login') }}">ログインはこちら</a></p>
</div>
@endsection
