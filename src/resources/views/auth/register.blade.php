@extends('layouts.app')

{{-- CSSセクションの開始 --}}
@section('css')
{{-- 'css/register.css'のスタイルシートを読み込む --}}
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

{{-- コンテンツセクションの開始 --}}
@section('content')
{{-- 全体を囲むコンテナ要素。Bootstrapなどのフレームワークで使用される --}}
<div class="container">
    {{-- 行（row）レイアウトの開始 --}}
    <div class="row justify-content-center">
        {{-- 中央に配置される列（column）。中程度のデバイスで幅5/12を占める --}}
        <div class="col-md-5">
            {{-- カード形式のUIコンポーネント（会員登録フォーム全体を囲む） --}}
            <div class="card">
                {{-- カードのヘッダー部分。タイトルを表示 --}}
                <div class="card-header text-center">
                    <h2>会員登録</h2>
                </div>

                {{-- カードの本文部分（フォーム本体） --}}
                <div class="card-body">
                    {{-- 会員登録フォームの開始 --}}
                    {{-- method="POST"でデータを送信し、action="/register"で送信先URLを指定 --}}
                    <form method="POST" action="/register" novalidate>
                        {{-- @csrf：CSRF対策のためのトークンを埋め込む。Laravelでは必須 --}}
                        @csrf

                        {{-- お名前入力欄のグループ --}}
                        <div class="form-group row mb-3">
                            {{-- お名前入力欄のラベル --}}
                            <label for="name" class="col-md-4 col-form-label text-md-right">お名前</label>
                            {{-- 入力フィールドを囲む列 --}}
                            <div class="col-md-6">
                                {{-- お名前入力フィールド --}}
                                {{-- @errorディレクティブで、検証エラー時に'is-invalid'クラスを付与 --}}
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                {{-- @error('name')：お名前に関する検証エラーが存在する場合に表示 --}}
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    {{-- エラーメッセージの表示 --}}
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        {{-- メールアドレス入力欄のグループ --}}
                        <div class="form-group row mb-3">
                            {{-- メールアドレス入力欄のラベル --}}
                            <label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>
                            {{-- 入力フィールドを囲む列 --}}
                            <div class="col-md-6">
                                {{-- メールアドレス入力フィールド --}}
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                {{-- @error('email')：メールアドレスに関する検証エラーが存在する場合に表示 --}}
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    {{-- エラーメッセージの表示 --}}
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        {{-- パスワード入力欄のグループ --}}
                        <div class="form-group row mb-3">
                            {{-- パスワード入力欄のラベル --}}
                            <label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>
                            {{-- 入力フィールドを囲む列 --}}
                            <div class="col-md-6">
                                {{-- パスワード入力フィールド --}}
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                {{-- @error('password')：パスワードに関する検証エラーが存在する場合に表示 --}}
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    {{-- エラーメッセージの表示 --}}
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        {{-- 確認用パスワード入力欄のグループ --}}
                        <div class="form-group row mb-3">
                            {{-- 確認用パスワード入力欄のラベル --}}
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">確認用パスワード</label>
                            {{-- 入力フィールドを囲む列 --}}
                            <div class="col-md-6">
                                {{-- 確認用パスワード入力フィールド。name属性は'password_confirmation'がLaravelの規約 --}}
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        {{-- 登録ボタンのグループ --}}
                        <div class="form-group row mb-0">
                            {{-- ボタンの配置調整 (オフセットと幅) --}}
                            <div class="col-md-6 offset-md-4">
                                {{-- 登録実行ボタン --}}
                                <button type="submit" class="btn btn-primary">
                                    登録
                                </button>
                            </div>
                        </div>
                    </form>
                    {{-- フォームの終了 --}}

                    {{-- ログインへのリンク --}}
                    <div class="text-center mt-3">
                        <a href="/login">ログインの方はこちら</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- コンテンツセクションの終了 --}}