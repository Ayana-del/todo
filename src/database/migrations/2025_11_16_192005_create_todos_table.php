<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //データベースにtodosという新しいテーブルを作成する
        Schema::create('todos', function (Blueprint $table)
        {
            //データベースに'todos'という新しいテーブルを作成する
            $table->id();
            //'category_id'という外部キーカラムを追加する
            //このカラムは、'categories'テーブルのIDカラムを参照
            //参照先のレコードが削除された場合（category_idレコードが削除された場合）
            //このテーブルの関連レコードも一緒に削除されるように設定する。
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            //'content'という名前の文字列型のカラムを追加し、最大文字数を２０に設定
            $table->string('content',20);
            //'create_at'と'update_at'のタイムスタンプカラムを自動的に追加する
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todos');
    }
}
