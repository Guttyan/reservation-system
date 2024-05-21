<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class EditShopRequest extends FormRequest
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
            'area_id' => ['required'],
            'genre_id' => ['nullable'],
            'new_genre' => ['nullable'],
            'explanation' => ['required', 'min:10', 'max:300'],
            'images' => ['nullable'],
        ];
    }

    public function withValidator($validator){
        $validator->after(function ($validator) {
            $genreId = $this->input('genre_id');
            $newGenre = $this->input('new_genre');

            if (!$genreId && !$newGenre) {
                $validator->errors()->add('new_genre', 'ジャンルを選択もしくは新しいジャンルを入力してください。');
            } elseif ($genreId && $newGenre) {
                $validator->errors()->add('new_genre', 'ジャンルが複数設定されています。');
            }
        });
    }

    public function messages(){
        return [
            'name.required' => '店舗名を入力してください',
            'area_id.required' => 'エリアを選択してください',
            'explanation.required' => '店舗説明を入力してください',
            'explanation.min' => '最低１０文字は入力してください',
            'explanation.max' => '３００文字以内で入力してください'
        ];
    }
}
