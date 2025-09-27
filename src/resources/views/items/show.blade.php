@extends('layouts.app')
@section('title', $product->name)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/product-show.css') }}?v=15">
@endpush

@section('content')
@php
   
  $isMyItem = auth()->check() && auth()->id() === $product->user_id;
  $isSold   = $product->is_sold ?? false;
@@
  $likesCount    = $likesCount ?? 0;
  $commentsCount = $commentsCount ?? 0;
  $isLikedByMe   = $isLikedByMe ?? (auth()->check() ? $product->isLikedBy(auth()->user()) : false);

  $buyUrl = \Illuminate\Support\Facades\Route::has('purchase.create')
            ? route('purchase.create', $product->id)
            : (\Illuminate\Support\Facades\Route::has('orders.create') ? route('orders.create', $product) : '#');
@endphp

<div class="product container--wide">
  <div class="product__grid">

    {{-- 左：画像だけ --}}
    <div class="product__image">
       <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
      @if($isSold)
        <span class="badge-sold product__sold">SOLD</span>
      @endif
    </div>

    {{-- 右：その他ぜんぶ --}}
    <div class="product__body">
      <h1 class="product__title">{{ $product->name }}</h1>
      <div class="product__brand">ブランド: {{ $product->brand ?? '—' }}</div>

      <div class="product__price-like">
        <div class="product__price">
          ¥{{ number_format($product->price) }} <span class="product__price-note">(税込)</span>
        </div>

        {{-- いいね --}}
        @if(auth()->check())
          <form method="POST" action="{{ route('products.like', $product) }}">
            @csrf
            <button type="submit" class="btn-like {{ $isLikedByMe ? 'is-active' : '' }}">
              ♥ {{ $isLikedByMe ? 'いいね解除' : 'いいね' }}
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="btn-like">♥ いいね</a>
        @endif

        <span class="product__meta">いいね {{ $likesCount }}</span>
        <span class="product__meta">💬 {{ $commentsCount }}</span>
      </div>

      {{-- 購入ボタン --}}
      @if(!auth()->check())
        <a href="{{ route('login') }}" class="btn-primary-red">購入手続きへ</a>
      @else
        @if($isMyItem)
          <button class="btn-primary-red" disabled>自分の商品は購入できません</button>
        @elseif($isSold)
          <button class="btn-primary-red" disabled>Sold</button>
        @else
          <a href="{{ $buyUrl }}" class="btn-primary-red">購入手続きへ</a>
        @endif
      @endif

      <h2 class="section-title">商品説明</h2>
      <p class="product__desc">{{ $product->description ?? '—' }}</p>

      <h2 class="section-title">商品の情報</h2>
      <ul class="product__info">
        <li>
          カテゴリ:
          @php
            $cats = is_array($product->categories)
                    ? $product->categories
                    : (empty($product->categories) ? [] : explode(',', $product->categories));
          @endphp
          @if (count($cats))
            @foreach ($cats as $cat)
              <span class="chip">{{ $cat }}</span>
            @endforeach
          @else
            ー
          @endif
        </li>
        <li>商品の状態: {{ $product->condition }}</li>
      </ul>

      {{-- コメント --}}
      <div class="product__comments">
        <h3 class="section-title">コメント（{{ $commentsCount }}）</h3>

        @forelse($product->comments as $c)
          <div class="comment">
            <div class="comment__meta">
              {{ $c->user->name ?? 'ユーザー' }}・{{ $c->created_at->format('Y/m/d H:i') }}
            </div>
            <div class="comment__body">{{ $c->body }}</div>
          </div>
        @empty
          <div class="comment--empty">（コメントはまだありません）</div>
        @endforelse

        @if(auth()->check())
        <form id="comment-form" method="POST"
         action="{{ route('products.comments.store', $product) }}"
         class="comment-form">
         @csrf
         <textarea name="body" rows="4" placeholder="ここにコメントを書いてください">{{ old('body') }}</textarea>
        @error('body') <div class="form-error">{{ $message }}</div> @enderror
        <div class="comment-form__actions">
         <button type="submit" class="btn-primary-red">コメントを送信する</button>
        </div>
      </form>
        @else
          <div class="comment-form__login">
            <a href="{{ route('login') }}" class="btn-primary-red">ログインしてコメント</a>
          </div>
        @endif
      </div>

      
    </div>
  </div>
</div> 
@endsection




