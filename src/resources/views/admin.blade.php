@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('main')
<h2 class="admin-ttl">店舗代表者作成</h2>

<div class="admin-upper">
    <div class="user-search-form">
        <form action="">
            @csrf
            <div class="user-search-form__text">
                <button class="search-form__btn" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                <input type="text" name="keyword" class="user-search__text-input" placeholder="Search ..." value="{{ Request::input('keyword') }}">
            </div>
        </form>
    </div>
    <div class="pagination">
        {{-- {{ $works->appends(request()->except('page'))->links('vendor.pagination.bootstrap-4') }} --}}
    </div>
</div>

<table class="users-table">
    <tr class="users-table__header">
        <th class="users-table__header-item">名前</th>
        <th class="users-table__header-item">メールアドレス</th>
        <th class="users-table__header-item"></th>
    </tr>
    @foreach($users as $user)
        <tr class="users-table__row">
            <td class="users-table__row-name">{{ $user->name }}</td>
            <td class="users-table__row-email">{{ $user->email }}</td>
            <td class="users-table__row-btn">
                <form action="/admin/{{ $user->id }}" method="GET">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <button class="select-btn">選択</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>
@endsection