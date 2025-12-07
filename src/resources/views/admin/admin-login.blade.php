@extends('layouts.admin-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/admin-login.css') }}">
@endsection

@section('content')
<div class="login__content">
    <div class="login__header">
        <h2 class="login__header--item">管理者ログイン</h2>
    </div>
    <form class="form" action="/admin/login" method="post">
        @csrf
        <div class="form__group">
            <label class="form__group--label" for="email">メールアドレス</label>
            <input class="form__group--input" type="email" name="email" value="{{ old('email') }}">
            <div class="form__error">
                @error('email')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form__group">
            <label class="form__group--label" for="password">パスワード</label>
            <input class="form__group--input" type="password" name="password">
            <div class="form__error">
                @error('password')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form__button">
            <button class="form__button--submit" type="submit">管理者ログインする</button>
        </div>
    </form>
</div>
@endsection