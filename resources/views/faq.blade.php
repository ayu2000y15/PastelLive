@extends('layouts.app')

@section('title', 'AUDITION')

@section('content')

    <div class="faq-container">
        <img class="faq-img img1" src="{{ asset($faqImg1->file_path . $faqImg1->file_name) }}" alt="{{ $faqImg1->alt }}">
        <img class="faq-img img2" src="{{ asset($faqImg2->file_path . $faqImg2->file_name) }}" alt="{{ $faqImg2->alt }}">
        <p class="faq-p">その他不明な点がございましたら、お気軽にCONTACTよりお問い合わせください。</p>
    </div>
@endsection