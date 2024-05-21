@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('main')
<div class="content">
    <h3 class="content__ttl">Registration</h3>
    <form action="/register" method="POST" class="input-form">
        @csrf
        <ul class="input-form__list">
            <li class="input-form__item">
                <i class="fa-solid fa-user input-form__icon"></i>
                <input type="text" class="input-form__column" name="name" placeholder="Username" value="{{ old('name') }}">
            </li>
            <div class="form__error">
                @error('name')
                <p>{{ $message }}</p>
                @enderror
            </div>
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
        <button class="input-form__btn">登録</button>
    </form>
</div>
@endsection