@extends('layouts.app')
@section('title',$variant->exists?'バリアント編集':'バリアント作成')
@section('content')
<h1 class="h3 mb-3">{{ $variant->exists?'バリアント編集':'バリアント作成' }}</h1>

<form method="post" action="{{ $variant->exists ? route('admin.variants.update',$variant) : route('admin.products.variants.store',$product) }}" class="vstack gap-3">
  @csrf
  @if($variant->exists) @method('put') @endif

  <input type="hidden" name="product_id" value="{{ $product->id }}">

  <div>
    <label class="form-label">SKU</label>
    <input name="sku" class="form-control" value="{{ old('sku',$variant->sku) }}" required>
  </div>
  <div>
    <label class="form-label">価格</label>
    <input name="price_amount" type="number" class="form-control" value="{{ old('price_amount',$variant->price_amount) }}" required>
  </div>
  <div>
    <label class="form-label">通貨</label>
    <input name="currency" class="form-control" value="{{ old('currency',$variant->currency ?? 'JPY') }}" required>
  </div>
  <div>
    <label class="form-label">在庫</label>
    <input name="stock" type="number" class="form-control" value="{{ old('stock',$variant->stock) }}" required>
  </div>

  <button class="btn btn-primary">保存</button>
  <a href="{{ route('admin.products.edit',$product) }}" class="btn btn-outline-secondary">戻る</a>
</form>
@endsection
