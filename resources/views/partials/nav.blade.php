<nav class="navbar navbar-expand navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="/">EC PLUS ONE</a>
    <ul class="navbar-nav ms-auto">
      @auth('admin')
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.home') }}">Admin</a></li>
      @endauth
      @auth('seller')
        <li class="nav-item"><a class="nav-link" href="{{ route('seller.orders.index', $currentMerchant ?? 'default') }}">Seller</a></li>
      @endauth
      @auth('customer')
        <li class="nav-item"><a class="nav-link" href="{{ route('customer.account', $currentMerchant ?? 'default') }}">マイページ</a></li>
      @endauth
      <li class="nav-item"><a class="nav-link" href="{{ route('shop.cart.show', $currentMerchant ?? 'default') }}">カート</a></li>
    </ul>
  </div>
</nav>
