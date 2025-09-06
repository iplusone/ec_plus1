@extends('layouts.app')
@section('title','商品一覧')
@section('content')
<h1 class="mb-3">商品一覧</h1>
<div class="row">
@foreach($products as $p)
  @php $v = $p->variants->first(); @endphp
  <div class="col-md-3 mb-3">
    <div class="card h-100 p-3">
      <h5 class="card-title">{{ $p->name }}</h5>
      @if($v)<div class="mb-2">¥ {{ number_format($v->price_amount) }}</div>@endif
      <a class="btn btn-outline-primary btn-sm" href="{{ route('shop.product.show',$p->slug) }}">詳細</a>
    </div>
  </div>
@endforeach
</div>
{{ $products->links() }}
@endsection
