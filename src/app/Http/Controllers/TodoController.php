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
//認証済みユーザーの情報を扱うファサードをインポート
use Illuminate\Support\Facades\Auth;

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
    //HTTPリクエストオブジェクトを受け取り、URLパラメータなどを取得
    public function index(Request $request)
    {
        // 1. ソート条件の定義
        //データベースでソートを許可するカラム名の配列
        $allowedSorts = ['due_date', 'created_at', 'category_id'];
        //ソートパラメータがない場合のデフォルトのカラム
        $defaultSort = 'created_at';

        //URLパラメータからソートカラム（sort）と方向（direction）を取得
        // get('パラメータ名', 'デフォルト値')
        $sortColumn = $request->get('sort', $defaultSort);
        // デフォルトのソート方向は降順（desc）
        $sortDirection = $request->get('direction', 'desc');

        // 不正な値のチェック (セキュリティと安定性のための防御処理)
        // ユーザーが指定した $sortColumn が $allowedSorts に含まれているかチェックする。
        if (!in_array($sortColumn, $allowedSorts)) {
            $sortColumn = $defaultSort; //含まれていなければデフォルト値に戻す
        }
        //ユーザーが指定した $sortDirection が 'asc' または 'desc' かをチェックする。
        if (!in_array(strtolower($sortDirection), ['asc', 'desc'])) {
            $sortDirection = 'desc'; //含まれていなければ降順（desc）に戻す
        }

        // 2. クエリの構築とソート適用
        $query = Todo::with('category'); //Todoモデルのクエリを開始し、リレーション（category）をEager Loadする

        // ソート条件の適用
        // データベースクエリにorderByメソッドを使って、指定されたカラムと方向で並び替えを適用
        $query->orderBy($sortColumn, $sortDirection);

        // ページネーションの実行
        // 1ページあたり10件のToDoを取得する
        $todos = $query->paginate(10)
            // appends() でソートパラメータをページネーションリンクに引き継ぐ
            //ページ遷移時にもソートの状態を維持するために、現在のソート条件をURLに追加する
            ->appends(['sort' => $sortColumn, 'direction' => $sortDirection]);

        // Categoryモデルを使って、全てのカテゴリーデータを取得する（新規作成・検索フォーム用）。
        $categories = Category::all();

        //Viewにデータを渡す
        // 'index'ビューファイルを呼び出し、取得した $todos、$categories、および現在の$requestを渡す
        // $requestをViewに渡すことで、Bladeで現在のソート状態（▲/▼マーク）を判定可能にする
        return view('index', compact('todos', 'categories','request'));
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
        // 1. ソート条件の定義（indexメソッドと同様）
        $allowedSorts = ['due_date', 'created_at', 'category_id'];
        $defaultSort = 'created_at';

        // 検索時もソート条件を適用できるようにパラメータを取得
        $sortColumn = $request->get('sort', $defaultSort);
        $sortDirection = $request->get('direction', 'desc');

        if (!in_array($sortColumn, $allowedSorts)) {
            $sortColumn = $defaultSort;
        }
        if (!in_array(strtolower($sortDirection), ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        // 2. 検索クエリの構築
        $query = Todo::with('category')
            // categorySearch()、keywordSearch()、dueDateSearch() はTodoモデルに定義されたローカルスコープを呼び出し、フィルタリングを行う
            ->categorySearch($request->category_id)
            ->keywordSearch($request->keyword)
            ->dueDateSearch($request->due_date);

        // ★ソート条件の適用★
        $query->orderBy($sortColumn, $sortDirection);

        // 3. ページネーションの実行
        $todos = $query->paginate(10)
            // withQueryString() を追加し、検索条件をページネーションリンクに含める
            ->withQueryString()
            // ★appends() でソートパラメータも確実に引き継ぐ★
            ->appends(['sort' => $sortColumn, 'direction' => $sortDirection]);

        // 全カテゴリーデータを取得する（検索フォームの選択肢表示用。）
        $categories = Category::all();

        // 4. Viewにデータを渡す
        // 検索結果を表示する'index'ビューファイルを呼び出し、取得したデータを渡す
        return view('index', compact('todos', 'categories', 'request')); // $requestをViewに渡す
    }

    /**
     * 新規Todo作成（store）メソッド
     * ルート：POST /todos
     * 処理：バリデーション済みのデータで新しいTodoをDBに保存し、メインページへリダイレクトする。
     *
     * @param TodoRequest $request バリデーション済みのリクエストデータ
     * @return \Illuminate\Http\RedirectResponse
     */

    public function complete($id)
    {
        // 認証ユーザーに属するTodoをIDで検索
        // fail() を使用し、Todoが存在しない、またはユーザーに属さない場合は404を返す
        $todo = Todo::where('user_id', Auth::id())->findOrFail($id);

        // completed の値を反転させて更新
        // 例: true -> false, false -> true
        $todo->update([
            'completed' => !$todo->completed // 現在の値の論理否定
        ]);

        // 元のページにリダイレクトして完了メッセージを表示
        return redirect('/')->with('message', 'Todoの状態を更新しました');
    }

    public function store(TodoRequest $request)
    {
        // リクエストデータから'category_id'、'content'、'due_date'フィールドのみを抽出する。
        $todoData = $request->only(['category_id', 'content', 'due_date']);

        $todoData['user_id'] = $request->user()->id;

        //抽出したデータでTodoモデルの新しいレコードをデータベースに作成する
        Todo::create($todoData);

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
