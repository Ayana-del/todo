<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class UserController extends Controller
{
    //新規ユーザーの登録処理を行うメソッドの例
    public function store(RegisterRequest $request)
    {
        //バリデーション済みの安全なデータ配列を取得
        $validatedData = $request->validated();

        //データベースにレコードを作成（保存）する処理を追加
        User::create([
            'name' => $validatedData['お名前'],
            'email' => $validatedData['メールアドレス'],
            'password' => Hash::make($validatedData['パスワード']),
        ]);
        //成功時のリダイレクト
    return redirect('dashboard')->with('success','登録が完了しました。');
    }
}
