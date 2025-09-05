@extends('layouts.app')
@section('title','商品一覧')

@section('content')

<div class="tabs" style="display:flex;gap:12px;margin:12px 0;">
  <a href="{{ route('products.index', ['q' => request('q')]) }}"
     class="{{ request('tab') === 'mylist' ? '' : 'is-active' }}">おすすめ</a>

  <a href="{{ route('products.index', ['tab' => 'mylist', 'q' => request('q')]) }}"
     class="{{ request('tab') === 'mylist' ? 'is-active' : '' }}">マイリスト</a>
</div>

<div class="product-grid">
  @foreach ($products as $product)
    <a class="product-card" href="{{ route('products.show', $product) }}">
      <div class="product-thumb">
        @php
          $src = $product->image_path ? asset('storage/'.$product->image_path)
                                      : asset('images/noimage.png');
        @endphp
        <img src="{{ $src }}" alt="{{ $product->name }}">
        @if ($product->is_sold ?? false)
          <span class="badge-sold">Sold</span>
        @endif
      </div>
      <div class="product-meta">
        <div class="brand">{{ $product->brand }}</div>
        <div class="name">{{ $product->name }}</div>
        <div class="price">¥{{ number_format($product->price) }}</div>
      </div>
    </a>
  @endforeach
</div>

<div class="pagination">{{ $products->links() }}</div>
@endsection

