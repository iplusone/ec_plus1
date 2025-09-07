@extends('layouts.app')
@section('title','注文詳細')
@section('content')
<h1 class="h4 mb-3">注文：{{ $order->number }}</h1>
@if(session('ok'))<div class="alert alert-success">{{ session('ok') }}</div>@endif

<div class="row g-3">
  <div class="col-md-6">
    <div class="card p-3">
      <h2 class="h6">金額</h2>
      <table class="table table-sm w-auto">
        <tr><th>小計（税抜）</th><td class="text-end">¥{{ number_format($order->subtotal_excl_tax) }}</td></tr>
        <tr><th>税10%</th><td class="text-end">¥{{ number_format($order->tax_10_amount) }}</td></tr>
        <tr><th>税8%</th><td class="text-end">¥{{ number_format($order->tax_8_amount) }}</td></tr>
        <tr><th>送料</th><td class="text-end">¥{{ number_format($order->shipping_fee) }}</td></tr>
        <tr class="table-active"><th>合計</th><td class="text-end">¥{{ number_format($order->grand_total) }}</td></tr>
      </table>
    </div>
    <div class="card p-3 mt-3">
      <h2 class="h6">明細</h2>
      <table class="table table-sm">
        <thead><tr><th>商品</th><th>数量</th><th class="text-end">小計(税込)</th><th>税率</th><th class="text-end">税額</th></tr></thead>
        <tbody>
        @foreach($order->items as $it)
          <tr>
            <td>{{ $it->name }}<br><small>SKU: {{ $it->sku }}</small></td>
            <td>{{ $it->qty }}</td>
            <td class="text-end">¥{{ number_format($it->subtotal) }}</td>
            <td>{{ $it->tax_rate }}%</td>
            <td class="text-end">¥{{ number_format($it->tax_amount) }}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card p-3">
      <h2 class="h6">出荷</h2>
      <form method="post" action="{{ route('admin.orders.update',$order) }}" class="vstack gap-2">
        @csrf @method('PUT')
        <div>
          <label class="form-label">出荷ステータス</label>
          <select name="shipping_status" class="form-select">
            @foreach(['pending'=>'未出荷','picking'=>'ピッキング','shipped'=>'出荷済','delivered'=>'配達完了'] as $k=>$v)
              <option value="{{ $k }}" @selected($order->shipping_status===$k)>{{ $v }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="form-label">追跡番号</label>
          <input class="form-control" name="tracking_number" value="{{ old('tracking_number',$order->tracking_number) }}">
        </div>
        <div>
          <label class="form-label">注文ステータス</label>
          <select name="status" class="form-select">
            @foreach(['paid','cancelled','refunded'] as $s)
              <option value="{{ $s }}" @selected($order->status===$s)>{{ $s }}</option>
            @endforeach
          </select>
        </div>
        <div class="text-end mt-2">
          <button class="btn btn-primary">保存</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
