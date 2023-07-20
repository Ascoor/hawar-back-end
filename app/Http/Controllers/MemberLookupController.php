<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MemberLookup;

class MemberLookupController extends Controller
{
    // Get all member lookup records
    public function index()
    {
        $lookups = MemberLookup::all();
        return response()->json($lookups);
    }

    // Add a new member lookup record
    public function store(Request $request)
    {
        $lookup = MemberLookup::create($request->all());
        return response()->json($lookup, 201);
    }

    // Update an existing member lookup record
    public function update(Request $request, $id)
    {
        $lookup = MemberLookup::findOrFail($id);
        $lookup->update($request->all());
        return response()->json($lookup, 200);
    }

    // Delete a member lookup record
    public function destroy($id)
    {
        MemberLookup::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
