@extends('layouts.app')

@section('title', 'TALENT')

@section('content')
    <?php
    // YouTube APIキーを環境変数から取得
    $apiKey = 'AIzaSyAMnnsm82DiTNefsH9ZV6uWoy3lutUslOo';

    // フォームから送信されたチャンネルURLを取得
    $channelUrl = $talentProf->talent_youtube_link;
    $channelId = '';

    // URLが送信された場合、チャンネルIDを取得
    if (!empty($channelUrl)) {
        $channelId = getChannelIdFromUrl($apiKey, $channelUrl);
    }

    /**
     * YouTubeチャンネルURLからチャンネルIDを取得する
     *
     * @param string $apiKey YouTube API Key
     * @param string $url YouTubeチャンネルURL
     * @return string|null チャンネルID、取得できない場合はnull
     */
    function getChannelIdFromUrl($apiKey, $url)
    {
        // URLからチャンネルIDを直接抽出できるか試みる
        if (preg_match('/youtube\.com\/channel\/([^\/\?&]+)/', $url, $matches)) {
            return $matches[1]; // チャンネルIDを直接返す
        }

        // ユーザー名を抽出
        $username = null;
        if (preg_match('/youtube\.com\/user\/([^\/\?&]+)/', $url, $matches)) {
            $username = $matches[1];
        }

        // カスタムURLを抽出
        $customUrl = null;
        if (preg_match('/youtube\.com\/c\/([^\/\?&]+)/', $url, $matches)) {
            $customUrl = $matches[1];
        }

        // @ハンドルを抽出
        $handle = null;
        if (preg_match('/youtube\.com\/@([^\/\?&]+)/', $url, $matches)) {
            $handle = $matches[1];
        }

        // ユーザー名、カスタムURL、ハンドルのいずれかが取得できた場合
        if ($username || $customUrl || $handle) {
            // 検索クエリを決定
            $searchQuery = $username ?: ($customUrl ?: $handle);

            // YouTube Data API v3のエンドポイント
            $apiUrl = 'https://www.googleapis.com/youtube/v3/search';

            // APIリクエストパラメータ
            $params = [
                'key' => $apiKey,
                'q' => $searchQuery,
                'part' => 'snippet',
                'type' => 'channel',
                'maxResults' => 1
            ];

            // URLにパラメータを追加
            $url = $apiUrl . '?' . http_build_query($params);

            // cURLを使用してAPIリクエストを実行
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'cURLエラー: ' . curl_error($ch);
                return null;
            }

            curl_close($ch);

            // JSONレスポンスをデコード
            $data = json_decode($response, true);

            // エラーチェック
            if (isset($data['error'])) {
                echo 'APIエラー: ' . $data['error']['message'];
                return null;
            }

            // 検索結果からチャンネルIDを取得
            if (isset($data['items']) && !empty($data['items'])) {
                return $data['items'][0]['snippet']['channelId'];
            }
        }

        // チャンネルIDが取得できなかった場合
        return null;
    }

    /**
     * YouTubeチャンネルの配信予定のライブスケジュールを取得する
     *
     * @param string $apiKey YouTube API Key
     * @param string $channelId YouTubeチャンネルID
     * @return array 配信予定のライブ情報
     */
    function getUpcomingLiveStreams($apiKey, $channelId)
    {
        if (empty($channelId)) {
            return [];
        }

        // YouTube Data API v3のエンドポイント
        $apiUrl = 'https://www.googleapis.com/youtube/v3/search';

        // APIリクエストパラメータ
        $params = [
            'key' => $apiKey,
            'channelId' => $channelId,
            'part' => 'snippet',
            'eventType' => 'upcoming',
            'type' => 'video',
            'order' => 'date',
            'maxResults' => 10
        ];

        // URLにパラメータを追加
        $url = $apiUrl . '?' . http_build_query($params);

        // cURLを使用してAPIリクエストを実行
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'cURLエラー: ' . curl_error($ch);
            return [];
        }

        curl_close($ch);

        // JSONレスポンスをデコード
        $data = json_decode($response, true);

        // エラーチェック
        if (isset($data['error'])) {
            echo 'APIエラー: ' . $data['error']['message'];
            return [];
        }

        $liveStreams = [];

        // 検索結果から動画IDを取得
        $videoIds = [];
        if (isset($data['items']) && !empty($data['items'])) {
            foreach ($data['items'] as $item) {
                $videoIds[] = $item['id']['videoId'];
            }

            // 動画の詳細情報を取得（ライブ配信時間などの詳細情報を取得するため）
            $liveStreams = getVideoDetails($apiKey, $videoIds);
        }

        return $liveStreams;
    }

    /**
     * 動画IDから詳細情報を取得する
     *
     * @param string $apiKey YouTube API Key
     * @param array $videoIds 動画IDの配列
     * @return array 動画の詳細情報
     */
    function getVideoDetails($apiKey, $videoIds)
    {
        if (empty($videoIds)) {
            return [];
        }

        // YouTube Data API v3のエンドポイント
        $apiUrl = 'https://www.googleapis.com/youtube/v3/videos';

        // APIリクエストパラメータ
        $params = [
            'key' => $apiKey,
            'id' => implode(',', $videoIds),
            'part' => 'snippet,liveStreamingDetails'
        ];

        // URLにパラメータを追加
        $url = $apiUrl . '?' . http_build_query($params);

        // cURLを使用してAPIリクエストを実行
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'cURLエラー: ' . curl_error($ch);
            return [];
        }

        curl_close($ch);

        // JSONレスポンスをデコード
        $data = json_decode($response, true);

        // エラーチェック
        if (isset($data['error'])) {
            echo 'APIエラー: ' . $data['error']['message'];
            return [];
        }

        $videos = [];

        if (isset($data['items']) && !empty($data['items'])) {
            foreach ($data['items'] as $item) {
                // ライブ配信情報がある場合のみ追加
                if (isset($item['liveStreamingDetails']) && isset($item['liveStreamingDetails']['scheduledStartTime'])) {
                    // 最高解像度のサムネイルを取得
                    $thumbnailUrl = isset($item['snippet']['thumbnails']['maxres'])
                        ? $item['snippet']['thumbnails']['maxres']['url']
                        : (isset($item['snippet']['thumbnails']['high'])
                            ? $item['snippet']['thumbnails']['high']['url']
                            : $item['snippet']['thumbnails']['default']['url']);

                    $videos[] = [
                        'id' => $item['id'],
                        'title' => $item['snippet']['title'],
                        'description' => $item['snippet']['description'],
                        'thumbnailUrl' => $thumbnailUrl,
                        'channelTitle' => $item['snippet']['channelTitle'],
                        'scheduledStartTime' => $item['liveStreamingDetails']['scheduledStartTime'],
                        'url' => 'https://www.youtube.com/watch?v=' . $item['id']
                    ];
                }
            }
        }

        // 配信開始時間でソート
        usort($videos, function ($a, $b) {
            return strtotime($a['scheduledStartTime']) - strtotime($b['scheduledStartTime']);
        });

        return $videos;
    }

    /**
     * 日時をフォーマットする
     *
     * @param string $dateTime ISO 8601形式の日時
     * @return string フォーマットされた日時
     */
    function formatDateTime($dateTime)
    {
        $timestamp = strtotime($dateTime);
        return date('Y年m月d日 H:i', $timestamp);
    }

    // チャンネルIDが取得できた場合のみライブ配信予定を取得
    $liveStreams = [];
    if (!empty($channelId)) {
        $liveStreams = getUpcomingLiveStreams($apiKey, $channelId);
    }

    // チャンネル情報を取得
    $channelInfo = null;
    if (!empty($channelId)) {
        $channelInfo = getChannelInfo($apiKey, $channelId);
    }

    /**
     * チャンネル情報を取得する
     *
     * @param string $apiKey YouTube API Key
     * @param string $channelId YouTubeチャンネルID
     * @return array|null チャンネル情報
     */
    function getChannelInfo($apiKey, $channelId)
    {
        // YouTube Data API v3のエンドポイント
        $apiUrl = 'https://www.googleapis.com/youtube/v3/channels';

        // APIリクエストパラメータ
        $params = [
            'key' => $apiKey,
            'id' => $channelId,
            'part' => 'snippet,statistics'
        ];

        // URLにパラメータを追加
        $url = $apiUrl . '?' . http_build_query($params);

        // cURLを使用してAPIリクエストを実行
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'cURLエラー: ' . curl_error($ch);
            return null;
        }

        curl_close($ch);

        // JSONレスポンスをデコード
        $data = json_decode($response, true);

        // エラーチェック
        if (isset($data['error'])) {
            echo 'APIエラー: ' . $data['error']['message'];
            return null;
        }

        // チャンネル情報を取得
        if (isset($data['items']) && !empty($data['items'])) {
            $item = $data['items'][0];
            return [
                'id' => $item['id'],
                'title' => $item['snippet']['title'],
                'description' => $item['snippet']['description'],
                'thumbnailUrl' => $item['snippet']['thumbnails']['high']['url'],
                'subscriberCount' => $item['statistics']['subscriberCount'],
                'videoCount' => $item['statistics']['videoCount'],
                'viewCount' => $item['statistics']['viewCount'],
                'url' => 'https://www.youtube.com/channel/' . $item['id']
            ];
        }

        return null;
    }
                                                                                                                        ?>
    <div class="container talent">
        {{-- <h1 class="page-title">TALENT</h1> --}}
        <img src="{{ asset($titleTalent->file_path . $titleTalent->file_name) }}" alt="タイトル" class="title-image">

        <div class="breadcrumb">
            <span class="breadcrumb-separator">▶</span>
            <span class="breadcrumb-item">{{ $talentProf->gender_flg . ' ▶' . $talentProf->talent_name }}</span>
        </div>

        <div class="talent-detail-container">
            <!-- メインビジュアル -->
            <div class="talent-main-visual">
                <img src="{{ asset($talentProf->talent_topimage) }}" alt="タレント画像">
            </div>

            <!-- プロフィール情報 -->
            <div class="talent-profile">
                <div class="profile-card">
                    <h1 class="talent-name">{{  $talentProf->talent_name }}</h1>
                    @if($talentProf->talent_name_en <> "" or $talentProf->talent_name_en <> null)
                        <p class="talent-name-en">{{  $talentProf->talent_name_en }}</p>
                    @endif
                    <hr class="talent-prof-line">

                    @if($talentProf->talent_comment <> '' or $talentProf->talent_comment <> null)
                        <p class="talent-description">
                            {!!  nl2br($talentProf->talent_comment) !!}
                        </p>
                    @endif

                    <div class="talent-info">
                        @if($talentProf->talent_birthday <> '' or $talentProf->talent_birthday <> null)
                            <p>誕生日：{{ date('Y/n/j', strtotime($talentProf->talent_birthday)) }}</p>
                        @endif
                        @if($talentProf->talent_debut <> '' or $talentProf->talent_debut <> null)
                            <p>デビュー日：{{ date('Y/n/j', strtotime($talentProf->talent_debut)) }}</p>
                        @endif
                    </div>

                    <div class="social-links">
                        @if($talentProf->talent_youtube_link <> '' or $talentProf->talent_youtube_link <> null)
                            <a href="{{ $talentProf->talent_youtube_link }}" target="_blank" class="social-btn">YouTube</a>
                        @endif
                        @if($talentProf->talent_x_link <> '' or $talentProf->talent_x_link <> null)
                            <a href="{{ $talentProf->talent_x_link }}" target="_blank" class="social-btn">X</a>
                        @endif
                        @if($talentProf->talent_shop_link <> '' or $talentProf->talent_shop_link <> null)
                            <a href="{{ $talentProf->talent_shop_link }}" target="_blank" class="social-btn">公式グッズ</a>
                        @endif
                    </div>
                    @if($talentProf->talent_voice_link <> '' or $talentProf->talent_voice_link <> null)
                        <div class="voice-sample">
                            <button class="voice-btn">
                                <a href="{{ $talentProf->talent_voice_link }}" target="_blank">ボイスサンプル</a>
                            </button>
                        </div>
                        <div class="voice-sample">
                        </div>
                    @endif

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

        <!-- ライブスケジュール -->
        @if (!empty($liveStreams))
            {{-- @if(!empty($talentProf->live_schedule) and $talentProf->live_schedule <> '' and $talentProf->live_schedule <>
                    null) --}}
                    <div class="live-schedule-section">
                        <h2 class="schedule-title">LIVE SCHEDULE</h2>
                        <div class="schedule-grid">
                            @foreach ($liveStreams as $live)
                                <a href="{{htmlspecialchars($live['url'])}}">
                                    <div class="schedule-card"
                                        style="background-image: url({{asset($liveBackImg->file_path . $liveBackImg->file_name) }});">
                                        {{-- <iframe src="{{ $schedule[" 配信リンク"] }}" title="YouTube video player" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe> --}}
                                        <img src="{{ htmlspecialchars($live['thumbnailUrl']) }}"
                                            alt="{{ htmlspecialchars($live['title']) }}">
                                        <p class="schedule-date">{{ formatDateTime($live['scheduledStartTime']) }}</p>
                                        <p class="schedule-text">{{ htmlspecialchars($live['title'])}}</p>
                                    </div>
                                </a>
                            @endforeach
        @endif
                    </div>
                </div>
                {{-- @endif --}}
                {{-- @if(!empty($talentProf->live_schedule) and $talentProf->live_schedule <> '' and
                    $talentProf->live_schedule <>
                        null)
                        <div class="live-schedule-section">
                            <h2 class="schedule-title">LIVE SCHEDULE</h2>
                            <div class="schedule-grid">
                                @foreach ($talentProf->live_schedule as $schedule)
                                <div class="schedule-card"
                                    style="background-image: url({{asset($liveBackImg->file_path . $liveBackImg->file_name) }});">
                                    <iframe src="{{ $schedule[" 配信リンク"] }}" title="YouTube video player" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

                                    <p class="schedule-date">{{ $schedule["配信日"] }}</p>
                                    <p class="schedule-text">{{ $schedule["配信コメント"] }}</p>
                                </div>
                                @endforeach

                            </div>
                        </div>
                        @endif --}}

                        <hr class="talent-line">
                        <div class="talent-model">
                            @foreach ($talentProf->talent_image_standing as $img)
                                <img class="model-img" src="{{ asset($img) }}" alt="モデル">
                            @endforeach
                        </div>
    </div>
@endsection