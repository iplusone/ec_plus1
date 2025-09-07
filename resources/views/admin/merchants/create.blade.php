@extends('layouts.admin')
@section('title','マーチャント追加')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">マーチャント追加</h1>
    <a href="{{ route('admin.merchants.index') }}" class="btn btn-outline-secondary btn-sm">一覧に戻る</a>
  </div>

  <form method="post" action="{{ route('admin.merchants.store') }}" class="card card-body">
    @include('admin.merchants._form', ['merchant' => $merchant ?? null])
    <div class="mt-3">
      <button class="btn btn-primary">保存</button>
    </div>
  </form>
@endsection
