<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','マーチャント管理')</title>
    @vite(['resources/css/app.scss','resources/js/app.js'])
  </head>
  <body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('merchant.home', $merchant) }}">
          {{ $merchant->name }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#merchantNav" aria-controls="merchantNav"
                aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="merchantNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('merchant.home') ? 'active' : '' }}"
                 href="{{ route('merchant.home',$merchant) }}">
                 ダッシュボード
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('merchant.products.*') ? 'active' : '' }}"
                 href="{{ route('merchant.products.index',$merchant) }}">
                 商品管理
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('merchant.orders.*') ? 'active' : '' }}"
                 href="{{ route('merchant.orders.index',$merchant) }}">
                 注文管理
              </a>
            </li>
          </ul>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-light btn-sm" type="submit">ログアウト</button>
          </form>
        </div>
      </div>
    </nav>

    <main class="container py-4">
      @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
      @endif
      @yield('content')
    </main>
  </body>
</html>
