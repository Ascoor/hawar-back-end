<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Member;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{

         public function index()
         {
             $members = Member::orderBy('LastPayedFees', 'desc')->limit(100)
                 ->get();
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
        public function   getMemberBySearch(Request $request)
        {
            $searchTerm = $request->input('searchTerm');

            // Your logic to fetch members based on the category and search term
            $members = Member::where(function ($query) use ($searchTerm) {
                                 $query->where('name', 'like', '%' . $searchTerm . '%')
                                       ->orWhere('member_id', 'like', '%' . $searchTerm . '%')
                                       ->orWhere('phone', 'like', '%' . $searchTerm . '%');
                             })
                             ->get();

            return response()->json(['data' => $members]);
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
public function getCategoryAndSubCategory($memberId)
{
    $memberCategory = DB::table('member_category')
                        ->where('member_id', $memberId)
                        ->first();

    if (!$memberCategory) {
        return response()->json(['error' => 'Member category not found'], 404);
    }

    $category = Category::find($memberCategory->category_id);
    $subcategory = SubCategory::find($memberCategory->sub_category_id);

    return response()->json([
        'category' => $category,
        'subcategory' => $subcategory,
    ], 200);
}

public function getCategoryMembers(Request $request)
{
    $category = $request->input('category'); // Assuming you are sending the selected category from the frontend

    $categories = [
        'work' => '1',
        'affiliate' => '2',
        'founding' => '3',
        'honory' => '4',
        'seasonal' => '5',
        'athletic' => 'عضو رياضي',
        'A permit' => 'تصريح',
    ];

    if (array_key_exists($category, $categories)) {
        $query = Member::where('Category_id', $categories[$category])->orderBy('last_payed_fiscal_year', 'desc');

        // If the category is 'عضو عامل' or 'عضو تابع', limit the result to 100 members
        if ($category === 'work' || $category === 'affiliate') {
            $members = $query->take(50)->get();
        } else {
            $members = $query->get();
        }

        return response()->json($members);
    } else {
        // Handle invalid category here
        return response()->json(['message' => 'Invalid category'], 404);
    }
}

}
