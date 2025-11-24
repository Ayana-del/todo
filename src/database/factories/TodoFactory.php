<?php

namespace Database\Factories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
// use Faker\Factory as FakerFactory; // 不要なインポートは削除

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */

    protected $model = Todo::class;

    protected $faker = 'ja_JP';

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $this->faker を使用します。

        // カテゴリIDの最大値を取得 (CategorySeederで5つ登録していると仮定)
        $maxCategoryId = 5;
        $maxUserId = 2;

        return [
            // ToDoの内容: $this->faker を使って最大10文字程度の日本語文章を生成
            'content' => $this->faker->realText(maxNbChars: 10),

            // カテゴリID: $this->faker を使ってランダムな整数をセット
            'category_id' => $this->faker->numberBetween(1, $maxCategoryId),

            // 期日: $this->faker を使ってランダムな日付をセット
            'due_date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),

            // 完了フラグ: $this->faker を使って30%の確率で完了
            'completed' => $this->faker->boolean(30),

            'user_id' => $this->faker->numberBetween(1, $maxUserId),

        ];
    }
}
