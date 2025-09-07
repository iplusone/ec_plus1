@csrf
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">名称</label>
    <input name="name" value="{{ old('name', $merchant->name ?? '') }}" required class="form-control">
  </div>
  <div class="col-md-6">
    <label class="form-label">名称かな</label>
    <input name="name_kana" value="{{ old('name_kana', $merchant->name_kana ?? '') }}" required class="form-control">
  </div>
  <div class="col-md-3">
    <label class="form-label">コード</label>
    <input name="code" value="{{ old('code', $merchant->code ?? '') }}" class="form-control">
  </div>
  <div class="col-md-5">
    <label class="form-label">メール</label>
    <input type="email" name="email" value="{{ old('email', $merchant->email ?? '') }}" class="form-control">
  </div>
  <div class="col-md-4">
    <label class="form-label">電話</label>
    <input name="phone" value="{{ old('phone', $merchant->phone ?? '') }}" class="form-control">
  </div>
  <div class="col-md-3">
    <label class="form-label">郵便番号</label>
    <input name="zip" value="{{ old('zip', $merchant->zip ?? '') }}" class="form-control">
  </div>
  <div class="col-md-9">
    <label class="form-label">住所</label>
    <input name="address" value="{{ old('address', $merchant->address ?? '') }}" class="form-control">
  </div>
  <div class="col-md-3">
    <label class="form-label">状態</label>
    <select name="is_active" class="form-select">
      <option value="1" @selected(old('is_active', $merchant->is_active ?? 1)==1)>有効</option>
      <option value="0" @selected(old('is_active', $merchant->is_active ?? 1)==0)>無効</option>
    </select>
  </div>
</div>

@if($errors->any())
  <div class="alert alert-danger mt-3">
    <ul class="mb-0">
      @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
  </div>
@endif
