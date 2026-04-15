<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellRequest extends FormRequest
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
            'description' => ['required', 'max:255'],
            'image' => ['required', 'mimes:jpeg,png,jpg'],
            'categories' => ['required', 'array'],
            'condition_id' => ['required'],
            'price' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
        'name.required' => '商品名を入力してください',
        'description.required' => '商品の説明を入力してください',
        'description.max' => '商品の説明は255文字以内で入力してください',
        'image.required' => '商品画像をアップロードしてください',
        'image.mimes' => 'プロフィール画像は拡張子が.jpegもしくは.pngのものをアップロードしてください',
        'categories.required' => 'カテゴリーを選択してください',
        'categories.array' => 'カテゴリーを選択してください',
        'condition_id.required' => '商品の状態を選択してください',
        'price.required' => '販売価格を入力してください',
        'price.integer' => '販売価格は数値で入力してください',
        'price.min' => '販売価格は０円以上で入力してください',
        ];
    }
}
