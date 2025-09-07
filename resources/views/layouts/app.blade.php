{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','EC PLUS ONE')</title>
  @vite(['resources/scss/app.scss','resources/js/app.js'])
</head>
<body>
<nav class="navbar navbar-expand navbar-dark bg-dark">
  <div class="container">

    {{-- ブランドロゴの遷移先 --}}
    @if (Auth::guard('merchant')->check())
      {{-- 販売者ならダッシュボードへ --}}
      <a class="navbar-brand" href="{{ route('merchant.home') }}">EC PLUS ONE</a>
    @elseif (Auth::guard('web')->check()) // customer -> web 
      {{-- 顧客向けショップ画面 --}}
      <a class="navbar-brand" href="{{ route('shop.index') }}">EC PLUS ONE</a>
    @else
      {{-- それ以外（ゲストなど） --}}
      <a class="navbar-brand" href="{{ url('/') }}">EC PLUS ONE</a>
    @endif

    <ul class="navbar-nav ms-auto">
      @if (Auth::guard('merchant')->check())
        {{-- 販売者メニュー（必要に応じて増やす） --}}
        <li class="nav-item"><a class="nav-link" href="{{ route('merchant.home') }}">ダッシュボード</a></li>
        {{-- 例：<li class="nav-item"><a class="nav-link" href="{{ route('merchant.products.index', ['merchant'=>'default']) }}">商品</a></li> --}}
      @elseif (Auth::guard('web')->check()) // customer -> web 
        {{-- 顧客向けメニュー --}}
        <li class="nav-item">
          <a class="nav-link" href="{{ route('shop.cart.show', ['merchant' => $merchantSlug]) }}">カート</a>
        </li>
      @endif
    </ul>
  </div>
</nav>

<main class="container py-4">
  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @yield('content')
</main>
</body>
</html>
