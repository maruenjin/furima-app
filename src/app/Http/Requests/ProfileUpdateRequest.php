<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
             'name'       => ['required','string','max:255'],
            'zip_code'   => ['nullable','regex:/^\d{7}$/'],   
            'address'    => ['required','string','max:255'],
            'building'   => ['nullable','string','max:255'],
            'avatar'     => ['nullable','image','mimes:jpeg,png','max:2048'], 
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'お名前を入力してください',
            'address.required'  => '住所を入力してください',
            'zip_code.regex'    => '郵便番号はハイフンなしの7桁で入力してください',
            'avatar.image'      => 'プロフィール画像は画像ファイルを選択してください',
            'avatar.mimes'      => 'プロフィール画像はjpegまたはpng形式でアップロードしてください',
            'avatar.max'        => 'プロフィール画像は2MB以下でアップロードしてください',
        ];
    }
}
