@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('main')
<div class="content">
    <h3 class="content__ttl">Login</h3>
    <form action="/login" method="POST" class="input-form">
        @csrf
        <ul class="input-form__list">
            <li class="input-form__item">
                <i class="fa-solid fa-envelope input-form__icon"></i>
                <input type="email" class="input-form__column" name="email" placeholder="Email" value="{{ old('email') }}">
            </li>
            <div class="form__error">
                @error('email')
                <p>{{ $message }}</p>
                @enderror
            </div>
            <li class="input-form__item">
                <i class="fa-solid fa-lock input-form__icon"></i>
                <input type="password" class="input-form__column" name="password" placeholder="Password">
            </li>
            <div class="form__error">
                @error('password')
                <p>{{ $message }}</p>
                @enderror
            </div>
        </ul>
        <button class="input-form__btn">ログイン</button>
    </form>
</div>
@endsection