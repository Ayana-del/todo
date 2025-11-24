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
                <th class="todo-table__header">Todo</th>
                <th class="todo-table__header">カテゴリ</th>
                <th class="todo-table__header">期日</th>
                <th class="todo-table__header"></th> {{-- 更新ボタン用 --}}
                <th class="todo-table__header"></th> {{-- 削除ボタン用 --}}
            </tr>
            {{-- コントローラーから渡された$todosコレクションをループ処理する。 --}}
            @foreach ($todos as $todo)
            {{-- 個々のTodoアイテムの行 --}}
            <tr class="todo-table__row">
                {{-- Todo内容のセル (更新フォーム内) --}}
                <td class="todo-table__item">
                    {{-- Todoを更新するためのフォーム。PATCHメソッドで送信 --}}
                    <form class="update-form" action="/todos/update" method="POST">
                        @method('PATCH')
                        @csrf
                        {{-- 更新対象のTodoのIDを隠しフィールドとして保持 --}}
                        <input type="hidden" name="id" value="{{ $todo['id'] }}">
                        {{-- 現在のTodo内容を表示し、編集するための入力フィールド --}}
                        <input class="update-form__item-input" type="text" name="content" value="{{ $todo['content'] }}">
                </td>

                {{-- カテゴリ名のセル --}}
                <td class="todo-table__item">
                    {{-- カテゴリを選択・変更するためのドロップダウンリスト --}}
                    <select class="update-form__item-input" name="category_id">
                        @foreach ($categories as $category)
                        <option value="{{ $category['id'] }}" {{ $todo['category_id'] == $category['id'] ? 'selected' : '' }}>
                            {{ $category['name'] }}
                        </option>
                        @endforeach
                    </select>
                </td>

                {{-- 期日のセル --}}
                <td class="todo-table__item">
                    {{-- 現在の期日を表示（日付フォーマットの調整） --}}
                    <p class="update-form__item-p">
                        {{ $todo['due_date'] ? date('Y/m/d', strtotime($todo['due_date'])) : '未設定' }}
                    </p>
                    {{-- 期日を変更するための日付入力フィールド --}}
                    <input class="update-form__item-input" type="date" name="due_date"
                        value="{{ $todo['due_date'] }}">
                </td>

                {{-- 更新ボタンのセル --}}
                <td class="todo-table__item">
                    <div class="update-form__button">
                        {{-- フォームを送信する「更新」ボタン --}}
                        <button class="update-form__button-submit" type="submit">更新</button>
                    </div>
                    {{-- Todo内容の入力フィールドと更新ボタンを同じフォームに入れるため、フォームを閉じる --}}
                    </form>
                </td>

                {{-- 削除フォームとボタンのセル --}}
                <td class="todo-table__item">
                    {{-- Todoを削除するためのフォーム。DELETEメソッドで送信 --}}
                    <form class="delete-form" action="/todos/delete" method="POST">
                        @method('DELETE')
                        @csrf
                        <div class="delete-form__button">
                            {{-- 削除対象のTodoのIDを隠しフィールドとして保持 --}}
                            <input type="hidden" name="id" value="{{ $todo['id'] }}">
                            {{-- フォームを送信する「削除」ボタン --}}
                            <button class="delete-form__button-submit" type="submit">削除</button>
                        </div>
                    </form>
                </td>
            </tr>
            {{-- ループの終了 --}}
            @endforeach
        </table>
    </div>
    {{-- ★ ページネーションリンクの追加 ★ --}}
    <div class="pagination">
        {{-- Laravelのページネーションリンクを表示する（$todos変数がページネーターインスタンスである必要あり） --}}
        {{ $todos->links() }}
    </div>
    {{-- ★ ページネーションリンクの追加 終 ★ --}}

</div>
{{-- 'content'セクションの終了 --}}
@endsection