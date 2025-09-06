@extends('layouts.app')
@section('title','お客様情報')
@section('content')
<h1 class="h4 mb-3">お客様情報</h1>
@if($errors->any())<div class="alert alert-danger">入力に誤りがあります。</div>@endif
<form method="post" action="{{ route('checkout.store', $currentMerchant->slug) }}" class="row g-3">
  @csrf
  <div class="col-md-6">
    <label class="form-label">メールアドレス</label>
    <input name="email" type="email" class="form-control" required value="{{ old('email') }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">氏名</label>
    <input name="name" class="form-control" value="{{ old('name') }}">
  </div>
  <div class="col-md-3"><label class="form-label">郵便番号</label><input name="postal" class="form-control" value="{{ old('postal') }}"></div>
  <div class="col-md-3"><label class="form-label">都道府県</label><input name="pref" class="form-control" value="{{ old('pref') }}"></div>
  <div class="col-md-3"><label class="form-label">市区町村</label><input name="city" class="form-control" value="{{ old('city') }}"></div>
  <div class="col-md-6"><label class="form-label">住所1</label><input name="line1" class="form-control" value="{{ old('line1') }}"></div>
  <div class="col-md-6"><label class="form-label">住所2</label><input name="line2" class="form-control" value="{{ old('line2') }}"></div>
  <div class="col-md-4"><label class="form-label">電話</label><input name="tel" class="form-control" value="{{ old('tel') }}"></div>

  <div class="col-12 form-check mt-2">
    <input class="form-check-input" type="checkbox" name="create_account" id="ca" value="1">
    <label class="form-check-label" for="ca">アカウントを作成する</label>
  </div>
  <div class="col-md-6">
    <input name="password" type="password" class="form-control" placeholder="パスワード（任意・8文字以上）">
  </div>

  <div class="col-12 text-end">
    <button class="btn btn-primary">注文を確定する</button>
  </div>
</form>
@endsection
