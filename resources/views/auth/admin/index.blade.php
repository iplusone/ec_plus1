@extends('layouts.guest')
@section('title','ログイン一覧')

@section('content')
<h1 class="h4 mb-4">ログイン一覧</h1>

<div class="vstack gap-3">
  <a class="btn btn-dark w-100" href="{{ route('admin.login.form') }}">管理者ログイン (/admin/login)</a>
  <a class="btn btn-secondary w-100" href="{{ route('merchant.login.form') }}">販売者ログイン (/merchant/login)</a>
  <a class="btn btn-primary w-100" href="{{ route('customer.login.form') }}">顧客ログイン (/customer/login)</a>
</div>

<hr class="my-4">

<h2 class="h6">現在の状態</h2>
<ul class="small">
  @auth('admin')
    <li>admin でログイン中 → <a href="{{ route('admin.home') }}">管理ダッシュボード</a></li>
  @endauth
  @auth('merchant')
    <li>merchant でログイン中 → <a href="{{ url('/merchant') }}">販売者画面</a></li>
  @endauth
  @auth('customer')
    <li>customer でログイン中 → <a href="{{ url('/mypage') }}">マイページ</a></li>
  @endauth
  @guest('admin') @guest('merchant') @guest('customer')
    <li>未ログイン</li>
  @endguest @endguest @endguest
</ul>
@endsection
