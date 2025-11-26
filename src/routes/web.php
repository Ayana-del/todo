<?php
//laravelのルーティング機能を使うためにひつようなRouteファサードをインポートする
use Illuminate\Support\Facades\Route;
//TodoControllerクラスを使用することを宣言
use App\Http\Controllers\TodoController;
//CategoryControllerクラスを使用することを宣言
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\AuthController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/register', [AuthController::class, 'createRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::get('/login', [AuthController::class, 'createSessionForm'])->name('login');
Route::post('/login', [AuthController::class, 'storeSession'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroySession'])->name('logout');


// 'auth'ミドルウェアを適用するグループ定義
// このグループ内のすべてのルートは、ユーザーがログインしていることを確認する（認証が必要な）
Route::middleware('auth')->group(function () {
    // GETリクエストが'/'(ルートURL)に来たとき、TodoControllerのindexメソッドを実行する。
    // 役割：Todoリストの一覧表示（メイン画面のビュー表示）
    Route::get('/', [TodoController::class, 'index']);
    // GETリクエストが'/todos/search'に来たとき、TodoControllerのsearchメソッドを実行する。
    // 役割：Todoリストの検索機能を提供
    Route::get('/todos/search', [TodoController::class, 'search']);
    // POSTリクエストが'/todos'に来たとき、TodoControllerのstoreメソッドを実行する。
    // 役割：新しいTodoアイテムをデータベースに保存（作成）
    Route::post('/todos', [TodoController::class, 'store']);
    // PATCHリクエストが'/todos/update'に来たとき、TodoControllerのupdateメソッドを実行する。
    // 役割：既存のTodoアイテムの内容や状態を更新
    Route::patch('/todos/update', [TodoController::class, 'update']);
    // DELETEリクエストが'/todos/delete'に来たとき、TodoControllerのdestroyメソッドを実行する。
    // 役割：指定したTodoアイテムをデータベースから削除
    Route::delete('/todos/delete', [TodoController::class, 'destroy']);

    // GETリクエストが'/categories'に来たとき、CategoryControllerのindexメソッドを実行する。
    // 役割：カテゴリ一覧ページを表示する
    Route::get('/categories', [CategoryController::class,  'index']);
    // POSTリクエストが'/categories'に来たとき、CategoryControllerのstoreメソッドを実行する。
    // 役割：新しいカテゴリをデータベースに保存（作成）
    Route::post('/categories', [CategoryController::class, 'store']);
    // PATCHリクエストが'/categories/update'に来たとき、CategoryControllerのupdateメソッドを実行する。
    // 役割：既存のカテゴリ名を更新
    Route::patch('/categories/update', [CategoryController::class, 'update']);
    // DELETEリクエストが'/categories/delete'に来たとき、CategoryControllerのdestroyメソッドを実行する。
    // 役割：指定したカテゴリをデータベースから削除
    Route::delete('/categories/delete', [CategoryController::class, 'destroy']);
});
