@php
  $thumb = $product->thumbnail_path ?? optional($product->images->first())->path;
@endphp

<div class="card">
  <div class="card-header">画像</div>
  <div class="card-body">
    {{-- サムネイル --}}
    <div class="mb-3">
      <div class="mb-2">サムネイル</div>
      <div class="d-flex align-items-center gap-3">
        <div style="width:96px;height:96px;border:1px solid #eee;border-radius:8px;display:flex;align-items:center;justify-content:center;overflow:hidden">
          @if($thumb)
            <img src="{{ asset('storage/'.$thumb) }}" style="width:100%;height:100%;object-fit:cover">
          @else
            <span class="text-muted small">No Image</span>
          @endif
        </div>
        <form method="post" action="{{ route('seller.products.thumbnail.upload', [$merchant->slug, $product]) }}" enctype="multipart/form-data">
          @csrf
          <input type="file" name="file" accept="image/*" class="form-control mb-2" required style="max-width:260px">
          <button class="btn btn-outline-primary btn-sm">サムネイルをアップロード</button>
        </form>

        @if($product->thumbnail_path)
          <form method="post" action="{{ route('seller.products.thumbnail.destroy', [$merchant->slug, $product]) }}"
                onsubmit="return confirm('サムネイルを削除しますか？');">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger btn-sm">サムネイル削除</button>
          </form>
        @endif
      </div>
      @error('file')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
    </div>

    {{-- ギャラリー --}}
    <div class="mb-3">
      <div class="mb-2">追加画像</div>
      <div class="d-flex flex-wrap gap-2 mb-2">
        @foreach($product->images as $img)
          <div class="position-relative" style="width:96px;height:96px;border:1px solid #eee;border-radius:8px;overflow:hidden">
            <img src="{{ asset('storage/'.$img->path) }}" style="width:100%;height:100%;object-fit:cover">
            <form method="post" class="position-absolute" style="top:4px;right:4px"
                  action="{{ route('seller.products.images.destroy', [$merchant->slug,$product,$img]) }}"
                  onsubmit="return confirm('画像を削除しますか？');">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger py-0 px-1">×</button>
            </form>
          </div>
        @endforeach
      </div>

      <form method="post" action="{{ route('seller.products.images.upload', [$merchant->slug, $product]) }}"
            enctype="multipart/form-data">
        @csrf
        <input type="file" name="files[]" multiple accept="image/*" class="form-control mb-2" required style="max-width:360px">
        <button class="btn btn-outline-primary btn-sm">画像を追加</button>
      </form>
      @error('files.*')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
    </div>
  </div>
</div>
