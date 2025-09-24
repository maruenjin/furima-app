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
            'password' => ['required','string','confirmed', Password::min(8)],
             'password_confirmation' => ['required','string','min:8','same:password'], 
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'お名前',
            'email' => 'メールアドレス',
            'password' => 'パスワード',
            'password_confirmation' => '確認用パスワード',
        ];
    }

    public function messages(): array {
        return [
            
            'name.required'     => 'お名前を入力してください',
            'name.max' => 'ユーザー名は20文字以内で入力してください',
            'email.required'    => 'メールアドレスを入力してください',
            'email.email'       => 'メールアドレスはメール形式が正しくありません',
            'password.required' => 'パスワードを入力してください',

            
            'password.min'      => 'パスワードは8文字以上で入力してください',
            
            'password.confirmed'=> 'パスワードと一致しません',
            'password_confirmation.required' => '確認用パスワードを入力してください', 
            'password_confirmation.min' => '確認用パスワードは8文字以上で入力してください',
            'password_confirmation.same' => 'パスワードと一致しません',
            
        ];
    } 
}
