@extends('layouts.app')
@section('title','プロフィール設定')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="profile-wrap">
  <h1 class="auth-title">プロフィール設定</h1>

  

  {{-- アバター＋アップロード --}}
  <div class="avatar-block">
    @php $src = $user->avatar_path ? asset('storage/'.$user->avatar_path) : null; @endphp
    <div class="avatar {{ $src ? '' : 'is-empty' }}">
      @if($src)<img src="{{ $src }}" alt="avatar">@endif
    </div>

    <label class="avatar-upload">
      画像を選択する
      
      <input type="file" name="avatar" id="avatar-input" accept="image/*" form="profile-form" hidden>
    </label>
    @error('avatar') <div class="form-error">{{ $message }}</div> @enderror
  </div>

  {{-- ★ enctype を必ず付ける！ --}}
  <form id="profile-form" method="POST" action="{{ route('profile.update') }}" class="auth-form" enctype="multipart/form-data">
    @csrf
    {{-- もしルートを PUT にしているなら↓を有効化
    @method('PUT')
    --}}

    <div class="form-group">
      <label class="form-label" for="name">ユーザー名</label>
      <input id="name" name="name" type="text" class="input" value="{{ old('name', $user->name) }}">
      @error('name') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label class="form-label" for="postal_code">郵便番号</label>
      <input id="postal_code" name="postal_code" type="text" class="input" value="{{ old('postal_code', $user->postal_code) }}">
      @error('postal_code') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label class="form-label" for="address">住所</label>
      <input id="address" name="address" type="text" class="input" value="{{ old('address', $user->address) }}">
      @error('address') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label class="form-label" for="building">建物名</label>
      <input id="building" name="building" type="text" class="input" value="{{ old('building', $user->building) }}">
      @error('building') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn-primary btn-auth">更新する</button>
  </form>
</div>

{{-- 画像プレビュー --}}
<script>
  document.getElementById('avatar-input')?.addEventListener('change', (e) => {
    const file = e.target.files?.[0]; if(!file) return;
    const url = URL.createObjectURL(file);
    const box = document.querySelector('.avatar'); box.classList.remove('is-empty');
    box.innerHTML = `<img src="${url}" alt="preview">`;
  });
</script>
@endsection



