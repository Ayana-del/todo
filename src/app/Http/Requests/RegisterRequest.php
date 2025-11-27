<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize():bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'min:8',
                'max:15',
                'confirmed',
                // パスワードのルール: 小文字、数字をそれぞれ1文字以上入力
                'regex:/[a-z]/',
                'regex:/[0-9]/',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            // --- お名前 ---
            'name.required' => 'お名前を入力してください。',
            'name.min' => 'お名前は3文字以上で入力してください。',
            'name.max' => 'お名前は50文字以内で入力してください。',

            // --- メールアドレス ---
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '正しいメールアドレス形式で入力してください。',
            'email.unique' => 'このメールアドレスは既に登録されています。',

            // --- パスワード ---
            'password.required' => 'パスワードを入力してください。',
            'password.min' => 'パスワードは8文字以上で設定してください。',
            'password.max' => 'パスワードは15文字以内で設定してください。',
            'password.confirmed' => 'パスワードと確認用パスワードが一致しません。',
            // 複雑性ルールの共通メッセージ
            'password.regex' => 'パスワードには、小文字、数字をそれぞれ1文字以上含めてください。',
        ];
    }
}
