@extends('layouts.app')
@section('title','Merchant Dashboard')
@section('content')
  <h1 class="h4 mb-3">販売者ダッシュボード</h1>
  @if(session('ok'))<div class="alert alert-success">{{ session('ok') }}</div>@endif

  <div class="row g-3">
    <div class="col-md-4">
      <div class="card"><div class="card-body">
        <div class="fs-5 fw-bold">商品</div>
        <a class="btn btn-sm btn-primary mt-2" href="{{ route('seller.home') }}">一覧</a>
        {{-- 後で: route('seller.products.index', 現在のmerchant) などに変更 --}}
      </div></div>
    </div>
    <div class="col-md-4">
      <div class="card"><div class="card-body">
        <div class="fs-5 fw-bold">注文</div>
        <a class="btn btn-sm btn-outline-secondary mt-2" href="#">注文一覧</a>
      </div></div>
    </div>
  </div>
@endsection
