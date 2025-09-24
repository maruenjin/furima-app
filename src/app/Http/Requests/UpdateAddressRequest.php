<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'postal_code' => ['required','regex:/^\d{3}-?\d{4}$/'],
            'address'     => ['required','string','max:255'],
            'building'    => ['nullable','string','max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'postal_code' => '郵便番号',
            'address'     => '住所',
            'building'    => '建物名',
        ];
    }

    public function messages(): array
    {
        return [
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex'    => '郵便番号は 123-4567 の形式で入力してください。',
            'address.required'     => '住所を入力してください。',
            'address.max'          => '住所は:max文字以内で入力してください。',
            'building.max'         => '建物名は:max文字以内で入力してください。',
        ];
    }

   
    public function normalized(): array
    {
        $raw    = (string) $this->input('postal_code', '');
        $digits = preg_replace('/\D/', '', $raw);
        $postal = preg_match('/^\d{7}$/', $digits)
            ? substr($digits, 0, 3).'-'.substr($digits, 3)
            : $raw;

        return [
            'postal_code' => $postal,
            'address'     => (string) $this->input('address', ''),
            'building'    => (string) $this->input('building', ''),
        ];
    }
}


