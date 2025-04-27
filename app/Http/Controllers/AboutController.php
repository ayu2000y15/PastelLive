<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\HpText;
use App\Models\ContentMaster;
use App\Services\ContentMasterService;
use App\Services\ContentDataService;

class AboutController extends Controller
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
        $backImg = Image::where('VIEW_FLG', 'HP_002')->first();

        $titleAbout = Image::where('VIEW_FLG', 'HP_011')->first();

        $aboutImg1 = Image::where('VIEW_FLG', 'HP_201')->first();
        $aboutImg2 = Image::where('VIEW_FLG', 'HP_202')->first();
        $aboutBtn = Image::where('VIEW_FLG', 'HP_203')->first();
        // $aboutText = HpText::where('TEXT_ID', 'ABOUT_TOP')->first();

        $options = [
            ['priority', true],
            ['created_at', false]
        ];
        // $aboutImg = $this->contentData->getContentByMasterId('T006', 0, [], $options);

        // 会社情報の取得（T001マスターID）
        $aboutData = $this->contentData->getPublicDataByMasterId('T001');
        $aboutContent = null;

        if (count($aboutData) > 0) {
            $aboutContent = $aboutData[0];
        }

        // 会社情報のスキーマを取得
        $companyMaster = ContentMaster::where('master_id', 'T001')
            ->where('delete_flg', '0')
            ->first();

        // 会社情報データとスキーマ情報を組み合わせる
        $company = $this->contentData->getContentWithSchema('T001');

        return view('about', compact(
            'logoImg',
            'logoMinImg',
            'XBtn',
            'backImg',
            'aboutImg1',
            'aboutImg2',
            'aboutBtn',
            'titleAbout',
            'company',
        ));
    }
}
