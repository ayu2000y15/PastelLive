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
        $XBtn = Image::where('VIEW_FLG', 'HP_007')->first();
        $backImg = Image::where('VIEW_FLG', 'HP_005')->first();

        $titleAudition = Image::where('VIEW_FLG', 'HP_014')->first();

        $auditionImg1 = Image::where('VIEW_FLG', 'HP_501')->first();
        $auditionImg2 = Image::where('VIEW_FLG', 'HP_502')->first();
        $auditionImg3 = Image::where('VIEW_FLG', 'HP_503')->first();
        $auditionImg4 = Image::where('VIEW_FLG', 'HP_504')->first();
        $auditionImg5 = Image::where('VIEW_FLG', 'HP_505')->first();
        $entryBtn = Image::where('VIEW_FLG', 'HP_506')->first();

        return view('audition', compact(
            'logoImg',
            'logoMinImg',
            'XBtn',
            'backImg',
            'titleAudition',
            'entryBtn',
            'auditionImg1',
            'auditionImg2',
            'auditionImg3',
            'auditionImg4',
            'auditionImg5'
        ));
    }
}
