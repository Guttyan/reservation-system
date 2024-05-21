<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AfterCurrentDateTime;

class ReserveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => ['required'],
            'time' => ['required', new AfterCurrentDateTime],
            'number' => ['required'],
        ];
    }

    public function messages(){
        return [
            'date.required' => '日付を入力してください',
            'time.required' => '予約時間を選択してください',
            'number.required' => '予約人数を入力してください'
        ];
    }
}
