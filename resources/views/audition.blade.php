@extends('layouts.app')

@section('title', 'AUDITION')

@section('content')
    {{-- <h1 class="page-title">AUDITION</h1> --}}
    {{-- <img src="{{ asset($titleAudition->file_path . $titleAudition->file_name) }}" alt="タイトル" class="title-image">
    --}}
    <div class="audition-container">
        <img class="audition-img img1" src="{{ asset($auditionImg1->file_path . $auditionImg1->file_name) }}"
            alt="{{ $auditionImg1->alt }}">
        <img class="audition-img img2" src="{{ asset($auditionImg2->file_path . $auditionImg2->file_name) }}"
            alt="{{ $auditionImg2->alt }}">
        <img class="audition-img img3" src="{{ asset($auditionImg3->file_path . $auditionImg3->file_name) }}"
            alt="{{ $auditionImg3->alt }}">
        <img class="audition-img img4" src="{{ asset($auditionImg4->file_path . $auditionImg4->file_name) }}"
            alt="{{ $auditionImg4->alt }}">
        <img class="audition-img img5" src="{{ asset($auditionImg5->file_path . $auditionImg5->file_name) }}"
            alt="{{ $auditionImg5->alt }}">

        <div class="audition-button-wrapper">
            <a href="https://docs.google.com/forms/d/e/1FAIpQLSfdyFt2m0Gkz8LhvyF-sPMa_p_ytGV1wLMV5g3jm5gfEP6EvA/viewform"
                target="_blank">
                <img class="audition-btn" src="{{ asset($entryBtn->file_path . $entryBtn->file_name) }}"
                    alt="{{ $entryBtn->alt }}">
            </a>
        </div>
    </div>
@endsection