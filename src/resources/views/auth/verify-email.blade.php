@extends('layouts.app')
@section('title','メール認証')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="verify-wrap">
 

  <p class="verify-lead">
    登録していただいたメールアドレスに認証メールを送信しました。<br>
    メールをご確認ください。
  </p>

  
  <a href="{{ route('login') }}" class="btn-secondary">認証はこちらから</a>

  
  @if (session('status') === 'verification-link-sent')
    <div class="flash-info">認証メールを再送しました！</div>
  @endif

  
  <form method="POST" action="{{ route('verification.send') }}" class="verify-resend">
    @csrf
    <button type="submit" class="link-button">認証メールを再送する</button>
  </form>
</div>
@endsection



