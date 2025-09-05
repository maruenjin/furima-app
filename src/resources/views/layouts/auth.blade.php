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
  <header class="auth-header">
    <div class="header-inner">
      <a href="{{ url('/') }}" class="brand">
        <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" class="brand-logo">
      </a>
    </div>
  </header>

  <main class="container">@yield('content')</main>
</body>
</html>
