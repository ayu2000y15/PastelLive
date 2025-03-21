<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Services\ContentMasterService;
use App\Services\ContentDataService;

class ContactController extends Controller
{
    protected $contentMaster;
    protected $contentData;

    public function __construct(ContentMasterService $contentMaster, ContentDataService $contentData)
    {
        $this->contentMaster = $contentMaster;
        $this->contentData = $contentData;
    }
    public function index()
    {
        $logoImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 1)->first();
        $logoMinImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 2)->first();
        $shopBtn = Image::where('VIEW_FLG', 'HP_007')->first();
        $backImg = Image::where('VIEW_FLG', 'HP_005')->first();

        $titleContact = Image::where('VIEW_FLG', 'HP_015')->first();

        $contactBtn = Image::where('VIEW_FLG', 'HP_601')->first();

        // お問い合わせフォーム（T003）のスキーマを取得
        $formFields = $this->contentData->getSchemaByMasterId('T003', true, true);

        return view('contact', compact(
            'logoImg',
            'logoMinImg',
            'shopBtn',
            'backImg',
            'titleContact',
            'contactBtn',
            'formFields'
        ));
    }
    /**
     * フォーム送信処理の例
     */
    public function confirmForm(Request $request)
    {
        $logoImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 1)->first();
        $logoMinImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 2)->first();
        $shopBtn = Image::where('VIEW_FLG', 'HP_007')->first();
        $backImg = Image::where('VIEW_FLG', 'HP_005')->first();

        $titleContact = Image::where('VIEW_FLG', 'HP_015')->first();

        // スキーマを取得してバリデーションルールを動的に生成
        $schema = $this->contentData->getSchemaByMasterId('T003');
        $rules = [];
        $messages = [];

        foreach ($schema as $field) {
            $colName = $field['col_name'];
            $viewName = $field['view_name'];

            // 必須項目のバリデーション
            if (isset($field['required_flg']) && $field['required_flg'] === '1') {
                $rules[$colName] = 'required';
                $messages[$colName . '.required'] = $viewName . 'は必須項目です。';
            }

            // フィールドタイプに応じたバリデーション
            switch ($field['type']) {
                case 'email':
                    $rules[$colName] .= '|email';
                    $messages[$colName . '.email'] = $viewName . 'は正しいメールアドレス形式で入力してください。';
                    break;
                case 'tel':
                    $rules[$colName] .= '|regex:/^[0-9\-]+$/';
                    $messages[$colName . '.regex'] = $viewName . 'は数字とハイフンのみで入力してください。';
                    break;
            }
        }
        // バリデーション実行
        $validatedData = $request->validate($rules, $messages);

        $formData = $request->all();
        unset($formData['_token']); // CSRFトークンを除外

        // 入力データをセッションに保存
        session(['contact_input' => $formData]);

        // 確認画面に表示するためのフィールド情報を取得
        $formFields = $this->contentData->getSchemaByMasterId('T003', true, true);

        return view('contact-confirm', compact(
            'logoImg',
            'logoMinImg',
            'shopBtn',
            'titleContact',
            'backImg',
            'formFields',
            'formData'
        ));
    }

    /**
     * フォーム送信処理（ステップ3: 送信処理）
     */
    public function submitForm(Request $request)
    {
        // セッションから入力データを取得
        $input = session('contact_input');

        // セッションにデータがない場合は入力画面にリダイレクト
        if (!$input) {
            return redirect()->route('contact')->with('error', '入力データが見つかりません。もう一度入力してください。');
        }

        // 「戻る」ボタンが押された場合
        if ($request->has('back')) {
            return redirect()->route('contact');
        }

        // データ保存処理
        $result = $this->contentData->store('T003', $input, '1');

        // セッションから入力データを削除
        session()->forget('contact_input');

        if ($result['status'] === 'success') {
            // 完了画面にリダイレクト
            return redirect()->route('contact.complete');
        } else {
            return redirect()->route('contact')->with('error', $result['mess']);
        }
    }

    /**
     * 送信完了画面を表示する
     */
    public function completeForm()
    {
        $logoImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 1)->first();
        $logoMinImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 2)->first();
        $shopBtn = Image::where('VIEW_FLG', 'HP_007')->first();
        $backImg = Image::where('VIEW_FLG', 'HP_005')->first();

        $titleContact = Image::where('VIEW_FLG', 'HP_015')->first();

        return view('contact-complete', compact(
            'logoImg',
            'logoMinImg',
            'shopBtn',
            'titleContact',
            'backImg',
        ));
    }
}
