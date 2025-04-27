<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Services\ContentMasterService;
use App\Services\ContentDataService;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    protected $contentMaster;
    protected $contentData;

    public function __construct(ContentMasterService $contentMaster, ContentDataService $contentData)
    {
        $this->contentMaster = $contentMaster;
        $this->contentData = $contentData;
    }

    public function index()
    {
        $logoImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 1)->first();
        $logoMinImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 2)->first();
        $XBtn = Image::where('VIEW_FLG', 'HP_007')->first();
        $backImg = Image::where('VIEW_FLG', 'HP_004')->first();

        $titleNews = Image::where('VIEW_FLG', 'HP_013')->first();
        $heartImg = Image::where('VIEW_FLG', 'HP_124')->first();

        // ニュース情報の取得（既存のメソッド）
        // ニュース用のオプション
        $options = [
            ['priority', true],
            ['created_at', false]
        ];
        $newsItems = $this->contentData->getContentByMasterId('T004', 0, [], $options);

        return view('news', compact(
            'logoImg',
            'logoMinImg',
            'XBtn',
            'backImg',
            'titleNews',
            'newsItems',
            'heartImg'
        ));
    }

    // ニュース詳細ページ表示用のメソッドを追加
    public function show(Request $request, $id)
    {
        $logoImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 1)->first();
        $logoMinImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 2)->first();
        $XBtn = Image::where('VIEW_FLG', 'HP_007')->first();
        $backImg = Image::where('VIEW_FLG', 'HP_004')->first();

        $titleNews = Image::where('VIEW_FLG', 'HP_013')->first();
        $heartImg = Image::where('VIEW_FLG', 'HP_124')->first();

        // 特定のニュース記事を取得
        $options = [
            ['publish_date', false], // 公開日の降順
            ['created_at', false]
        ];
        $newsItem = $this->contentData->getContentByMasterId('T004', 0, [], $options, $id);

        // 最新のお知らせを取得（最新の5件）
        $options = [
            ['publish_date', false], // 公開日の降順
            ['created_at', false]
        ];
        $latestNews = $this->contentData->getContentByMasterId('T004', 4, [], $options);

        return view('news-detail', compact(
            'logoImg',
            'logoMinImg',
            'XBtn',
            'backImg',
            'titleNews',
            'newsItem',
            'latestNews',
            'heartImg'
        ));
    }
}
