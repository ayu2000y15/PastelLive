@extends('layouts.app')

@section('title', 'NEWS')

@section('content')
    @php
        $dateImg = asset($dateImg->file_path . $dateImg->file_name);
    @endphp
    <style>
        .date {
            background-image: url({{$dateImg}});
            background-repeat: no-repeat;
            background-position: top;
            background-size: contain;

        }
    </style>
    <div class="container news-page">
        {{-- <h1 class="page-title">NEWS</h1> --}}
        <img src="{{ asset($titleNews->file_path . $titleNews->file_name) }}" alt="タイトル" class="title-image">

        <div class="news-grid">
            @foreach($newsItems as $item)
                    <div class="news-area">
                        <div class="news-card">
                            <div class="news-card-image">
                                @if($item->image_info <> null)
                                    <img src="{{ asset($item->image_info) }}" alt="お知らせ画像">
                                @endif
                            </div>
                            <div class="news-info">
                                <span class="date">
                                    {{ date('Y.n.j', strtotime($item->publish_date)) }}
                                </span>
                                <h3>{{ $item->title }}</h3>
                                @php
                                    $convert = new App\Services\PlanetextToUrl;
                                    $item->content = $convert->convertLink($item->content);
                                @endphp
                                <p>{!! nl2br($item->content) !!}</p>
                            </div>
                        </div>
                        @if (!$loop->last)
                            <div class="line-area">
                                <hr class="line">
                            </div>
                        @endif

                    </div>
            @endforeach
        </div>
    </div>
@endsection