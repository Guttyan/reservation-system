@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/completed_reservations.css') }}">
@endsection

@section('main')
@if (session('result'))
<div class="flash_message">
    {{ session('result') }}
</div>
@endif

<h2 class="ttl{{ session('result') ? ' with-flash-message' : '' }}">過去の予約をレビューする</h2>

<div class="reservation-wrapper">
    @foreach($completedReservations as $reservation)
        <div class="reservation-card">
            <a href="/create/review/{{ $reservation->id }}" class="reservation-card__link">
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
            </a>
        </div>
    @endforeach
</div>
@endsection