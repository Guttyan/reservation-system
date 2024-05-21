@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/create_shop.css') }}">
@endsection

@section('main')
<h2 class="ttl">店舗作成</h2>
<form action="/create-shop" class="create-form" method="POST" enctype="multipart/form-data">
    @csrf
    <ul class="create-form__table">
        <li class="create-form__list">
            <label for="name">店舗名:</label>
            <input type="text" name="name" id="name" class="input-name" value="{{ old('name') }}">
        </li>
        @error('name')
        <li class="form__error">
            <p>{{ $message }}</p>
        </li>
        @enderror
        <li class="create-form__list create-form__list-area">
            <label for="area">エリア選択:</label>
            <select name="area_id" class="input-area" id="area">
                <option value="">All area</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ Request::input('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                @endforeach
            </select>
            <i class="fa-solid fa-sort-down"></i>
        </li>
        @error('area_id')
        <li class="form__error">
            <p>{{ $message }}</p>
        </li>
        @enderror
        <li class="create-form__list">
            <label for="genre">ジャンル:</label>
            <select name="genre_id" id="genre" class="input-genre">
                <option value="">ジャンルを選択してください</option>
                @foreach($genres as $genre)
                <option value="{{ $genre->id }}" {{ Request::input('genre_id') == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                @endforeach
            </select>
            <input type="text" name="new_genre" class="input-new-genre" placeholder="選択肢に無いジャンルを追加" value="{{ old('new_genre') }}">
        </li>
        @error('new_genre')
        <li class="form__error">
            <p>{{ $message }}</p>
        </li>
        @enderror
        <li class="create-form__list create-form__list-explanation">
            <label class="explanation-label">店舗説明：</label>
            <textarea name="explanation" class="input-explanation" maxlength="300" value="{{ old('explanation') }}"></textarea>
        </li>
        @error('explanation')
        <li class="form__error">
            <p>{{ $message }}</p>
        </li>
        @enderror
        <li class="create-form__list">店舗写真：
            <label class="input-photo"><input type="file" name="images[]" id="photo" multiple></label>
        </li>
        @error('images')
        <li class="form__error">
            <p>{{ $message }}</p>
        </li>
        @enderror
        <div id="preview" class="preview"></div>
    </ul>
    <button class="create-form__btn" type="submit">店舗作成</button>
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
</script>
@endsection
