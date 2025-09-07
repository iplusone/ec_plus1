<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','管理画面')</title>
    @vite(['resources/css/app.scss','resources/js/app.js'])
  </head>
  <body class="bg-light">

    {{-- ヘッダー ナビバー --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('admin.home') }}">Admin</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav"
          aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNav">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}"
                 href="{{ route('admin.home') }}">
                ダッシュボード
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.merchants.*') ? 'active' : '' }}"
                 href="{{ route('admin.merchants.index') }}">
                マーチャント
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                 href="{{ route('admin.users.index') }}">
                ユーザ
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                 href="{{ route('admin.products.index') }}">
                商品
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                 href="{{ route('admin.orders.index') }}">
                注文
              </a>
            </li>
          </ul>

          <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="btn btn-outline-light btn-sm" type="submit">ログアウト</button>
          </form>
        </div>
      </div>
    </nav>

    {{-- メインコンテンツ --}}
    <main class="container-fluid py-4">
      @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
      @endif

      @yield('content')
    </main>

  </body>
</html>
