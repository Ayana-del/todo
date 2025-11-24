<?php
//このファイルがApp\Http\Requests名前空間（ディレクトリ）に属する
namespace App\Http\Requests;
//FormRequestクラスをインポートし、これを継承して使用する
use Illuminate\Foundation\Http\FormRequest;

//Todoクラスを定義し、FormRequestを継承する。
class TodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *リクエストを実行するユーザーが、この操作を行う権限を持っているか判断する
     * @return bool //権限がある場合はtrue,ない場合はfalseを返す。
     */
    public function authorize()
    {
        return true;    //【許可】常にtrueを返すことで、権限チェックをスキップし、リクエストを常に許可する
    }

    /**
     * Get the validation rules that apply to the request.
     *リクエストに適用されるバリデーションルール（検証規則）を取得する。
     * @return array    //ルールを定義した配列を返す。
     */
    public function rules()
    {
        //バリデーションルールを配列で定義
        return [
            //'content'（Todoの内容）に適用するルール。
            'content' => ['required', 'string', 'max:20'],
            //'category_id（カテゴリID）に適用するルール。
            'category_id' => ['required'],
            //'due_date'（期日）に適用するルール。
            'due_date' =>['nullable','date'],
        ];
    }

    //バリデーションルールが満たされなかった場合のエラーメッセージを定義
    public function messages()
    {
        return [
            //'content'フィールドに関するエラーメッセージ
            'content.required' => 'Todoを入力してください',         //requiredルールの失敗時
            'content.string' => 'Todoを文字列で入力してください',       //stringルールの失敗時
            'content.max' => 'Todoを20文字以下で入力してください',      //maxルールの失敗時
            //'category_id'フィールドに関するエラーメッセージ
            'category_id.required' => 'カテゴリを入力してください',     //requiredルールの失敗時
            //'due_date'フィールドに関するエラーメッセージ
            'due_date.date'=>'期日は有効な日付形式で入力してください',      //dateルールの失敗時
        ];
    }
}
