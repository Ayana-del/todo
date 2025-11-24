<?php

// このファイルがApp\Http\Controllers名前空間に属することを定義する。
namespace App\Http\Controllers;

// バリデーションルールを含むカスタムリクエストクラスをインポートする。
use App\Http\Requests\TodoRequest;
// Categoryモデル（カテゴリ情報を扱うクラス）をインポートする。
use App\Models\Category;
// Todoモデル（Todoアイテムの情報を扱うクラス）をインポートする。
use App\Models\Todo;
// HTTPリクエスト（ユーザーからの入力データなど）を扱う Request クラスをインポートする。
use Illuminate\Http\Request;

// TodoControllerクラスを定義し、Laravelの基底コントローラ（Controller)を継承
class TodoController extends Controller
{
    /**
     * Todoリスト表示（index)メソッド
     * ルート：GET /
     * 処理：最新のTodoを10件ずつページネーションで取得し、カテゴリ一覧とともにビューに渡す。
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // ページネーションを適用: 1ページあたり10件のToDoを、関連するカテゴリデータととも
        // に最新の更新順（latest()）で取得する
        $todos = Todo::with('category')->latest()->paginate(10);

        // Categoryモデルを使って、全てのカテゴリーデータを取得する（新規作成・検索フォーム用）。
        $categories = Category::all();

        // 'index'ビューファイルを呼び出し、取得した $todos と $categories データをビューに渡す。
        return view('index', compact('todos', 'categories'));
    }

    /**
     * Todo検索（search)メソッド
     * ルート：GET /todos/search
     * 処理：リクエストに基づきTodoを絞り込み、検索結果とカテゴリ一覧をビューに渡す。
     *
     * @param Request $request 検索条件を含むリクエスト
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        // Todoモデルから、関連カテゴリデータとともにデータを取得する
        // categorySearch()、keywordSearch()、dueDateSearch()はTodoモデルに定義されたローカルスコープである必要がある。
        $todos = Todo::with('category')
            ->categorySearch($request->category_id)
            ->keywordSearch($request->keyword)
            // 期日検索ロジックを反映
            ->dueDateSearch($request->due_date)
            //get() を paginate(10) に変更し、ページネーションを適用する
            ->paginate(10)
            // withQueryString() を追加し、検索条件をページネーションリンクに含める
            ->withQueryString();

        // 全カテゴリーデータを取得する（検索フォームの選択肢表示用。）
        $categories = Category::all();

        // 検索結果を表示する'index'ビューファイルを呼び出し、取得したデータを渡す。
        return view('index', compact('todos', 'categories'));
    }

    /**
     * 新規Todo作成（store）メソッド
     * ルート：POST /todos
     * 処理：バリデーション済みのデータで新しいTodoをDBに保存し、メインページへリダイレクトする。
     *
     * @param TodoRequest $request バリデーション済みのリクエストデータ
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TodoRequest $request)
    {
        // リクエストデータから'category_id'、'content'、'due_date'フィールドのみを抽出する。
        $todo = $request->only(['category_id', 'content', 'due_date']);

        // 抽出したデータで Todo モデルの新しいレコードをデータベースに作成する
        Todo::create($todo);

        // ルートURL('/')にリダイレクトし、セッションに成功メッセージを一時的に保存する。
        return redirect('/')->with('message', 'Todoを作成しました');
    }

    /**
     * Todo更新（update）メソッド
     * ルート：PATCH /todos/update
     * 処理：リクエストIDに基づきTodoを特定し、バリデーション済みのデータで更新する。
     *
     * @param Request $request 更新データ（id、content、category_id、due_date）を含むリクエスト
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // リクエストデータから'content'、'category_id'、'due_date'フィールドのみを抽出
        $todo = $request->only(['content', 'category_id', 'due_date']);

        // リクエストに含まれる id を基に対象のTodoを見つけ、抽出したデータで更新する
        Todo::find($request->id)->update($todo);

        // ルートURL（'/'）にリダイレクトし、セッションに成功メッセージを一時的に保存する
        return redirect('/')->with('message', 'Todoを更新しました');
    }

    /**
     * Todo削除（destroy）メソッド
     * ルート：DELETE /todos/delete
     * 処理：リクエストIDに基づきTodoを特定し、DBから削除する。
     *
     * @param Request $request 削除対象のidを含むリクエスト
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        // リクエストに含まれる id を基に対象のTodoを見つけ、データベースから削除する
        Todo::find($request->id)->delete();

        // ルートURL（'/'）にリダイレクトし、セッションに成功メッセージを一時的に保存する
        return redirect('/')->with('message', 'Todoを削除しました');
    }
}
