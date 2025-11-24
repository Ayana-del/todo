<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 最初のテストユーザーを作成
        User::create([
            'name' => 'テスト太郎',
            'email' => 'test1@example.com',
            'password' => Hash::make('aaaa1111'),
        ]);

        // 2番目のテストユーザーを作成
        User::create([
            'name' => 'テスト次郎',
            'email' => 'test2@example.com',
            'password' => Hash::make('aaaa2222'),
        ]);

    }
}
