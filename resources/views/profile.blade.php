@extends('layouts.app')

@section('title', 'TALENT')

@section('content')
    <div class="container talent">
        {{-- <h1 class="page-title">TALENT</h1> --}}
        <img src="{{ asset($titleTalent->file_path . $titleTalent->file_name) }}" alt="タイトル" class="title-image">

        <div class="breadcrumb">
            <span class="breadcrumb-separator">▶</span>
            <span class="breadcrumb-item">{{ $talentProf->talent_name }}</span>
        </div>

        <div class="talent-detail-container">
            <!-- メインビジュアル -->
            <div class="talent-main-visual">
                <img src="{{ asset($talentProf->talent_image_standing) }}" alt="タレント画像">
            </div>

            <!-- プロフィール情報 -->
            <div class="talent-profile">
                <div class="profile-card">
                    <h1 class="talent-name">{{  $talentProf->talent_name }}</h1>
                    @if($talentProf->talent_name_en <> "" or $talentProf->talent_name_en <> null)
                        <p class="talent-name-en">{{  $talentProf->talent_name_en }}</p>
                    @endif
                    <img class="talent-prof-line" src="{{ asset($line->file_path . $line->file_name) }}" alt="区切り">

                    @if($talentProf->talent_comment <> '' or $talentProf->talent_comment <> null)
                        <p class="talent-description">
                            {!! nl2br($talentProf->talent_comment) !!}
                        </p>
                    @endif
                    <img class="talent-prof-line" src="{{ asset($line->file_path . $line->file_name) }}" alt="区切り">

                    <div class="talent-info">
                        @if($talentProf->talent_birthday <> '' or $talentProf->talent_birthday <> null)
                            <p>誕生日　　：{{ date('n月j日', strtotime($talentProf->talent_birthday)) }}</p>
                        @endif
                        @if($talentProf->talent_debut <> '' or $talentProf->talent_debut <> null)
                            <p>デビュー日：{{ date('Y.n.j', strtotime($talentProf->talent_debut)) }}</p>
                        @endif
                    </div>

                    <img class="talent-prof-line" src="{{ asset($line->file_path . $line->file_name) }}" alt="区切り">

                    <div class="social-links">
                        @if($talentProf->talent_youtube_link <> '' or $talentProf->talent_youtube_link <> null)
                            <button onclick="location.href='{{ $talentProf->talent_youtube_link }}'" type="submit"
                                class="btn submit-button">
                                <img class="social-btn" src="{{ asset($talentBtn1->file_path . $talentBtn1->file_name) }}"
                                    alt="{{ $talentBtn1->alt }}">
                            </button>
                        @endif
                        @if($talentProf->talent_x_link <> '' or $talentProf->talent_x_link <> null)
                            <button onclick="location.href='{{ $talentProf->talent_x_link }}'" type="submit"
                                class="btn submit-button">
                                <img class="social-btn" src="{{ asset($talentBtn2->file_path . $talentBtn2->file_name) }}"
                                    alt="{{ $talentBtn2->alt }}">
                            </button>
                        @endif
                        @if($talentProf->talent_shop_link <> '' or $talentProf->talent_shop_link <> null)
                            <button onclick="location.href='{{ $talentProf->talent_shop_link }}'" type="submit"
                                class="btn submit-button">
                                <img class="social-btn" src="{{ asset($talentBtn3->file_path . $talentBtn3->file_name) }}"
                                    alt="{{ $talentBtn3->alt }}">
                            </button>
                        @endif
                    </div>
                    @if($talentProf->talent_voice_link <> '' or $talentProf->talent_voice_link <> null)
                        <div class="voice-sample">
                            <button onclick="location.href='{{ $talentProf->talent_voice_link }}'" type="submit"
                                class="btn submit-button">
                                <img class="voice-btn" src="{{ asset($talentBtn4->file_path . $talentBtn4->file_name) }}"
                                    alt="{{ $talentBtn4->alt }}">
                            </button>
                        </div>
                    @endif

                    <img class="talent-prof-line" src="{{ asset($line->file_path . $line->file_name) }}" alt="区切り">

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

    </div>
@endsection