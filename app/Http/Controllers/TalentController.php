<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ContentMasterService;
use App\Services\ContentDataService;
use App\Models\Image;

class TalentController extends Controller
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
        $backImg = Image::where('VIEW_FLG', 'HP_003')->first();

        $titleTalent = Image::where('VIEW_FLG', 'HP_012')->first();
        $talentBtn = Image::where('VIEW_FLG', 'HP_203')->first();

        // タレント情報の取得
        // タレント用のオプション
        $options = [
            ['priority', true],
            ['created_at', false]
        ];
        $talents = $this->contentData->getContentByMasterId('T002', 0, [], $options);

        return view('talent', compact(
            'logoImg',
            'logoMinImg',
            'XBtn',
            'backImg',
            'titleTalent',
            'talentBtn',
            'talents'
        ));
    }

    public function show(Request $request)
    {
        $logoImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 1)->first();
        $logoMinImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 2)->first();
        $XBtn = Image::where('VIEW_FLG', 'HP_007')->first();
        $backImg = Image::where('VIEW_FLG', 'HP_003')->first();

        $titleTalent = Image::where('VIEW_FLG', 'HP_012')->first();

        $line = Image::where('VIEW_FLG', 'HP_301')->first();
        $talentBtn1 = Image::where('VIEW_FLG', 'HP_302')->first();
        $talentBtn2 = Image::where('VIEW_FLG', 'HP_303')->first();
        $talentBtn3 = Image::where('VIEW_FLG', 'HP_304')->first();
        $talentBtn4 = Image::where('VIEW_FLG', 'HP_305')->first();


        // タレント情報の取得
        // タレント用のオプション
        $options = [
            ['priority', true],
            ['created_at', false]
        ];

        $talentProf = $this->contentData->getContentByMasterId('T002', 0, [], $options, $request->talent_id);


        return view('profile', compact(
            'logoImg',
            'logoMinImg',
            'XBtn',
            'backImg',
            'titleTalent',
            'line',
            'talentBtn1',
            'talentBtn2',
            'talentBtn3',
            'talentBtn4',
            'talentProf'
        ));
    }
}
