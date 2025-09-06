@extends('layouts.app')
@section('title','新規登録')
@section('content')
<h1 class="h4 mb-3">新規登録</h1>
<form method="post" action="{{ route('customer.register') }}" class="vstack gap-3" style="max-width:480px">
  @csrf
  <input name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
  <input name="name" type="text" class="form-control" placeholder="お名前（任意）" value="{{ old('name') }}">
  <input name="password" type="password" class="form-control" placeholder="パスワード" required>
  <input name="password_confirmation" type="password" class="form-control" placeholder="パスワード（確認）" required>
  @if($errors->any())<div class="text-danger small">入力に誤りがあります。</div>@endif
  <button class="btn btn-primary">登録してログイン</button>
</form>
@endsection
