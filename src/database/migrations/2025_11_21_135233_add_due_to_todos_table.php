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
            $table->unsignedBigInteger('user_id')->after('category_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('due_date')->nullable()->after('category_id'); // user_idの後に移動
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
            // 外部キー制約を解除
            $table->dropForeign(['user_id']);
            // user_id カラムを削除
            $table->dropColumn('user_id');
            //'due_date'カラムを削除
            $table->dropColumn('due_date');
        });
    }
};
