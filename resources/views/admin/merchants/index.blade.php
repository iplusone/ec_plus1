@extends('layouts.admin')
@section('title','マーチャント一覧')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">マーチャント一覧</h1>
    <a href="{{ route('admin.merchants.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i> マーチャント追加
    </a>
  </div>

  {{-- 検索フォーム（kw=名称/かな/コード/メール） --}}
  <form method="get" class="row g-2 mb-3">
    <div class="col-auto">
      <input type="text" name="kw" value="{{ request('kw') }}" class="form-control" placeholder="名称・コード・メールで検索">
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary">検索</button>
    </div>
  </form>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>名称</th>
            <th>コード</th>
            <th>メール</th>
            <th>状態</th>
            <th class="text-end">操作</th>
          </tr>
        </thead>
        <tbody>
          @forelse($merchants as $m)
            <tr>
              <td>{{ $m->id }}</td>
              <td>{{ $m->name }}</td>
              <td>{{ $m->code }}</td>
              <td>{{ $m->email }}</td>
              <td>
                @if($m->is_active)
                  <span class="badge bg-success">有効</span>
                @else
                  <span class="badge bg-secondary">無効</span>
                @endif
              </td>
              <td class="text-end">
                <a href="{{ route('admin.merchants.edit', $m) }}" class="btn btn-sm btn-outline-primary">
                  編集
                </a>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center text-muted">データがありません</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $merchants->links() }}
  </div>
@endsection
