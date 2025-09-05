@extends('layouts.app')
@section('title', $product->name)

@section('content')
@php
  $img = $product->image_path ? asset('storage/'.$product->image_path) : asset('images/noimage.png');
  $isMyItem = auth()->check() && auth()->id() === $product->user_id;
  $isSold   = $product->is_sold ?? false;

  $likesCount    = $likesCount ?? 0;
  $commentsCount = $commentsCount ?? 0;
  $isLikedByMe   = $isLikedByMe ?? (auth()->check() ? $product->isLikedBy(auth()->user()) : false);
@endphp

<div class="container" style="max-width:960px;">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">

    
    <div>
      <img src="{{ $img }}" alt="{{ $product->name }}"
           style="width:100%;height:420px;object-fit:cover;border-radius:12px;background:#f7f7f7;">
      @if($isSold)
        <span style="display:inline-block;margin-top:8px;background:#e53935;color:#fff;padding:4px 8px;border-radius:6px;">Sold</span>
      @endif

      {{-- デバッグしたい時は表示してOK
      <div style="font-size:12px;color:#999;margin-top:4px;">SRC: {{ $img }}</div>
      <div style="font-size:12px;color:#999;">PATH: {{ $product->image_path }}</div> --}}
    </div>

    
    <div>
      <h1 style="margin:0 0 4px;font-size:22px;font-weight:800;">{{ $product->name }}</h1>
      <div style="color:#666;">ブランド: {{ $product->brand ?? '—' }}</div>

      {{-- ★ここに「価格＋いいね」ブロックを入れる --}}
      <div style="display:flex;align-items:center;gap:12px;margin:8px 0 12px;">
        {{-- 価格 --}}
        <div style="font-size:22px;font-weight:700;">
          ¥{{ number_format($product->price) }}
          <span style="font-size:12px;color:#666;">(税込)</span>
        </div>

        {{-- ♥ いいね ボタン＆カウント --}}
        @auth
          <form method="POST" action="{{ route('products.like', $product) }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn-like {{ $isLikedByMe ? 'is-active' : '' }}">
              ♥ {{ $isLikedByMe ? 'いいね解除' : 'いいね' }}
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="btn-like">♥ いいね</a>
        @endauth
        <span style="color:#666;">いいね {{ $likesCount }}</span>
        <span style="color:#666;">　💬 {{ $commentsCount }}</span>
      </div>
      {{-- ★ここまで --}}

      {{-- 購入動線 --}}
      <div style="margin-bottom:16px;">
        @guest
          <a href="{{ route('login') }}" class="btn-primary-red">購入手続きへ</a>
        @else
          @if($isMyItem)
            <button class="btn-primary-red" disabled>自分の商品は購入できません</button>
          @elseif($isSold)
            <button class="btn-primary-red" disabled>Sold</button>
          @else
            @php
              $buyUrl = \Illuminate\Support\Facades\Route::has('orders.create')
                        ? route('orders.create', $product) : '#';
            @endphp
            <a href="{{ $buyUrl }}" class="btn-primary-red">購入手続きへ</a>
          @endif
        @endguest
      </div>

      <h2 style="font-size:16px;margin:12px 0 6px;">商品説明</h2>
      <p style="white-space:pre-line;line-height:1.8;color:#222;">
        {{ $product->description ?? '—' }}
      </p>

      <h2 style="font-size:16px;margin:16px 0 6px;">商品の情報</h2>
<ul style="padding-left:18px;margin:0;color:#222;">
  <li>
    カテゴリ:
    @php
      // JSON配列を想定。万一カンマ区切り文字列でも動くようフォールバック
      $cats = is_array($product->categories)
              ? $product->categories
              : (empty($product->categories) ? [] : explode(',', $product->categories));
    @endphp

    @if (count($cats))
      @foreach ($cats as $cat)
        <span style="background:#eee;border-radius:12px;padding:2px 8px;font-size:12px;margin-right:4px;display:inline-block;">
          {{ $cat }}
        </span>
      @endforeach
    @else
      ー
    @endif
  </li>
  <li>商品の状態: {{ $product->condition }}</li>
</ul>

    </div>
  </div>

{{-- コメント --}}
<div style="margin-top:32px;">
  <h3 style="font-size:16px;">コメント（{{ $commentsCount }}）</h3>

  {{-- 一覧 --}}
  @forelse($product->comments as $c)
    <div style="border:1px solid #eee;border-radius:6px;padding:8px 12px;margin:8px 0;">
      <div style="font-size:12px;color:#666;">
        {{ $c->user->name ?? 'ユーザー' }}・{{ $c->created_at->format('Y/m/d H:i') }}
      </div>
      <div style="margin-top:4px;white-space:pre-line;">{{ $c->body }}</div>
    </div>
  @empty
    <div style="margin:8px 0;color:#666;">（コメントはまだありません）</div>
  @endforelse

  {{-- 入力欄：ログイン後のみ表示 --}}
  @auth
    <form id="comment-form"
          method="POST"
          action="{{ url('/item/'.$product->id.'/comments') }}"
          style="margin-top:12px;">
      @csrf

      <textarea name="body" rows="4" maxlength="255" placeholder="ここにコメントを書いてください"
                style="width:100%;box-sizing:border-box;padding:10px;border:1px solid #ccc;border-radius:6px;">{{ old('body') }}</textarea>
      @error('body')
        <div style="color:#e53935;font-size:12px;margin-top:4px;">{{ $message }}</div>
      @enderror

      <div style="margin-top:8px;">
        <button type="submit"
                class="btn-primary-red"
                formmethod="POST"
                formaction="{{ url('/item/'.$product->id.'/comments') }}">
          コメントを送信する
        </button>
      </div>

      {{-- 一時デバッグ（動いたら消してOK） --}}
      <div style="font-size:12px;color:#999;margin-top:6px;">
        POST先: {{ url('/item/'.$product->id.'/comments') }}
      </div>
    </form>
  @else
    <div style="margin-top:8px;">
      <a href="{{ route('login') }}" class="btn-primary-red" style="display:inline-block;">ログインしてコメント</a>
    </div>
  @endauth
</div> {{-- ← コメントブロックを閉じる --}}

<div style="margin-top:24px;">
  <a href="{{ route('products.index') }}" style="text-decoration:none;color:#333;">← 一覧に戻る</a>
</div>

</div> {{-- ← .container を閉じる --}}
@endsection
