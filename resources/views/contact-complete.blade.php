@extends('layouts.app')

@section('title', 'お問い合わせ完了')

@section('content')
    <div class="container">
        <h1 class="page-title">お問い合わせ完了</h1>

        <div class="step-indicator">
            <div class="step completed" data-title="入力">1</div>
            <div class="step completed" data-title="確認">2</div>
            <div class="step active" data-title="完了">3</div>
        </div>

        <div class="contact-form">
            <div class="completion-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>お問い合わせを受け付けました</h2>
            <p>
                お問い合わせいただき、ありがとうございます。<br>
                内容を確認の上、担当者より折り返しご連絡いたします。
            </p>
            <div class="button-container">
                <a href="{{ route('home') }}" class="btn-top-back">
                    トップページに戻る
                </a>
            </div>
        </div>
    </div>
@endsection