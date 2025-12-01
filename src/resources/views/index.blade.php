{{-- レイアウトファイル’layouts/app.blade.php'を継承する --}}
@extends('layouts.app')

{{-- 'css'セクションの開始 --}}
@section('css')
{{-- index.cssのCSSファイルを読み込む --}}
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
{{-- 'css'セクションの終了 --}}
@endsection

{{-- 'content'セクションの開始。ここに固有のコンテンツを記述する --}}
@section('content')
{{-- アラートメッセージ（成功・エラー）を表示するためのコンテナ。 --}}
<div class="todo__alert">
    {{-- セッションに’message'が存在するかどうかを確認する（成功メッセージ用） --}}
    @if(session('message'))
    {{-- 成功メッセージのスタイルを持つdiv --}}
    <div class="todo__alert--success">
        {{-- セッションに保存されているメッセージ（例：作成・更新・削除完了）を表示する。 --}}
        {{ session('message') }}
    </div>
    {{-- if文の終了文 --}}
    @endif
    {{-- バリデーションエラーが存在するかどうかを確認する。 --}}
    @if ($errors->any())
    {{-- エラーメッセージのスタイルを持つdiv --}}
    <div class="todo__alert--danger">
        {{-- エラーメッセージのリスト --}}
        <ul>
            {{-- 全てのエラーメッセージをループ処理する --}}
            @foreach ($errors->all() as $error)
            {{-- 個々のエラーメッセージをリストアイテムとして表示する --}}
            <li>{{ $error }}</li>
            {{-- ループ処理の終了 --}}
            @endforeach
        </ul>
    </div>
    @endif
</div>

{{-- Todoリストのメインコンテンツコンテナ --}}
<div class="todo__content">
    {{-- セクション見出しのコンテナ --}}
    <div class="section__title">
        {{-- 新規作成セクションの見出し --}}
        <h2>新規作成</h2>
    </div>
    {{-- 新しいTodoを作成するためのフォーム。送信先は/todos, HTTPメソッドはPOST --}}
    <form class="create-form" action="/todos" method="post">
        @csrf
        {{-- CSRFトークン --}}
        <div class="create-form__item">
            {{-- Todo内容の入力フィールド。value="{{ old('content') }}"で入力値を保持 --}}
            <input class="create-form__item-input" type="text" name="content" value="{{ old('content') }}">
            {{-- カテゴリ選択のドロップダウンリスト --}}
            <select class="create-form__item-select" name="category_id">
                <option value="">カテゴリ</option>
                {{-- コントローラーから渡された $categories をループしてオプションを生成 --}}
                @foreach ($categories as $category)
                <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                @endforeach
            </select>
            {{-- 期日入力フィールド（日付ピッカー）。title属性でホバー時の説明を提供 --}}
            <input class="create-form__item-input" type="date" name="due_date" value="{{ old('due_date') }}" title="期日">
        </div>
        <div class="create-form__button">
            {{-- 作成実行ボタン --}}
            <button class="create-form__button-submit" type="submit">作成</button>
        </div>
    </form>
    {{-- セクション見出しのコンテナ --}}
    <div class="section__title">
        {{-- Todo検索セクションの見出し --}}
        <h2>Todo検索</h2>
    </div>
    {{-- Todoを検索するためのフォームの開始。送信先は/todos/search, HTTPメソッドはGET --}}
    <form class="search-form" action="/todos/search" method="get">
        {{-- 検索フォームにCSRFトークンは通常不要だが、含めても問題はない（GETメソッドのため） --}}
        @csrf
        <div class="search-form__item">
            {{-- 検索ワードを入力するためのテキスト入力フィールド --}}
            <input class="search-form__item-input" type="text" name="keyword" value="{{ old('keyword') }}">
            {{-- 検索カテゴリを選択するためのドロップダウンリスト --}}
            <select class="search-form__item-select" name="category_id">
                {{-- 初期表示のオプション --}}
                <option value="">カテゴリ</option>
                @foreach ($categories as $category)
                <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                @endforeach
            </select>
            {{-- 検索期日入力フィールド。value="{{ request('due_date') }}"で検索後の値を保持 --}}
            <input class="search-form__item-input" type="date" name="due_date" value="{{ request('due_date') }}" title="期日">
        </div>
        <div class="search-form__button">
            {{-- フォームを送信する「検索」ボタン --}}
            <button class="search-form__button-submit" type="submit">検索</button>
        </div>
    </form>
    {{-- Todoリストのテーブル全体を囲むコンテナ --}}
    <div class="todo-table">
        {{-- Todoリストのテーブル本体 --}}
        <table class="todo-table__inner">
            {{-- Todoテーブルヘッダー行 --}}
            <tr class="todo-table__row">
                <th class="todo-table__header"></th>{{--チェックボックス用--}}
                <th class="todo-table__header">Todo</th>
                <th class="todo-table__header">カテゴリ</th>
                <th class="todo-table__header">期日</th>
                {{-- ソートメニュー全体を囲むコンテナ --}}
                <div class="sort-select-container">

                    {{-- ★JavaScriptで操作するためのプルダウンメニュー★ --}}
                    <select id="sort-menu" class="sort-menu-select">
                        <option value="">並べ替え</option> {{-- デフォルト表示 --}}
                        <option value="due_date">期日順</option>
                        <option value="created_at">作成順</option>
                        <option value="category_id">カテゴリ順</option>
                    </select>

                    {{-- ★現在のソート状態を示すアイコン（任意）★ --}}
                    {{-- 現在のソート条件をPHPで取得し、表示する --}}
                    @php
                    $currentSort = $request->get('sort');
                    $currentDirection = $request->get('direction', 'desc');
                    @endphp
                    @if ($currentSort)
                    <span class="sort-status-icon">
                        @if ($currentDirection == 'asc') ▲ @else ▼ @endif
                    </span>
                    @endif
                </div>
                <th class="todo-table__header"></th> {{-- 更新ボタン用 --}}
                <th class="todo-table__header"></th> {{-- 削除ボタン用 --}}
            </tr>

            {{-- コントローラーから渡された$todosコレクションをループ処理する。 --}}
            @foreach ($todos as $todo)
            <tr class="todo-table__row">

                {{-- 1. 完了チェックボックスのセル --}}
                <td class="todo-table__item todo-item-checkbox">
                    <form class="complete-form" action="{{ route('todo.complete', ['id' => $todo['id']]) }}" method="POST">
                        @method('PATCH') @csrf
                        <input type="checkbox" name="completed" value="1" onchange="this.form.submit()" {{ $todo['completed'] ? 'checked' : '' }}>
                    </form>
                </td>
                <form class="update-form update-form__wrapper" action="/todos/update" method="POST">
                    @method('PATCH') @csrf
                    <input type="hidden" name="id" value="{{ $todo['id'] }}">

                    {{-- 2. Todo内容のセル --}}
                    <td class="todo-table__item todo-item-content">
                        <input class="update-form__item-input" type="text" name="content" value="{{ $todo['content'] }}">
                    </td>

                    {{-- 3. カテゴリのセル --}}
                    <td class="todo-table__item todo-item-category">
                        <select class="update-form__item-input" name="category_id">
                            @foreach ($categories as $category)
                            <option value="{{ $category['id'] }}" {{ $todo['category_id'] == $category['id'] ? 'selected' : '' }}>
                                {{ $category['name'] }}
                            </option>
                            @endforeach
                        </select>
                    </td>

                    {{-- 4. 期日のセル --}}
                    <td class="todo-table__item todo-item-due-date">
                        <input class="update-form__item-input" type="date" name="due_date" value="{{ $todo['due_date'] }}">
                    </td>

                    {{-- 5. 更新ボタンのセル --}}
                    <td class="todo-table__item todo-item-update-button">
                        <button class="update-form__button-submit" type="submit">更新</button>
                    </td>
                </form>

                {{-- 6. 削除ボタンのセル --}}
                <td class="todo-table__item todo-item-delete-button">
                    <form class="delete-form" action="/todos/delete" method="POST">
                        @method('DELETE') @csrf
                        <input type="hidden" name="id" value="{{ $todo['id'] }}">
                        <button class="delete-form__button-submit" type="submit">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
<script>
    // ページロード時に実行
    document.addEventListener('DOMContentLoaded', function() {
        const sortMenu = document.getElementById('sort-menu');

        // 1. URLから現在のソート条件を取得し、プルダウンに反映
        const urlParams = new URLSearchParams(window.location.search);
        const currentSort = urlParams.get('sort');

        if (currentSort) {
            sortMenu.value = currentSort;
        }

        // 2. プルダウンの選択が変更されたときにページをリロード（ソート条件を適用）
        sortMenu.addEventListener('change', function() {
            const selectedSort = this.value;
            const currentDirection = urlParams.get('direction') || 'desc'; // デフォルトは降順

            if (selectedSort) {
                // 選択されたソート条件と現在の方向をURLに追加してリダイレクト
                window.location.href = `{{ url()->current() }}?sort=${selectedSort}&direction=${currentDirection}`;
            } else {
                // 「並べ替え」が選ばれた場合、ソート条件を解除してリダイレクト
                window.location.href = `{{ url()->current() }}`;
            }
        });
    });
</script>
{{-- ★ ページネーションリンクの追加 ★ --}}
<div class="pagination">
    {{-- Laravelのページネーションリンクを表示する（$todos変数がページネーターインスタンスである必要あり） --}}
    {{ $todos->links() }}
</div>
{{-- ★ ページネーションリンクの追加 終 ★ --}}

</div>
{{-- 'content'セクションの終了 --}}
@endsection