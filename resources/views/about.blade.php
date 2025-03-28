@extends('layouts.app')

@section('title', 'ABOUT')

@section('content')
    <div class="container about">
        <h1 class="page-title">ABOUT</h1>
        {{-- <img src="{{ asset($titleAbout->file_path . $titleAbout->file_name) }}" alt="タイトル" class="title-image"> --}}

        <div class="about-container">
            <div class="about-logo">
                <img src="{{ asset($logoImg->file_path . $logoImg->file_name) }}" alt="{{ $logoImg->alt }}">
            </div>
            <div class="about-content">
                <p>{!! nl2br(e($aboutText->content)) !!}</p>
            </div>
        </div>


        <div class="about-company">
            <h2 class="about-company-title">
                <a href="">
                    <span class="title-arrow"><span class="dli-caret-right"></span></span>
                    COMPANY INFO
                </a>
            </h2>
            <table class="about-company-info">
                @foreach ($company as $item)
                    <div class="info-row">
                        <div class="info-label">{{ $item["view_name"] }}</div>
                        <div class="info-value">{{ $item["value"] }}</div>
                    </div>
                @endforeach
            </table>
        </div>

        <div class="about-thumbnail">
            @foreach ($aboutImg as $item)
                <img src="{{ $item->about_img }}">
            @endforeach
        </div>
    </div>
@endsection