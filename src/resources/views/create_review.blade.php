@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/create_review.css') }}">
@endsection

@section('main')
<div class="content">
    <div class="shop-wrapper">
        <h2 class="title">今回のご利用はいかがでしたか？</h2>
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
        <form action="/create/review" method="POST" class="review-form" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <p class="input-head">体験を評価してください</p>
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
                <p class="input-head">口コミを投稿</p>
                <textarea name="comment" id="textarea" class="comment-input" placeholder="カジュアルな夜のお出かけにおすすめのスポット" maxlength="400">{{ old('comment') }}</textarea>
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
            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
            <div id="preview" class="preview"></div>
            <button class="review-form__btn">口コミを投稿</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.getElementById('textarea');
        const charCounter = document.getElementById('char-counter');

        textarea.addEventListener('input', () => {
            const currentLength = textarea.value.length;
            charCounter.textContent = `${currentLength}/400（最高文字数）`;
        });

        const imageUpload = document.getElementById('image-upload');
        const imageInput = document.getElementById('image-input');
        const preview = document.getElementById('preview');

        imageUpload.addEventListener('click', () => imageInput.click());

        imageUpload.addEventListener('dragover', (event) => {
            event.preventDefault();
            imageUpload.classList.add('dragging');
        });

        imageUpload.addEventListener('dragleave', () => {
            imageUpload.classList.remove('dragging');
        });

        imageUpload.addEventListener('drop', (event) => {
            event.preventDefault();
            imageUpload.classList.remove('dragging');
            handleFiles(event.dataTransfer.files);
        });

        imageInput.addEventListener('change', (event) => {
            handleFiles(event.target.files);
        });
    });

    function previewFiles(files) {
        const preview = document.getElementById('preview');

        // すでに表示されているプレビューをすべて削除
        preview.innerHTML = '';

        for (let i = 0; i < files.length; i++) {
            const file = files[i];

            // FileReaderオブジェクトを作成
            const reader = new FileReader();

            // ファイルが読み込まれたときに実行する
            reader.onload = function (e) {
                const imageUrl = e.target.result; // 画像のURLはevent.target.resultで呼び出せる
                const previewItem = document.createElement("div"); // プレビューアイテムを作成
                previewItem.classList.add("preview-item");

                // 画像をプレビューアイテムに追加
                const img = document.createElement("img");
                img.src = imageUrl;
                previewItem.appendChild(img);

                preview.appendChild(previewItem); // プレビューアイテムをプレビューに追加
            }
            reader.readAsDataURL(file);
        }
    }
    // <input>でファイルが選択されたときの処理
    const fileInput = document.getElementById('image-input');
    const handleFileSelect = () => {
        const files = fileInput.files;
        previewFiles(files);
    }
    fileInput.addEventListener('change', handleFileSelect);
</script>
@endsection