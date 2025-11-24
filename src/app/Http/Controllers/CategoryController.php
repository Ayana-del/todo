<?php
// このファイルが属する名前空間（ディレクトリ構造）を定義する
// このクラスが App\Http\Controllers フォルダにあることを示す
namespace App\Http\Controllers;

// カテゴリ作成・更新用のバリデーションルールを含むカスタムリクエストクラスをインポートする。
use App\Http\Requests\CategoryRequest;
// データベースの Category モデル（カテゴリ情報を扱うクラス）をインポートする
use App\Models\Category;
// HTTPリクエスト（ユーザーからの入力データなど）を扱う Request クラスをインポートする
use Illuminate\Http\Request;

// CategoryControllerクラスを定義する。Controllerクラスを継承しており、Webリクエストを処理。
class CategoryController extends Controller
{
    /**
     * カテゴリ一覧ページを表示するメソッド (Read)
     * ルート: GET /categories
     * 処理：全てのカテゴリを取得し、ビューに渡して表示する。
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Categoryモデルを使って、データベースの categories テーブルから全てのレコードを取得する
        // 取得したデータは $categories 変数に格納される。
        $categories = Category::all();

        // 'category' という名前のビュー（resources/views/category.blade.phpなど）を読み込んで返す
        // compact('categories') は【'categories' => $categories】という形でビューに変数を渡す
        return view('category', compact('categories'));
    }

    /**
     * 新しいカテゴリを作成するメソッド (Create)
     * ルート: POST /categories
     * 処理：バリデーション済みのデータで新しいカテゴリをDBに保存し、リダイレクトする。
     *
     * @param CategoryRequest $request カテゴリ作成・更新用のバリデーション済みリクエスト
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryRequest $request)
    {
        // リクエストデータから 'name' フィールドのみを抽出する（マスアサインメント用）。
        $category = $request->only(['name']);
        // 抽出したデータで Category モデルの新しいレコードをデータベースに作成する。
        Category::create($category);

        // カテゴリ一覧ページ (/categories) にリダイレクトし、成功メッセージをセッションに保存する。
        return redirect('/categories')->with('message', 'カテゴリを作成しました');
    }

    /**
     * 既存のカテゴリを更新するメソッド (Update)
     * ルート: PATCH /categories/update
     * 処理：リクエストIDに基づいてカテゴリを特定し、バリデーション済みのデータで更新する。
     *
     * @param CategoryRequest $request カテゴリ作成・更新用のバリデーション済みリクエスト
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CategoryRequest $request)
    {
        // リクエストデータから更新対象の 'name' フィールドのみを抽出する。
        $category = $request->only(['name']);
        // リクエストに含まれる id を基に対象のカテゴリを見つけ、抽出したデータで更新する。
        Category::find($request->id)->update($category);

        // カテゴリ一覧ページ (/categories) にリダイレクトし、成功メッセージをセッションに保存する。
        return redirect('/categories')->with('message', 'カテゴリを更新しました');
    }


    /**
     * カテゴリを削除するメソッド (Delete)
     * ルート: DELETE /categories/delete
     * 処理：リクエストIDに基づいてカテゴリを特定し、DBから削除する。
     *
     * @param Request $request 削除対象のidを含むリクエスト
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        // リクエストに含まれる id を基に対象のカテゴリを見つけ、データベースから削除する。
        Category::find($request->id)->delete();

        // カテゴリ一覧ページ (/categories) にリダイレクトし、成功メッセージをセッションに保存する。
        return redirect('/categories')->with('message', 'カテゴリを削除しました');
    }
}
// このコントローラの役割
// このコントローラは、ウェブアプリケーションの「カテゴリ一覧」ページと、その管理機能（CRUD - 作成、読み取り、更新、削除）を担当します。
// 例: index()メソッドはデータベースから全てのカテゴリーデータを取得し、そのデータを category.blade.php ビューファイルに渡して、ユーザーに表示させるという一連の流れを制御します。