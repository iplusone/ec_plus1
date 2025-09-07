@extends('layouts.app')
@section('title','注文詳細')
@section('content')
<h1 class="h4 mb-3">注文 {{ $order->number }}</h1>
<table class="table table-sm">
  <thead><tr><th>商品</th><th>数量</th><th class="text-end">小計</th></tr></thead>
  <tbody>
  @foreach($order->items as $it)
    <tr>
      <td>{{ $it->name }} <small class="text-muted">SKU: {{ $it->sku }}</small></td>
      <td>{{ $it->qty }}</td>
      <td class="text-end">¥{{ number_format($it->subtotal) }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
<div class="text-end fs-5">合計：<strong>¥{{ number_format($order->grand_total ?: $order->amount_total) }}</strong></div>

<form method="post" action="{{ route('shop.customer.orders.reorder', [$currentMerchant->slug, $order->number]) }}" class="mt-3">
  @csrf <button class="btn btn-primary">この注文を再注文</button>
</form>
@endsection
