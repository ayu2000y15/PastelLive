@extends('layouts.app')

@section('title', 'NEWS')

@section('content')

    <div class="container news-page">
        {{-- <h1 class="page-title">NEWS</h1> --}}
        <img src="{{ asset($titleNews->file_path . $titleNews->file_name) }}" alt="タイトル" class="title-image">

        <div class="news-list">
            @foreach ($newsItems as $news)
                <a href="{{ route('news.show', $news->id) }}" class="news-list-item">
                    <div class="news-list-date">
                        <img class="heart-img-small" src="{{ asset($heartImg->file_path . $heartImg->file_name) }}"
                            alt="{{ $heartImg->alt }}">
                        <span>{{ date('Y.n.j', strtotime($news->publish_date)) }}</span>
                    </div>
                    <div class="news-list-title">{{ $news->title }}</div>
                </a>
            @endforeach
        </div>

    </div>
@endsection