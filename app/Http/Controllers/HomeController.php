<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\HpText;
use App\Services\ContentMasterService;
use App\Services\ContentDataService;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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

        $backImg = Image::where('VIEW_FLG', 'HP_001')->first();

        $titleAbout = Image::where('VIEW_FLG', 'HP_011')->first();
        $titleTalent = Image::where('VIEW_FLG', 'HP_012')->first();
        $titleNews = Image::where('VIEW_FLG', 'HP_013')->first();
        $titleAudition = Image::where('VIEW_FLG', 'HP_014')->first();
        $titleContact = Image::where('VIEW_FLG', 'HP_015')->first();

        //$aboutContent = HpText::where('TEXT_ID', 'TOP_ABOUT')->first();

        $slides = Image::where('VIEW_FLG', 'HP_101')->orderBy('PRIORITY')->get();
        $slideCnt = Image::where('VIEW_FLG', 'HP_101')->count();
        $viewBtnPink = Image::where('VIEW_FLG', 'HP_121')->first();
        $viewBtnOrange = Image::where('VIEW_FLG', 'HP_122')->first();
        $viewBtnPurple = Image::where('VIEW_FLG', 'HP_123')->first();

        $heartImg = Image::where('VIEW_FLG', 'HP_124')->first();

        $aboutContent = Image::where('VIEW_FLG', 'HP_102')->first();
        $topImg1 = Image::where('VIEW_FLG', 'HP_103')->first();
        $topImg2 = Image::where('VIEW_FLG', 'HP_104')->first();

        // タレント情報の取得（既存のメソッド）
        // タレント用のオプション
        $options = [
            ['priority', true],
            ['id', true]
        ];
        $talents = $this->contentData->getContentByMasterId('T002', 5, [], $options);

        // ニュース情報の取得（既存のメソッド）
        // ニュース用のオプション
        $options = [
            ['priority', true],
            ['created_at', false]
        ];
        $newsList = $this->contentData->getContentByMasterId('T004', 0, [], $options);

        return view('home', compact(
            'logoImg',
            'logoMinImg',
            'slides',
            'XBtn',
            'aboutContent',
            'backImg',
            'titleAbout',
            'titleTalent',
            'titleNews',
            'titleAudition',
            'titleContact',
            'slideCnt',
            'viewBtnPink',
            'viewBtnOrange',
            'viewBtnPurple',
            'heartImg',
            'topImg1',
            'topImg2',
            'talents',
            'newsList'
        ));
    }
}
