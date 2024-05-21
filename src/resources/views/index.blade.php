@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('main')
@if($shops->isEmpty())
<div class="no-results">
    <p>該当店舗はありません。</p>
</div>
@else
<div class="shop-card__wrapper">
    @foreach($shops as $shop)
    <div class="shop-card">
        @if(is_array($shop->photo))
            <img src="{{ asset('storage/shop_images/' . $shop->photo[0]) }}" alt="{{ $shop->name }}" class="shop-card__img">
        @else
            <img src="{{ $shop->photo }}" alt="{{ $shop->name }}" class="shop-card__img">
        @endif
        <div class="shop-card__content">
            <div class="shop-card__header">
                <h3 class="shop-card__name">{{ $shop->name }}</h3>
                @if(isset($averageRatings[$shop->id]))
                    <div class="average-rating">
                        <p class="averagi-rating__number">{{ $averageRatings[$shop->id] }}</p>
                        <div class="rating-stars">
                            @php
                                $averageRating = $averageRatings[$shop->id];
                                $fullStars = floor($averageRating);
                                $halfStar = $averageRating - $fullStars >= 0.5;
                            @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $fullStars)
                                    <i class="fa-solid fa-star" style="color: #FFD700;"></i>
                                @elseif ($i == $fullStars + 1 && $halfStar)
                                    <i class="fa-solid fa-star-half-alt" style="color: #FFD700;"></i>
                                @else
                                    <i class="fa-solid fa-star" style="color: #ccc;"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                @endif
            </div>
            <div class="shop-card__details">
                <p class="shop-card__text-area">#{{ $shop->area->name }}</p>
                @foreach($shop->genres as $genre)
                    <p class="shop-card__text-genre">#{{ $genre->name }}</p>
                @endforeach
            </div>
            <div class="shop-card__actions">
                <a href="/detail/{{ $shop->id }}" class="shop-card__btn-detail">詳しくみる</a>
                <form action="/favorite" method="POST">
                    @csrf
                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                    <button class="shop-card__btn-favorite">
                        @if(Auth::user()->favorites->contains('shop_id', $shop->id))
                            <i class="fa-solid fa-heart" style="color:red;"></i>
                        @else
                            <i class="fa-solid fa-heart" style="color:#d8dadf;"></i>
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection