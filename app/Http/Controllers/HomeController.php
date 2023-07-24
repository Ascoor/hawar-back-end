<?php
namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function getMemberCounts()
    {
        $memberWork = 'عضو عامل';
        $memberPart = 'عضو تابع';
        $memberMale = 'ذكر';
        $memberFemale = 'أنثى';
        $ageOver25 = 25;
        $ageOver60 = 60;

        $workMemberCount = Member::where('category', $memberWork)->count();
        $partMemberCount = Member::where('category', $memberPart)->count();
        $maleCount = Member::where('Gender', $memberMale)->count();
        $femaleCount = Member::where('Gender', $memberFemale)->count();
        $countOver25 = Member::where('age', '>', $ageOver25)->count();
        $countOver60 = Member::where('age', '>', $ageOver60)->count();

        return response()->json([
            'workMemberCount' => $workMemberCount,
            'partMemberCount' => $partMemberCount,
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
            'countOver25' => $countOver25,
            'countOver60' => $countOver60,
        ]);
    }

}
