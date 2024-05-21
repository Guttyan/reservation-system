@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/my_shop_reservation.css') }}">
@endsection

@section('main')
<div class="ttl">
    <a class="arrow-left" href="/my-shops/reservation/{{ $shop->id }}/{{ $num - 1 }}">
        <i class="fa-solid fa-chevron-left"></i>
    </a>
    <h2 class="date">{{ $date }}</h2>
    <a class="arrow-right" href="/my-shops/reservation/{{ $shop->id }}/{{ $num + 1 }}">
        <i class="fa-solid fa-chevron-right"></i>
    </a>
</div>

<table class="reservation-table">
    <tr class="reservation-table__row">
        <th class="reservation-table__header">予約時間</th>
        <th class="reservation-table__header">予約人数</th>
        <th class="reservation-table__header">予約者</th>
    </tr>
    @foreach($reservations as $reservation)
    <tr class="reservation-table__row">
        <td class="reservation-table__item">{{ \Carbon\Carbon::createFromFormat('H:i:s', $reservation->time)->format('H:i') }}</td>
        <td class="reservation-table__item">{{ $reservation->number }}人</td>
        <td class="reservation-table__item">{{ $reservation->user->name }}</td>
    </tr>
    @endforeach
</table>

@endsection