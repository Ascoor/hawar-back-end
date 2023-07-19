<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = Member::where('MembershipType', 'عضو عامل')->paginate(30);
    
        return response()->json($members);
    }
    


        public function getMasterMember(Request $request)
        {
            $Mem_relation = $request->input('Mem_Relation');

            // Fetch fees for the specified member code
            $masterMember = Member::where('Mem_Code', $Mem_relation)->get();

            return response()->json($masterMember);
        }
        public function getParentMembers(Request $request)
        {
            $memberCode = $request->input('Mem_Code');

            // Fetch fees for the specified member code
            $parnetMembers = Member::where('Mem_ParentMember', $memberCode)->get();

            return response()->json($parnetMembers);
        }
        public function getMemberBySearch($term)
    {
        // Your logic to fetch members based on the search term
        $members = Member::where('Mem_Name', 'like', '%'.$term.'%')
                         ->orWhere('Mem_Code', 'like', '%'.$term.'%')
                         ->orWhere('Mem_BOD', 'like', '%'.$term.'%')
                         ->orWhere('Mem_Mobile', 'like', '%'.$term.'%')
                         ->get();

        return response()->json(['data' => $members]);
    }
}
