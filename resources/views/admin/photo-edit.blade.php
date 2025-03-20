@extends('layouts.admin')

@section('title', 'HP画像編集')

@section('content')
    <div class="d-flex justify-content-between align-items-center page-title">
        <h2>HP画像編集</h2>
        <a href="{{ route('admin.photo') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 戻る
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">画像編集フォーム</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.photo.update') }}" method="POST" class="data-form" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="image_id" value="{{ $photo->image_id }}">

                <!-- 画像アップロード機能を削除し、現在の画像を表示するだけにします -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <label class="form-label">現在の画像</label>
                        <div class="text-center mb-3">
                            <img src="{{ asset($photo->file_path . $photo->file_name) }}" alt="{{ $photo->alt }}"
                                class="img-thumbnail" style="max-width: 300px;">
                            <div class="mt-2 text-muted">
                                <small>{{ $photo->file_name }}</small>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>画像ファイルは変更できません。変更が必要な場合は、この画像を削除して新しく登録してください。
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="alt" class="form-label">タイトル</label>
                        <input type="text" id="alt" name="alt" class="form-control" value="{{ $photo->alt }}">
                    </div>

                    <div class="col-md-6">
                        <label for="view_flg" class="form-label">表示先<span class="text-danger ms-1">*</span></label>
                        <select name="view_flg" id="view_flg" class="form-select" required>
                            @foreach ($viewFlg as $select)
                                <option value="{{ $select['view_flg'] }}" {{ $photo->view_flg == $select['view_flg'] ? 'selected' : '' }}>
                                    {{ $select['comment'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="priority" class="form-label">優先度</label>
                    <input type="number" id="priority" name="priority" class="form-control" value="{{ $photo->priority }}">
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.photo') }}" class="btn btn-secondary">キャンセル</a>
                    <button type="submit" class="btn btn-primary">更新</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 通知モーダル -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel"
        aria-hidden="true">
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
        document.addEventListener('DOMContentLoaded', function () {
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
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const imageId = this.dataset.imageId;
                    const previewItem = this.closest('.file-preview-item');

                    // 削除確認
                    if (!confirm('画像を削除してもよろしいですか？この操作は元に戻せません。')) {
                        return;
                    }

                    // APIエンドポイントを構築
                    let url = `/admin/photo/delete-image/${imageId}`;

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

                                // 削除後はリストページにリダイレクト
                                window.location.href = "{{ route('admin.photo') }}";
                            } else {
                                // エラー時: エラーメッセージを表示
                                showNotification(data.message, true);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('画像削除中にエラーが発生しました。', true);
                        });
                });
            });

            // ファイルアップロード処理
            const fileInput = document.getElementById('image');
            const uploadArea = document.getElementById('upload-area-image');
            const previewContainer = document.getElementById('preview-image');

            // アップロードエリアをクリックしたらファイル選択ダイアログを開く
            uploadArea.addEventListener('click', function () {
                fileInput.click();
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
            uploadArea.addEventListener('drop', function (e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    handleFiles([files[0]]);
                }
            });

            // ファイル選択処理
            fileInput.addEventListener('change', function () {
                if (this.files.length > 0) {
                    handleFiles([this.files[0]]);
                }
            });

            function handleFiles(files) {
                // 新しいプレビューをクリア
                const newPreviews = previewContainer.querySelectorAll('.file-preview-item.new-file');
                newPreviews.forEach(preview => preview.remove());

                [...files].forEach(file => {
                    if (file.type.match('image.*')) {
                        const reader = new FileReader();

                        reader.onload = function (e) {
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
                            removeBtn.addEventListener('click', function (e) {
                                e.preventDefault();
                                preview.remove();
                                fileInput.value = '';
                            });

                            preview.appendChild(img);
                            preview.appendChild(info);
                            preview.appendChild(size);
                            preview.appendChild(removeBtn);

                            previewContainer.appendChild(preview);
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
    </script>
@endpush