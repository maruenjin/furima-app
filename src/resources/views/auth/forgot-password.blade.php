@extends('layouts.app')
@section('title','パスワード再設定')

@section('content')
  <h1 style="text-align:center;font-size:20px;font-weight:bold;margin-bottom:16px;">パスワード再設定</h1>

  @if (session('status'))
    <div style="background:#eef;color:#114;padding:12px 16px;margin-bottom:12px;">
      {{ session('status') }}
    </div>
  @endif

  @if ($errors->any())
    <div style="background:#fee;color:#900;padding:12px 16px;margin-bottom:12px;">
      {{ $errors->first() }}
    </div>
  @endif

  <form method="POST" action="{{ route('password.email') }}" style="max-width:480px;margin:0 auto;display:grid;gap:12px;">
    @csrf
    <label>メールアドレス
      <input name="email" type="email" value="{{ old('email') }}" required style="width:100%;padding:8px;">
      @error('email') <small style="color:#c00;">{{ $message }}</small> @enderror
    </label>
    <button style="padding:10px;background:#000;color:#fff;border:none;cursor:pointer;">送信</button>
  </form>
@endsection
