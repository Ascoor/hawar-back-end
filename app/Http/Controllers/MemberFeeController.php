<?php

namespace App\Http\Controllers;

use App\Models\MemberFee;
use Illuminate\Http\Request;

class MemberFeeController extends Controller
{

    public function getMemberFees(Request $request)
    {
        $Member_ID = $request->input('member_id');

        // Fetch fees for the specified member code
        $fees = MemberFee::where('member_id', $Member_ID)->select('FeeYear', 'FeeAmount', 'FeeDate', 'FeeRecieptNumber', 'FeeStatus')->get();

        return response()->json($fees);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MemberFee  $memberFee
     * @return \Illuminate\Http\Response
     */

     public function show($Member_ID)
     {
         try {
             // Fetch the latest member fee by FeeDate for the given Member_ID
             $fee = MemberFee::where('Member_ID', $Member_ID)
                             ->latest('FeeDate') // Order by FeeDate in descending order (latest first)
                             ->first();
     
             // Check if a fee was found for the member
             if (!$fee) {
                 return response()->json(['message' => 'No fee found for the member'], 404);
             }
     
             // Return the member fee as a JSON response
             return response()->json($fee, 200);
         } catch (\Exception $e) {
             // Handle any errors that may occur during the database query
             return response()->json(['message' => 'Failed to fetch member fee'], 500);
         }
     }
     

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MemberFee  $memberFee
     * @return \Illuminate\Http\Response
     */
    public function edit(MemberFee $memberFee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MemberFee  $memberFee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MemberFee $memberFee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MemberFee  $memberFee
     * @return \Illuminate\Http\Response
     */
    public function destroy(MemberFee $memberFee)
    {
        //
    }
}
