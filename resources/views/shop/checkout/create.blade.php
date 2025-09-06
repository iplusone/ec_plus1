@extends('layouts.app')
@section('title','チェックアウト')
@section('content')
<h1 class="mb-3">チェックアウト</h1>

<div class="row">
  <div class="col-md-7">
    <form method="post" action="{{ route('checkout.store') }}" class="vstack gap-3">
      @csrf
      <div>
        <label class="form-label">メールアドレス</label>
        <input name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="form-label">お名前（任意）</label>
        <input name="name" type="text" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <button class="btn btn-primary btn-lg">注文を確定</button>
    </form>
  </div>

  <div class="col-md-5">
    <div class="card">
      <div class="card-header">注文概要</div>
      <div class="card-body">
        <ul class="list-unstyled">
          @foreach($cart->items as $i)
            <li class="d-flex justify-content-between">
              <span>{{ $i->variant->product->name }} × {{ $i->qty }}</span>
              <span>¥ {{ number_format($i->unit_price * $i->qty) }}</span>
            </li>
          @endforeach
        </ul>
        <hr>
        <div class="d-flex justify-content-between">
          <strong>合計</strong>
          <strong>¥ {{ number_format($sum) }}</strong>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
