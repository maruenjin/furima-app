{{-- resources/views/items/index.blade.php --}}
@extends('layouts.app')

@section('title', '商品一覧')

@section('content')
<div class="container" style="max-width:960px;">
  <h1 class="mb-4">商品一覧（仮）</h1>

  @auth
    <p>ようこそ、{{ auth()->user()->name }} さん！</p>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit">ログアウト</button>
    </form>
  @else
    <p><a href="{{ route('login') }}">ログイン</a> / <a href="{{ route('register') }}">会員登録</a></p>
  @endauth

  <hr class="my-4">
  <p>ここに商品カードを並べていきます。</p>
</div>
@endsection
