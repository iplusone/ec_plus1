@extends('layouts.app')
@section('title','Admin Dashboard')
@section('content')
<h1 class="h4 mb-3">マーチャント管理ダッシュボード</h1>
<ul class="list-unstyled">
  <li><a href="{{ route('merchants.products.index') }}">商品一覧</a></li>
  <li><a href="{{ route('merchants.orders.index') }}">注文一覧</a></li>
  <li><a href="{{ route('merchants.merchants.index') }}">マーチャント管理</a></li>
  <li><a href="{{ route('merchants.users.index') }}">ユーザ管理</a></li>
</ul>
<form method="post" action="{{ route('merchants.logout') }}">@csrf
  <button class="btn btn-outline-secondary btn-sm">ログアウト</button>
</form>
@endsection
