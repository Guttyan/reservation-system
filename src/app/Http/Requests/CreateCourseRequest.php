<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCourseRequest extends FormRequest
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
            'name' => ['required'],
            'price' => ['required', 'integer', 'min:1']
        ];
    }

    public function messages(){
        return [
            'name.required' => 'コース名を入力してください',
            'price.required' => 'コース料金を入力してください',
            'price.integer' => 'コース料金には整数値を入力してください',
            'price.min' => 'コース料金には1以上の値を入力してください',
        ];
    }
}
