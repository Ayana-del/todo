<?php
//マイグレーションの基底クラスをインポート
use Illuminate\Database\Migrations\Migration;
//データベーステーブルの構造を定義するためのクラスをインポート（テーブルの設計図）
use Illuminate\Database\Schema\Blueprint;
//データベース構造操作を行うためのファサードをインポート（テーブルの作成・変更）
use Illuminate\Support\Facades\Schema;

//Migrationクラスを継承してAddCompletedToTodosTableクラスを定義
class AddCompletedToTodosTable extends Migration
{
    /**
     * Run the migrations.
     *【順方向の変更】マイグレーションを実行する（データベースに変更を運用する）。
     *このメソッドで、テーブルに新しいカラムを追加する。
     * @return void
     */
    public function up():void
    {
        //既存の'todos'テーブルに対して操作を行う
        Schema::table('todos', function (Blueprint $table) {
            // 'completed'という名前の新しいカラムを追加する
            $table->boolean('completed')    //データ型を真偽値に設定
            ->default(false)                //デフォルト値（初期値）を'false'(未完了)に設定
            ->after('due_date');            //'due_date'カラムの直後にこの新しいカラムを配置する
        });
    }

    /**
     * Reverse the migrations.
     *【逆方向の変更】マイグレーションを取り消す（もとに戻す）際に実行されるメソッド
     *'php artisan migrate:rollback'コマンドなどで呼び出される
     * @return void
     */
    public function down()
    {   //既存の'todos'テーブルに対して操作を行う
        Schema::table('todos', function (Blueprint $table) {
            //'completed'カラムをテーブルから削除する
            $table->dropColumn('completed');
        });
    }
}
