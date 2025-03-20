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
        $shopBtn = Image::where('VIEW_FLG', 'HP_007')->first();
        $backImg = Image::where('VIEW_FLG', 'HP_004')->first();
        $dateImg = Image::where('VIEW_FLG', 'HP_402')->first();

        // ニュース情報の取得（既存のメソッド）
        // ニュース用のオプション
        $options = [
            ['priority', true],
            ['created_at', false]
        ];
        $newsItems = $this->contentData->getContentByMasterId('T004', 0, [], $options);

        return view('news', compact('logoImg', 'logoMinImg', 'shopBtn', 'backImg', 'dateImg',  'newsItems'));
    }
}
