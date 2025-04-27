@extends('layouts.app')

@section('title', 'ABOUT')

@section('content')
    <div class="container about">
        {{-- <h1 class="page-title">ABOUT</h1> --}}
        {{-- <img src="{{ asset($titleAbout->file_path . $titleAbout->file_name) }}" alt="タイトル" class="title-image"> --}}

        <div class="about-container">
            <img class="about-img1" src="{{ asset($aboutImg1->file_path . $aboutImg1->file_name) }}"
                alt="{{ $aboutImg1->alt }}">
            <img class="about-img2" src="{{ asset($aboutImg2->file_path . $aboutImg2->file_name) }}"
                alt="{{ $aboutImg2->alt }}">
        </div>

        <div class="about-company">
            <img class="about-logo" src="{{ asset($logoMinImg->file_path . $logoMinImg->file_name) }}"
                alt="{{ $logoMinImg->comment }}">

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

        <form action="{{ route('faq') }}">
            <button type="submit" class="btn submit-button">
                <img class="about-btn" src="{{ asset($aboutBtn->file_path . $aboutBtn->file_name) }}"
                    alt="{{ $aboutBtn->comment }}">
            </button>
        </form>


    </div>
@endsection