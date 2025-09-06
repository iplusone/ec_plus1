@extends('layouts.app')
@section('title','カート')
@section('content')
<h1 class="h4 mb-3">カート</h1>
@if(session('ok'))<div class="alert alert-success">{{ session('ok') }}</div>@endif
<form method="post" action="{{ route('cart.update', $currentMerchant->slug) }}">
@csrf
<table class="table align-middle">
  <thead><tr><th>商品</th><th class="text-end">単価</th><th>数量</th><th class="text-end">小計</th><th></th></tr></thead>
  <tbody>
  @foreach($items as $i)
    <tr>
      <td>{{ $i->variant->product->name }}<br><small>SKU: {{ $i->variant->sku }}</small></td>
      <td class="text-end">¥{{ number_format($i->price_amount) }}</td>
      <td style="width:120px">
        <input type="number" name="items[{{ $i->id }}]" value="{{ $i->qty }}" min="0" max="99" class="form-control">
      </td>
      <td class="text-end">¥{{ number_format($i->qty * $i->price_amount) }}</td>
      <td class="text-end">
        <form method="post" action="{{ route('cart.remove', $currentMerchant->slug) }}" onsubmit="return confirm('削除しますか？')">
          @csrf <input type="hidden" name="id" value="{{ $i->id }}">
          <button class="btn btn-sm btn-outline-danger">削除</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
<div class="text-end fs-5">合計：<strong>¥{{ number_format($total) }}</strong></div>
<div class="mt-3 d-flex justify-content-end gap-2">
  <button class="btn btn-outline-primary">数量を更新</button>
  <a class="btn btn-primary" href="{{ route('checkout.create', $currentMerchant->slug) }}">レジに進む</a>
</div>
</form>
@endsection
