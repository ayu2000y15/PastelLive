    @extends('layouts.admin')

    @section('title', $master->title . ' - データ編集')

    @section('content')
    <div class="d-flex justify-content-between align-items-center page-title">
        <h2>{{ $master->title }} - データ編集</h2>
        <a href="{{ route('admin.content-data.master', ['masterId' => $master->master_id]) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 戻る
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">データ編集フォーム</h5>
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

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sort_order" class="form-label">表示順</label>
                            <input type="number" id="sort_order" name="sort_order" class="form-control" min="0" value="{{ $contentData->sort_order ?? 0 }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">公開状態</label>
                            <div class="form-check">
                                <input class="form-check-input"
                                        type="radio"
                                        name="public_flg"
                                        id="public_yes"
                                        value="1"
                                        {{ $contentData->public_flg == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="public_yes">公開</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input"
                                        type="radio"
                                        name="public_flg"
                                        id="public_no"
                                        value="0"
                                        {{ $contentData->public_flg == '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="public_no">非公開</label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    @foreach($sortedSchema as $field)
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label for="{{ $field['col_name'] }}" class="form-label">
                                    {{ $field['view_name'] }}
                                    @if($field['required_flg'] == '1')
                                        <span class="required">*</span>
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
                                @elseif($field['type'] == 'date' || $field['type'] == 'month')
                                    <input type="{{ $field['type'] }}"
                                            id="{{ $field['col_name'] }}"
                                            name="{{ $field['col_name'] }}"
                                            class="form-control"
                                            value="{{ old($field['col_name'], $contentData->content[$field['col_name']] ?? date('Y-m-d')) }}"
                                            @if($field['required_flg'] == '1') required @endif>
                                @else
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
                    @endforeach
                @endif

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.content-data.master', ['masterId' => $master->master_id]) }}" class="btn btn-secondary">キャンセル</a>
                    <button type="submit" class="btn btn-primary">更新</button>
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
    </script>
    @endpush

