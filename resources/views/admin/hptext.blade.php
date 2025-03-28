@extends('layouts.admin')

@section('title', 'HPテキスト管理')

@section('content')
    <div class="d-flex justify-content-between align-items-center page-title mb-4">
        <h2>HPテキスト管理</h2>
        @if (session('access_id') == '0')
            <button type="button" class="btn btn-primary" id="newEntryBtn">
                <i class="fas fa-plus me-1"></i> 新規登録
            </button>
        @else
            <button type="button" class="btn btn-primary" id="newEntryBtn" style="display: none">
                <i class="fas fa-plus me-1"></i> 新規登録
            </button>
        @endif
    </div>

    <!-- 登録・更新フォーム -->
    @component('components.admin-card', ['title' => '登録・更新'])
        <div id="dataForm" style="display: none;">
            <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn-close" id="cancelBtn" aria-label="閉じる"></button>
            </div>
            <form action="{{ route('admin.hptext.store') }}" method="POST" class="data-form">
                @csrf

                <div class="row mb-3">
                    @if (session('access_id') == '0')
                        <div class="col-md-6">
                            @include('components.form-field', [
                                'name' => 'text_id',
                                'label' => 'ID',
                                'type' => 'text',
                                'required' => true
                            ])
                        </div>
                    @else
                        <div class="col-md-6">
                            @include('components.form-field', [
                                'name' => 'text_id',
                                'label' => 'ID',
                                'type' => 'text',
                                'required' => true,
                                'display' => true
                            ])
                        </div>
                    @endif
                    <div class="col-md-6">
                        @include('components.form-field', [
                            'name' => 'memo',
                            'label' => 'タイトル、メモ',
                            'type' => 'text',
                            'required' => false
                        ])
                    </div>
                </div>

                @include('components.form-field', [
                    'name' => 'content',
                    'label' => '内容',
                    'type' => 'textarea',
                    'required' => true,
                    'rows' => 5
                ])

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" onclick="return confirm('登録しますか？');" class="btn btn-primary"
                        id="submitBtn">登録</button>
                </div>
            </form>
        </div>
    @endcomponent

    <!-- データ一覧 -->
    @component('components.admin-card', ['title' => '登録済みデータ一覧', 'icon' => 'list'])
        @php
            $columns = [
                ['label' => '操作', 'class' => 'col-actions'],
                ['label' => 'ID'],
                ['label' => 'タイトル、メモ'],
                ['label' => '内容']
            ];
        @endphp

        @component('components.data-table', ['columns' => $columns, 'headerClass' => 'table-light'])
            @foreach ($hpText as $def)
                <tr>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $def->t_id }}"
                                data-content="{{ $def->content }}" data-memo="{{ $def->memo }}">
                                <i class="fas fa-edit"></i> 編集
                            </button>
                            @if (session('access_id') == '0')
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
                                @endif
                        </div>
                    </td>
                    <td>{{ $def->t_id }}</td>
                    <td>{{ $def->memo }}</td>
                    <td>{{ $def->content }}</td>
                </tr>
            @endforeach
        @endcomponent
    @endcomponent
@endsection

@push('scripts')
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
@endpush

