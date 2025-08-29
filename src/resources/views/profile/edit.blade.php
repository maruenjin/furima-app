@extends('layouts.app')

@section('title','プロフィール編集')

@section('content')
<div class="container" style="max-width:720px;">
  <h1 class="mb-4">プロフィール編集</h1>

  @if (session('status'))
    <div style="padding:.75rem; background:#e6ffed; border:1px solid #a7f3d0; margin-bottom:1rem;">
      {{ session('status') }}
    </div>
  @endif

  <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div style="margin-bottom:1rem;">
      <label>ユーザー名</label><br>
      <input type="text" name="name" value="{{ old('name', $user->name) }}" style="width:100%;">
      @error('name')<div style="color:#d00;">{{ $message }}</div>@enderror
    </div>

    <div style="margin-bottom:1rem;">
      <label>郵便番号（ハイフンなし7桁）</label><br>
      <input type="text" name="zip_code" value="{{ old('zip_code', $user->zip_code) }}" style="width:100%;">
      @error('zip_code')<div style="color:#d00;">{{ $message }}</div>@enderror
    </div>

    <div style="margin-bottom:1rem;">
      <label>住所</label><br>
      <input type="text" name="address" value="{{ old('address', $user->address) }}" style="width:100%;">
      @error('address')<div style="color:#d00;">{{ $message }}</div>@enderror
    </div>

    <div style="margin-bottom:1rem;">
      <label>建物名（任意）</label><br>
      <input type="text" name="building" value="{{ old('building', $user->building) }}" style="width:100%;">
      @error('building')<div style="color:#d00;">{{ $message }}</div>@enderror
    </div>

    <div style="margin-bottom:1rem;">
      <label>プロフィール画像（任意）</label><br>
      <input type="file" name="avatar" accept="image/*">
      @error('avatar')<div style="color:#d00;">{{ $message }}</div>@enderror
      @if ($user->avatar_path)
        <div style="margin-top:.5rem;">
          <img src="{{ asset('storage/'.$user->avatar_path) }}" alt="avatar" style="width:120px;height:120px;object-fit:cover;border-radius:8px;border:1px solid #ddd;">
        </div>
      @endif
    </div>

    <button type="submit">保存する</button>
  </form>
</div>
@endsection
