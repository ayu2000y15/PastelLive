@extends('layouts.admin')

@section('title', $master->title . ' - データ編集')

@section('content')
<div class="d-flex justify-content-between align-items-center page-title mb-4">
    <h2>{{ $master->title }} - データ編集</h2>
    <a href="{{ route('admin.content-data.master', ['masterId' => $master->master_id]) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> 戻る
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>データ編集フォーム</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.content-data.update', ['dataId' => $contentData->data_id]) }}" method="POST" class="data-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if(isset($master->schema) && is_array($master->schema))
                @php
                    // スキーマを表示順でソート
                    $sortedSchema = collect($master->schema)->sortBy('sort_order')->values()->all();
                @endphp

                <div class="card mb-4 border-primary">
                    <div class="card-header bg-primary bg-opacity-10">
                        <h6 class="mb-0 fw-bold">基本設定</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sort_order" class="form-label fw-bold">表示順</label>
                                <input type="number" id="sort_order" name="sort_order" class="form-control" min="0" value="{{ $contentData->sort_order ?? 0 }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">公開状態</label>
                                <div class="d-flex gap-4 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                                type="radio"
                                                name="public_flg"
                                                id="public_yes"
                                                value="1"
                                                {{ $contentData->public_flg == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="public_yes">
                                            <span class="badge bg-success">公開</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input"
                                                type="radio"
                                                name="public_flg"
                                                id="public_no"
                                                value="0"
                                                {{ $contentData->public_flg == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="public_no">
                                            <span class="badge bg-secondary">非公開</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 fw-bold">コンテンツ詳細</h6>
                    </div>
                    <div class="card-body">
                        @foreach($sortedSchema as $field)
                            <div class="card mb-4 border-0 border-bottom pb-4">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="{{ $field['col_name'] }}" class="form-label fs-5 mb-3">
                                            {{ $field['view_name'] }}
                                            @if($field['required_flg'] == '1')
                                                <span class="required badge bg-danger ms-2" style="color: white;">必須</span>
                                            @endif
                                        </label>

                                        @if($field['type'] == 'textarea')
                                            <textarea id="{{ $field['col_name'] }}"
                                                        name="{{ $field['col_name'] }}"
                                                        class="form-control"
                                                        rows="5"
                                                        @if($field['required_flg'] == '1') required @endif>{{ old($field['col_name'], $contentData->content[$field['col_name']] ?? '') }}</textarea>
                                        @elseif($field['type'] == 'select')
                                        <select id="{{ $field['col_name'] }}"
                                                name="{{ $field['col_name'] }}"
                                                class="form-select"
                                                @if($field['required_flg'] == '1') required @endif>
                                            <option value="">選択してください</option>
                                            @if(isset($field['options']) && is_array($field['options']))
                                                @foreach($field['options'] as $option)
                                                    <option value="{{ $option['value'] }}"
                                                        {{ old($field['col_name'], $contentData->content[$field['col_name']] ?? '') == $option['value'] ? 'selected' : '' }}>
                                                        {{ $option['label'] }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @elseif($field['type'] == 'radio')
                                            <div class="form-check form-check-inline">
                                                <!-- ラジオボタンはここに追加 -->
                                            </div>
                                        @elseif($field['type'] == 'file')
                                            <div class="file-upload-container" data-field="{{ $field['col_name'] }}">
                                                <input type="file"
                                                        id="{{ $field['col_name'] }}"
                                                        name="{{ $field['col_name'] }}"
                                                        class="file-upload-input"
                                                        accept="image/*"
                                                        @if($field['required_flg'] == '1' && empty($contentData->content[$field['col_name']])) required @endif>
                                                <div class="file-upload-area" id="upload-area-{{ $field['col_name'] }}">
                                                    <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                                                    <p>ここにファイルをドラッグするか、クリックして選択してください</p>
                                                    <p class="text-muted small">対応形式: JPG, PNG, GIF</p>
                                                </div>
                                                <div class="file-preview-container mt-3" id="preview-{{ $field['col_name'] }}">
                                                    @if(!empty($contentData->content[$field['col_name']]))
                                                        <div class="file-preview-item">
                                                            <img src="{{ asset( $contentData->content[$field['col_name']]) }}" class="file-preview-image">
                                                            <div class="file-preview-info">現在のファイル</div>
                                                            <input type="hidden" name="{{ $field['col_name'] }}_current" value="{{ $contentData->content[$field['col_name']] }}">
                                                            <button type="button" class="btn btn-sm btn-danger file-delete-btn" data-field="{{ $field['col_name'] }}" data-data-id="{{ $contentData->data_id }}">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($field['type'] == 'files')
                                            <div class="file-upload-container" data-field="{{ $field['col_name'] }}">
                                                <input type="file"
                                                        id="{{ $field['col_name'] }}"
                                                        name="{{ $field['col_name'] }}[]"
                                                        class="file-upload-input"
                                                        accept="image/*"
                                                        multiple
                                                        @if($field['required_flg'] == '1' && empty($contentData->content[$field['col_name']])) required @endif>
                                                <div class="file-upload-area" id="upload-area-{{ $field['col_name'] }}">
                                                    <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                                                    <p>ここに複数のファイルをドラッグするか、クリックして選択してください</p>
                                                    <p class="text-muted small">対応形式: JPG, PNG, GIF</p>
                                                </div>
                                                <div class="file-preview-container mt-3" id="preview-{{ $field['col_name'] }}">
                                                    @if(!empty($contentData->content[$field['col_name']]) && is_array($contentData->content[$field['col_name']]))
                                                        @foreach($contentData->content[$field['col_name']] as $index => $filePath)
                                                            <div class="file-preview-item">
                                                                <img src="{{ asset( $filePath) }}" class="file-preview-image">
                                                                <div class="file-preview-info">現在のファイル {{ $index + 1 }}</div>
                                                                <input type="hidden" name="{{ $field['col_name'] }}_current[]" value="{{ $filePath }}">
                                                                <button type="button" class="btn btn-sm btn-danger file-delete-btn" data-field="{{ $field['col_name'] }}" data-index="{{ $index }}" data-data-id="{{ $contentData->data_id }}">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($field['type'] == 'array')
                                            <div class="array-field-container" data-field="{{ $field['col_name'] }}">
                                                <div class="array-items" id="array-items-{{ $field['col_name'] }}">
                                                    @php
                                                        $arrayData = old($field['col_name'], $contentData->content[$field['col_name']] ?? []);
                                                        if (!is_array($arrayData)) {
                                                            $arrayData = [];
                                                        }
                                                    @endphp
                                                    @foreach($arrayData as $index => $item)
                                                        <div class="array-item card p-3 mb-2">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <h6 class="mb-0">項目 #{{ $index + 1 }}</h6>
                                                                <button type="button" class="btn btn-sm btn-danger remove-array-item">
                                                                    <i class="fas fa-times"></i> 削除
                                                                </button>
                                                            </div>
                                                            @if(isset($field['array_items']) && is_array($field['array_items']))
                                                                @foreach($field['array_items'] as $arrayItem)
                                                                    <div class="mb-2">
                                                                        <label class="form-label">{{ $arrayItem['name'] }}</label>
                                                                        @if($arrayItem['type'] == 'text')
                                                                            <input type="text"
                                                                                name="{{ $field['col_name'] }}[{{ $index }}][{{ $arrayItem['name'] }}]"
                                                                                class="form-control"
                                                                                value="{{ $item[$arrayItem['name']] ?? '' }}">
                                                                        @elseif($arrayItem['type'] == 'number')
                                                                            <input type="number"
                                                                                name="{{ $field['col_name'] }}[{{ $index }}][{{ $arrayItem['name'] }}]"
                                                                                class="form-control"
                                                                                value="{{ $item[$arrayItem['name']] ?? '' }}">
                                                                        @elseif($arrayItem['type'] == 'boolean')
                                                                            <div class="form-check">
                                                                                <input type="checkbox"
                                                                                    class="form-check-input"
                                                                                    id="{{ $field['col_name'] }}_{{ $index }}_{{ $arrayItem['name'] }}"
                                                                                    name="{{ $field['col_name'] }}[{{ $index }}][{{ $arrayItem['name'] }}]"
                                                                                    value="1"
                                                                                    {{ isset($item[$arrayItem['name']]) && $item[$arrayItem['name']] ? 'checked' : '' }}>
                                                                                <label class="form-check-label" for="{{ $field['col_name'] }}_{{ $index }}_{{ $arrayItem['name'] }}">
                                                                                    有効
                                                                                </label>
                                                                            </div>
                                                                        @elseif($arrayItem['type'] == 'date')
                                                                            <input type="date"
                                                                                name="{{ $field['col_name'] }}[{{ $index }}][{{ $arrayItem['name'] }}]"
                                                                                class="form-control"
                                                                                value="{{ $item[$arrayItem['name']] ?? '' }}">
                                                                        @elseif($arrayItem['type'] == 'url')
                                                                            <label>
                                                                                <p>※YouTubeの共有ボタンより、埋め込むを選択して作成されたURLのsrcの部分を入力してください。<br>
                                                                                例、<br>＜iframe width="560" height="315" src="<span style="color: red;">https://www.youtube.com/embed/RAVDdfksdi</span>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen＞＜/iframe＞</p>
                                                                            </label>
                                                                            <input type="url"
                                                                                name="{{ $field['col_name'] }}[{{ $index }}][{{ $arrayItem['name'] }}]"
                                                                                class="form-control"
                                                                                value="{{ $item[$arrayItem['name']] ?? '' }}"
                                                                                placeholder="https://example.com">
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <button type="button" class="btn btn-sm btn-primary mt-2 add-array-item" data-field="{{ $field['col_name'] }}">
                                                    <i class="fas fa-plus"></i> 項目を追加
                                                </button>
                                            </div>
                                        @elseif($field['type'] == 'date' || $field['type'] == 'month')
                                            <input type="{{ $field['type'] }}"
                                                    id="{{ $field['col_name'] }}"
                                                    name="{{ $field['col_name'] }}"
                                                    class="form-control"
                                                    value="{{ old($field['col_name'], $contentData->content[$field['col_name']] ?? date('Y-m-d')) }}"
                                                    @if($field['required_flg'] == '1') required @endif>
                                        @else
                                            @if($field['col_name'] == 'profile_youtube_link')
                                            <label>
                                                <p>※YouTubeの共有ボタンより、埋め込むを選択して作成されたURLのsrcの部分を入力してください。<br>
                                                例、<br>＜iframe width="560" height="315" src="<span style="color: red;">https://www.youtube.com/embed/RAVDdfksdi</span>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen＞＜/iframe＞</p>
                                            </label>
                                            @endif
                                            <input type="{{ $field['type'] }}"
                                                    id="{{ $field['col_name'] }}"
                                                    name="{{ $field['col_name'] }}"
                                                    class="form-control"
                                                    value="{{ old($field['col_name'], $contentData->content[$field['col_name']] ?? '') }}"
                                                    @if($field['required_flg'] == '1') required @endif>
                                        @endif

                                        @if($errors->has($field['col_name']))
                                            <div class="text-danger mt-1">
                                                {{ $errors->first($field['col_name']) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.content-data.master', ['masterId' => $master->master_id]) }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> キャンセル
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> 更新
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 通知モーダル -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="notificationModalLabel">通知</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
        </div>
        <div class="modal-body" id="notificationModalBody">
        <!-- 通知内容がここに入ります -->
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
        </div>
    </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* フォーム要素のスタイル改善 */
    .form-label {
        font-weight: 500;
    }

    .required {
        font-size: 0.75rem;
        vertical-align: middle;
    }

    /* ファイルアップロード関連のスタイル改善 */
    .file-upload-area {
        border: 2px dashed #ddd;
        padding: 30px;
        text-align: center;
        border-radius: 8px;
        margin-bottom: 10px;
        background-color: #f9f9f9;
        cursor: pointer;
        transition: all 0.3s;
    }

    .file-upload-area:hover,
    .file-upload-area.drag-over {
        border-color: #0d6efd;
        background-color: #f0f7ff;
    }

    .file-preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }

    .file-preview-item {
        position: relative;
        width: 150px;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 8px;
        background-color: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .file-preview-image {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 4px;
        margin-bottom: 5px;
    }

    /* 配列フィールドのスタイル改善 */
    .array-item {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // CSRFトークンを取得
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // 通知モーダル
    let notificationModal;
    const notificationModalElement = document.getElementById('notificationModal');
    if (notificationModalElement) {
        notificationModal = new bootstrap.Modal(notificationModalElement);
    }
    const notificationModalBody = document.getElementById('notificationModalBody');

    // 通知を表示する関数
    function showNotification(message, isError = false) {
        if (notificationModalBody && notificationModal) {
            notificationModalBody.innerHTML = message;
            notificationModalBody.className = isError ? 'text-danger' : 'text-success';
            notificationModal.show();
        } else {
            // モーダルが利用できない場合はアラートを使用
            alert(message);
        }
    }

    // 既存ファイル削除ボタンの処理
    const deleteButtons = document.querySelectorAll('.file-delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const fieldName = this.dataset.field;
            const dataId = this.dataset.dataId;
            const index = this.dataset.index;
            const previewItem = this.closest('.file-preview-item');

            // 削除確認
            if (!confirm('ファイルを削除してもよろしいですか？この操作は元に戻せません。')) {
                return;
            }

            // APIエンドポイントを構築
            let url = `/admin/content-data/delete-file/${dataId}/${fieldName}`;
            if (index !== undefined) {
                url += `/${index}`;
            }

            // ファイル削除APIを呼び出す
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // 成功時: プレビュー要素を削除
                    previewItem.remove();
                    showNotification(data.message);

                    // 必須フィールドの場合、required属性を再設定
                    const input = document.getElementById(fieldName);
                    if (input && input.hasAttribute('data-required')) {
                        input.setAttribute('required', '');
                    }
                } else {
                    // エラー時: エラーメッセージを表示
                    showNotification(data.message, true);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('ファイル削除中にエラーが発生しました。', true);
            });
        });
    });

    // ファイルアップロード処理
    const fileInputs = document.querySelectorAll('.file-upload-input');

    fileInputs.forEach(input => {
        const fieldName = input.id;
        const uploadArea = document.getElementById('upload-area-' + fieldName);
        const previewContainer = document.getElementById('preview-' + fieldName);
        const isMultiple = input.hasAttribute('multiple');

        // required属性がある場合、データ属性に保存
        if (input.hasAttribute('required')) {
            input.setAttribute('data-required', 'true');
        }

        // アップロードエリアをクリックしたらファイル選択ダイアログを開く
        uploadArea.addEventListener('click', function() {
            input.click();
        });

        // ドラッグ&ドロップイベント
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            uploadArea.classList.add('drag-over');
        }

        function unhighlight() {
            uploadArea.classList.remove('drag-over');
        }

        // ファイルドロップ処理
        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (isMultiple) {
                handleFiles(files);
            } else if (files.length > 0) {
                handleFiles([files[0]]);
            }
        }

        // ファイル選択処理
        input.addEventListener('change', function() {
            if (isMultiple) {
                handleFiles(this.files);
            } else if (this.files.length > 0) {
                handleFiles([this.files[0]]);
            }
        });

        function handleFiles(files) {
            if (!isMultiple) {
                // 単一ファイルの場合は新しいプレビューをクリア
                const newPreviews = previewContainer.querySelectorAll('.file-preview-item.new-file');
                newPreviews.forEach(preview => preview.remove());
            }

            [...files].forEach(file => {
                if (file.type.match('image.*')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const preview = document.createElement('div');
                        preview.className = 'file-preview-item new-file';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'file-preview-image';

                        const info = document.createElement('div');
                        info.className = 'file-preview-info';
                        info.textContent = file.name;

                        const size = document.createElement('div');
                        size.className = 'file-preview-size';
                        size.textContent = formatFileSize(file.size);

                        const removeBtn = document.createElement('button');
                        removeBtn.className = 'btn btn-sm btn-danger file-preview-remove';
                        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                        removeBtn.type = 'button';
                        removeBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            preview.remove();

                            // 単一ファイルの場合は入力をリセット
                            if (!isMultiple) {
                                input.value = '';
                            }
                        });

                        preview.appendChild(img);
                        preview.appendChild(info);
                        preview.appendChild(size);
                        preview.appendChild(removeBtn);

                        previewContainer.appendChild(preview);

                        // ファイルが選択されたらrequired属性を削除
                        if (input.hasAttribute('data-required')) {
                            input.removeAttribute('required');
                        }
                    };

                    reader.readAsDataURL(file);
                }
            });
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    });
});

