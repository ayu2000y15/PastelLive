@extends('layouts.app')

@section('title', 'お問い合わせ完了')

@section('content')
    <div class="container">
        {{-- <img src="{{ asset($titleContact->file_path . $titleContact->file_name) }}" alt="タイトル" class="title-image">
        --}}

        <div class="step-indicator">
            <div class="step completed" data-title="入力">1</div>
            <div class="step completed" data-title="確認">2</div>
            <div class="step active" data-title="完了">3</div>
        </div>

        <div class="contact-form1">
            <div class="completion-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>お問い合わせを受け付けました</h2>
            <p>
                お問い合わせいただき、ありがとうございます。<br>
                内容を確認の上、担当者より折り返しご連絡いたします。
            </p>
            <button type="submit" class="btn submit-button">
                <a href="{{ route('home') }}">
                    <img class="btn-img confirm" src="{{ asset($topBackBtn->file_path . $topBackBtn->file_name) }}"
                        alt="Button Image">
                </a>
            </button>

        </div>
    </div>
@endsection