@extends('layouts.app')
@section('title', $merchant->name . ' / 受注一覧')

@section('content')
<div class="container">
  <h1 class="h4 mb-3">{{ $merchant->name }} / 受注一覧</h1>
  @if(session('ok'))<div class="alert alert-success">{{ session('ok') }}</div>@endif

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th><th>注文番号</th><th>状態</th><th class="text-end">合計</th><th>日時</th><th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $o)
          <tr>
            <td>{{ $o->id }}</td>
            <td>{{ $o->number }}</td>
            <td>{{ $o->status }}</td>
            <td class="text-end">{{ number_format($o->total_amount) }} {{ $o->currency }}</td>
            <td>{{ $o->created_at }}</td>
            <td>
              <a class="btn btn-sm btn-primary"
                 href="{{ route('seller.orders.show', [$merchant->slug, $o]) }}">詳細</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{ $orders->links() }}
</div>
@endsection
