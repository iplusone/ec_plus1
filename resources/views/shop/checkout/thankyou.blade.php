@extends('layouts.app')
@section('title','ご注文完了')
@section('content')
<h1 class="h4">ご注文ありがとうございます</h1>
<p>注文番号：<strong>{{ $order->number }}</strong></p>
<p>合計：¥{{ number_format($order->amount_total) }}</p>

<table class="table w-auto">
  <tr><th class="text-end">小計（税抜）</th><td class="text-end">¥{{ number_format($order->subtotal_excl_tax) }}</td></tr>
  <tr><th class="text-end">消費税10%</th><td class="text-end">¥{{ number_format($order->tax_10_amount) }}</td></tr>
  <tr><th class="text-end">軽減税率8%</th><td class="text-end">¥{{ number_format($order->tax_8_amount) }}</td></tr>
  <tr><th class="text-end">送料</th><td class="text-end">¥{{ number_format($order->shipping_fee) }}</td></tr>
  @if($order->discount_amount>0)
  <tr><th class="text-end">割引</th><td class="text-end">-¥{{ number_format($order->discount_amount) }}</td></tr>
  @endif
  <tr class="table-active"><th class="text-end">合計</th><td class="text-end fs-5">¥{{ number_format($order->grand_total) }}</td></tr>
</table>

<a class="btn btn-primary" href="{{ route('shop.index', $currentMerchant->slug) }}">トップへ戻る</a>
@endsection



<a class="btn btn-primary" href="{{ route('shop.index', $currentMerchant->slug) }}">トップへ戻る</a>
@endsection
