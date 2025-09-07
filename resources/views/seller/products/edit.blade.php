@extends('layouts.app')
@section('title', $merchant->name . ' / 商品編集')

@section('content')
<div class="container" style="max-width:900px">
  <h1 class="h4 mb-3">{{ $merchant->name }} / 商品編集</h1>

  @if(session('ok'))<div class="alert alert-success">{{ session('ok') }}</div>@endif

  <div class="row g-4">
    <div class="col-md-6">
      <form method="post" action="{{ route('seller.products.update', [$merchant->slug, $product]) }}">
        @csrf @method('PUT')
        <div class="mb-3">
          <label class="form-label">商品名</label>
          <input type="text" name="name" value="{{ old('name',$product->name) }}" class="form-control" required>
          @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">価格（税込）</label>
          <input type="number" name="price" value="{{ old('price',$product->price) }}" class="form-control" required>
          @error('price')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">説明</label>
          <textarea name="description" class="form-control" rows="4">{{ old('description',$product->description) }}</textarea>
          @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="form-check mb-3">
          <input type="checkbox" name="is_published" value="1" class="form-check-input" id="pub"
                 @checked(old('is_published',$product->is_published))>
          <label class="form-check-label" for="pub">公開する</label>
        </div>

        <button class="btn btn-primary">更新</button>
      </form>
    </div>

    <div class="col-md-6">
      @include('seller.products._media', ['merchant'=>$merchant, 'product'=>$product])
    </div>
  </div>

  <a class="btn btn-link mt-3" href="{{ route('seller.products.index', $merchant->slug) }}">← 一覧へ</a>
</div>
@endsection