// 配列フィールドの処理
const arrayFieldContainers = document.querySelectorAll('.array-field-container');

arrayFieldContainers.forEach(container => {
    const fieldName = container.dataset.field;
    const itemsContainer = document.getElementById(`array-items-${fieldName}`);
    const addButton = container.querySelector('.add-array-item');

    // PHPの配列をJavaScriptで使いやすい形式に変換
    const arrayItems = @json(array_values(array_filter($sortedSchema, function($field) { return $field['type'] === 'array'; })));

    // 項目追加ボタンのイベントリスナー
    addButton.addEventListener('click', function() {
        // 配列の構造をコンソールに出力して確認
        console.log('Array items:', arrayItems);

        // 対応するフィールドを検索
        const field = arrayItems.find(item => item.col_name === fieldName);
        if (!field || !field.array_items) {
            console.error('Field not found or array_items missing:', fieldName);
            return;
        }

        const itemIndex = itemsContainer.querySelectorAll('.array-item').length;
        let itemHtml = `
            <div class="array-item card p-3 mb-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">項目 #${itemIndex + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-array-item">
                        <i class="fas fa-times"></i> 削除
                    </button>
                </div>
        `;

        field.array_items.forEach(arrayItem => {
            itemHtml += `
                <div class="mb-2">
                    <label class="form-label">${arrayItem.name}</label>
            `;

            if (arrayItem.type === 'text') {
                itemHtml += `
                    <input type="text"
                           name="${fieldName}[${itemIndex}][${arrayItem.name}]"
                           class="form-control"
                           value="">
                `;
            } else if (arrayItem.type === 'number') {
                itemHtml += `
                    <input type="number"
                           name="${fieldName}[${itemIndex}][${arrayItem.name}]"
                           class="form-control"
                           value="">
                `;
            } else if (arrayItem.type === 'boolean') {
                itemHtml += `
                    <div class="form-check">
                        <input type="checkbox"
                               class="form-check-input"
                               id="${fieldName}_${itemIndex}_${arrayItem.name}"
                               name="${fieldName}[${itemIndex}][${arrayItem.name}]"
                               value="1">
                        <label class="form-check-label" for="${fieldName}_${itemIndex}_${arrayItem.name}">
                            有効
                        </label>
                    </div>
                `;
            } else if (arrayItem.type === 'date') {
                itemHtml += `
                    <input type="date"
                           name="${fieldName}[${itemIndex}][${arrayItem.name}]"
                           class="form-control"
                           value="">
                `;
            } else if (arrayItem.type === 'url') {
                itemHtml += `
                    <input type="url"
                           name="${fieldName}[${itemIndex}][${arrayItem.name}]"
                           class="form-control"
                           value=""
                           placeholder="https://example.com">
                `;
            }

            itemHtml += `
                </div>
            `;
        });

        itemHtml += `</div>`;

        itemsContainer.insertAdjacentHTML('beforeend', itemHtml);

        // 削除ボタンのイベントリスナーを設定
        const removeButtons = itemsContainer.querySelectorAll('.remove-array-item');
        const lastRemoveButton = removeButtons[removeButtons.length - 1];

        lastRemoveButton.addEventListener('click', function() {
            this.closest('.array-item').remove();
            // インデックスを更新
            updateArrayItemIndexes(fieldName);
        });
    });

    // 既存の削除ボタンにイベントリスナーを設定
    const removeButtons = container.querySelectorAll('.remove-array-item');
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.array-item').remove();
            // インデックスを更新
            updateArrayItemIndexes(fieldName);
        });
    });

    // 配列項目のインデックスを更新する関数
    function updateArrayItemIndexes(fieldName) {
        const items = document.querySelectorAll(`#array-items-${fieldName} .array-item`);

        items.forEach((item, index) => {
            // タイトルを更新
            const title = item.querySelector('h6');
            if (title) {
                title.textContent = `項目 #${index + 1}`;
            }

            // 入力フィールドの名前属性を更新
            const inputs = item.querySelectorAll('input');
            inputs.forEach(input => {
                const name = input.name;
                // 正規表現で現在のインデックスを抽出
                const pattern = new RegExp(`${fieldName}\\[(\\d+)\\]`);
                const match = name.match(pattern);

                if (match) {
                    const oldIndex = match[1];
                    const newName = name.replace(`${fieldName}[${oldIndex}]`, `${fieldName}[${index}]`);
                    input.name = newName;

                    // チェックボックスのIDも更新
                    if (input.type === 'checkbox') {
                        const id = input.id;
                        const newId = id.replace(`${fieldName}_${oldIndex}`, `${fieldName}_${index}`);
                        input.id = newId;

                        // ラベルのforも更新
                        const label = item.querySelector(`label[for="${id}"]`);
                        if (label) {
                            label.setAttribute('for', newId);
                        }
                    }
                }
            });
        });
    }
});
</script>
@endpush
