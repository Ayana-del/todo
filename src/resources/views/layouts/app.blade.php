<!DOCTYPE html>
{{-- HTML5文書の開始宣言 --}}

<html lang="ja">
{{-- HTMLの開始タグ。文書の言語を日本語（ja）に設定 --}}

<head>
    {{-- メタデータセクションの開始 --}}
    <meta charset="UTF-8">
    {{-- 文字エンコーディングをUTF-8に設定 --}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{-- Internet Explorerの互換モードを設定（最新のレンダリングを強制） --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- ビューポートの設定。デバイスの幅に合わせ、初期ズームを1.0に設定（レスポンシブ対応に必須） --}}
    <title>Todo</title>
    {{-- ブラウザのタブに表示されるタイトルを設定 --}}
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    {{-- CSSファイルを読み込み。asset()ヘルパー関数でpublic/css/sanitize.cssへのパスを生成（ブラウザ間のCSS差異を吸収） --}}
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    {{-- アプリケーション共通のCSSファイルを読み込み（public/css/common.cssを想定） --}}
    @yield('css')
    {{-- 子ビューファイル（個別ページ）で定義された追加のCSSセクションを挿入するためのプレースホルダー --}}
</head>

<body>
    {{-- ドキュメントの本文（可視領域）の開始 --}}
    <header class="header">
        {{-- サイト全体のヘッダー要素の開始 --}}
        <div class="header__inner">
            <div class="header-utilities">
                {{-- ヘッダー内部のコンテナ --}}
                <a class="header__logo" href="/">
                    {{-- ロゴ（リンク）。ルートURL（/）へのリンクを設定 --}}
                    Todo
                </a>
                {{-- ナビケーションセクションの開始。サイト内の他のページへのリンクをグループ化 --}}
                <nav class="header__nav">
                    {{-- ナビケーション要素（リンクのリスト）の開始。ヘッダーナビケーション用のスタイルを適用 --}}
                    <ul class="header-nav">
                        {{-- @if (Auth::check())：ユーザーがログインしているかチェック（Laravelのヘルパー関数） --}}
                        @if (Auth::check())
                        <li class="header-nav__item">
                            {{-- ログイン済みの場合：ログアウトボタンを表示 --}}
                            <form action="/logout" method="post">
                                @csrf
                                {{-- ログアウトを実行するためのPOSTリクエストを送信するボタン --}}
                                <button class="header-nav__link header-nav__button-reset logout-button" type="submit">
                                    ログアウト
                                </button>
                            </form>
                        </li>
                        @else
                        {{-- 未ログインの場合：ログインと会員登録へのリンクを表示 --}}
                        <li class="header-nav__item">
                            <a class="header-nav__link" href=/login>ログイン</a>
                        </li>
                        <li class="header-nav__item">
                            <a class="header-nav__link" href="/register">会員登録</a>
                        </li>
                        @endif
                        <li class="header-nav__item">
                            {{-- カテゴリ一覧ページ（/categories）へのリンク --}}
                            <a class="header-nav__link" href="/categories">カテゴリ一覧</a>
                        </li>
                    </ul>
                    {{-- ナビゲーションの終了 --}}
                </nav>
                {{-- header-utilitiesのコンテナ終了 --}}
            </div>
            {{-- header__innerのコンテナ終了 --}}
        </div>
        {{-- サイト全体のヘッダー要素の終了 --}}
    </header>

    <main>
        {{-- メインコンテンツ領域の開始 --}}
        @yield('content')
        {{-- 子ビューファイルで定義されたメインコンテンツ（@section('content')）を挿入するためのプレースホルダー --}}
    </main>
</body>

</html>
{{-- HTML文書の終了 --}}