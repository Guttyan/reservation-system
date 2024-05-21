@component('mail::message')
@if(isset($shop))
# 【Rese】「{{ $shop->name }}」からのお知らせ
@else
# 【Rese】運営からのお知らせ
@endif

- **件名**: {{ $subject }}

{{ $content }}

@endcomponent