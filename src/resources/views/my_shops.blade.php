@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/my_shops.css') }}">
@endsection

@section('main')
@if($shops->isEmpty())
    <div class="no-results">
        <p>店舗はありません。</p>
    </div>
@else
<h2 class="ttl">My Shops</h2>
<div class="shop-card__wrapper">
    @foreach($shops as $shop)
    <div class="shop-card">
        @if(is_array($shop->photo))
            <img src="{{ asset('storage/shop_images/' . $shop->photo[0]) }}" alt="{{ $shop->name }}" class="shop-card__img">
        @else
            <img src="{{ $shop->photo }}" alt="{{ $shop->name }}" class="shop-card__img">
        @endif
        <div class="shop-card__content">
            <h3 class="shop-card__name">{{ $shop->name }}</h3>
            <div class="shop-card__details">
                <p class="shop-card__text-area">#{{ $shop->area->name }}</p>
                @foreach($shop->genres as $genre)
                    <p class="shop-card__text-genre">#{{ $genre->name }}</p>
                @endforeach
            </div>
            <div class="shop-card__actions">
                <a href="/my-shops/edit/{{ $shop->id }}" class="shop-card__btn">店舗情報更新</a>
                <a href="/courses/{{ $shop->id }}" class="shop-card__btn">コース情報管理</a>
                <a href="/my-shops/reservation/{{ $shop->id }}/0" class="shop-card__btn">予約状況確認</a>
                <a href="/create-mail/{{ $shop->id }}" class="shop-card__btn">メール送信</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection