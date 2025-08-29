
<!doctype html><html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>@yield('title','フリマ')</title>
  @vite(['resources/css/app.css','resources/js/app.js']) {{-- Vite利用時 --}}
  <style>
  .form-error{ color:#b00020; font-size:0.875rem; margin-top:4px; }
  .flash-success{ color:#2e7d32; }
</style>

</head>
<body class="min-h-screen bg-gray-50">
  <header class="bg-black text-white">
    <div class="mx-auto max-w-5xl flex items-center justify-between p-4">
      <a href="{{ url('/') }}" class="font-bold">COACHTECH</a>
      <nav class="flex gap-4">
        @auth
          <a href="{{ url('/sell') }}">出品</a>
          <a href="{{ url('/mypage') }}">マイページ</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf <button>ログアウト</button>
          </form>
        @else
          <a href="{{ route('login') }}">ログイン</a>
          <a href="{{ route('register') }}">会員登録</a>
        @endauth
      </nav>
    </div>
  </header>
  <main class="mx-auto max-w-5xl p-6">@yield('content')</main>
  <footer class="text-center text-sm text-gray-500 py-8">&copy; COACHTECH</footer>
</body></html>

