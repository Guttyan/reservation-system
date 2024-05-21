@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('main')
@if (session('result'))
<div class="flash_message">
    {{ session('result') }}
</div>
@endif

<h2 class="mypage-name{{ session('result') ? ' with-flash-message' : '' }}">{{ Auth::user()->name }}さん</h2>

<div class="mypage-content">
    <div class="reservation-wrapper">
        <h3 class="reservation-ttl">予約状況</h3>
        @foreach($reservations as $loopIndex => $reservation)
            <div class="reservation-card">
                <a href="/reservation/edit/{{ $reservation->id }}" class="reservation-card__link">
                    <div class="reservation-card__header">
                        <i class="fa-solid fa-clock"></i>
                        <p class="reservation-card__ttl">予約{{ $loopIndex + 1 }}</p>
                        <object class="reservation-card__cancel-btn--wrapper">
                            <a href="#cancelPopup{{ $loopIndex + 1 }}" class="reservation-card__cancel-btn">
                                <i class="fa-regular fa-circle-xmark"></i>
                            </a>
                        </object>
                    </div>
                    <table class="reservation-card__table">
                        <tr class="reservation-card__table-row">
                            <td class="reservation-card__table-head">Shop</td>
                            <td class="reservation-card__table-data">{{ $reservation->shop->name }}</td>
                        </tr>
                        <tr class="reservation-card__table-row">
                            <td class="reservation-card__table-head">Date</td>
                            <td class="reservation-card__table-data">{{ $reservation->date }}</td>
                        </tr>
                        <tr class="reservation-card__table-row">
                            <td class="reservation-card__table-head">Time</td>
                            <td class="reservation-card__table-data">{{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}</td>
                        </tr>
                        <tr class="reservation-card__table-row">
                            <td class="reservation-card__table-head">Number</td>
                            <td class="reservation-card__table-data">{{ $reservation->number }}人</td>
                        </tr>
                    </table>
                    <object>
                        <a href="#qrPopup{{ $loopIndex + 1 }}" class="reservation-card__qr-btn">QRコードを表示</a>
                    </object>
                </a>
            </div>
            {{-- 予約キャンセル確認ポップアップ --}}
            <div id="cancelPopup{{ $loopIndex + 1 }}" class="cancel-modal popup">
                <div class="cancel-modal__content popup__content">
                    <h2 class="cancel-modal__text">予約{{ $loopIndex + 1 }}をキャンセルしますか？</h2>
                    <div class="cancel-modal__btns">
                        <form action="/reservation/cancel" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $reservation->id }}">
                            <button class="cancel-modal__btn-delete">予約をキャンセル</button>
                        </form>
                        <a href="/mypage" class="cancel-modal__btn-back">戻る</a>
                    </div>
                </div>
            </div>
            {{-- QRコード表示ポップアップ --}}
            <div id="qrPopup{{ $loopIndex + 1 }}" class="popup">
                <div class="popup__content">
                    <img src="{{ asset($reservation->qr_code) }}" alt="QRコード">
                </div>
            </div>
        @endforeach
    </div>

    <div class="favorite-wrapper">
        <h3 class="favorite-ttl">お気に入り店舗</h3>
        <div class="shop-card__wrapper">
            @foreach($favorite_shops as $shop)
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
                            <form action="/mypage/favorite" method="POST">
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
    </div>
</div>

<script>
    // QRコード表示ボタンがクリックされたときの処理
    document.querySelectorAll('.reservation-card__qr-btn').forEach((button, index) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            // 対応するQRコードポップアップを表示
            document.getElementById(`qrPopup${index + 1}`).style.display = 'block';
        });
    });

    // ポップアップの背景部分をクリックしたときの処理
    document.querySelectorAll('.popup').forEach((popup) => {
        popup.addEventListener('click', (event) => {
            if (event.target === popup) {
                // ポップアップを非表示
                popup.style.display = 'none';
            }
        });
    });

    // 予約キャンセルボタンがクリックされたときの処理
    document.querySelectorAll('.reservation-card__cancel-btn').forEach((button, index) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            // 対応するキャンセルポップアップを表示
            document.getElementById(`cancelPopup${index + 1}`).style.display = 'block';
        });
    });

    // ポップアップの背景部分をクリックしたときの処理
    document.querySelectorAll('.cancel-modal').forEach((popup) => {
        popup.addEventListener('click', (event) => {
            if (event.target === popup) {
                // ポップアップを非表示
                popup.style.display = 'none';
            }
        });
    });
</script>
@endsection