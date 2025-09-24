@extends('layouts.app')
@section('title','住所の変更')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/address-edit.css') }}?v=2">
@endpush

@section('content')
<div class="address-page">
  <h1 class="address-title">住所の変更</h1>

  <form method="POST" action="{{ route('purchase.address.update', $product) }}" class="address-form">
    @csrf
    @method('PATCH')

    <div class="form-group">
      <label class="form-label" for="postal_code">郵便番号</label>
      <input id="postal_code" name="postal_code" class="input" inputmode="numeric" pattern="\d{3}-?\d{4}"
           value="{{ old('postal_code', $addr['postcode'] ?? '') }}">   
      @error('postal_code')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
      <label class="form-label" for="address">住所</label>
      <input id="address" name="address" class="input"
             value="{{ old('address', $addr['address'] ?? '') }}">
      @error('address')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
      <label class="form-label" for="building">建物名</label>
      <input id="building" name="building" class="input"
             value="{{ old('building', $addr['building'] ?? '') }}">
      @error('building')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn-primary-red address-submit">更新する</button>
  </form>
</div>
@endsection
