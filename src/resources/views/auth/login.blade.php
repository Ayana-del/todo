@extends('layouts.app')

{{-- CSSセクションの開始 --}}
@section('css')
{{-- 'css/login.css'のスタイルシートを読み込む --}}
<link rel="stylesheet" href="{{ asset('css/login.css')}}">
@endsection

{{-- コンテンツセクションの開始 --}}
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header text-center">
                    <h2>ログイン</h2>
                </div>
                <div class="card-body">

                    {{-- 成功メッセージの表示（ログアウト後や登録後） --}}
                    @if (session('success'))
                    <div class="alert alert-success text-center" role="alert" style="color: green;">
                        {{ session('success') }}
                    </div>
                    @endif

                    {{-- フォームのactionをルートヘルパーに変更（必須） --}}
                    <form method="POST" action="{{ route('login.store') }}" novalidate>
                        @csrf

                        {{-- メールアドレス入力欄のグループ --}}
                        <div class="form-group row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('メールアドレス') is-invalid @enderror" name="メールアドレス" value="{{ old('メールアドレス') }}" required autocomplete="email" autofocus>
                                @error('メールアドレス')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        {{-- パスワード入力欄のグループ --}}
                        <div class="form-group row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('パスワード') is-invalid @enderror" name="パスワード" required autocomplete="current-password">
                                @error('パスワード')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        {{-- ログインボタンのグループ --}}
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    ログイン
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- 会員登録へのリンク --}}
                    <div class="text-center mt-3">
                        <a href="{{ route('register') }}">会員登録の方はこちら</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection