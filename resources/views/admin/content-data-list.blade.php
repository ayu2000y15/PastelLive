@extends('layouts.admin')

@section('title', $master->title . ' - データ一覧')

@section('content')
    <div class="d-flex justify-content-between align-items-center page-title">
        <h2>{{ $master->title }} - データ一覧</h2>
        <div>
            <a href="{{ route('admin.content-data') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> 戻る
            </a>
            <a href="{{ route('admin.content-data.create', ['masterId' => $master->master_id]) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> 新規登録
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if(count($data) > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 150px;">操作</th>
                                <th style="width: 80px;">表示順</th>
                                @if(isset($master->schema) && is_array($master->schema))
                                                    @php
                                                        // スキーマを表示順でソート
                                                        $sortedSchema = collect($master->schema)->sortBy('sort_order')->values()->all();
                                                      @endphp
                                                    @foreach($sortedSchema as $field)
                                                        @if($field['public_flg'] == '1')
                                                            <th>{{ $field['view_name'] }}</th>
                                                        @endif
                                                    @endforeach
                                @endif
                                <th>公開状態</th>
                                <th>登録日時</th>
                                <th>更新日時</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-items">
                            @foreach($data as $item)
                                <tr data-id="{{ $item->data_id }}">
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.content-data.edit', ['dataId' => $item->data_id]) }}"
                                                class="btn btn-sm btn-warning btn-action">
                                                <i class="fas fa-edit"></i> 編集
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger btn-action" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $item->data_id }}">
                                                <i class="fas fa-trash"></i> 削除
                                            </button>
                                        </div>

                                        <!-- 削除確認モーダル -->
                                        <div class="modal fade" id="deleteModal{{ $item->data_id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">削除確認</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="閉じる"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>このデータを削除してもよろしいですか？</p>
                                                        <p>この操作は取り消せません。</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">キャンセル</button>
                                                        <form
                                                            action="{{ route('admin.content-data.delete', ['dataId' => $item->data_id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">削除する</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="sort-handle me-2"><i class="fas fa-grip-vertical text-muted"></i></span>
                                            <span class="sort-order">{{ $item->sort_order ?? 0 }}</span>
                                            <input type="hidden" name="sort_order" value="{{ $item->sort_order ?? 0 }}">
                                        </div>
                                    </td>
                                    @if(isset($master->schema) && is_array($master->schema))
                                        @foreach($sortedSchema as $field)
                                            @if($field['public_flg'] == '1')
                                                <td>
                                                    @if(isset($item->content[$field['col_name']]))
                                                        @if($field['type'] == 'textarea')
                                                            <div style="max-height: 100px; overflow-y: auto;">
                                                                {!! nl2br(e($item->content[$field['col_name']])) !!}
                                                            </div>
                                                        @elseif($field['type'] == 'file')
                                                            @if(!empty($item->content[$field['col_name']]))
                                                                <a href="{{ asset($item->content[$field['col_name']]) }}" target="_blank"
                                                                    class="image-preview">
                                                                    <img src="{{ asset($item->content[$field['col_name']]) }}" alt="画像"
                                                                        class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                                                </a>
                                                            @else
                                                                <span class="text-muted">ファイルなし</span>
                                                            @endif
                                                        @elseif($field['type'] == 'files')
                                                            @if(!empty($item->content[$field['col_name']]) && is_array($item->content[$field['col_name']]))
                                                                <div class="d-flex flex-wrap gap-1">
                                                                    @foreach($item->content[$field['col_name']] as $filePath)
                                                                        <a href="{{ asset($filePath) }}" target="_blank" class="image-preview">
                                                                            <img src="{{ asset($filePath) }}" alt="画像" class="img-thumbnail"
                                                                                style="width: 50px; height: 50px; object-fit: cover;">
                                                                        </a>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <span class="text-muted">ファイルなし</span>
                                                            @endif
                                                        @else
                                                            {{ $item->content[$field['col_name']] }}
                                                        @endif
                                                    @endif
                                                </td>
                                            @endif
                                        @endforeach
                                    @endif
                                    <td>
                                        <span class="badge {{ $item->public_flg == '1' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $item->public_flg == '1' ? '公開' : '非公開' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- 表示順保存ボタン -->
                <div class="mt-3">
                    <form id="sort-form"
                        action="{{ route('admin.content-data.update-order', ['masterId' => $master->master_id]) }}"
                        method="POST">
                        @csrf
                        <input type="hidden" id="sort-data" name="sort_data" value="">
                        <button type="submit" class="btn btn-success" id="save-sort-btn">
                            <i class="fas fa-save"></i> 表示順を保存
                        </button>
                    </form>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>データがありません。新規登録ボタンからデータを追加してください。
                </div>
            @endif
        </div>
    </div>

    <!-- 画像プレビューモーダル -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">画像プレビュー</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="/placeholder.svg" id="previewImage" class="img-fluid" alt="プレビュー">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 画像プレビュー機能
            const imageLinks = document.querySelectorAll('.image-preview');
            const previewModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
            const previewImage = document.getElementById('previewImage');

            imageLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    previewImage.src = this.href;
                    previewModal.show();
                });
            });

            // ドラッグ&ドロップでの並べ替え
            const sortableList = document.getElementById('sortable-items');
            if (sortableList) {
                new Sortable(sortableList, {
                    handle: '.sort-handle',
                    animation: 150,
                    onEnd: function () {
                        updateSortOrder();
                    }
                });

                // 表示順の更新
                function updateSortOrder() {
                    const items = sortableList.querySelectorAll('tr');
                    items.forEach((item, index) => {
                        const orderSpan = item.querySelector('.sort-order');
                        const orderInput = item.querySelector('input[name="sort_order"]');
                        if (orderSpan && orderInput) {
                            orderSpan.textContent = index + 1;
                            orderInput.value = index + 1;
                        }
                    });
                }

                // 表示順保存
                const sortForm = document.getElementById('sort-form');
                const sortDataInput = document.getElementById('sort-data');

                sortForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const items = sortableList.querySelectorAll('tr');
                    const sortData = [];

                    items.forEach((item) => {
                        const id = item.dataset.id;
                        const order = item.querySelector('input[name="sort_order"]').value;
                        sortData.push({ id: id, order: order });
                    });

                    sortDataInput.value = JSON.stringify(sortData);
                    this.submit();
                });
            }
        });
    </script>
@endpush