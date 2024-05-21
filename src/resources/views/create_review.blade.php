@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/create_review.css') }}">
@endsection

@section('main')
<div class="shop-wrapper">
    <div class="shop-header">
        <a href="/reservations/completed" class="back-btn"><i class="fa-solid fa-chevron-left"></i></a>
        <h2 class="shop-name">{{ $shop->name }}</h2>
    </div>
    @if(is_array($shop->photo))
        @if(count($shop->photo) > 1)
            <ul class="slideshow-fade">
                @foreach($shop->photo as $image)
                    <li class="slideshow-fade__li">
                        <img class="slideshow-fade__img" src="{{ asset('storage/shop_images/' . $image) }}" alt="{{ $shop->name }}">
                    </li>
                @endforeach
            </ul>
        @else
            <img src="{{ asset('storage/shop_images/' . $shop->photo[0]) }}" alt="{{ $shop->name }}" class="shop-image">
        @endif
    @else
        <img src="{{ $shop->photo }}" alt="{{ $shop->name }}" class="shop-image">
    @endif
    <p class="shop__text-area">#{{ $shop->area->name }}</p>
        @foreach($shop->genres as $genre)
            <p class="shop__text-genre">#{{ $genre->name }}</p>
        @endforeach
    <p class="shop__text-comment">{{ $shop->explanation }}</p>
</div>

<div class="review-form__wrapper">
    <h2 class="reservation-content__head">予約履歴</h2>
    <div class="reservation-content__table-wrapper">
        <table class="reservation-content__table">
            <tr class="reservation-content__table-row">
                <td class="reservation-content__table-head">Shop</td>
                <td class="reservation-content__table-data">{{ $shop->name }}</td>
            </tr>
            <tr class="reservation-contetn__table-row">
                <td class="reservation-content__table-head">Date</td>
                <td class="reservation-content__table-data">{{ $reservation->date }}</td>
            </tr>
            <tr class="reservation-content__table-row">
                <td class="reservation-content__table-head">Time</td>
                <td class="reservation-content__table-data">{{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}</td>
            </tr>
            <tr class="reservation-content__table-row">
                <td class="reservation-content__table-head">Number</td>
                <td class="reservation-content__table-data">{{ $reservation->number }}人</td>
            </tr>
        </table>
    </div>

    <h2 class="review-form__head">レビューする</h2>
    <form action="/create/review" method="POST">
        @csrf
        <div class="form-group">
            <p class="input-head">５段階評価</p>
            <div class="rating">
                <input type="radio" id="star5" name="rating" value="5" class="rating-input"><label for="star5" class="rating-label"><i class="fa-solid fa-star"></i></label>
                <input type="radio" id="star4" name="rating" value="4" class="rating-input"><label for="star4" class="rating-label"><i class="fa-solid fa-star"></i></label>
                <input type="radio" id="star3" name="rating" value="3" class="rating-input"><label for="star3" class="rating-label"><i class="fa-solid fa-star"></i></label>
                <input type="radio" id="star2" name="rating" value="2" class="rating-input"><label for="star2" class="rating-label"><i class="fa-solid fa-star"></i></label>
                <input type="radio" id="star1" name="rating" value="1" class="rating-input"><label for="star1" class="rating-label"><i class="fa-solid fa-star"></i></label>
            </div>
            <div class="form__error">
                @error('rating')
                    <p>{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="form-group">
            <p class="input-head">コメント</p>
            <textarea name="comment" rows="5" class="comment-input">{{ old('comment') }}</textarea>
        </div>
        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
        <button class="review-form__btn">レビューする</button>
    </form>
</div>

<script>
    // スライドショー
    $(function(){
        $(".slideshow-fade__li").css({"position":"relative","overflow":"hidden"});
        $(".slideshow-fade__li").hide().css({"position":"absolute","top":0,"left":0});
        $(".slideshow-fade__li:first").addClass("fade").show();
        setInterval(function(){
            var $active = $(".slideshow-fade__li.fade");
            var $next = $active.next("li").length?$active.next("li"):$(".slideshow-fade__li:first");
            $active.fadeOut(1500).removeClass("fade");
            $next.fadeIn(1500).addClass("fade");
        },5000);
    });
</script>

@endsection