@extends('layouts.admin')

@section('title', 'ダッシュボード')

@section('content')
    <div class="page-title">
        <h2>ダッシュボード</h2>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">データ数</h6>
                            <h2 class="mb-0">{{ count($allData ?? []) }}</h2>
                        </div>
                        <i class="fas fa-database fa-3x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.content-data') }}" class="text-white text-decoration-none">詳細を見る</a>
                    <i class="fas fa-arrow-right text-white"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">公開中</h6>
                            <h2 class="mb-0">
                                {{-- {{ count(array_filter($allData ?? [], function ($item) {
                                return $item->public_flg == '1'; })) }} --}}
                            </h2>
                        </div>
                        <i class="fas fa-globe fa-3x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.content-data') }}" class="text-white text-decoration-none">詳細を見る</a>
                    <i class="fas fa-arrow-right text-white"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">マスター数</h6>
                            <h2 class="mb-0">{{ count($masters ?? []) }}</h2>
                        </div>
                        <i class="fas fa-table fa-3x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.content-schema') }}" class="text-white text-decoration-none">詳細を見る</a>
                    <i class="fas fa-arrow-right text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 style="font-weight: bold;" class="mb-0">管理者からのお知らせ</h5>
            <span class="text-muted small">最終アクセス日時：{{ $lastAccess }}</span>
        </div>
        <div class="card-body">
            @if(isset($adminNews) && count($adminNews) > 0)
                <div class="news-container">
                    @for($i = 0; $i <= $rowIdCount; $i++)
                            @php
                                $title = null;
                                $content = null;
                                $created_at = null;
                                $isNew = false;

                                foreach ($adminNews as $info) {
                                    if ($info["row_id"] == $i) {
                                        if ($info['col_name'] == 'TITLE') {
                                            $title = $info['data'];
                                            $created_at = $info['created_at'];
                                            $isNew = strtotime($lastAccess) < strtotime($info['created_at']);
                                        } elseif ($info['col_name'] == 'CONTENT') {
                                            $content = $info['data'];
                                        }
                                    }
                                }
                            @endphp

                            @if($title && $content)
                                <div class="card mb-3 position-relative {{ $isNew ? 'border-danger' : 'border-light' }}">
                                    @if($isNew)
                                        <span class="badge bg-danger position-absolute top-0 end-0 translate-middle">NEW</span>
                                    @endif
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                            <h5 class="mb-0 me-2">{{ $title }}</h5>
                                            <span class="news-date text-muted">{{ date('Y-m-d', strtotime($created_at)) }}</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @php
                                            if (class_exists('App\Services\PlanetextToUrl')) {
                                                $convert = new \App\Services\PlanetextToUrl;
                                                $content = $convert->convertLink($content);
                                            }
                                        @endphp
                                        <div class="card-text">{!! nl2br($content) !!}</div>
                                    </div>
                                </div>
                            @endif
                    @endfor
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>お知らせはありません。
                </div>
            @endif
        </div>
    </div>

    <style>
        @media (max-width: 767.98px) {
            .news-date {
                display: block;
                margin-top: 2rem;
            }
        }
    </style>
@endsection