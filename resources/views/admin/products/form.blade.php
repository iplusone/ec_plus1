@extends('layouts.app')
@section('title',$product->exists?'商品編集':'商品作成')
@section('content')
<h1 class="h3 mb-3">{{ $product->exists?'商品編集':'商品作成' }}</h1>

@if(session('ok'))<div class="alert alert-success">{{ session('ok') }}</div>@endif
@if($errors->any())<div class="alert alert-danger">入力に誤りがあります。</div>@endif

<form method="post" action="{{ $product->exists ? route('admin.products.update',$product) : route('admin.products.store') }}" class="vstack gap-3">
  @csrf
  @if($product->exists) @method('put') @endif

  <div>
    <label class="form-label">名称</label>
    <input name="name" class="form-control" required value="{{ old('name',$product->name) }}">
  </div>
  <div>
    <label class="form-label">スラッグ</label>
    <input name="slug" class="form-control" required value="{{ old('slug',$product->slug) }}">
  </div>
  <div>
    <label class="form-label">説明</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description',$product->description) }}</textarea>
  </div>
  <div class="form-check">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active',$product->is_active)?'checked':'' }}>
    <label class="form-check-label" for="is_active">公開する</label>
  </div>

  <button class="btn btn-primary">保存</button>
  <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">戻る</a>
</form>

@if($product->exists)
<hr>
<h2 class="h5">バリアント</h2>
<a href="{{ route('admin.products.variants.create',$product) }}" class="btn btn-sm btn-outline-primary mb-2">バリアント追加</a>
<table class="table">
  <thead><tr><th>SKU</th><th>価格</th><th>在庫</th><th></th></tr></thead>
  <tbody>
    @foreach($product->variants as $v)
    <tr>
      <td>{{ $v->sku }}</td>
      <td>¥{{ number_format($v->price_amount) }}</td>
      <td>{{ $v->stock }}</td>
      <td class="text-end">
        <a href="{{ route('admin.variants.edit',$v) }}" class="btn btn-sm btn-outline-primary">編集</a>
        <form method="post" action="{{ route('admin.variants.destroy',$v) }}" class="d-inline">@csrf @method('delete')
          <button class="btn btn-sm btn-outline-danger" onclick="return confirm('削除しますか？')">削除</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

@if($product->exists)
<hr>
<h3 class="h6">画像</h3>

<div class="mb-2">
  <form method="post" action="{{ route('seller.products.thumbnail.upload', [$currentMerchant ?? $currentMerchant ?? app('merchant'), $product]) }}" enctype="multipart/form-data">
    @csrf
    <div class="d-flex align-items-center gap-2">
      <input type="file" name="file" accept="image/*" class="form-control" required>
      <button class="btn btn-outline-primary btn-sm">サムネ更新</button>
    </div>
  </form>
  @if($product->thumbnail_path)
    <img src="{{ asset('storage/'.$product->thumbnail_path) }}" class="mt-2 img-thumbnail" style="max-width:200px;">
  @endif
</div>

<div class="mb-2">
  <form method="post" action="{{ route('seller.products.images.upload', [$currentMerchant ?? app('merchant'), $product]) }}" enctype="multipart/form-data">
    @csrf
    <input type="file" name="files[]" multiple accept="image/*" class="form-control" required>
    <button class="btn btn-outline-primary btn-sm mt-2">画像を追加</button>
  </form>
  <div class="d-flex flex-wrap gap-2 mt-2">
    @foreach($product->images as $img)
      <img src="{{ asset('storage/'.$img->path) }}" class="img-thumbnail" style="width:120px;">
    @endforeach
  </div>
</div>
@endif

@endif
@endsection
