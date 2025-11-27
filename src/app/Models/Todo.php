<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Todo extends Model
{
    use HasFactory;

    //TodoCOntroller@index で Auth::user()->todosが使えるようになる
    //このTodoは一人のUserに属する（belongsTo)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * マスアサインメント可能な属性.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'content',
        'due_date',
        'user_id',  //ユーザーIDをfillableに設定
    ];

    /**
     * Categoryモデルとのリレーション定義 (多対一).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * ローカルスコープ：カテゴリIDによる絞り込み
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $categoryId
     * @return void
     */
    public function scopeCategorySearch(Builder $query, $categoryId)
    {
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }
    }

    /**
     * ローカルスコープ：キーワード（Todo内容）による絞り込み
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $keyword
     * @return void
     */
    public function scopeKeywordSearch(Builder $query, $keyword)
    {
        if (!empty($keyword)) {
            // contentフィールドに対して部分一致検索を実行
            $query->where('content', 'like', '%' . $keyword . '%');
        }
    }

    /**
     * ローカルスコープ：期日による絞り込み
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $dueDate YYYY-MM-DD形式の日付
     * @return void
     */
    public function scopeDueDateSearch(Builder $query, $dueDate)
    {
        if (!empty($dueDate)) {
            // whereDateを使って、日付部分のみを比較して完全一致を検索
            $query->whereDate('due_date', $dueDate);
        }
    }
}

