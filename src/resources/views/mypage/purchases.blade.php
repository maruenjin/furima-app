@extends('layouts.app')
@section('title','マイページ')

@section('content')
  {{-- プロフィールヘッダー --}}
  <div class="mypage-head">
  <div class="avatar {{ $user->avatar_path ? '' : 'is-empty' }}">
   <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
  </div>

  <div class="mypage-head__name">{{ $user->name }}</div>

  <a class="btn-outline" href="{{ route('profile.edit') }}">プロフィールを編集</a>
</div>


  {{-- タブ --}}
  <div class="tabs tabs--mypage">
    <a href="{{ route('mypage.purchases', ['tab'=>'sell']) }}"
       class="{{ $tab==='sell' ? 'is-active' : '' }}">出品した商品</a>
    <a href="{{ route('mypage.purchases', ['tab'=>'buy']) }}"
       class="{{ $tab==='buy' ? 'is-active' : '' }}">購入した商品</a>
  </div>

  {{-- 出品した商品 --}}
  <div class="tab-panel {{ $tab==='sell' ? 'is-active' : '' }}">
    <div class="product-grid">
      @forelse ($myProducts as $p)
        <a class="product-card" href="{{ route('products.show', $p) }}">
          <div class="product-thumb">
            
            <img src="{{ $p->image_url }}" alt="{{ $p->name }}" loading="lazy">
            

            
          </div>
          <div class="product-meta">
            <div class="brand">{{ $p->brand }}</div>
            <div class="name">{{ $p->name }}</div>
            <div class="price">¥{{ number_format($p->price) }}</div>
          </div>
        </a>
      @empty
        <p>まだ出品がありません。</p>
      @endforelse
    </div>
    <div class="pagination">
      {{ $myProducts->appends(['tab'=>'sell'])->links() }}
    </div>
  </div>

  {{-- 購入した商品 --}}
  <div class="tab-panel {{ $tab==='buy' ? 'is-active' : '' }}">
    <div class="product-grid">
      @forelse ($items as $p)
        <a class="product-card" href="{{ route('products.show', $p) }}">
          <div class="product-thumb">
           
            <img src="{{ $p->image_url }}" alt="{{ $p->name }}" loading="lazy">
           


          </div>
          <div class="product-meta">
            <div class="brand">{{ $p->brand }}</div>
            <div class="name">{{ $p->name }}</div>
            <div class="price">¥{{ number_format($p->price) }}</div>
          </div>
        </a>
      @empty
        <p>購入履歴はまだありません。</p>
      @endforelse
    </div>
    <div class="pagination">
      {{ $items->appends(['tab'=>'buy'])->links() }}
    </div>
  </div>
@endsection





