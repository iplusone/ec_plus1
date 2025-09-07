@extends('layouts.app')
@section('title', $merchant->name . ' / 商品登録')

@section('content')
<div class="container" style="max-width:640px">
  <h1 class="h4 mb-3">{{ $merchant->name }} / 商品登録</h1>

  <form method="post" action="{{ route('seller.products.store', $merchant->slug) }}">
    @csrf
    <div class="mb-3">
      <label class="form-label">商品名</label>
      <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
      @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
      <label class="form-label">価格（税込）</label>
      <input type="number" name="price" value="{{ old('price') }}" class="form-control" required>
      @error('price')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
      <label class="form-label">説明</label>
      <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
      @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="form-check mb-3">
      <input type="checkbox" name="is_published" value="1" class="form-check-input" id="pub">
      <label class="form-check-label" for="pub">公開する</label>
    </div>

    <button class="btn btn-primary w-100">登録</button>
  </form>
</div>
@endsection
