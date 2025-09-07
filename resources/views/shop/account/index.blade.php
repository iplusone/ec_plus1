@extends('layouts.app')
@section('title','マイページ')
@section('content')
<h1 class="h4 mb-3">マイページ</h1>
<p>ようこそ、{{ $customer->name ?? $customer->email }} さん。</p>
<ul>
  <li><a href="{{ route('shop.customer.orders', $currentMerchant->slug) }}">注文履歴</a></li>
</ul>
@endsection
