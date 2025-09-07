@extends('layouts.app')
@section('title','注文一覧')
@section('content')
<h1 class="h4 mb-3">注文一覧</h1>

<form class="row g-2 mb-3">
  <div class="col-auto"><input class="form-control" type="text" name="number" value="{{ request('number') }}" placeholder="注文番号"></div>
  <div class="col-auto">
    <select name="status" class="form-select">
      <option value="">-- ステータス --</option>
      @foreach(['pending','paid','cancelled','refunded'] as $s)
        <option value="{{ $s }}" @selected(request('status')===$s)>{{ $s }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-auto"><button class="btn btn-outline-primary">検索</button></div>
</form>

<table class="table table-sm align-middle">
  <thead><tr>
    <th>#</th><th>番号</th><th>店舗</th><th class="text-end">合計</th><th>支払</th><th>出荷</th><th></th>
  </tr></thead>
  <tbody>
  @foreach($orders as $o)
    <tr>
      <td>{{ $o->id }}</td>
      <td><a href="{{ route('admin.orders.show',$o) }}">{{ $o->number }}</a></td>
      <td>{{ $o->merchant?->name ?? '-' }}</td>
      <td class="text-end">¥{{ number_format($o->grand_total ?: $o->amount_total) }}</td>
      <td>{{ $o->status }}/{{ $o->payment_status }}</td>
      <td>{{ $o->shipping_status ?? '-' }}</td>
      <td class="text-end"><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.orders.show',$o) }}">詳細</a></td>
    </tr>
  @endforeach
  </tbody>
</table>

{{ $orders->links() }}
@endsection
