@extends('layouts.app')
@section('title','ご注文完了')
@section('content')
<h1 class="h4">ご注文ありがとうございます</h1>
<p>注文番号：<strong>{{ $order->number }}</strong></p>
<p>合計：¥{{ number_format($order->amount_total) }}</p>
<a class="btn btn-primary" href="{{ route('shop.index', $currentMerchant->slug) }}">トップへ戻る</a>
@endsection
