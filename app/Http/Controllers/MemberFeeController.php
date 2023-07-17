<?php

namespace App\Http\Controllers;

use App\Models\MemberFee;
use Illuminate\Http\Request;

class MemberFeeController extends Controller
{

    public function getMemberFees(Request $request)
    {
        $memberCode = $request->input('Mem_Code');

        // Fetch fees for the specified member code
        $fees = MemberFee::where('Mem_Code', $memberCode)->select('Fee_ID', 'Fee_Year', 'Fee_Amount', 'Fee_Date', 'Fee_RecieptNumber', 'Fee_Status')->get();

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
    public function show(MemberFee $memberFee)
    {
        //
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
