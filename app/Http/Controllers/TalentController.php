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
        $shopBtn = Image::where('VIEW_FLG', 'HP_007')->first();
        $backImg = Image::where('VIEW_FLG', 'HP_003')->first();

        $femaleBtn = Image::where('VIEW_FLG', 'HP_301')->first();
        $maleBtn = Image::where('VIEW_FLG', 'HP_302')->first();

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
            'shopBtn',
            'backImg',
            'femaleBtn',
            'maleBtn',
            'talents'
        ));
    }

    public function show(Request $request)
    {
        $logoImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 1)->first();
        $logoMinImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 2)->first();
        $shopBtn = Image::where('VIEW_FLG', 'HP_007')->first();
        $backImg = Image::where('VIEW_FLG', 'HP_003')->first();

        $liveBackImg = Image::where('VIEW_FLG', 'HP_303')->first();

        // タレント情報の取得
        // タレント用のオプション
        $options = [
            ['priority', true],
            ['created_at', false]
        ];

        $talentProf = $this->contentData->getContentByMasterId('T002', 0, [], $options, $request->talent_id);

        $schedule = [
            [
                "schedule_date" => "2024.01.01",
                "schedule-text" => "ライブ配信予定1",
                "thumbnail" => "storage/img/hp/sample.png"
            ],
            [
                "schedule_date" => "2024.01.02",
                "schedule-text" => "ライブ配信予定2",
                "thumbnail" => "storage/img/hp/sample.png"
            ],
            [
                "schedule_date" => "2024.01.03",
                "schedule-text" => "ライブ配信予定3",
                "thumbnail" => "storage/img/hp/sample.png"
            ],
        ];

        return view('profile', compact(
            'logoImg',
            'logoMinImg',
            'shopBtn',
            'backImg',
            'liveBackImg',
            'talentProf',
            'schedule',
        ));
    }
}
