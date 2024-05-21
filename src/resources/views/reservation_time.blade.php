@for ($hour = 8; $hour < 24; $hour++)
    @for ($minute = 0; $minute < 60; $minute += 15)
        @php
            $formatted_hour = str_pad($hour, 2, '0', STR_PAD_LEFT);
            $formatted_minute = str_pad($minute, 2, '0', STR_PAD_LEFT);
            $time = $formatted_hour . ':' . $formatted_minute;
        @endphp
        <option value="{{ $time }}" @if(old('time') == $time) selected @endif>{{ $time }}</option>
    @endfor
@endfor