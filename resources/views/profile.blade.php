@extends('layouts.app')

@section('title', 'TALENT')

@section('content')
    <div class="container talent">
        <h1 class="page-title">TALENT</h1>
        <div class="breadcrumb">
            <span class="breadcrumb-separator">▶</span>
            <span class="breadcrumb-item">{{ $talentProf->gender_flg . ' ▶' . $talentProf->talent_name }}</span>
        </div>

        <div class="talent-detail-container">
            <!-- メインビジュアル -->
            <div class="talent-main-visual">
                <img src="{{ asset($talentProf->talent_topimage) }}" alt="タレント画像">
            </div>

            <!-- プロフィール情報 -->
            <div class="talent-profile">
                <div class="profile-card">
                    <h1 class="talent-name">{{  $talentProf->talent_name }}</h1>
                    <p class="talent-name-en">{{  $talentProf->talent_name }}</p>
                    <hr class="talent-prof-line">
                    <p class="talent-description">
                        {{  $talentProf->talent_comment }}
                    </p>

                    <div class="talent-info">
                        <p>誕生日：{{ $talentProf->talent_birthday }}</p>
                        <p>デビュー日：{{ $talentProf->talent_debut }}</p>
                    </div>

                    <div class="social-links">
                        <a href="{{ $talentProf->talent_youtube_link }}" class="social-btn">YouTube</a>
                        <a href="{{ $talentProf->talent_x_link }}" class="social-btn">X</a>
                        <a href="{{ $talentProf->talent_voice_link }}" class="social-btn">公式グッズ</a>
                    </div>
                    <div class="voice-sample">
                        <button class="voice-btn">ボイスサンプル</button>
                    </div>

                    <div class="profile-youtube">
                        <iframe width="450" height="300" src="" title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>

        <!-- ライブスケジュール -->
        <div class="live-schedule-section">
            <h2 class="schedule-title">LIVE SCHEDULE</h2>

            <div class="schedule-grid">
                <div class="schedule-card"
                    style="background-image: url({{asset($liveBackImg->file_path . $liveBackImg->file_name) }});">
                    <img src="{{ asset('storage/img/sample/sample.png') }}" alt="Schedule 1">
                    <p class="schedule-date">2024.01.01</p>
                    <p class="schedule-text">ライブ配信予定</p>
                </div>
                <div class="schedule-card"
                    style="background-image: url({{asset($liveBackImg->file_path . $liveBackImg->file_name) }});">
                    <img src="{{ asset('storage/img/sample/sample.png') }}" alt="Schedule 1">
                    <p class="schedule-date">2024.01.01</p>
                    <p class="schedule-text">ライブ配信予定</p>
                </div>
                <div class="schedule-card"
                    style="background-image: url({{asset($liveBackImg->file_path . $liveBackImg->file_name) }});">
                    <img src="{{ asset('storage/img/sample/sample.png') }}" alt="Schedule 1">
                    <p class="schedule-date">2024.01.01</p>
                    <p class="schedule-text">ライブ配信予定</p>
                </div>
            </div>
        </div>
        <hr class="talent-line">
        <div class="talent-model">
            @foreach ($talentProf->talent_image_standing as $img)
                <img class="model-img" src="{{ asset($img) }}" alt="モデル">
            @endforeach
        </div>
    </div>
@endsection