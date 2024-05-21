@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reservation_edit.css') }}">
@endsection

@section('main')
<div class="shop-wrapper">
    <div class="shop-header">
        <a href="/mypage" class="back-btn"><i class="fa-solid fa-chevron-left"></i></a>
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

    @if($reviews->isNotEmpty())
        <div class="reviews-wrapper">
            <div class="average-rating">
                <p class="average-rating__text">平均評価:</p>
                <h3 class="average-rating__number"> {{ $averageRating }}</h3>
                <div class="rating-stars">
                    @php
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
            <button id="showAllReviews" class="all-review__btn">すべてのレビューを表示</button>
            <div id="all-reviews" style="display:none;" class="all-reviews">
                @foreach ($reviews as $review)
                    <div class="review">
                        <div class="review__header">
                            {{ $review->user->name }}: {{ $review->rating }}
                            @for ($i = 0; $i < $review->rating; $i++)
                            <i class="fa-solid fa-star" style="color: #FFD700;"></i>
                            @endfor
                        </div>
                        <p class="review__comment">{{ $review->comment }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<div class="reservation-form__wrapper">
<form action="/reservation/edit" class="reservation-form" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $reservation->id }}">
    <h2 class="reservation-form__head">予約変更</h2>
    <input type="date" name="date" class="reservation-form__input-date" value="{{ old('date', $reservation->date) }}">
    <div class="form__error">
        @error('date')
        <p>{{ $message }}</p>
        @enderror
    </div>
    <div class="reservation-form__input">
        <select class="reservation-form__input-time" name="time">
                @for ($hour = 8; $hour < 24; $hour++)
                    @for ($minute = 0; $minute < 60; $minute += 15)
                        @php
                            $formatted_hour = str_pad($hour, 2, '0', STR_PAD_LEFT);
                            $formatted_minute = str_pad($minute, 2, '0', STR_PAD_LEFT);
                            $time = $formatted_hour . ':' . $formatted_minute;
                        @endphp
                        <option value="{{ $time }}" @if(\Carbon\Carbon::parse($reservation->time)->format('H:i') == $time) selected @endif>{{ $time }}</option>
                    @endfor
                @endfor
        </select>
        <i class="fa-solid fa-sort-down"></i>
    </div>
    <div class="form__error">
        @error('time')
        <p>{{ $message }}</p>
        @enderror
    </div>
    <div class="reservation-form__input">
        <select class="reservation-form__input-number" name="number">
            @for ($number = 1; $number <= 10; $number++)
            <option value="{{ $number }}" @if(old('number', $reservation->number) == $number) selected @endif>{{ $number }}人</option>
            @endfor
        </select>
        <i class="fa-solid fa-sort-down"></i>
    </div>
        <div class="form__error">
        @error('number')
        <p>{{ $message }}</p>
        @enderror
    </div>
    <div class="reservation-form__table-wrapper">
        <table class="reservation-form__table">
            <tr class="reservation-form__table-row">
                <td class="reservation-form__table-head">Shop</td>
                <td class="reservation-form__table-data">{{ $shop->name }}</td>
            </tr>
            <tr class="reservation-form__table-row">
                <td class="reservation-form__table-head">Date</td>
                <td class="reservation-form__table-data reservation-form__table-data--date">{{ $reservation->date }}</td>
            </tr>
            <tr class="reservation-form__table-row">
                <td class="reservation-form__table-head">Time</td>
                <td class="reservation-form__table-data reservation-form__table-data--time"></td>
            </tr>
            <tr class="reservation-form__table-row">
                <td class="reservation-form__table-head">Number</td>
                <td class="reservation-form__table-data reservation-form__table-data--number"></td>
            </tr>
        </table>
    </div>
    <button class="reservation-form__reserve-btn">予約内容変更</button>
</form>
<form action="/reservation/cancel" class="reservation-cancel-form" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $reservation->id }}">
    <button type="submit" class="reservation-cancel-form__cancel-btn">予約キャンセル</button>
</form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dateInput = document.querySelector('.reservation-form__input-date');
        const timeInput = document.querySelector('.reservation-form__input-time');
        const numberInput = document.querySelector('.reservation-form__input-number');
        const dateData = document.querySelector('.reservation-form__table-data--date');
        const timeData = document.querySelector('.reservation-form__table-data--time');
        const numberData = document.querySelector('.reservation-form__table-data--number');

        dateData.textContent = dateInput.value;
        timeData.textContent = timeInput.value;
        numberData.textContent = numberInput.value + '人';

        dateInput.addEventListener('input', function () {
            dateData.textContent = this.value;
        });

        timeInput.addEventListener('change', function () {
            timeData.textContent = this.value;
        });

        numberInput.addEventListener('change', function () {
            if (this.value !== '') {
                numberData.textContent = this.options[this.selectedIndex].text;
            } else {
                numberData.textContent = '';
            }
        });
    });

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

    // レビュー表示
    document.getElementById("showAllReviews").addEventListener("click", function () {
        var allReviews = document.getElementById("all-reviews");
        if (allReviews.style.display === "none") {
            allReviews.style.display = "block";
        } else {
            allReviews.style.display = "none";
        }
    });


</script>

@endsection