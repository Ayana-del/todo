<?php

//マイグレーションの基底クラスをインポート
use Illuminate\Database\Migrations\Migration;
//データベーステーブルのスキーマ（構造）を定義するためのクラスをインポート
use Illuminate\Database\Schema\Blueprint;
//データベーススキーマ操作を行うためのファサード（静的メソッドへのアクセス）をインポート
use Illuminate\Support\Facades\Schema;

//CreateCategoriesTableクラスを定義し、Migrationクラスを継承する
class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *マイグレーションを実行する（テーブルを作成または変更する）。
     * @return void
     */
    public function up()
    {
        //'categories'という名前のテーブルを作成する
        Schema::create('categories', function (Blueprint $table) {
            //主キーとしてauto-incrementの'id'カラムを作成する
            $table->id();
            //'name'というVARCHAR（１０）型のカラムを作成し、値が一意（重複不可）であることを指定する
            $table->string('name',10 )->unique();
            //'create_at'と'updated_at'のタイムスタンプカラムを自動的に作成する
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //'categories'テーブルが存在すれば削除する
        Schema::dropIfExists('categories');
    }
}
