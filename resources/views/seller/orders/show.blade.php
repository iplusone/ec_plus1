@extends('layouts.app')
@section('title','受注詳細')

@section('content')
<div class="container" style="max-width:900px">
  <h1 class="h4 mb-3">受注 {{ $order->number }}</h1>
  @if(session('ok'))<div class="alert alert-success">{{ session('ok') }}</div>@endif
  @error('status')<div class="alert alert-danger">{{ $message }}</div>@enderror

  <div class="row g-4">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">概要</div>
        <div class="card-body">
          <dl class="row mb-0">
            <dt class="col-5">状態</dt><dd class="col-7">{{ $order->status }}</dd>
            <dt class="col-5">小計</dt><dd class="col-7">{{ number_format($order->subtotal_amount) }}</dd>
            <dt class="col-5">消費税</dt><dd class="col-7">{{ number_format($order->tax_amount) }}</dd>
            <dt class="col-5">送料</dt><dd class="col-7">{{ number_format($order->shipping_amount) }}</dd>
            <dt class="col-5">割引</dt><dd class="col-7">-{{ number_format($order->discount_amount) }}</dd>
            <dt class="col-5">合計</dt><dd class="col-7 fw-bold">{{ number_format($order->total_amount) }} {{ $order->currency }}</dd>
          </dl>
        </div>
      </div>

      <form class="card mt-3" method="post"
            action="{{ route('seller.orders.update', [$merchant->slug, $order]) }}">
        @csrf @method('PUT')
        <div class="card-header">出荷ステータス更新</div>
        <div class="card-body">
          <div class="input-group" style="max-width:420px">
            <label class="input-group-text">状態</label>
            <select class="form-select" name="status" required>
              @foreach(['pending','processing','shipped','cancelled'] as $s)
                <option value="{{ $s }}" @selected($order->status === $s)>{{ $s }}</option>
              @endforeach
            </select>
            <button class="btn btn-primary">更新</button>
          </div>
        </div>
      </form>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">明細</div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            @foreach($order->items as $it)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <div class="fw-semibold">{{ $it->name }}</div>
                  <div class="text-muted small">x {{ $it->quantity }}</div>
                </div>
                <div>¥{{ number_format($it->unit_price) }}</div>
              </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>

  <a class="btn btn-link mt-3" href="{{ route('seller.orders.index', $merchant->slug) }}">← 一覧へ</a>
</div>
@endsection
