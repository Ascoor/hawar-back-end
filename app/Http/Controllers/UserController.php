<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
     public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required|in:admin,user,assistant' // Validating the role
        ]);

        // Update the user profile
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        if ($validatedData['password']) {
            $user->password = bcrypt($validatedData['password']);
        }

        $user->role = $validatedData['role'];

        $user->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }

    public function getUserDetails($userId)
    {
        try {
            $user = User::findOrFail($userId);
            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

}
