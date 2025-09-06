@extends('layouts.app')
@section('title','ご注文ありがとうございます')
@section('content')
<div class="text-center py-5">
  <h1>ご注文ありがとうございます</h1>
  <p class="mt-3">注文番号：<strong>{{ $number }}</strong></p>
  <a class="btn btn-outline-primary mt-3" href="{{ route('shop.index') }}">トップへ戻る</a>
</div>
@endsection
