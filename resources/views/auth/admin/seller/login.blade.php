@extends('layouts.app')
@section('title','Sellerログイン')
@section('content')
<h1 class="h4 mb-3">Seller ログイン</h1>
<form method="post" action="{{ route('seller.login') }}" class="vstack gap-3" style="max-width:420px">
  @csrf
  <input name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
  <input name="password" type="password" class="form-control" placeholder="Password" required>
  @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
  <div class="form-check">
    <input class="form-check-input" type="checkbox" name="remember" id="remember">
    <label class="form-check-label" for="remember">ログイン状態を保持</label>
  </div>
  <button class="btn btn-primary">ログイン</button>
</form>
@endsection
