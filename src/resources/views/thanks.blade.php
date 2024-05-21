@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection


@section('main')
<div class="content">
    <h2 class="content__text">会員登録ありがとうございます</h2>
    <a href="/login" class="login__link">ログインする</a>
</div>
@endsection