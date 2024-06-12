@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit_review.css') }}">
@endsection

@section('main')
<div class="content">
    <div class="shop-wrapper">
        <h2 class="title">あなたのレビューを編集します</h2>
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
    </div>

    <div class="review-form__wrapper">
        <form action="/edit/review" method="POST" class="review-form" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <p class="input-head">体験を評価してください</p>
                <div class="rating">
                    @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="rating-input" {{ old('rating', $my_review->rating) == $i ? 'checked' : '' }}>
                        <label for="star{{ $i }}" class="rating-label"><i class="fa-solid fa-star"></i></label>
                    @endfor
                </div>
                <div class="form__error">
                    @error('rating')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <p class="input-head">口コミを投稿</p>
                <textarea name="comment" id="textarea" class="comment-input" placeholder="カジュアルな夜のお出かけにおすすめのスポット" maxlength="400">{{ old('comment', $my_review->comment) }}</textarea>
                <p class="char-counter" id="char-counter">0/400（最高文字数）</p>
            </div>
            <div class="form-group form-group__image">
                <p class="input-head">画像の追加</p>
                <div class="image-upload">
                    <p class="image-upload__comment-upper">クリックして写真を追加</p>
                    <p class="image-upload__comment-under">またはドラッグアンドドロップ</p>
                    <input type="file" class="image-input" id="image-input" multiple name="images[]">
                </div>
                <div class="form__error">
                    @error('images.*')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div id="preview" class="preview">
                @if(is_array($my_review->review_image))
                    @foreach($my_review->review_image as $image)
                        <img src="{{ asset('storage/review_images/' . $image) }}" alt="Review Image" class="review-image">
                    @endforeach
                @endif
            </div>
            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
            <input type="hidden" name="review_id" value="{{ $my_review->id }}">
            <button class="review-form__btn">口コミを編集</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.getElementById('textarea');
        const charCounter = document.getElementById('char-counter');

        charCounter.textContent = `${textarea.value.length}/400（最高文字数）`;

        textarea.addEventListener('input', function () {
            charCounter.textContent = `${this.value.length}/400（最高文字数）`;
        });

        const imageInput = document.getElementById('image-input');
        const preview = document.getElementById('preview');

        imageInput.addEventListener('change', function () {
            preview.innerHTML = ''; // 既存のプレビューをクリア
            for (const file of this.files) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('review-image');
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection