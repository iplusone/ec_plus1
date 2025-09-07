@extends('layouts.admin')
@section('title','マーチャント編集')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">マーチャント編集</h1>
    <a href="{{ route('admin.merchants.index') }}" class="btn btn-outline-secondary btn-sm">一覧に戻る</a>
  </div>

  <form method="post" action="{{ route('admin.merchants.update', $merchant) }}" class="card card-body">
    @method('PUT')
    @include('admin.merchants._form', ['merchant' => $merchant])
    <div class="mt-3">
      <button class="btn btn-primary">更新</button>
    </div>
  </form>
@endsection
