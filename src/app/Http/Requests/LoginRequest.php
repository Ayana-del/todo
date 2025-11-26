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
            'メールアドレス' => 'required|email|max:255',
            // パスワード: 必須チェック
            'パスワード' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            // --- メールアドレス ---
            'メールアドレス.required' => 'メールアドレスを入力してください。',
            'メールアドレス.email' => '正しいメールアドレス形式で入力してください。',
            'メールアドレス.max' => 'メールアドレスは255文字以内で入力してください。',

            // --- パスワード ---
            'パスワード.required' => 'パスワードを入力してください。',
            'パスワード.string' => 'パスワードは文字列形式で入力してください。',
        ];
    }
}
