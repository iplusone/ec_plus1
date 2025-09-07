@extends('layouts.guest')
@section('title','Seller Login')
@section('content')
<div class="container" style="max-width:420px">
  <h1 class="h4 mb-3">販売者ログイン</h1>
  @if($errors->any())
    <div class="alert alert-danger">メールまたはパスワードが違います。</div>
  @endif
  <form method="post" action="{{ route('merchant.login') }}" class="vstack gap-2">
    @csrf
    <input name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email','merchant@example.com') }}" required>
    <input name="password" type="password" class="form-control" placeholder="Password" value="password" required>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="remember" id="rm">
      <label class="form-check-label" for="rm">ログイン状態を保持</label>
    </div>
    <button class="btn btn-primary w-100">ログイン</button>
  </form>
</div>
@endsection
