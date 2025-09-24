@extends('layouts.app')
@section('title','商品購入')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}?v=19"> 
@endpush

@section('content')
@php
  $img = $product->image_path ? asset('storage/'.$product->image_path) : asset('images/noimage.png');
@endphp

<div class="container--purchase">
  

  <div class="purchase">
    {{-- 左：商品／入力（フォーム） --}}
    <div class="purchase__main purchase-card">

      {{-- 商品ミニカード --}}
      <div class="purchase-item">
        <img src="{{ $img }}" alt="" class="purchase-item__thumb">
        <div class="purchase-item__meta">
          <div class="purchase-item__name">{{ $product->name }}</div>
          <div class="purchase-item__price">¥{{ number_format($product->price) }}</div>
        </div>
      </div>

      <hr class="purchase__hr">

      
      <form id="order-form" method="POST" action="{{ url('/purchase/'.$product->id) }}">
        @csrf

        {{-- 支払い方法 --}}
        <div class="field">
          <div class="field__label">支払い方法</div>
          <select id="payment_method" name="payment_method" class="select">
            <option value="">選択してください</option>
            <option value="convenience" {{ old('payment_method')==='convenience'?'selected':'' }}>コンビニ支払い</option>
            <option value="card"        {{ old('payment_method')==='card'?'selected':'' }}>カード支払い</option>
          </select>
          @error('payment_method')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        {{-- 配送先 --}}
        <div class="field">
          <div class="field__label">配送先</div>
          <div class="address-box">
            〒{{ $addr['postal_code'] ?: '未設定' }}<br>
            {{ $addr['address'] ?: '未設定' }}<br>
            {{ $addr['building'] }}
          </div>
          <div class="address-note">
            <a href="{{ route('purchase.address.edit', $product) }}" class="link">変更する</a>
           
          </div>
        </div>
      </form>
    
    </div>
  
    <aside class="purchase__aside">
  
  <div class="summary-table">
    <div class="summary-table__row">
      <div class="summary-table__label">商品代金</div>
      <div class="summary-table__value">¥{{ number_format($product->price) }}</div>
    </div>
    <div class="summary-table__row">
      <div class="summary-table__label">支払い方法</div>
      <div class="summary-table__value" id="pmLabel">
        {{ old('payment_method')==='card' ? 'カード支払い' : (old('payment_method')==='convenience' ? 'コンビニ支払い' : '選択してください') }}
      </div>
    </div>
  </div>

  
  <button id="buyBtn" type="submit" class="btn-primary-red summary-button" form="order-form">
    購入する
  </button>
</aside>


  </div>
</div>


<script>
(function(){
  var sel = document.getElementById('payment_method');
  var lbl = document.getElementById('pmLabel');
  var btn = document.getElementById('buyBtn');

  function update(){
    var v = sel.value;
    lbl.textContent = v==='card' ? 'カード支払い'
                     : v==='convenience' ? 'コンビニ支払い'
                     : '選択してください';
    var dis = (v === '');
    btn.disabled = dis;
    
    btn.style.cursor  = dis ? 'not-allowed' : 'pointer';
  }
  if (sel) { sel.addEventListener('change', update); update(); }
})();
</script>
@endsection





