@extends('layouts.app')
@section('title',$product->name)
@section('content')
<h1 class="mb-3">{{ $product->name }}</h1>
<p class="text-muted">{{ $product->description }}</p>
@php $v = $product->variants->first(); @endphp
@if($v)
  <p class="fs-4">¥ {{ number_format($v->price_amount) }}</p>
  <form method="post" action="{{ route('cart.add') }}" class="row g-2">
    @csrf
    <input type="hidden" name="variant_id" value="{{ $v->id }}">
    <div class="col-auto">
      <input name="qty" type="number" class="form-control" value="1" min="1" max="999" required>
    </div>
    <div class="col-auto">
      <button class="btn btn-primary">カートに入れる</button>
    </div>
  </form>
@endif
@endsection
