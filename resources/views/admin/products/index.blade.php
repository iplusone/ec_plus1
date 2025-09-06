@extends('layouts.app')
@section('title','商品一覧')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3">商品一覧</h1>
  <a href="{{ route('admin.products.create') }}" class="btn btn-primary">新規作成</a>
</div>

@if(session('ok'))<div class="alert alert-success">{{ session('ok') }}</div>@endif

<table class="table table-hover">
  <thead><tr><th>ID</th><th>名称</th><th>スラッグ</th><th>状態</th><th>バリアント数</th><th></th></tr></thead>
  <tbody>
    @foreach($products as $p)
    <tr>
      <td>{{ $p->id }}</td>
      <td>{{ $p->name }}</td>
      <td>{{ $p->slug }}</td>
      <td>{!! $p->is_active ? '<span class="badge bg-success">公開</span>' : '<span class="badge bg-secondary">非公開</span>' !!}</td>
      <td>{{ $p->variants_count }}</td>
      <td class="text-end">
        <a href="{{ route('admin.products.edit',$p) }}" class="btn btn-sm btn-outline-primary">編集</a>
        <form method="post" action="{{ route('admin.products.destroy',$p) }}" class="d-inline" onsubmit="return confirm('削除しますか？')">
          @csrf @method('delete')
          <button class="btn btn-sm btn-outline-danger">削除</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
{{ $products->links() }}
@endsection
