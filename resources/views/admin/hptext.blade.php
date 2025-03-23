@extends('layouts.admin')

@section('title', 'HPテキスト管理')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center page-title mb-4">
            <h2>HPテキスト管理</h2>
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
                <form action="{{ route('admin.hptext.store') }}" method="POST" class="data-form">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="text_id" class="form-label">ID<span class="text-danger ms-1">*</span></label>
                            <input type="text" id="text_id" name="text_id" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="memo" class="form-label">タイトル、メモ<span class="text-danger ms-1">*</span></label>
                            <input type="text" id="memo" name="memo" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">内容<span class="text-danger ms-1">*</span></label>
                        <textarea rows="5" type="text" id="content" name="content" class="form-control" required></textarea>
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
                                <th>ID</th>
                                <th>タイトル、メモ</th>
                                <th>内容</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hpText as $def)
                                <tr>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $def->t_id }}"
                                                data-content="{{ $def->content }}" data-memo="{{ $def->memo }}">
                                                <i class="fas fa-edit"></i> 編集
                                            </button>
                                            <form action="{{ route('admin.hptext.delete') }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="text_id" value="{{ $def->t_id }}">
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('本当に削除しますか？');">
                                                    <i class="fas fa-trash"></i> 削除
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>{{ $def->t_id }}</td>
                                    <td>{{ $def->memo }}</td>
                                    <td>{{ $def->content }}</td>
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
                    const textId = this.getAttribute('data-id');
                    const content = this.getAttribute('data-content');
                    const memo = this.getAttribute('data-memo');

                    document.getElementById('text_id').value = textId;
                    document.getElementById('content').value = content;
                    document.getElementById('memo').value = memo;

                    submitBtn.textContent = '更新';
                    form.action = "{{ route('admin.hptext.update') }}";

                    showForm();
                });
            });

            function resetForm() {
                form.reset();
                document.getElementById('text_id').value = '';
                document.getElementById('content').value = '';
                document.getElementById('memo').value = '';

                submitBtn.innerHTML = '<i class="fas fa-save me-1"></i> 登録';
                form.action = "{{ route('admin.hptext.store') }}";
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