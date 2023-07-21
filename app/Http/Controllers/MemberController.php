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

         return response()->json([
             'data' => $members->items(),
             'current_page' => $members->currentPage(),
             'per_page' => $members->perPage(),
             'total' => $members->total(),
             'last_page' => $members->lastPage(),
         ]);
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


    public function store(Request $request)
    {
        $request->validate([
            // Add validation rules for the fields you want to validate during member creation.
            'Name' => 'required|string|max:255',
            'FamilyId' => 'required|string|max:255',
            'Category' => 'required|string|max:255',
            'City' => 'required|string|max:255',
            'State' => 'required|string|max:255',
            'Address' => 'required|string',
            // Add more validation rules for other fields if necessary.
        ]);

        $member = Member::create($request->all());
        return response()->json($member, 201);
    }

    public function show($id)
    {
        $member = Member::findOrFail($id);
        return response()->json($member);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // Add validation rules for the fields you want to validate during member update.
            'Name' => 'required|string|max:255',
            'FamilyId' => 'required|string|max:255',
            'Category' => 'required|string|max:255',
            'City' => 'required|string|max:255',
            'State' => 'required|string|max:255',
            'Address' => 'required|string',
            // Add more validation rules for other fields if necessary.
        ]);


}



}
