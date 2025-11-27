<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest; // ログイン用のバリデーション
use App\Http\Requests\RegisterRequest; // 登録用のバリデーション
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //新規登録フォームを表示する（GET/register)
    public function createRegistrationForm()
    {
        return view('auth.register');
    }

    //新規ユーザーをデータベースに保存する（POST/register）
    public function register(RegisterRequest $request)
    {
        // RegisterRequestによりバリデーションは完了済み
        $validatedData = $request->validated();

        $user = User::create([
            'name' => $validatedData['お名前'],
            'email' => $validatedData['メールアドレス'],
            'password' => Hash::make($validatedData['パスワード']), // ハッシュ化
        ]);

        Auth::login($user); // 登録後、自動でログイン
        $request->session()->regenerate();

        return redirect('/')->with('success', 'ご登録ありがとうございます！');
    }

    //ログインフォームを表示する（GET /login)
    public function createSessionForm()
    {
        return view('auth.login');
    }
    /**
     * ユーザーを認証し、セッションを開始する（POST /login）
     */
    public function storeSession(LoginRequest $request)
    {
        // LoginRequestにより入力形式のバリデーションは完了済み
        $credentials = $request->validated();

        // 認証処理（メールアドレスとパスワードの組み合わせチェック）
        if (Auth::attempt($credentials)) {
            // 認証成功
            $request->session()->regenerate();

            // ログイン後の任意のページへリダイレクト
            return redirect()->intended('/')->with('success', 'ログインしました。');
        }

        // 認証失敗
        return back()->withErrors([
            'email' => '入力された認証情報が記録と一致しません。',
        ])->onlyInput('email');
    }

        //ユーザーをログアウトさせ、セッションを終了する（POST/logout)
        public function destroySession(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'ログアウトしました。');
    }
} // AuthControllerクラスの終了

