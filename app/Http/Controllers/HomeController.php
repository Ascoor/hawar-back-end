<?php
namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberFee;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function getMemberCounts()
{
    $memberWork = '1';

    $memberPart = '2';
    $memberMale = '1';
    $memberFemale = '2';
    $membersIgnored = '2';
    $ageOver25 = 25;
    $ageOver60 = 60;

    $workMemberCount = Member::where('category_id', $memberWork)->count();
    $partMemberCount = Member::where('category_id', $memberPart)->count();
    $maleCount = Member::where('gender', $memberMale)->count();
    $femaleCount = Member::where('gender', $memberFemale)->count();
    $membersIgnoredCount = Member::where('status_id', 'like', '%' . $membersIgnored . '%')->count(); // Modify the query condition here
    $countOver25 = Member::where('age', '>', $ageOver25)->count();
    $countOver60 = Member::where('age', '>', $ageOver60)->count();

    $currentYear = now()->year;

    // Calculate the previous fiscal year
    $previousYear = $currentYear - 1;


    // Get the count of members who paid the fee in the current fiscal year
    $membersPaidCurrentFiscalYear = Member::where('last_payed_fiscal_year', 'like', '%' . $currentYear . '%')->count();

    // Get the count of members who paid the fee in the previous fiscal year
    $membersPaidPreviousFiscalYear = Member::where('last_payed_fiscal_year', 'like', '%' . $previousYear . '%')->count();


    return response()->json([
        'workMemberCount' => $workMemberCount,
        'partMemberCount' => $partMemberCount,
        'maleCount' => $maleCount,
        'femaleCount' => $femaleCount,
        'membersIgnored' => $membersIgnoredCount,
        'countOver25' => $countOver25,
        'countOver60' => $countOver60,
        'membersPaidCurrentFiscalYear' => $membersPaidCurrentFiscalYear,
        'membersPaidPreviousFiscalYear' => $membersPaidPreviousFiscalYear,

    ]);
}

}
