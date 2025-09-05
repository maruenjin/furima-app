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
            'brand'       => ['nullable','string','max:100'],
            'price'       => ['required','integer','min:1','max:99999999'],
            'description' => ['nullable','string','max:1000'],
            'condition'   => ['required','string','max:50'],
            'image'       => ['nullable','image','mimes:jpeg,png,jpg,gif,webp','max:4096'],
             'categories'  => ['nullable','array'],
             'categories.*'=> ['string','max:30'],
        ];
    }

    
    public function messages(): array
    {
        return [
            'name.required'   => '商品名を入力してください。',
            'price.required'  => '価格を入力してください。',
            'price.integer'   => '価格は数値で入力してください。',
            'condition.required' => '商品の状態を選択してください。',
            'image.image'     => '画像ファイルを選択してください。',
        ];
    }
}
