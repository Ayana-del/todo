@extends('layouts.app')

{{-- CSSセクションの開始 --}}
@section('css')
{{-- 'css/category.css'のスタイルシートを読み込む --}}
<link rel="stylesheet" href="{{ asset('css/category.css') }}">
@endsection

{{-- コンテンツセクションの開始 --}}
@section('content')
{{-- アラートメッセージ表示エリアのコンテナ --}}
<div class="category__alert">
    {{-- セッションに'message'（成功メッセージ）があるかチェック --}}
    @if (session('message'))
    <div class="category__alert--success">
        {{-- 成功メッセージを表示 --}}
        {{ session('message') }}
    </div>
    @endif
    {{-- バリデーションエラーがあるかチェック --}}
    @if ($errors->any())
    <div class="category__alert--danger">
        <ul>
            {{-- すべてのエラーメッセージをリストとして表示 --}}
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
{{-- メインコンテンツ（作成フォームと一覧テーブル）のコンテナ --}}
<div class="category__content">
    {{-- 新規カテゴリ作成フォームの開始 --}}
    <form class="create-form" action="/categories" method="post">
        @csrf
        {{-- CSRF対策トークン --}}
        <div class="create-form__item">
            {{-- カテゴリ名入力フィールド。value="{{ old('name') }}"で入力値を保持 --}}
            <input class="create-form__item-input" type="text" name="name" value="{{ old('name') }}">
        </div>
        <div class="create-form__button">
            {{-- 作成実行ボタン --}}
            <button class="create-form__button-submit" type="submit">作成</button>
        </div>
    </form>
    {{-- カテゴリ一覧テーブルのコンテナ --}}
    <div class="category-table">
        <table class="category-table__inner">
            {{-- テーブルのヘッダー行 --}}
            <tr class="category-table__row">
                {{-- ヘッダーセル（カテゴリ名用） --}}
                <th class="category-table__header">category</th>
            </tr>
            {{-- コントローラから渡された $categories コレクションをループ処理 --}}
            @foreach ($categories as $category)
            <tr class="category-table__row">
                {{-- カテゴリ名（更新フォームを含む）を表示するセル --}}
                <td class="category-table__item">
                    {{-- カテゴリ更新フォームの開始 --}}
                    <form class="update-form" action="/categories/update" method="post">
                        @method('PATCH')
                        {{-- HTTPメソッドをPATCH（更新）として認識させるためのBladeディレクティブ --}}
                        @csrf
                        {{-- CSRF対策トークン --}}
                        <div class="update-form__item">
                            {{-- 現在のカテゴリ名を表示し、編集できるようにする入力フィールド --}}
                            <input class="update-form__item-input" type="text" name="name" value="{{ $category['name'] }}">
                            {{-- 更新対象のカテゴリIDをサーバーに送るための隠しフィールド --}}
                            <input type="hidden" name="id" value="{{ $category['id'] }}">
                        </div>
                        <div class="update-form__button">
                            {{-- 更新実行ボタン --}}
                            <button class="update-form__button-submit" type="submit">更新</button>
                        </div>
                    </form>
                </td>
                {{-- 削除ボタンを表示するセル --}}
                <td class="category-table__item">
                    {{-- カテゴリ削除フォームの開始 --}}
                    <form class="delete-form" action="/categories/delete" method="post">
                        @method('DELETE')
                        {{-- HTTPメソッドをDELETE（削除）として認識させるためのBladeディレクティブ --}}
                        @csrf
                        {{-- CSRF対策トークン --}}
                        <div class="delete-form__button">
                            {{-- 削除対象のカテゴリIDをサーバーに送るための隠しフィールド --}}
                            <input type="hidden" name="id" value="{{ $category['id'] }}">
                            {{-- 削除実行ボタン --}}
                            <button class="delete-form__button-submit" type="submit">削除</button>
                        </div>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection