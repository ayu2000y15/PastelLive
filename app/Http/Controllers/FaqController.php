<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\HpText;

class FaqController extends Controller
{
    public function index()
    {
        $logoImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 1)->first();
        $logoMinImg = Image::where('VIEW_FLG', 'HP_999')->where('PRIORITY', 2)->first();
        $XBtn = Image::where('VIEW_FLG', 'HP_007')->first();
        $backImg = Image::where('VIEW_FLG', 'HP_008')->first();

        $faqImg1 = Image::where('VIEW_FLG', 'HP_701')->first();
        $faqImg2 = Image::where('VIEW_FLG', 'HP_702')->first();

        return view('faq', compact(
            'logoImg',
            'logoMinImg',
            'XBtn',
            'backImg',
            'faqImg1',
            'faqImg2',
        ));
    }
}
