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
        'name'      => ['required','string','max:20'],
        'postal_code'  => ['required','regex:/^\d{3}-?\d{4}$/'], 
        'address'   => ['required','string','max:255'],
        'building'  => ['nullable','string','max:255'],
        'avatar'    => ['nullable','image','mimes:jpeg,jpg,png','max:2048'],
    ];
}

public function messages(): array
{
    return [
        'name.required'    => 'お名前を入力してください',
        'name.max'         => 'ユーザー名は20文字以内で入力してください',
        'postal_code.required' => '郵便番号を入力してください',
        'postal_code.regex'    => '郵便番号は「123-4567」で入力してください',
        'address.required' => '住所を入力してください',
        'avatar.image'     => 'プロフィール画像は画像ファイルを選択してください',
        'avatar.mimes'     => 'プロフィール画像はjpeg、jpg、png形式でアップロードしてください',
        'avatar.max'       => 'プロフィール画像は2MB以下でアップロードしてください',
    ];
}

public function attributes(): array
{
    return [
        'name'     => 'ユーザー名',
        'postal_code' => '郵便番号',
        'address'  => '住所',
        'building' => '建物名',
        'avatar'   => 'プロフィール画像',
    ];
}
}