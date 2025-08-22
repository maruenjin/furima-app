<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')
@section('title','ログイン')
@section('content')
<h2 class="text-center text-2xl font-bold mb-6">ログイン</h2>
<form method="POST" action="{{ route('login') }}" class="max-w-xl mx-auto space-y-4">
  @csrf
  <div>
    <label for="email">メールアドレス</label>
    <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full">
    @error('email')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
  </div>
  <div>
    <label for="password">パスワード</label>
    <input id="password" name="password" type="password" required class="w-full">
    @error('password')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
  </div>
  <button class="w-full py-3 bg-black text-white rounded">ログイン</button>
</form>
@endsection
