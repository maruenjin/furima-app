<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','フリマ')</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @stack('styles')
</head>
<body>
  <header class="site-header">
    <div class="header-inner">
      {{-- 左：ロゴ --}}
      <a href="{{ url('/') }}" class="brand">
        <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" class="brand-logo">
      </a>

      {{-- 中央：検索（一覧 / に q を投げる） --}}
      <form action="{{ url('/') }}" method="GET" class="search-form" role="search">
        <input type="text" name="q" placeholder="なにをお探しですか？" value="{{ request('q') }}">
      </form>

      {{-- 右：ナビ（認証状態で出し分け） --}}
      <nav class="nav-links">
        @auth
          {{-- 左：ログアウト --}}
          <form class="logout-inline" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">ログアウト</button>
          </form>

          

          {{-- 中：マイページ --}}
          <a href="{{ route('mypage.index') }}">マイページ</a>

          {{-- 右：出品（ルートがあるときだけ） --}}
          @if (Route::has('products.create'))
            <a class="btn-white" href="{{ route('products.create') }}">出品</a>
          @endif
        @else
          <a href="{{ route('login') }}">ログイン</a>
          @if (Route::has('register'))
            <a href="{{ route('register') }}">会員登録</a>
          @endif
        @endauth
      </nav>
    </div>
  </header>

  <main class="container">@yield('content')</main>
</body>
</html>






