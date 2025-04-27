@extends('layouts.app')

@section('title', 'TALENT')

@section('content')
    <div class="container talent">
        {{-- <h1 class="page-title">TALENT</h1> --}}
        <img src="{{ asset($titleTalent->file_path . $titleTalent->file_name) }}" alt="タイトル" class="title-image">

        <div class="talent-grid">
            @foreach($talents as $talent)
                <form action="{{ route('talent.show') }}" name="form_{{ $talent->id }}" method="POST">
                    @csrf
                    <input type="hidden" name="talent_id" value="{{ $talent->id }}">
                    <button type="submit" class="talent-card-button">
                        <div class="talent-card">
                            <img src="{{ asset($talent->talent_topimage) }}" alt="タレント画像" class="talent-image">
                            <div class="talent-name-main">{{ $talent->talent_name }}</div>
                        </div>
                    </button>
                </form>
            @endforeach
        </div>

        <form action="{{ route('faq') }}">
            <button type="submit" class="btn submit-button">
                <img class="about-btn" src="{{ asset($talentBtn->file_path . $talentBtn->file_name) }}"
                    alt="{{ $talentBtn->comment }}">
            </button>
        </form>

    </div>

@endsection