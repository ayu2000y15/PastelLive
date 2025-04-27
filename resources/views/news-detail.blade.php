@extends('layouts.app')

@section('title', 'ニュース詳細')

@section('content')
    <style>
        .heart-img-small {
            height: 20px;
            object-fit: contain;
            margin-right: 10px;
        }
    </style>
    <div class="container news-detail-page">
        {{-- <div class="breadcrumb">
            <a href="{{ route('home') }}" class="breadcrumb-item">HOME</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('news') }}" class="breadcrumb-item">NEWS</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-item">{{ $newsItem->title }}</span>
        </div> --}}

        <img src="{{ asset($titleNews->file_path . $titleNews->file_name) }}" alt="タイトル" class="title-image">

        <div class="news-detail-container">
            <div class="news-detail-header">
                <div class="news-detail-title-row">
                    <h1 class="news-detail-title">{{ $newsItem->title }}</h1>
                    <div class="news-detail-date-container">
                        <img class="heart-img" src="{{ asset($heartImg->file_path . $heartImg->file_name) }}"
                            alt="{{ $heartImg->alt }}">
                        <p class="news-detail-date">{{ date('Y.n.j', strtotime($newsItem->publish_date)) }}</p>
                    </div>
                </div>
            </div>

            <div class="news-detail-content">
                @if(isset($newsItem->file_path) && isset($newsItem->file_name))
                    <div class="news-detail-image">
                        <img src="{{ asset($newsItem->file_path . $newsItem->file_name) }}" alt="{{ $newsItem->title }}">
                    </div>
                @endif

                <div class="news-detail-text">
                    {!! nl2br(e($newsItem->content)) !!}
                </div>
            </div>

            @if(count($latestNews) > 0)
                <div class="latest-news">
                    <h2 class="latest-news-title">最新のお知らせ</h2>
                    <div class="news-list">
                        @foreach($latestNews as $news)
                            @if($news->id != $newsItem->id)
                                <a href="{{ route('news.show', $news->id) }}" class="news-list-item">
                                    <div class="news-list-date">
                                        <img class="heart-img-small" src="{{ asset($heartImg->file_path . $heartImg->file_name) }}"
                                            alt="{{ $heartImg->alt }}">
                                        <span>{{ date('Y.n.j', strtotime($news->publish_date)) }}</span>
                                    </div>
                                    <div class="news-list-title">{{ $news->title }}</div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="news-detail-back">
                <a href="{{ route('news') }}" class="btn-back">ニュース一覧に戻る</a>
            </div>
        </div>
    </div>
@endsection