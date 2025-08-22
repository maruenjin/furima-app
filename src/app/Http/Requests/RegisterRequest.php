<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }


    public function rules(): array {
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255','unique:users,email'],
            'password' => ['required','confirmed', Password::min(8)],
             'password_confirmation' => ['required'], // 明示（文言要件のため）
        ];
    }
    public function attributes(): array {
        return [
            // 未入力
            'name.required'     => 'お名前を入力してください',
            'email.required'    => 'メールアドレスを入力してください',
            'email.email'       => 'メールアドレスはメール形式で入力してください',
            'password.required' => 'パスワードを入力してください',

            // 規則違反
            'password.min'      => 'パスワードは8文字以上で入力してください',
            // 確認用
            'password.confirmed'=> 'パスワードと一致しません',
            'password_confirmation.required' => 'パスワードと一致しません', // 入力なし=不一致扱い
        ];
    } 
}
