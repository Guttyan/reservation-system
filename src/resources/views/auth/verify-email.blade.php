@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('メールアドレスの確認') }}</div>

                    <div class="card-body">
                        {{ __('メールアドレスを確認するために、確認ボタンをクリックしてください。') }}
                        {{ __('もしメールを受信していない場合は、') }}
                        <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('こちらをクリックして再送信してください。') }}</button>.
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection