<?php

//マイグレーション機能を使うためのクラスをインポート
use Illuminate\Database\Migrations\Migration;
//データベーススキーマ（構造）定義のためのクラスをインポート
use Illuminate\Database\Schema\Blueprint;
//データベースの操作（テーブル作成・変更など）のためのファサードをインポート
use Illuminate\Support\Facades\Schema;

//無名クラスでmigrationを継承し、migration処理を定義
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //マイグレーションを実行（データベース変更）する際に呼び出されるメソッド
    public function up():void
    {
        Schema::table('todos', function (Blueprint $table) {
            //date型のカラム’due_date'を追加する。
            //nullable()はNULL値（値なし）を許容する設定。
            //after('category-id')は、’category_id'カラムの直後に追加する指定（オプション）
            $table->date('due_date')->nullable()->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
    */
    //マイグレーションをロールバック（元に戻す）する際に呼び出されるメソッド
    public function down():void
    {
        //'todos'テーブルに対して操作を行う
        //'due_date'カラムを削除する（upメソッドで行った変更を取り消す）
        Schema::table('todos', function (Blueprint $table) {
            $table->dropColumn('due_date');
        });
    }
};
