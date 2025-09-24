<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => ['required','string','max:100'],
            'brand'       => ['nullable','string','max:255'],
            'price'       => ['required','integer','min:0',],
            'description' => ['required','string','max:255'],
            'condition'   => ['required','in:新品未使用,未使用に近い,目立った傷や汚れなし,やや傷や汚れあり,傷や汚れあり,全体的に状態が悪い'],
            'image'       => ['required','image','mimes:jpeg,png,jpg','max:4096'],
             'categories'  => ['required','array','min:1'],
             'categories.*'=> ['string','max:50'],
        ];
    }

  public function messages(): array
    {
        return [
            'required'      => ':attributeを入力してください。',
            'string'        => ':attributeは文字列で入力してください。',
            'integer'       => ':attributeは数値で入力してください。',
            'min'           => ':attributeは:min以上で入力してください。',
            'max'           => ':attributeは:max以下で入力してください。',
            'in'            => ':attributeの値が不正です。',
            'array'         => ':attributeの形式が不正です。',
            'image'         => ':attributeは画像ファイルを選択してください。',
            'mimes'         => ':attributeは:values形式でアップロードしてください。',
            'categories.min'=> 'カテゴリは1つ以上選択してください。',
        ];
    }

    
    public function attributes(): array
    {
        return [
            'name'         => '商品名',
            'brand'        => 'ブランド名',
            'price'        => '価格',
            'description'  => '商品説明',
            'condition'    => '商品の状態',
            'categories'   => 'カテゴリ',
            'categories.*' => 'カテゴリ',
            'image'        => '商品画像',
        ];
    }
}

     


