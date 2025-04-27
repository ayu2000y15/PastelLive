@extends('layouts.app')

@section('title', 'HOME')

@section('content')
    <div class="hero home-slide">
        <div class="slideshow">
            @foreach($slides as $slide)
                <img class="slideshow-image" src="{{ asset($slide->file_path . $slide->file_name) }}" width="1920" height="760"
                    alt="{{ $slide->alt }}">
            @endforeach
        </div>
        <div class="slideshow-dots">
            @for ($i = 0; $i < $slideCnt; $i++)
                <span class="dot @if($i === 0) active @endif" data-index="{{ $i }}"></span>
            @endfor
        </div>
    </div>

    <div class="home-area">
        <div class="container home">
            <div class="home-about-img">
                <img src="{{ asset($titleAbout->file_path . $titleAbout->file_name) }}" alt="タイトル" class="title-image">
                {{-- <p>{!! nl2br(e($aboutContent->content)) !!}</p> --}}
                <img src="{{ asset($aboutContent->file_path . $aboutContent->file_name) }}" alt="about"
                    class="home-about-content">
            </div>
            <div class="home-button-wrapper">
                <form action="{{ route('about') }}">
                    <button type="submit" class="btn submit-button">
                        <img class="home-btn-img" src="{{ asset($viewBtnPink->file_path . $viewBtnPink->file_name) }}"
                            alt="{{ $viewBtnPink->alt }}">
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="home-area">
        <div class="container home">
            <img src="{{ asset($titleNews->file_path . $titleNews->file_name) }}" alt="タイトル" class="title-image">

            @php
                $count = 0;
            @endphp
            <div class="home-news-areas">
                @foreach ($newsList as $news)
                            <div class="home-news-area">
                                <div class="home-news-header">
                                    <img class="heart-img" src="{{ asset($heartImg->file_path . $heartImg->file_name) }}"
                                        alt="{{ $heartImg->alt }}">
                                    <p class="home-news-date">{{ date('Y.n.j', strtotime($news->publish_date)) }}</p>
                                </div>
                                <p>{{ $news->title }}</p>
                            </div>
                            @php
                                $count++;
                                if ($count == 5) {
                                    break;
                                }
                            @endphp
                @endforeach
            </div>

            <div class="home-button-wrapper">
                <form action="{{ route('news') }}">
                    <button type="submit" class="btn submit-button">
                        <img class="home-btn-img" src="{{ asset($viewBtnOrange->file_path . $viewBtnOrange->file_name) }}"
                            alt="{{ $viewBtnOrange->alt }}">
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="home-area">
        <div class="container home">
            {{-- <h1 class="home-title talent" style="color: white;">TALENT</h1> --}}
            <img src="{{ asset($titleTalent->file_path . $titleTalent->file_name) }}" alt="タイトル" class="title-image">

            <div class="talent-grid">
                @foreach($talents as $talent)
                    <form action="{{ route('talent.show') }}" name="form_{{ $talent->id }}" method="POST">
                        <input type="hidden" name="talent_id" value="{{ $talent->id }}">
                        @csrf
                        <button type="submit" class="talent-card-button">
                            <div class="talent-card">
                                <img src="{{ asset($talent->talent_topimage) }}" alt="タレント画像" class="talent-image">
                                <div class="talent-name-main">{{ $talent->talent_name }}</div>
                            </div>
                        </button>
                    </form>
                @endforeach
            </div>
            <div class="home-button-wrapper">
                <form action="{{ route('talent') }}">
                    <button type="submit" class="btn submit-button">
                        <img class="home-btn-img" src="{{ asset($viewBtnPurple->file_path . $viewBtnPurple->file_name) }}"
                            alt="{{ $viewBtnPurple->alt }}">
                    </button>
                </form>
            </div>
        </div>
    </div>

    <img class="home-img1" src="{{ asset($topImg1->file_path . $topImg1->file_name) }}" alt="タイトル" class="title-image">
    <img class="home-img2" src="{{ asset($topImg2->file_path . $topImg2->file_name) }}" alt="タイトル" class="title-image">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slideshow = document.querySelector('.slideshow');
            const images = document.querySelectorAll('.slideshow-image');
            const dots = document.querySelectorAll('.dot');
            let currentIndex = 0;
            let intervalId;

            function showImage(index) {
                slideshow.style.transform = `translateX(-${index * 100}%)`;
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === index);
                });
                currentIndex = index;
            }

            function nextImage() {
                currentIndex = (currentIndex + 1) % images.length;
                showImage(currentIndex);
            }

            function startSlideshow() {
                intervalId = setInterval(nextImage, 5000);
            }

            function stopSlideshow() {
                clearInterval(intervalId);
            }

            // 5秒ごとに画像を切り替え
            startSlideshow();

            // ドットクリックで画像切り替え
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    stopSlideshow();
                    showImage(index);
                    startSlideshow();
                });
            });

            // ニュースカルーセルの設定
            const newsItems = document.querySelector('.news-items');
            const prevButton = document.querySelector('.carousel-button.prev');
            const nextButton = document.querySelector('.carousel-button.next');
            let isDown = false;
            let startX;
            let scrollLeft;

            // ページ読み込み時に最初のアイテムを中央に配置
            function initializeCarousel() {
                if (window.innerWidth <= 768) {
                    // モバイル表示の場合は最初のアイテムを中央に配置
                    centerActiveItem();
                }
            }

            // 初期化を実行
            initializeCarousel();

            // タッチデバイス用のスワイプ機能
            newsItems.addEventListener('touchstart', (e) => {
                isDown = true;
                startX = e.touches[0].pageX - newsItems.offsetLeft;
                scrollLeft = newsItems.scrollLeft;
            });

            newsItems.addEventListener('touchend', () => {
                isDown = false;
                centerActiveItem();
            });

            newsItems.addEventListener('touchmove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.touches[0].pageX - newsItems.offsetLeft;
                const walk = (x - startX) * 2; // スクロール速度の調整
                newsItems.scrollLeft = scrollLeft - walk;
            });

            // マウス用のドラッグ機能
            newsItems.addEventListener('mousedown', (e) => {
                isDown = true;
                startX = e.pageX - newsItems.offsetLeft;
                scrollLeft = newsItems.scrollLeft;
                // newsItems.style.cursor = 'grabbing'; この行を削除
            });

            newsItems.addEventListener('mouseleave', () => {
                isDown = false;
                // newsItems.style.cursor = 'grab'; この行を削除
            });

            newsItems.addEventListener('mouseup', () => {
                isDown = false;
                // newsItems.style.cursor = 'grab'; この行を削除
                centerActiveItem();
            });

            newsItems.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - newsItems.offsetLeft;
                const walk = (x - startX) * 2; // スクロール速度の調整
                newsItems.scrollLeft = scrollLeft - walk;
            });

            // 矢印ボタンのクリックイベント
            prevButton.addEventListener('click', () => {
                const itemWidth = document.querySelector('.news-item').offsetWidth + 20; // マージンを含む
                newsItems.scrollBy({
                    left: -itemWidth,
                    behavior: 'smooth'
                });
                setTimeout(centerActiveItem, 500);
            });

            nextButton.addEventListener('click', () => {
                const itemWidth = document.querySelector('.news-item').offsetWidth + 20; // マージンを含む
                newsItems.scrollBy({
                    left: itemWidth,
                    behavior: 'smooth'
                });
                setTimeout(centerActiveItem, 500);
            });

            // アイテムを中央に配置する関数
            function centerActiveItem() {
                if (window.innerWidth <= 768) { // モバイルビューの場合のみ
                    const newsItems = document.querySelector('.news-items');
                    const newsItem = document.querySelector('.news-item');
                    const itemWidth = newsItem.offsetWidth + 20; // マージンを含む

                    // 現在のスクロール位置から最も近いアイテムを計算
                    const scrollPosition = newsItems.scrollLeft;
                    const itemIndex = Math.round(scrollPosition / itemWidth);

                    // そのアイテムが中央に来るようにスクロール
                    newsItems.scrollTo({
                        left: itemIndex * itemWidth,
                        behavior: 'smooth'
                    });
                }
            }

            // ウィンドウのリサイズ時にも中央配置を適用
            window.addEventListener('resize', () => {
                initializeCarousel();
            });
        });
    </script>

@endsection