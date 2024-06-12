<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'rating' => 'required',
            'images.*' => 'mimes:jpeg,png'
        ];
    }

    public function messages(){
        return [
            'rating.required' => '５段階評価は必ずご選択ください',
            'images.*.mimes' => 'JPEGまたはPNG形式の画像のみアップロードできます',
        ];
    }
}
