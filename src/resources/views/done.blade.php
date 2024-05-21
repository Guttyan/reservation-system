@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/done.css') }}">
@endsection


@section('main')
<div class="content">
    <h2 class="content__text">ご予約ありがとうございます</h2>
    <a href="/" class="return__link">戻る</a>
</div>
@endsection