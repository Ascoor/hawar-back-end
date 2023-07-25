<?php
namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberFee;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function getMemberCounts()
    {
        $memberWork = 'عضو عامل';
        $memberPart = 'عضو تابع';
        $memberMale = 'ذكر';
        $memberFemale = 'أنثى';
        $membersIgnored = 'مسقطة';
        $ageOver25 = 25;
        $ageOver60 = 60;

        $workMemberCount = Member::where('category', $memberWork)->count();
        $partMemberCount = Member::where('category', $memberPart)->count();
        $maleCount = Member::where('Gender', $memberMale)->count();
        $femaleCount = Member::where('Gender', $memberFemale)->count();
        $countOver25 = Member::where('Status', $membersIgnored)->count();
        $countOver25 = Member::where('age', '>', $ageOver25)->count();
        $countOver60 = Member::where('age', '>', $ageOver60)->count();
          // Get the current year
    $currentYear = now()->year;

    // Get the count of members who paid the fee in the current year
    $membersPaidCurrentYear = MemberFee::whereYear('FeeDate', $currentYear)->count();

    // Get the count of members who paid the fee in the previous year
    $previousYear = $currentYear - 1;
    $membersPaidPreviousYear = MemberFee::whereYear('FeeDate', $previousYear)->count();


        return response()->json([
            'workMemberCount' => $workMemberCount,
            'partMemberCount' => $partMemberCount,
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
            'membersIgnored' => $membersIgnored,
            'countOver25' => $countOver25,
            'countOver60' => $countOver60,
            'membersPaidCurrentYear' => $membersPaidCurrentYear,
            'membersPaidPreviousYear' => $membersPaidPreviousYear,
        ]);
    }

}
