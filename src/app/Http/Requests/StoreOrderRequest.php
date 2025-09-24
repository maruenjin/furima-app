<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'payment_method' => ['required','in:convenience,card'],
        ];
    }

    public function messages(): array
    {
        return ['payment_method.required' => '支払い方法を選択してください。',
        'payment_method.in'       => '支払い方法は「コンビニ支払い」または「カード支払い」を選択してください。',];

    }
}
