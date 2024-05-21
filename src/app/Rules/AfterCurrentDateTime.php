<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class AfterCurrentDateTime implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!request('date')) {
        // date フィールドが空の場合はバリデーションをスキップし、日付入力必須のバリデーションのみ表示
        return true;
        }

        $currentDateTime = now();
        $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', request('date') . ' ' . request('time') . ':00');
        return $dateTime->gt($currentDateTime);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '現在日時以降で予約してください';
    }
}
