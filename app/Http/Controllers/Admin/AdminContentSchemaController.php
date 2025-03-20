<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ContentMasterService;
use Illuminate\Http\Request;

class AdminContentSchemaController extends Controller
{
    protected $contentMaster;

    public function __construct(ContentMasterService $contentMaster)
    {
        $this->contentMaster = $contentMaster;
    }

    public function index()
    {
        $masters = $this->contentMaster->getMasterAll();
        return view('admin.content-schema', compact('masters'));
    }

    public function addField(Request $request)
    {
        $validatedData = $request->validate([
            'master_id' => 'required|string',
            'col_name' => 'required|string',
            'view_name' => 'required|string',
            'type' => 'required|string',
            'sort_order' => 'required|integer|min:0',
            'required_flg' => 'required|string',
            'public_flg' => 'required|string',
            'options' => 'nullable|string', // selectの選択肢用
        ]);

        $field = [
            'col_name' => $validatedData['col_name'],
            'view_name' => $validatedData['view_name'],
            'type' => $validatedData['type'],
            'sort_order' => $validatedData['sort_order'],
            'required_flg' => $validatedData['required_flg'],
            'public_flg' => $validatedData['public_flg'],
        ];

        // selectタイプの場合は選択肢を追加
        if ($validatedData['type'] === 'select' && !empty($validatedData['options'])) {
            $options = $this->parseOptions($validatedData['options']);
            if (!empty($options)) {
                $field['options'] = $options;
            }
        }

        $result = $this->contentMaster->addSchemaField($validatedData['master_id'], $field);

        return redirect()->route('admin.content-schema')
            ->with($result["status"], $result["mess"])
            ->with('active_master_id', $validatedData['master_id']);
    }

    public function updateField(Request $request)
    {
        $validatedData = $request->validate([
            'master_id' => 'required|string',
            'original_col_name' => 'required|string',
            'col_name' => 'required|string',
            'view_name' => 'required|string',
            'type' => 'required|string',
            'sort_order' => 'required|integer|min:0',
            'required_flg' => 'required|string',
            'public_flg' => 'required|string',
            'options' => 'nullable|string', // selectの選択肢用
        ]);

        $field = [
            'col_name' => $validatedData['col_name'],
            'view_name' => $validatedData['view_name'],
            'type' => $validatedData['type'],
            'sort_order' => $validatedData['sort_order'],
            'required_flg' => $validatedData['required_flg'],
            'public_flg' => $validatedData['public_flg'],
        ];

        // selectタイプの場合は選択肢を追加
        if ($validatedData['type'] === 'select') {
            if (!empty($validatedData['options'])) {
                $options = $this->parseOptions($validatedData['options']);
                if (!empty($options)) {
                    $field['options'] = $options;
                }
            } else {
                // 空の選択肢の場合は空の配列を設定
                $field['options'] = [];
            }
        }

        $result = $this->contentMaster->updateSchemaField(
            $validatedData['master_id'],
            $validatedData['original_col_name'],
            $field
        );

        return redirect()->route('admin.content-schema')
            ->with($result["status"], $result["mess"])
            ->with('active_master_id', $validatedData['master_id']);
    }

    public function deleteField(Request $request)
    {
        $validatedData = $request->validate([
            'master_id' => 'required|string',
            'col_name' => 'required|string',
        ]);

        $result = $this->contentMaster->deleteSchemaField(
            $validatedData['master_id'],
            $validatedData['col_name']
        );

        return redirect()->route('admin.content-schema')
            ->with($result["status"], $result["mess"])
            ->with('active_master_id', $validatedData['master_id']);
    }

    public function updateOrder(Request $request, $masterId)
    {
        $validatedData = $request->validate([
            'schema_order' => 'required|json',
        ]);

        $schemaOrder = json_decode($validatedData['schema_order'], true);

        $result = $this->contentMaster->updateSchemaOrder($masterId, $schemaOrder);

        return redirect()->route('admin.content-schema')
            ->with($result["status"], $result["mess"])
            ->with('active_master_id', $masterId);
    }

    /**
     * 選択肢テキストを解析して配列に変換する
     *
     * @param string $optionsText 選択肢テキスト（各行が「値:表示名」の形式）
     * @return array 選択肢の配列
     */
    private function parseOptions($optionsText)
    {
        $options = [];
        // 改行コードを統一して分割
        $lines = preg_split('/\r\n|\r|\n/', $optionsText);

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // 値:表示名 の形式をチェック
            if (strpos($line, ':') !== false) {
                list($value, $label) = explode(':', $line, 2);
                $value = trim($value);
                $label = trim($label);

                if (!empty($value)) {
                    $options[] = [
                        'value' => $value,
                        'label' => $label ?: $value
                    ];
                }
            } else {
                // 区切り文字がない場合は値と表示名を同じにする
                $value = $line;
                $options[] = [
                    'value' => $value,
                    'label' => $value
                ];
            }
        }

        return $options;
    }
}
