<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\HpText;

class AuditionController extends Controller
{
    public function index()
    {
        $logoImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 1)->first();
        $logoMinImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 2)->first();
        $shopBtn = Image::where('VIEW_FLG', 'HP_007')->first();
        $backImg = Image::where('VIEW_FLG', 'HP_005')->first();

        $applyBtn = Image::where('VIEW_FLG', 'HP_501')->first();
        $auditionIconUp = Image::where('VIEW_FLG', 'HP_502')->first();
        $auditionIconDown = Image::where('VIEW_FLG', 'HP_503')->first();
        $auditionPop = Image::where('VIEW_FLG', 'HP_504')->first();

        $auditionContent = HpText::where('TEXT_ID', 'AUDITION_TOP')->first();
        $exp1 = HpText::where('TEXT_ID', 'AUDITION_EXP1')->first();

        return view('audition', compact(
            'logoImg',
            'logoMinImg',
            'shopBtn',
            'backImg',
            'applyBtn',
            'auditionIconUp',
            'auditionIconDown',
            'auditionPop',
            'auditionContent',
            'exp1'
        ));
    }
}
