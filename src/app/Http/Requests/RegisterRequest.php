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
            'お名前' => 'required|string|min:3|max:50',
            'メールアドレス' => 'required|email|unique:users',
            'パスワード' => [
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
            'お名前.required' => 'お名前を入力してください。',
            'お名前.min' => 'お名前は3文字以上で入力してください。',
            'お名前.max' => 'お名前は50文字以内で入力してください。',

            // --- メールアドレス ---
            'メールアドレス.required' => 'メールアドレスを入力してください。',
            'メールアドレス.email' => '正しいメールアドレス形式で入力してください。',
            'メールアドレス.unique' => 'このメールアドレスは既に登録されています。',

            // --- パスワード ---
            'パスワード.required' => 'パスワードを入力してください。',
            'パスワード.min' => 'パスワードは8文字以上で設定してください。',
            'パスワード.max' => 'パスワードは15文字以内で設定してください。',
            'パスワード.confirmed' => 'パスワードと確認用パスワードが一致しません。',
            // 複雑性ルールの共通メッセージ
            'パスワード.regex' => 'パスワードには、小文字、数字をそれぞれ1文字以上含めてください。',
        ];
    }
}
