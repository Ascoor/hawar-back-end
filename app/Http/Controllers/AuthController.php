<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login','profile', 'register','refresh']);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register()
    {
        // Retrieve input data from the request
        $name = request('name');
        $email = request('email');
        $password = request('password');

        // Hash the password
        $hashedPassword = bcrypt($password);

        // Create a new user in the database
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = $hashedPassword;
        $user->save();

        // Return a JSON response indicating success
        return response()->json('success');
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile($userId)
    {
        $user = User::find($userId);

        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }



    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

/**
 * Get the token array structure.
 *
 * @param  string  $token
 * @return \Illuminate\Http\JsonResponse
 */
public function respondWithToken($token)
{
    return response()->json([
        'access_token' => $token->plainTextToken,
        'token_type' => 'Bearer',
        'expires_in' => config('sanctum.expiration'),
        'user' => Auth::user(),
    ]);
}

public function login()
{
    // Perform authentication logic
    $credentials = request(['email', 'password']);
    if (! Auth::attempt($credentials)) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Generate token
    $token = Auth::user()->createToken('authToken');

    return $this->respondWithToken($token);
}
}
