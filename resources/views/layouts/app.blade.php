<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','EC+1')</title>
  @vite(['resources/scss/app.scss','resources/js/app.js'])
</head>
<body>
<nav class="navbar navbar-expand navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="{{ route('shop.index') }}">EC+1</a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a class="nav-link" href="{{ route('cart.show') }}">カート</a></li>
    </ul>
  </div>
</nav>
<main class="container py-4">
  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @yield('content')
</main>
</body>
</html>
