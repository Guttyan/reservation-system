@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/create_notification_mail.css') }}">
@endsection

@section('main')
<h2 class="ttl">メール送信</h2>
<form action="/send-mail" class="create-mail" method="POST">
@csrf
    <div class="form-group">
        <label for="subject" class="form-label">件　名:</label>
        <input type="text" class="input-subject" id="subject" name="subject">
    </div>
    <div class="form-group">
        <label for="address" class="form-label">宛　先:</label>
        <select class="input-address" id="address" name="address">
            <option value="">宛先を選択してください</option>
            <option value="all">全員</option>
            @foreach($users as $user)
                <option value="{{ $user->email }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="content" class="form-label">メール内容：</label>
        <textarea class="input-content" id="content" name="content"></textarea>
    </div>
    <input type="hidden" name="shop_id" value="{{ $shop_id }}">
    <button class="create-mail__btn" type="submit">メール送信</button>
</form>

@endsection