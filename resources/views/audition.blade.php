@extends('layouts.app')

@section('title', 'AUDITION')

@section('content')
    <div class="container audition">
        <h1 class="page-title">AUDITION</h1>
        <div class="audition-top">
            <img class="person-img1" src="{{ asset($auditionIconUp->file_path . $auditionIconUp->file_name) }}"
                alt="{{ $auditionIconUp->alt}}">
            <div class="audition-content-right">
                <img class="pop-img1" src="{{ asset($auditionPop->file_path . $auditionPop->file_name) }}"
                    alt="{{ $auditionPop->alt}}">
                <p>{!! nl2br(e($auditionContent->content)) !!}</p>
            </div>
        </div>

        <div class="audition-content">
            <p class="audition-content-title">{!! nl2br(e($exp1->memo)) !!}</p>
            <p class="audition-content-content">{!! nl2br(e($exp1->content)) !!}</p>
        </div>

        <div class="audition-content-bottom">
            <div class="audition-button-wrapper">
                <button
                    onclick="location.href='https://docs.google.com/forms/d/e/1FAIpQLSfdyFt2m0Gkz8LhvyF-sPMa_p_ytGV1wLMV5g3jm5gfEP6EvA/viewform'"
                    type="submit" class="btn submit-button">
                    <img class="btn-img audition" src="{{ asset($applyBtn->file_path . $applyBtn->file_name) }}"
                        alt="{{ $applyBtn->alt}}">
                </button>
            </div>
            <img class="person-img2" src="{{ asset($auditionIconDown->file_path . $auditionIconDown->file_name) }}"
                alt="{{ $auditionIconDown->alt}}">
        </div>
    </div>
@endsection