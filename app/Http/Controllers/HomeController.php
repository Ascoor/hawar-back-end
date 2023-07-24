<?php
namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Other methods...

    /**
     * Get counts based on Gender.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGenderCount(Request $request)
    {
        // Get the gender from the request
        $gender = $request->input('gender');

        // Query the database to get the count of members with the specified gender
        $count = Member::where('gender', $gender)->count();

        // Return JSON response with the count
        return response()->json(['count' => $count]);
    }

    /**
     * Get counts based on Age.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAgeCount(Request $request)
    {
        // Get the age from the request
        $age = $request->input('age');

        // Query the database to get the count of members with age greater than 25 and less than 60
        $count = Member::where('age', '>', 25)
            ->where('age', '<', 60)
            ->count();

        // Return JSON response with the count
        return response()->json(['count' => $count]);
    }

    /**
     * Get counts based on Category.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoryCount(Request $request)
    {
        // Get the category from the request
        $category = $request->input('category');

        // Query the database to get the count of members with the specified category
        $count = Member::where('category', $category)->count();

        // Return JSON response with the count
        return response()->json(['count' => $count]);
    }
}
