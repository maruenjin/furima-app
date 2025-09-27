@extends('layouts.app')
@section('title','商品一覧')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/products.css') }}">
  <link rel="stylesheet" href="{{ asset('css/pagination-fix.css') }}">
@endpush

@section('content')


@php $qs = request()->only('q'); @endphp
<div class="tabs tabs--row">
  <a href="{{ route('products.index', array_merge($qs, ['tab'=>'recommended'])) }}"
     class="{{ $tab==='mylist' ? '' : 'is-active' }}">おすすめ</a>

  <a href="{{ route('products.index', array_merge($qs, ['tab'=>'mylist'])) }}"
     class="{{ $tab==='mylist' ? 'is-active' : '' }}">マイリスト</a>
</div>



<div class="product-grid">
  @forelse ($products as $product)
    <a class="product-card" href="{{ route('products.show', $product) }}">
      <div class="product-thumb">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
        @if (!is_null($product->buyer_id))
          <span class="badge-sold">Sold</span>
        @endif
      </div>
      <div class="product-meta">
        <div class="brand">{{ $product->brand }}</div>
        <div class="name">{{ $product->name }}</div>
        <div class="price">¥{{ number_format($product->price) }}</div>
      </div>
    </a>
  @empty
    <p class="empty">
      @if($tab === 'mylist' && !auth()->check())
        マイリストを表示するにはログインしてください。
      @elseif($q)
        「{{ $q }}」に一致する商品はありません。
      @else
        商品がありません。
      @endif
    </p>
  @endforelse
</div>

<div class="pagination">
  {{ $products->links() }}
</div>
@endsection



