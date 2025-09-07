@extends('layouts.app')
@section('title', $merchant->name . ' / 商品一覧')

@section('content')
<div class="container">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">{{ $merchant->name }} / 商品一覧</h1>
    <a href="{{ route('seller.products.create', $merchant->slug) }}" class="btn btn-primary">
      商品登録
    </a>
  </div>

  @if (session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  @if ($products->isEmpty())
    <div class="text-muted">商品がありません。右上の「商品登録」から作成してください。</div>
  @else
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th style="width: 64px;">ID</th>
            <th style="width: 72px;">画像</th>
            <th>商品名</th>
            <th class="text-end" style="width: 140px;">価格（税込）</th>
            <th class="text-center" style="width: 110px;">公開</th>
            <th class="text-end" style="width: 220px;"></th>
          </tr>
        </thead>
        <tbody>
        @foreach ($products as $p)
          <tr>
            <td>{{ $p->id }}</td>
            <td>
              @php $thumb = $p->thumbnail_path ?? optional($p->images->first())->path; @endphp
              @if ($thumb)
                <img src="{{ asset('storage/'.$thumb) }}" alt="" style="width:56px;height:56px;object-fit:cover;border-radius:6px">
              @endif
            </td>
            <td>
              <div class="fw-semibold">{{ $p->name }}</div>
              <div class="text-muted small">{{ $p->sku ?? '' }}</div>
            </td>
            <td class="text-end">{{ number_format($p->price) }} 円</td>
            <td class="text-center">
              @if ($p->is_published ?? false)
                <span class="badge text-bg-success">公開中</span>
              @else
                <span class="badge text-bg-secondary">下書き</span>
              @endif
            </td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary"
                 href="{{ route('seller.products.edit', [$merchant->slug, $p]) }}">編集</a>
              <form method="post" action="{{ route('seller.products.destroy', [$merchant->slug, $p]) }}"
                    class="d-inline"
                    onsubmit="return confirm('削除しますか？');">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">削除</button>
              </form>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $products->withQueryString()->links() }}
    </div>
  @endif
</div>
@endsection
