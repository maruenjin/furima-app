@extends('layouts.app')
@section('title','パスワード再設定')

@section('content')
  <h1 style="text-align:center;font-size:20px;font-weight:bold;margin-bottom:16px;">新しいパスワードを設定</h1>

  @if ($errors->any())
    <div style="background:#fee;color:#900;padding:12px 16px;margin-bottom:12px;">
      {{ $errors->first() }}
    </div>
  @endif

  <form method="POST" action="{{ route('password.update') }}" style="max-width:480px;margin:0 auto;display:grid;gap:12px;">
    @csrf
    <input type="hidden" name="token" value="{{ request()->route('token') }}">
    <input type="hidden" name="email" value="{{ request('email') }}">

    <label>新しいパスワード
      <input name="password" type="password" required style="width:100%;padding:8px;">
      @error('password') <small style="color:#c00;">{{ $message }}</small> @enderror
    </label>

    <label>確認用パスワード
      <input name="password_confirmation" type="password" required style="width:100%;padding:8px;">
    </label>

    <button style="padding:10px;background:#000;color:#fff;border:none;cursor:pointer;">更新する</button>
  </form>
@endsection
