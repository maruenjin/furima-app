
@extends('layouts.app')
@section('title','ログイン')
@section('content')
<h2 class="text-center text-2xl font-bold mb-6">ログイン</h2>
<form method="POST" action="{{ route('login.attempt') }}" class="max-w-xl mx-auto space-y-4" novalidate>
  @csrf

  <div>
    <label for="email">メールアドレス</label>
    <input id="email" name="email" type="text" value="{{ old('email') }}"  class="w-full">
    @error('email') <p class="form-error">{{ $message }}</p>@enderror
  </div>

  <div>
    <label for="password">パスワード</label>
    <input id="password" name="password" type="password"  class="w-full">
    @error('password')<p class="form-error">{{ $message }}</p>@enderror
  </div>
  <button type="submit" class="w-full py-3 bg-black text-white rounded" formnovalidate>ログイン</button>
</form>
@endsection
