@extends('layouts.app')
@section('title','プロフィール設定')

@push('styles')
  
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v=2">
@endpush

@section('content')
<div class="profile-wrap">
  <h1 class="auth-title">プロフィール設定</h1>

  @if (session('status'))
    <div class="flash-success">{{ session('status') }}</div>
  @endif
   @method('PUT')

  <form class="auth-form" method="POST"
        action="{{ route('profile.update') }}"
        enctype="multipart/form-data">
    @csrf
     

   
    <div class="avatar-block">
      <div class="avatar {{ empty($user->avatar_path) ? 'is-empty' : '' }}">
        @if(!empty($user->avatar_path))
          <img id="avatar-preview" src="{{ $user->avatar_url }}" alt="avatar">
        @else
          <img id="avatar-preview" src="" alt="" style="display:none;">
        @endif
      </div>
      <label for="avatar" class="avatar-upload">画像を選択する</label>
      <input id="avatar" type="file" name="avatar" accept="image/*" hidden>
      @error('avatar')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    
    <div class="form-group">
      <label class="form-label" for="name">ユーザー名</label>
      <input id="name" name="name" type="text" class="input"
             value="{{ old('name', $user->name) }}">
      @error('name')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    
    <div class="form-group">
      <label class="form-label" for="postal_code">郵便番号</label>
      <input id="postal_code" name="postal_code" type="text" class="input"
             inputmode="numeric" pattern="\d{3}-?\d{4}" 
             value="{{ old('postal_code', $user->postal_code) }}">
      @error('postal_code')<div class="form-error">{{ $message }}</div>@enderror
    </div>

   
    <div class="form-group">
      <label class="form-label" for="address">住所</label>
      <input id="address" name="address" type="text" class="input"
             value="{{ old('address', $user->address) }}">
      @error('address')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    
    <div class="form-group">
      <label class="form-label" for="building">建物名</label>
      <input id="building" name="building" type="text" class="input"
             value="{{ old('building', $user->building) }}">
      @error('building')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    
    <button type="submit" class="btn-primary-red">更新する</button>
  </form>
</div>


<script>
  (function(){
    const input = document.getElementById('avatar');
    const img   = document.getElementById('avatar-preview');
    if(!input) return;
    input.addEventListener('change', e => {
      const f = e.target.files && e.target.files[0];
      if(!f) return;
      const reader = new FileReader();
      reader.onload = () => {
        img.src = reader.result;
        img.style.display = 'block';
      };
      reader.readAsDataURL(f);
    });
  })();
</script>
@endsection

