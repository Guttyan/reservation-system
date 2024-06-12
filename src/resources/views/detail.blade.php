@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('main')
<div class="shop-wrapper">
    @if(!$my_review)
        <div class="shop-header">
            <a href="/" class="back-btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="shop-name">{{ $shop->name }}</h2>
        </div>
    @endif
    @if(is_array($shop->photo))
        @if(count($shop->photo) > 1)
            <ul class="slideshow-fade {{ $my_review ? 'my-review-exists' : '' }}">
                @foreach($shop->photo as $image)
                    <li class="slideshow-fade__li {{ $my_review ? 'my-review-exists' : '' }}">
                        <img class="slideshow-fade__img {{ $my_review ? 'my-review-exists' : '' }}" src="{{ asset('storage/shop_images/' . $image) }}" alt="{{ $shop->name }}">
                    </li>
                @endforeach
            </ul>
        @else
            <img src="{{ asset('storage/shop_images/' . $shop->photo[0]) }}" alt="{{ $shop->name }}" class="shop-image">
        @endif
    @else
        <img src="{{ $shop->photo }}" alt="{{ $shop->name }}" class="shop-image {{ $my_review ? 'my-review-exists' : '' }}">
    @endif
    <p class="shop__text-area {{ $my_review ? 'my-review-exists' : '' }}">#{{ $shop->area->name }}</p>
        @foreach($shop->genres as $genre)
            <p class="shop__text-genre {{ $my_review ? 'my-review-exists' : '' }}">#{{ $genre->name }}</p>
        @endforeach
    <p class="shop__text-comment {{ $my_review ? 'my-review-exists' : '' }}">{{ $shop->explanation }}</p>
    @if($reviews->isNotEmpty())
        <button id="showAllReviews" class="all-review__btn {{ $my_review ? 'my-review-exists' : '' }}">全ての口コミ情報</button>
    @endif
    @if(!$my_review && !(Auth::user()->hasRole('admin') || Auth::user()->hasRole('representative')))
        <a href="/create/review/{{ $shop->id }}" class="transition-create-review">口コミを投稿する</a>
    @endif
    @if($my_review)
        <div class="my-review">
            <div class="my-review__edit">
                <a href="/edit/review/{{ $my_review->id }}" class="my-review__update">口コミを編集</a>
                <form action="/delete/review" class="my-review__delete" method="POST">
                    @csrf
                    <input type="hidden" name="review_id" value="{{ $my_review->id }}">
                    <button class="my-review__delete-btn">口コミを削除</button>
                </form>
            </div>
            @for ($i = 0; $i < 5; $i++)
                @if ($i < $my_review->rating)
                    <i class="fa-solid fa-star" style="color: #1964fa;"></i>
                @else
                    <i class="fa-solid fa-star" style="color: #ccc;"></i>
                @endif
            @endfor
            <p class="my-review__comment">{{ $my_review->comment }}</p>
            @if ($my_review && $my_review->review_image)
                <div class="review-images">
                    @foreach($my_review->review_image as $image)
                        <img src="{{ asset('storage/review_images/' . $image) }}" class="review-image">
                    @endforeach
                </div>
            @endif
        </div>
    @endif
    <div id="all-reviews" style="display:none;" class="all-reviews">
        @foreach ($reviews as $review)
            <div class="review">
                @if(Auth::user()->hasRole('admin'))
                    <form action="/delete/review" method="POST">
                        @csrf
                        <input type="hidden" name="review_id" value="{{ $review->id }}">
                        <button class="review__delete-btn">口コミを削除</button>
                    </form>
                @endif
                @for ($i = 0; $i < 5; $i++)
                    @if ($i < $review->rating)
                        <i class="fa-solid fa-star" style="color: #1964fa;"></i>
                    @else
                        <i class="fa-solid fa-star" style="color: #ccc;"></i>
                    @endif
                @endfor
                <p class="review__comment">{{ $review->comment }}</p>
                <div class="review-images">
                    @if (!is_null($review->review_image) && is_array($review->review_image))
                        @foreach($review->review_image as $image)
                            <img src="{{ asset('storage/review_images/' . $image) }}" class="review-image">
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </div>
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
            const coursePrice = courseInput ? parseInt(courseInput.options[courseInput.selectedIndex].dataset.price) : 0;
            const numberOfPeople = parseInt(numberInput.value);
            if (coursePrice && numberOfPeople) {
                const totalPrice = coursePrice * numberOfPeople;
                totalData.textContent = totalPrice + '円';
            } else {
                totalData.textContent = '';
            }
        }

        // 各入力フィールドのイベントリスナー
        dateInput.addEventListener('input', function () {
            dateData.textContent = this.value;
        });

        timeInput.addEventListener('change', function () {
            timeData.textContent = this.value;
        });

        numberInput.addEventListener('change', function () {
            if (this.value !== '') {
                numberData.textContent = this.options[this.selectedIndex].text;
                updateTotal(); // numberが変更された場合、Totalを更新
            } else {
                numberData.textContent = '';
                totalData.textContent = ''; // numberが空の場合、Totalを空欄にする
            }
        });

        if (courseInput) {
            courseInput.addEventListener('change', function () {
                if (this.value !== '') {
                    updateTotal(); // courseが変更された場合、Totalを更新
                } else {
                    totalData.textContent = ''; // courseが空の場合、Totalを空欄にする
                }
            });
        }

        // 全てのレビューを表示ボタンのクリックイベントリスナー
        const showAllReviewsBtn = document.getElementById('showAllReviews');
        const allReviewsDiv = document.getElementById('all-reviews');
        showAllReviewsBtn.addEventListener('click', function() {
            if (allReviewsDiv.style.display === 'none' || allReviewsDiv.style.display === '') {
                allReviewsDiv.style.display = 'block';
                showAllReviewsBtn.textContent = 'レビューを閉じる';
            } else {
                allReviewsDiv.style.display = 'none';
                showAllReviewsBtn.textContent = '全ての口コミ情報';
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
