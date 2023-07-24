<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberFeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberLookupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|

*/

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::put('/user/{userId}', [UserController::class, 'updateProfile']);
Route::get('/user/{userId}', [UserController::class, 'getUserDetails']);

Route::group(['middleware' => 'api'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/fees', [MemberFeeController::class, 'getMemberFees']);
Route::get('/members', [MemberController::class, 'index']);


// Define routes for fetching members
Route::get('/member-category', [MemberController::class, 'getCategoryMembers']);

Route::get('/members/search/{term}', [MemberController::class, 'getMemberBySearch']);



Route::get('/member-work-count', [HomeController::class, 'memberWork']);
Route::get('/other-counts', [HomeController::class, 'getOtherCounts']);
Route::get('/gender-count', [HomeController::class, 'getGenderCount']);
Route::get('/age-count', [HomeController::class, 'getAgeCount']);
Route::get('/category-count', [HomeController::class, 'getCategoryCount']);

//

//    MemberLookup Routes

Route::get('member_lookups', [MemberLookupController::class, 'index']);
Route::post('member_lookups', [MemberLookupController::class, 'store']);
Route::put('member_lookups/{id}', [MemberLookupController::class, 'update']);
Route::delete('member_lookups/{id}', [MemberLookupController::class, 'destroy']);
