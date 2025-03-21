@extends('layouts.admin')

@section('title', 'HP画像管理')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center page-title mb-4">
            <h2>HP画像登録</h2>
            <button type="button" class="btn btn-primary" id="newEntryBtn">
                <i class="fas fa-plus me-1"></i> 新規登録
            </button>
        </div>

        <!-- 新規画像アップロードフォーム -->
        <div class="card mb-4" id="dataForm" style="display: none;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">新規画像アップロード</h5>
                <button type="button" class="btn-close" id="cancelBtn" aria-label="閉じる"></button>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.photo.store') }}" method="POST" enctype="multipart/form-data"
                    class="data-form">
                    @csrf

                    <div class="mb-4">
                        <label for="IMAGE" class="form-label">画像ファイル<span class="text-danger ms-1">*</span></label>
                        <div class="file-upload-container" data-field="IMAGE">
                            <input type="file" id="IMAGE" name="IMAGE[]" class="file-upload-input" accept="image/*" multiple
                                required>
                            <div class="file-upload-area" id="upload-area-IMAGE">
                                <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                                <p>ここにファイルをドラッグするか、クリックして選択してください</p>
                                <p class="text-muted small">対応形式: JPG, PNG, GIF</p>
                                <p class="text-muted small">※複数のファイルを選択できます</p>
                            </div>
                            <div class="file-preview-container mt-3" id="preview-IMAGE"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="alt" class="form-label">タイトル</label>
                            <input type="text" id="alt" name="alt" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="view_flg" class="form-label">表示先<span class="text-danger ms-1">*</span></label>
                            <select name="view_flg" id="view_flg" class="form-select" required>
                                @foreach ($viewFlg as $select)
                                    <option value="{{ $select['view_flg'] }}">
                                        {{ $select['comment'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="priority" class="form-label">優先度</label>
                        <input type="number" id="priority" name="priority" class="form-control">
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-upload me-1"></i> アップロード
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 画像一覧 -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">アップロード済み画像一覧</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 150px;">操作</th>
                                {{-- <th>ファイル名</th> --}}
                                <th>プレビュー</th>
                                <th>表示先</th>
                                <th>タイトル</th>
                                <th>優先度</th>
                                <th>アップロード日時</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($photos as $photo)
                                <tr>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.photo.edit', ['image_id' => $photo->image_id]) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> 編集
                                            </a>
                                            <form action="{{ route('admin.photo.delete') }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="image_id" value="{{$photo->image_id}}">
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('本当に削除しますか？')">
                                                    <i class="fas fa-trash"></i> 削除
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    {{-- <td>{{ $photo->file_name }}</td> --}}
                                    <td>
                                        <img src="{{ asset($photo->file_path . $photo->file_name) }}" alt="{{ $photo->alt }}"
                                            class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                    </td>
                                    <td>{{ $photo->v_comment }}</td>
                                    <td>{{ $photo->alt }}</td>
                                    <td>{{ $photo->priority }}</td>
                                    <td>{{ $photo->created_at}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 新規登録ボタンのイベントリスナー
            const newEntryBtn = document.getElementById('newEntryBtn');
            const dataForm = document.getElementById('dataForm');
            const cancelBtn = document.getElementById('cancelBtn');
            const fileInput = document.getElementById('IMAGE');
            const uploadArea = document.getElementById('upload-area-IMAGE');
            const previewContainer = document.getElementById('preview-IMAGE');

            // 新規登録ボタン
            newEntryBtn.addEventListener('click', function () {
                dataForm.style.display = 'block';
                dataForm.scrollIntoView({ behavior: 'smooth' });
            });

            // キャンセルボタン
            cancelBtn.addEventListener('click', function () {
                dataForm.style.display = 'none';
            });

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
                    handleFiles(files);
                }
            });

            // ファイル選択処理
            fileInput.addEventListener('change', function () {
                if (this.files.length > 0) {
                    handleFiles(this.files);
                }
            });

            function handleFiles(files) {
                previewContainer.innerHTML = '';

                [...files].forEach(file => {
                    if (file.type.match('image.*')) {
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            const preview = document.createElement('div');
                            preview.className = 'file-preview-item';

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

    <style>
        /* ファイルアップロード関連のスタイル */
        .file-upload-container {
            margin-bottom: 15px;
        }

        .file-upload-area {
            border: 2px dashed #ddd;
            padding: 30px;
            text-align: center;
            border-radius: 4px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-upload-area:hover,
        .file-upload-area.drag-over {
            border-color: #007bff;
            background-color: #f0f8ff;
        }

        .file-upload-input {
            display: none;
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
            border-radius: 4px;
            padding: 5px;
            background-color: white;
        }

        .file-preview-image {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 3px;
            margin-bottom: 5px;
        }

        .file-preview-info {
            font-size: 12px;
            color: #666;
            word-break: break-all;
        }

        .file-preview-size {
            font-size: 11px;
            color: #888;
            margin-top: 3px;
        }

        .file-preview-remove {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            font-size: 12px;
            padding: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .file-preview-remove:hover {
            background-color: #c82333;
        }
    </style>
@endsection