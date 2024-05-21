@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('main')
<div class="shop-wrapper">
    <div class="shop-header">
        <a href="/" class="back-btn"><i class="fa-solid fa-chevron-left"></i></a>
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
    <form id="reservation-form" action="/reserve" method="POST">
        @csrf
        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
        <h2 class="reservation-form__head">予約</h2>
        <input type="date" name="date" class="reservation-form__input-date" value="{{ old('date') }}">
        <div class="form__error">
            @error('date')
            <p>{{ $message }}</p>
            @enderror
        </div>
        <div class="reservation-form__input">
            <select class="reservation-form__input-time" name="time">
                <option selected disabled>予約時間</option>
                @include('reservation_time')
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
                <option selected disabled>予約人数</option>
                @for ($number = 1; $number <= 10; $number++)
                <option value="{{ $number }}" @if(old('number') == $number) selected @endif>{{ $number }}人</option>
                @endfor
            </select>
            <i class="fa-solid fa-sort-down"></i>
        </div>
            <div class="form__error">
            @error('number')
            <p>{{ $message }}</p>
            @enderror
        </div>
        @if($courses->isNotEmpty())
            <div class="reservation-form__input">
                <select name="course_id" class="reservation-form__input-course">
                    <option selected disabled>コースを選択する</option>
                    <option value="">コースを選択しない</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" data-price="{{ $course->price }}">{{ $course->name }} - {{ $course->price }}円</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-sort-down"></i>
            </div>
        @endif
        <div class="reservation-form__table-wrapper">
            <table class="reservation-form__table">
                <tr class="reservation-form__table-row">
                    <td class="reservation-form__table-head">Shop</td>
                    <td class="reservation-form__table-data">{{ $shop->name }}</td>
                </tr>
                <tr class="reservation-form__table-row">
                    <td class="reservation-form__table-head">Date</td>
                    <td class="reservation-form__table-data reservation-form__table-data--date"></td>
                </tr>
                <tr class="reservation-form__table-row">
                    <td class="reservation-form__table-head">Time</td>
                    <td class="reservation-form__table-data reservation-form__table-data--time"></td>
                </tr>
                <tr class="reservation-form__table-row">
                    <td class="reservation-form__table-head">Number</td>
                    <td class="reservation-form__table-data reservation-form__table-data--number"></td>
                </tr>
                @if($courses->isNotEmpty())
                    <tr class="reservation-form__table-row">
                        <td class="reservation-form__table-head">Total</td>
                        <td class="reservation-form__table-data reservation-form__table-data--total"></td>
                    </tr>
                @endif
            </table>
        </div>
        @if($courses->isNotEmpty())
            <button id="reserve-with-stripe-btn" class="reservation-form__reserve-btn--stripe">事前決済して予約する</button>
        @endif
        <button class="reservation-form__reserve-btn">予約する</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dateInput = document.querySelector('.reservation-form__input-date');
        const timeInput = document.querySelector('.reservation-form__input-time');
        const numberInput = document.querySelector('.reservation-form__input-number');
        const courseInput = document.querySelector('.reservation-form__input-course');
        const dateData = document.querySelector('.reservation-form__table-data--date');
        const timeData = document.querySelector('.reservation-form__table-data--time');
        const numberData = document.querySelector('.reservation-form__table-data--number');
        const totalData = document.querySelector('.reservation-form__table-data--total');

        function updateTotal() {
            const coursePrice = courseInput ? courseInput.options[courseInput.selectedIndex].dataset.price : 0;
            const numberOfPeople = numberInput.value;
            if (coursePrice && numberOfPeople) {
                const totalPrice = coursePrice * numberOfPeople;
                totalData.textContent = totalPrice + '円';
            } else {
                totalData.textContent = '';
            }
        }

        dateData.textContent = dateInput.value;
        timeData.textContent = timeInput.value;

        dateInput.addEventListener('input', function () {
            dateData.textContent = this.value;
        });

        timeInput.addEventListener('change', function () {
            timeData.textContent = this.value;
        });

        numberInput.addEventListener('change', function () {
            if (this.value !== '') {
                numberData.textContent = this.options[this.selectedIndex].text;
                updateTotal();
            } else {
                numberData.textContent = '';
            }
        });

        if (courseInput) {
            courseInput.addEventListener('change', function () {
                updateTotal();
            });
        }

        const errors = document.querySelectorAll('.form__error');
        if (errors.length > 0) {
            const lastDate = '{{ old('date') }}';
            const lastTime = '{{ old('time') }}';
            const lastNumber = '{{ old('number') }}';
            const lastCourse = '{{ old('course') }}';
            dateData.textContent = lastDate;
            timeData.textContent = lastTime;
            numberData.textContent = lastNumber !== '' ? lastNumber + '人' : '';
            if (lastCourse) {
                courseInput.value = lastCourse;
                updateTotal();
            }
        }
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

    // Stripeの公開キーをセットアップ
    const stripe = Stripe('{{ config('services.stripe.public_key') }}');

    // 事前決済ボタンがクリックされたときの処理
    const reserveWithStripeBtn = document.getElementById("reserve-with-stripe-btn");
    reserveWithStripeBtn.addEventListener('click', async function(event) {
        event.preventDefault();

        // 現在のフォームのデータを取得
        const dateInput = document.querySelector('.reservation-form__input-date').value;
        const timeInput = document.querySelector('.reservation-form__input-time').value;
        const numberInput = document.querySelector('.reservation-form__input-number').value;
        const courseInput = document.querySelector('.reservation-form__input-course').value;
        const shopId = document.querySelector('input[name="shop_id"]').value;

        try {
            // サーバーにリクエストしてStripe CheckoutのセッションIDを取得
            const response = await fetch('/reserve-with-stripe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    date: dateInput, // 日付
                    time: timeInput, // 時間
                    number: numberInput, // 人数
                    course_id: courseInput, // コース ID
                    shop_id: shopId, //ショップ ID
                }),
            });

            const data = await response.json();

            // Stripe Checkoutのモーダルを開く
            const result = await stripe.redirectToCheckout({
                sessionId: data.id,
            });

        } catch (error) {
            console.error('Error:', error.message);
        }
    });
</script>
@endsection
