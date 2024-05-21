@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/my_shop_edit.css') }}">
@endsection

@section('main')
<h2 class="ttl">{{ $shop->name }}</h2>
<form action="/my-shops/edit" class="edit-form" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
    <ul class="edit-form__table">
        <li class="edit-form__list">
            <label for="name">店舗名:</label>
            <input type="text" name="name" id="name" class="input-name" value="{{ $shop->name }}">
        </li>
        @error('name')
        <li class="form__error">
            <p>{{ $message }}</p>
        </li>
        @enderror
        <li class="edit-form__list edit-form__list-area">
            <label for="area">エリア選択:</label>
            <select name="area_id" class="input-area" id="area">
                @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ $shop->area_id == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                @endforeach
            </select>
            <i class="fa-solid fa-sort-down"></i>
        </li>
        @error('area_id')
        <li class="form__error">
            <p>{{ $message }}</p>
        </li>
        @enderror
        <li class="edit-form__list">
            <label for="genre">ジャンル:</label>
            <select name="genre_id" id="genre" class="input-genre">
                <option value="">ジャンルを選択してください</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}" {{ $shop->genres->contains('id', $genre->id) ? 'selected' : '' }}>{{ $genre->name }}</option>
                @endforeach
            </select>
            <input type="text" name="new_genre" class="input-new-genre" placeholder="選択肢に無いジャンルを追加" value="{{ old('new_genre') }}">
        </li>
        @error('new_genre')
        <li class="form__error">
            <p>{{ $message }}</p>
        </li>
        @enderror
        <li class="edit-form__list edit-form__list-explanation">
            <label class="explanation-label">店舗説明：</label>
            <textarea name="explanation" class="input-explanation" maxlength="300">{{ $shop->explanation }}</textarea>
        </li>
        @error('explanation')
        <li class="form__error">
            <p>{{ $message }}</p>
        </li>
        @enderror
        <li class="edit-form__list edit-form__list-photo">店舗写真：
            <label class="input-photo"><input type="file" name="images[]" id="photo" multiple></label>
        </li>
        <p class="input-photo__announcement">写真を更新しない場合、ファイルを選択せずに更新ボタンをクリックしてください</p>
        <div id="preview" class="preview">
            @foreach($shop->photo as $image)
                <div class="preview-item">
                    <img src="{{ asset('storage/shop_images/' . $image) }}" alt="画像">
                </div>
            @endforeach
        </div>
    </ul>
    <div class="edit-form__btn-area">
        <button class="edit-form__btn-edit" type="submit">店舗情報更新</button>
        <a href="/my-shops" class="edit-form__btn-back">キャンセル</a>
    </div>
</form>

<script>
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
        const fileInput = document.getElementById('photo');
        const handleFileSelect = () => {
            const files = fileInput.files;
            previewFiles(files);
        }
        fileInput.addEventListener('change', handleFileSelect);

        window.addEventListener('DOMContentLoaded', (event) => {
        const existingImages = document.querySelectorAll('.preview-item img');
        const photoInput = document.getElementById('photo');
        if(existingImages.length > 0){
            const fileList = new DataTransfer();
            existingImages.forEach(image => {
                const imageUrl = image.src;
                const byteString = atob(imageUrl.split(',')[1]);
                const mimeType = imageUrl.split(',')[0].split(':')[1].split(';')[0];
                const arrayBuffer = new ArrayBuffer(byteString.length);
                const uint8Array = new Uint8Array(arrayBuffer);
                for (let i = 0; i < byteString.length; i++) {
                    uint8Array[i] = byteString.charCodeAt(i);
                }
                const blob = new Blob([arrayBuffer], { type: mimeType });
                const file = new File([blob], 'image.png', { type: mimeType });
                fileList.items.add(file);
            });
            photoInput.files = fileList.files;
        }
    });
</script>
@endsection
