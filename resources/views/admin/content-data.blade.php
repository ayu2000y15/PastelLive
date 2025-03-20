@extends('layouts.admin')

@section('title', 'データ管理')

@section('content')
    <div class="page-title">
        <h2>データ管理</h2>
    </div>

    <div class="row">
        @foreach ($masters as $master)
            @php
                $dataCount = $master->contentData->where('delete_flg', '0')->count();
                $publicCount = $master->contentData->where('delete_flg', '0')->where('public_flg', '1')->count();
            @endphp
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $master->title }}</h5>
                        <span class="badge bg-primary">{{ $dataCount }}件</span>
                    </div>
                    <div class="card-body">
                        {{-- <p class="card-text">{{ $master->comment ?? 'データの管理ができます。' }}</p> --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">
                                <i class="fas fa-table me-1"></i>マスターID: {{ $master->master_id }}
                            </span>
                            <span class="badge bg-success">公開: {{ $publicCount }}件</span>
                        </div>
                        {{-- <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $dataCount > 0 ? ($publicCount / $dataCount) * 100 : 0 }}%;"
                                aria-valuenow="{{ $publicCount }}" aria-valuemin="0" aria-valuemax="{{ $dataCount }}">
                            </div>
                        </div> --}}
                    </div>
                    <div class="card-footer bg-transparent d-flex justify-content-between">
                        <a href="{{ route('admin.content-data.master', ['masterId' => $master->master_id]) }}"
                            class="btn btn-outline-primary">
                            <i class="fas fa-list me-1"></i> データ一覧
                        </a>
                        <a href="{{ route('admin.content-data.create', ['masterId' => $master->master_id]) }}"
                            class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> 新規登録
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection