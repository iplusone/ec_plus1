@extends('layouts.app')
@section('title','注文履歴')
@section('content')
<h1 class="h4 mb-3">注文履歴</h1>
<table class="table align-middle">
  <thead><tr><th>日時</th><th>注文番号</th><th class="text-end">金額</th><th>ステータス</th><th></th></tr></thead>
  <tbody>
  @foreach($orders as $o)
    <tr>
      <td>{{ $o->created_at }}</td>
      <td><a href="{{ route('shop.customer.orders.show', [$currentMerchant->slug, $o->number]) }}">{{ $o->number }}</a></td>
      <td class="text-end">¥{{ number_format($o->grand_total ?: $o->amount_total) }}</td>
      <td>{{ $o->status }}</td>
      <td class="text-end">
        <form method="post" action="{{ route('shop.customer.orders.reorder', [$currentMerchant->slug, $o->number]) }}">
          @csrf <button class="btn btn-sm btn-primary">再注文</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $orders->links() }}
@endsection
