@component('mail::message')
# 【Rese】本日の予約内容のお知らせ

以下の予約が本日の予定です。

- 店舗名: {{ $reservation->shop->name }}
- 日付: {{ $reservation->date }}
- 時間: {{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}
- 人数: {{ $reservation->number }}人

ご予約の詳細はマイページからご確認ください。

[マイページで予約を確認する]({{ url('/mypage') }})

@component('mail::panel')
@php
    $qrCodePath = public_path($reservation->qr_code);
    $qrCodeData = file_get_contents($qrCodePath);
    $qrCodeBase64 = base64_encode($qrCodeData);
@endphp
<div style="text-align: center; background:white;">
    <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code">
</div>
@endcomponent

@endcomponent
