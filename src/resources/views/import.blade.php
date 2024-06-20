@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/import.css') }}">
@endsection

@section('main')
<h2 class="ttl">店舗作成</h2>
<form action="{{ url('/import') }}" class="import-form" method="POST" enctype="multipart/form-data">
    @csrf
    <p class="form-head">CSVファイルをインポート</p>
    <div class="import-group">
        <p class="import__comment-upper">クリックしてファイルを選択</p>
        <p class="import__comment-under">またはドラッグアンドドロップ</p>
        <input type="file" name="csv_file" id="csv_file" class="input-csv" required>
    </div>
    <p id="file_name" class="file-name"></p>
    @if ($errors->any())
        <div class="error-message">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('error'))
        <div class="error-message">
            {!! session('error') !!}
        </div>
    @endif
    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif
    <button type="submit" class="import-btn">インポートして作成</button>
</form>

<script>
document.getElementById('csv_file').addEventListener('change', function() {
    var fileName = this.files[0].name;
    document.getElementById('file_name').textContent = "選択されたファイル: " + fileName;
});
</script>

@endsection
