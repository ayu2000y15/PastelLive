@extends('layouts.admin')

@section('title', 'ダッシュボード')

@section('content')
    <div class="page-title mb-4">
        <h2>ダッシュボード</h2>
    </div>

    <div class="admin-news-section">
        <div class="admin-news-header">
            <div class="d-flex align-items-center">
                <i class="fas fa-bullhorn news-icon"></i>
                <h3 class="admin-news-title">管理者からのお知らせ</h3>
            </div>
            <div class="last-access">
                最終アクセス：<span class="last-access-time">{{ $lastAccess }}</span>
            </div>
        </div>

        <div class="admin-news-container">
            @if(isset($adminNews) && count($adminNews) > 0)
                <div class="news-list">
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
                                <div class="news-list-item {{ $isNew ? 'news-item-new' : '' }}">
                                    <div class="news-item-row">
                                        <div class="news-date">{{ date('Y.m.d', strtotime($created_at)) }}</div>
                                        <div class="news-title-container">
                                            <h4 class="news-title">{{ $title }}</h4>
                                            @if($isNew)
                                                <span class="news-badge">NEW</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="news-content">
                                        @php
                                            if (class_exists('App\Services\PlanetextToUrl')) {
                                                $convert = new \App\Services\PlanetextToUrl;
                                                $content = $convert->convertLink($content);
                                            }
                                        @endphp
                                        {!! nl2br($content) !!}
                                    </div>
                                </div>
                            @endif
                    @endfor
                </div>
            @else
                <div class="no-news">
                    <i class="fas fa-info-circle"></i>
                    <p>お知らせはありません</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* 管理者からのお知らせ - 全体スタイル */
        .admin-news-section {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .admin-news-header {
            background: linear-gradient(135deg, #4a6cf7, #2651e8);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .admin-news-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            margin-left: 0.75rem;
        }

        .news-icon {
            font-size: 1.75rem;
        }

        .last-access {
            font-size: 0.85rem;
            background-color: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 50px;
        }

        .last-access-time {
            font-weight: 600;
        }

        .admin-news-container {
            padding: 1.5rem;
        }

        /* ニュースリスト */
        .news-list {
            display: flex;
            flex-direction: column;
        }

        .news-list-item {
            padding: 1.25rem 0;
            border-bottom: 1px solid #e9ecef;
            position: relative;
        }

        .news-list-item:last-child {
            border-bottom: none;
        }

        .news-item-row {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .news-date {
            width: 100px;
            font-size: 0.9rem;
            color: #495057;
            font-weight: 500;
            flex-shrink: 0;
        }

        .news-category {
            background-color: #e9ecef;
            color: #495057;
            font-size: 0.8rem;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            margin-right: 1rem;
            flex-shrink: 0;
            width: 100px;
            text-align: center;
        }

        .news-title-container {
            flex: 1;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .news-title {
            font-size: 1rem;
            font-weight: 500;
            margin: 0;
            color: #212529;
        }

        .news-badge {
            background-color: #dc3545;
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
        }

        .news-content {
            width: 100%;
            margin-top: 1rem;
            padding-left: 100px;
            /* 日付の幅と同じ */
            line-height: 1.6;
            color: #495057;
            display: none;
            /* デフォルトでは非表示 */
        }

        .news-list-item.expanded .news-content {
            display: block;
        }

        .news-item-new {
            background-color: #fff9f9;
        }

        /* お知らせがない場合 */
        .no-news {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            color: #6c757d;
            text-align: center;
        }

        .no-news i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .no-news p {
            font-size: 1.1rem;
            margin: 0;
        }

        /* レスポンシブ対応 */
        @media (max-width: 768px) {
            .admin-news-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .last-access {
                align-self: flex-start;
            }
        }

        @media (max-width: 576px) {
            .news-item-row {
                flex-wrap: wrap;
            }

            .news-date {
                width: auto;
                margin-right: 1rem;
            }

            .news-category {
                width: auto;
                margin-bottom: 0.5rem;
            }

            .news-title-container {
                width: 100%;
                margin-top: 0.5rem;
            }

            .news-content {
                padding-left: 0;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // お知らせの展開・折りたたみ機能
            const newsItems = document.querySelectorAll('.news-list-item');

            newsItems.forEach(item => {
                const itemRow = item.querySelector('.news-item-row');

                itemRow.addEventListener('click', function () {
                    item.classList.toggle('expanded');
                });
            });
        });
    </script>
@endsection