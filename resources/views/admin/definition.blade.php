@extends('layouts.admin')

@section('title', '汎用テーブル管理')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center page-title mb-4">
            <h2>汎用テーブル管理</h2>
            <button type="button" class="btn btn-primary" id="newEntryBtn">
                <i class="fas fa-plus me-1"></i> 新規登録
            </button>
        </div>

        <!-- 登録・更新フォーム -->
        <div class="card mb-4" id="dataForm" style="display: none;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">登録・更新</h5>
                <button type="button" class="btn-close" id="cancelBtn" aria-label="閉じる"></button>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.definition.store') }}" method="POST" class="data-form">
                    @csrf
                    <input type="hidden" name="definition_id" id="definition_id">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="definition" class="form-label">定義<span class="text-danger ms-1">*</span></label>
                            <input type="text" id="definition" name="definition" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="item" class="form-label">内容<span class="text-danger ms-1">*</span></label>
                            <input type="text" id="item" name="item" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="explanation" class="form-label">説明<span class="text-danger ms-1">*</span></label>
                        <input type="text" id="explanation" name="explanation" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" onclick="return confirm('登録しますか？');" class="btn btn-primary"
                            id="submitBtn">登録</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- データ一覧 -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">登録済みデータ一覧</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 150px;">操作</th>
                                <th>定義ID</th>
                                <th>定義</th>
                                <th>内容</th>
                                <th>説明</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($definition as $def)
                                <tr>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $def->definition_id }}"
                                                data-definition="{{ $def->definition }}" data-item="{{ $def->item }}"
                                                data-explanation="{{ $def->explanation }}">
                                                <i class="fas fa-edit"></i> 編集
                                            </button>
                                            <form action="{{ route('admin.definition.delete') }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="definition_id" value="{{ $def->definition_id }}">
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('本当に削除しますか？');">
                                                    <i class="fas fa-trash"></i> 削除
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>{{ $def->definition_id }}</td>
                                    <td>{{ $def->definition }}</td>
                                    <td>{{ $def->item }}</td>
                                    <td>{{ $def->explanation }}</td>
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
            const editButtons = document.querySelectorAll('.edit-btn');
            const form = document.querySelector('.data-form');
            const dataFormContainer = document.getElementById('dataForm');
            const newEntryBtn = document.getElementById('newEntryBtn');
            const submitBtn = document.getElementById('submitBtn');
            const cancelBtn = document.getElementById('cancelBtn');

            //キャンセルボタンのイベントリスナー
            cancelBtn.addEventListener('click', function () {
                hideForm();
            });

            // 新規登録ボタンのイベントリスナー
            newEntryBtn.addEventListener('click', function () {
                resetForm();
                showForm();
            });

            // 編集ボタンのイベントリスナー
            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const definitionId = this.getAttribute('data-id');
                    const definition = this.getAttribute('data-definition');
                    const item = this.getAttribute('data-item');
                    const explanation = this.getAttribute('data-explanation');

                    document.getElementById('definition_id').value = definitionId;
                    document.getElementById('definition').value = definition;
                    document.getElementById('item').value = item;
                    document.getElementById('explanation').value = explanation;

                    submitBtn.textContent = '更新';
                    form.action = "{{ route('admin.definition.update') }}";

                    showForm();
                });
            });

            function resetForm() {
                form.reset();
                document.getElementById('definition_id').value = '';
                document.getElementById('definition').value = '';
                document.getElementById('item').value = '';
                document.getElementById('explanation').value = '';

                submitBtn.innerHTML = '<i class="fas fa-save me-1"></i> 登録';
                form.action = "{{ route('admin.definition.store') }}";
            }

            function showForm() {
                dataFormContainer.style.display = 'block';
                dataFormContainer.scrollIntoView({ behavior: 'smooth' });
            }

            function hideForm() {
                dataFormContainer.style.display = 'none';
            }
        });
    </script>
@endsection