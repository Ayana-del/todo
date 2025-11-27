<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            // メールアドレス: 必須、形式チェック、最大文字数チェック
            'email' => 'required|email|max:255',
            // パスワード: 必須チェック
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            // --- メールアドレス ---
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '正しいメールアドレス形式で入力してください。',
            'email.max' => 'メールアドレスは255文字以内で入力してください。',

            // --- パスワード ---
            'password.required' => 'パスワードを入力してください。',
            'password.string' => 'パスワードは文字列形式で入力してください。',
        ];
    }
}
