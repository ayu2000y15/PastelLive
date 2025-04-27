<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pastel Live') - Pastel Live</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/script.js') }}" defer></script>
</head>
@php
    if (isset($backImg)) {
        $backImgHp = asset($backImg->file_path . $backImg->file_name);
    } else {
        $backImgHp = 'dummy';
    }
@endphp
<style>
    body {
        background-image: url({{$backImgHp}});
        background-repeat: repeat;
    }
</style>

<body>
    <header class="header" id="header">
        <div class="header-container">
            <a href="{{ route('home') }}" class="logo">
                @if($logoImg)
                    <img id="logo-max" src="{{ asset($logoImg->file_path . $logoImg->file_name) }}"
                        alt="{{ $logoImg->comment }}">
                    <img id="logo-min" src="{{ asset($logoMinImg->file_path . $logoMinImg->file_name) }}"
                        alt="{{ $logoMinImg->comment }}">
                @else
                    PastelLive
                @endif
            </a>
            <button class="menu-toggle" aria-label="メニューを開く" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <nav class="main-nav">
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">ABOUT</a>
                <a href="{{ route('news') }}" class="{{ request()->routeIs('news') ? 'active' : '' }}">NEWS</a>
                <a href="{{ route('talent') }}"
                    class="{{ request()->routeIs('talent') || request()->routeIs('talent.show') ? 'active' : '' }}">TALENT</a>
                {{-- <a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') ? 'active' : '' }}">SHOP</a>
                --}}
                <a href="{{ route('faq') }}" class="{{ request()->routeIs('faq') ? 'active' : '' }}">FAQ</a>
                <a href="{{ route('audition') }}"
                    class="{{ request()->routeIs('audition') ? 'active' : '' }}">AUDITION</a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">CONTACT</a>
            </nav>
            <div class="social-icons">
                <a href="https://x.com/PastelLive_NA" target="_blank">
                    <img class="x-btn" src="{{ asset($XBtn->file_path . $XBtn->file_name) }}" height="40px"
                        alt="{{ $XBtn->comment }}">
                </a>
                {{-- <a href="{{ $sns->SNS_2 }}" aria-label="Instagram" target="_blank" rel="noopener">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                        <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                    </svg>
                </a>
                <a href="{{ $sns->SNS_3 }}" aria-label="TikTok" target="_blank" rel="noopener">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path>
                    </svg>
                </a> --}}
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Pastel Live. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>