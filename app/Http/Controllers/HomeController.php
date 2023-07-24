<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{

        
    public function memberWork()
    {
        // Count members with category "عضو عامل"
        $workerMembersCount = Member::where('category', 'عضو عامل')->count();

        // Return JSON response with the count
        return response()->json(['workerMembersCount' => $workerMembersCount]);
    }

}
