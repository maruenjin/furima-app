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
      <a href="{{ url('/') }}" class="brand">
        <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" class="brand-logo">
      </a>
      <form method="GET" action="{{ route('products.index') }}" class="search-form" role="search">
  
      <input type="hidden" name="tab" value="{{ request('tab', 'recommended') }}">
      <input type="text" name="q" placeholder="なにをお探しですか？" value="{{ request('q') }}">
  
</form>

      <nav class="nav-links">
        @auth
          <form class="logout-inline" method="POST" action="{{ route('logout') }}">@csrf<button type="submit">ログアウト</button></form>
          <a href="{{ route('mypage.index') }}">マイページ</a>
          @if (Route::has('products.create'))
            <a class="btn-white" href="{{ route('products.create') }}">出品</a>
          @endif
        @else
          <a href="{{ route('login') }}">ログイン</a>
          @if (Route::has('register')) <a href="{{ route('register') }}">会員登録</a> @endif
        @endauth
      </nav>
    </div>
  </header>

  <main class="container">@yield('content')</main>
</body>
</html>







