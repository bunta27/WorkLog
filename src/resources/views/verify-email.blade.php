@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="authenticate verify">
    <p class="verify__message">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="verify__primary-btn">
            認証はこちらから
        </button>
    </form>

    <form method="POST" action="{{ route('verification.send') }}" class="verify__resend-form">
        @csrf
        <button type="submit" class="verify__resend-link">
            認証メールを再送する
        </button>
    </form>
</div>
@endsection
