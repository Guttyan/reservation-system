@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user_detail.css') }}">
@endsection

@section('main')
<div class="user-wrapper">
    <table class="user-table">
        <tr class="user-table__row">
            <td class="user-table__head">Name</td>
            <td class="user-table__data">{{ $user->name }}</td>
        </tr>
        <tr class="user-table__row">
            <td class="user-table__head">Email</td>
            <td class="user-table__data">{{ $user->email }}</td>
        </tr>
        <tr class="user-table__row">
            <td class="user-table__head">Registration Date</td>
            <td class="user-table__data">{{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d') }}</td>
        </tr>
    </table>
    <div class="btn-block">
        <form action="/representative/create" class="create-btn" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $user->id }}">
            <button class="create-btn__inner">店舗代表者に設定</button>
        </form>
        <div class="back-btn">
            <a href="/admin" class="back-btn__inner">戻る</a>
        </div>
    </div>
</div>
@endsection