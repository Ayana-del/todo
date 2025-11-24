<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;         //Laravelの基本的なシーダークラスをインポート
use Illuminate\Support\Facades\DB;      //DBファサード（データベース操作用クラス）をインポート
use Carbon\Carbon;                      //日付・時刻操作ライブラリをインポート

class CategorySeeder extends Seeder     //CategorySeederクラスを定義。LaravelのSeederクラスを継承
{
    /**
     * Run the database seeds.
     *データベースへのデータ投入を実行するメインのメソッド。
     *このメソッドが実行されることで、データがテーブルに挿入される。
     *このメソッドは処理を実行するだけで、値を返さない。
     * @return void
     */
    public function run(): void
    {   //投入するカテゴリデータの配列を定義（categoriesテーブルに挿入するレコード）
        $categories = [
            //挿入するレコードは、連想配列の形式で定義
            //挿入するデータ形式：['name' =>カテゴリ名, 'create_at' =>現在時刻,'update_at' => 現在時刻]
            ['name' => '仕事', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'プライベート', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => '買い物', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => '学習', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'その他', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];
        //DBファサードを使って'categories'テーブルに上記のデータを一括挿入。
        //この処理でデータベースにデータが登録され、カテゴリ一覧に上記５つのデータが表示される。
        DB::table('categories')->insert($categories);
    }
}
