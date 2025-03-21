@extends('layouts.app')

@section('title', 'TALENT')

@section('content')
    <div class="container talent">
        {{-- <h1 class="page-title">TALENT</h1> --}}
        <img src="{{ asset($titleTalent->file_path . $titleTalent->file_name) }}" alt="タイトル" class="title-image">

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
                    @if($talentProf->talent_name_en <> "" or $talentProf->talent_name_en <> null)
                        <p class="talent-name-en">{{  $talentProf->talent_name_en }}</p>
                    @endif
                    <hr class="talent-prof-line">

                    @if($talentProf->talent_comment <> '' or $talentProf->talent_comment <> null)
                        <p class="talent-description">
                            {{  $talentProf->talent_comment }}
                        </p>
                    @endif

                    <div class="talent-info">
                        @if($talentProf->talent_birthday <> '' or $talentProf->talent_birthday <> null)
                            <p>誕生日：{{ date('Y/n/j', strtotime($talentProf->talent_birthday)) }}</p>
                        @endif
                        @if($talentProf->talent_debut <> '' or $talentProf->talent_debut <> null)
                            <p>デビュー日：{{ date('Y/n/j', strtotime($talentProf->talent_debut)) }}</p>
                        @endif
                    </div>

                    <div class="social-links">
                        @if($talentProf->talent_youtube_link <> '' or $talentProf->talent_youtube_link <> null)
                            <a href="{{ $talentProf->talent_youtube_link }}" class="social-btn">YouTube</a>
                        @endif
                        @if($talentProf->talent_x_link <> '' or $talentProf->talent_x_link <> null)
                            <a href="{{ $talentProf->talent_x_link }}" class="social-btn">X</a>
                        @endif
                        @if($talentProf->talent_shop_link <> '' or $talentProf->talent_shop_link <> null)
                            <a href="{{ $talentProf->talent_shop_link }}" class="social-btn">公式グッズ</a>
                        @endif
                    </div>
                    @if($talentProf->talent_voice_link <> '' or $talentProf->talent_voice_link <> null)
                        <div class="voice-sample">
                            <a href="{{ $talentProf->talent_voice_link }}" class="voice-btn">ボイスサンプル</a>
                        </div>
                    @endif

                    @if($talentProf->profile_youtube_link <> '' or $talentProf->profile_youtube_link <> null)
                        <div class="profile-youtube">
                            <iframe width="450" height="300" src="{{$talentProf->profile_youtube_link}}"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ライブスケジュール -->
        @if($talentProf->live_schedule <> '' or $talentProf->live_schedule <> null)
            <div class="live-schedule-section">
                <h2 class="schedule-title">LIVE SCHEDULE</h2>
                <div class="schedule-grid">
                    @foreach ($talentProf->live_schedule as $schedule)
                        <div class="schedule-card"
                            style="background-image: url({{asset($liveBackImg->file_path . $liveBackImg->file_name) }});">
                            <iframe src="{{ $schedule["配信リンク"] }}" title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

                            <p class="schedule-date">{{ $schedule["配信日"] }}</p>
                            <p class="schedule-text">{{ $schedule["配信コメント"] }}</p>
                        </div>
                    @endforeach

                </div>
            </div>
        @endif
        <hr class="talent-line">
        <div class="talent-model">
            @foreach ($talentProf->talent_image_standing as $img)
                <img class="model-img" src="{{ asset($img) }}" alt="モデル">
            @endforeach
        </div>
    </div>
@endsection