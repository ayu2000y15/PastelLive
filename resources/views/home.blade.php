@extends('layouts.app')

@section('title', 'HOME')

@section('content')
    @php
        $backImgT = asset($backImgTalent->file_path . $backImgTalent->file_name);
        $backImgA = asset($backImgAudition->file_path . $backImgAudition->file_name);
    @endphp
    <style>
        .home.talent {
            background-image: url({{$backImgT}});
            background-repeat: no-repeat;
            background-position: top;
            background-size: cover;
        }

        .home.audition {
            background-image: url({{$backImgA}});
            background-repeat: no-repeat;
            background-position: top;
            background-size: cover;
        }
    </style>
    <div class="hero home-slide">
        <div class="slideshow">
            @foreach($slides as $slide)
                <img class="slideshow-image" src="{{ asset($slide->file_path . $slide->file_name) }}" width="1920" height="400"
                    alt="{{ $slide->alt }}">
            @endforeach
        </div>
        <div class="slideshow-dots">
            @for ($i = 0; $i < $slideCnt; $i++)
                <span class="dot @if($i === 0) active @endif" data-index="{{ $i }}"></span>
            @endfor
        </div>
    </div>

    <div class="home about" style="background-color: white;">
        <div class="container home">
            <h1 class="home-title about" style="color: #eccad9;">ABOUT</h1>
            <p>{!! nl2br(e($aboutContent->content)) !!}</p>
            <div class="home-button-wrapper">
                <form action="{{ route('about') }}">
                    <button type="submit" class="btn submit-button">
                        <img class="home-btn-img" src="{{ asset($aboutUsBtn->file_path . $aboutUsBtn->file_name) }}"
                            alt="{{ $aboutUsBtn->alt }}">
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="home talent">
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
                        <img class="home-btn-img" src="{{ asset($viewBtn->file_path . $viewBtn->file_name) }}"
                            alt="{{ $viewBtn->alt }}">
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="home news" style="background-color: white;">
        <div class="container home">
            <h1 class="home-title news" style="color: #f2dbb8;">NEWS</h1>
            <div class="news-carousel">
                <div class="carousel-container">
                    <button class="carousel-button prev">
                        <div class="carousel-arrow"></div>
                    </button>
                    <div class="news-items-container">

                        <div class="news-items">
                            <?php
    /*
    $xml = simplexml_load_file('https://www.youtube.com/feeds/videos.xml?playlist_id=PLzFNGS7Rcf-PZC8MfcTJX7srArpLdvyfb');
    $count = 0;

    foreach($xml as $item){
        if($item->id) {
            $title = $item->title;
            $date = date('Y/m/d',strtotime($item->published));

            $id = $item->children('yt', true)->videoId[0];
            $html = '<a href="https://www.youtube.com/watch?v='.$id.'" target="_blank">
                        <div class="news-item">
                            <img src="https://i1.ytimg.com/vi/'.$id.'/hqdefault.jpg">
                            <div class="news-header">
                                <div class="news-genre">aa</div>
                                <div class="news-date">'. $date .'</div>
                            </div>
                            <div class="news-text">'.$title.'</div>
                        </div>
                    </a>';
                    $html2 = '<div class="movie-area">
                        <a href="https://www.youtube.com/watch?v='.$id.'" target="_blank">
                            <div class="movie-img">
                                <img style="height: 200px" src="https://i1.ytimg.com/vi/'.$id.'/hqdefault.jpg">
                            </div>
                        <p>'.$title.'</p>
                        </a>
                    </div>';
            echo $html;
            $count++;
        }
        if($count >= 5) {
            break;
        }
    }
    */
                                                                                                                                                        ?>
                            @foreach($newsList as $news)
                                <div class="news-item">
                                    <img src="{{ asset($news->image_info) }}" alt="ニュース画像">
                                    <div class="news-header">
                                        <div class="news-genre">{{ $news->category}}</div>
                                        <div class="news-date">{{ $news->publish_date }}</div>
                                    </div>
                                    <div class="news-text"><strong>{{ $news->title }}</strong><br>
                                        <p class="news-content">{!! nl2br($news->content) !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button class="carousel-button next">
                        <span class="carousel-arrow"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="home audition">
        <div class="container home">
            {{-- <h1 class="home-title audition" style="color:white;">AUDITION</h1> --}}
            <img src="{{ asset($titleAudition->file_path . $titleAudition->file_name) }}" alt="タイトル" class="title-image">
            <div class="audition-img">
                <img class="person-img" src="{{ asset($auditionIcon->file_path . $auditionIcon->file_name) }}"
                    alt="{{ $auditionIcon->alt }}">
                <img class="pop-img" src="{{ asset($auditionPop->file_path . $auditionPop->file_name) }}"
                    alt="{{ $auditionPop->alt }}">
            </div>
            <div class="home-button-wrapper">
                <form action="{{ route('audition') }}">
                    <button type="submit" class="btn submit-button">
                        <img class="home-btn-img" src="{{ asset($viewBtn->file_path . $viewBtn->file_name) }}"
                            alt="{{ $viewBtn->alt }}">
                    </button>
                </form>
            </div>
        </div>
    </div>
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