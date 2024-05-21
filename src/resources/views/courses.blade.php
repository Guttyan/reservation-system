@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/courses.css') }}">
@endsection

@section('main')
<h2 class="shop-name">{{ $shop->name }}</h2>
@error('name')
    <div class="form__error">
        <p>{{ $message }}</p>
    </div>
@enderror
@error('price')
    @if(!$errors->has('name'))
        <div class="form__error">
            <p>{{ $message }}</p>
        </div>
    @endif
@enderror
<div class="create-course__wrapper">
    <h3 class="create-form__header">コース新規作成</h3>
    <form action="/course/create" class="create-form" method="POST">
        @csrf
        <input type="text" name="name" class="create-form__input-name" placeholder="コース名">
        <input type="number" name="price" class="create-form__input-price" placeholder="コース料金　　円">
        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
        <button class="create-form__btn">コース作成</button>
    </form>
</div>
<table class="courses-table">
    <tr class="courses-table__row">
        <th class="courses-table__header courses-table__name">コース名</th>
        <th class="courses-table__header courses-table__price">料金</th>
        <th class="courses-table__header courses-table__update"></th>
        <th class="courses-table__header courses-table__delete"></th>
    </tr>
    @foreach($courses as $course)
    <tr class="courses-table__row">
        <form action="/course/update" method="POST">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
            <td class="courses-table__item">
                <input type="text" class="update__input-name" value="{{ $course->name }}" name="name">
            </td>
            <td class="courses-table__item">
                <input type="number" class="update__input-price" value="{{ $course->price }}" name="price">円
            </td>
            <td class="courses-table__item"><button class="update__btn">更　新</button></td>
        </form>
        <form action="/course/delete" method="POST">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
            <td class="courses-table__item"><button class="delete__btn">削　除</button></td>
        </form>
    </tr>
    @endforeach
</table>


@endsection